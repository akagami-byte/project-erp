<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\AccountingService;

class PurchasingController extends Controller
{
    // ============ PURCHASE METHODS ============
    
    /**
     * Display a listing of purchases.
     */
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new purchase.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created purchase in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch' => 'required|string|max:255',
            'document_date' => 'required|date',
            'required_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'payment_method' => 'required|in:cash,credit',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request) {
            // Create purchase
            $purchase = Purchase::create([
                'branch' => $request->branch,
                'document_date' => $request->document_date,
                'required_date' => $request->required_date,
                'supplier_id' => $request->supplier_id,
                'payment_method' => $request->payment_method,
                'total' => 0
            ]);

            $total = 0;

            // Create purchase items
            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['price'];
                $total += $subtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price']
                ]);
            }

            // Update total
            $purchase->update(['total' => $total]);

            // Create invoice for credit purchases
            if ($request->payment_method === 'credit') {
                Invoice::create([
                    'purchase_id' => $purchase->id,
                    'invoice_date' => $request->document_date,
                    'total_amount' => $total,
                    'status' => 'unpaid'
                ]);
            }
        });

        return redirect()->route('purchase.index')
            ->with('success', 'Purchase berhasil dibuat!');
    }

    /**
     * Display the specified purchase.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product']);
        return view('purchase.show', compact('purchase'));
    }

    /**
     * Remove the specified purchase from storage.
     */
    public function destroy(Purchase $purchase)
    {
        // Check if goods receipt exists
        if ($purchase->goodsReceipts()->count() > 0) {
            return redirect()->route('purchase.index')
                ->with('error', 'Purchase tidak dapat dihapus karena sudah ada goods receipt!');
        }

        // Check if invoice exists
        if ($purchase->invoices()->count() > 0) {
            return redirect()->route('purchase.index')
                ->with('error', 'Purchase tidak dapat dihapus karena sudah ada invoice!');
        }

        $purchase->items()->delete();
        $purchase->delete();

        return redirect()->route('purchase.index')
            ->with('success', 'Purchase berhasil dihapus!');
    }

    // ============ GOODS RECEIPT METHODS ============

    /**
     * Display goods receipts list.
     */
    public function goodsReceiptIndex()
    {
        $goodsReceipts = GoodsReceipt::with(['purchase.supplier', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('purchase.goods_receipt.index', compact('goodsReceipts'));
    }

    /**
     * Show form for creating goods receipt.
     */
    public function goodsReceiptCreate()
    {
        // Get purchases that haven't been fully received
        $purchases = Purchase::with(['supplier', 'items.product'])
            ->whereHas('items')
            ->get()
            ->filter(function ($purchase) {
                // Check if all items have been received
                $totalOrdered = $purchase->items->sum('qty');
                $totalReceived = $purchase->goodsReceipts()
                    ->with('items')
                    ->get()
                    ->sum(function ($gr) {
                        return $gr->items->sum('qty_received');
                    });
                return $totalReceived < $totalOrdered;
            });

        $products = Product::all();
        return view('purchase.goods_receipt.create', compact('purchases', 'products'));
    }

    /**
     * Store goods receipt.
     */
    public function goodsReceiptStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_id' => 'required|exists:purchases,id',
            'location' => 'required|string|max:255',
            'receipt_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_received' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request) {
            // Create goods receipt
            $goodsReceipt = GoodsReceipt::create([
                'purchase_id' => $request->purchase_id,
                'location' => $request->location,
                'receipt_date' => $request->receipt_date
            ]);

            // Create goods receipt items and update stock
            $totalValue = 0;
            foreach ($request->items as $item) {
                GoodsReceiptItem::create([
                    'goods_receipt_id' => $goodsReceipt->id,
                    'product_id' => $item['product_id'],
                    'qty_received' => $item['qty_received']
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->stock += $item['qty_received'];
                $product->save();

                // Calculate value (qty_received * original purchase price)
                $purchaseItem = PurchaseItem::where('purchase_id', $request->purchase_id)
                    ->where('product_id', $item['product_id'])
                    ->first();
                if ($purchaseItem) {
                    $totalValue += $item['qty_received'] * $purchaseItem->price;
                }
            }

            // Create auto journal
            $accountingService = app(AccountingService::class);
            $accountingService->createGoodsReceiptJournal(
                $totalValue,
                'GR-' . $goodsReceipt->id,
                $goodsReceipt->receipt_date
            );

        });

        return redirect()->route('purchase.goods_receipt.index')
            ->with('success', 'Goods Receipt berhasil dibuat, stok diperbarui, dan journal otomatis dibuat!');
    }

    /**
     * Display goods receipt details.
     */
    public function goodsReceiptShow(GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load(['purchase.supplier', 'items.product']);
        return view('purchase.goods_receipt.show', compact('goodsReceipt'));
    }

    // ============ INVOICE METHODS ============

    /**
     * Display invoices list.
     */
    public function invoiceIndex()
    {
        $invoices = Invoice::with('purchase.supplier')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('purchase.invoice.index', compact('invoices'));
    }

    /**
     * Show form for creating invoice.
     */
    public function invoiceCreate()
    {
        // Get purchases with no invoice
        $purchases = Purchase::with('supplier')
            ->whereHas('goodsReceipts') // Only show purchases that have been received
            ->whereDoesntHave('invoices')
            ->get();
        return view('purchase.invoice.create', compact('purchases'));
    }

    /**
     * Store invoice.
     */
    public function invoiceStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_id' => 'required|exists:purchases,id',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Invoice::create([
            'purchase_id' => $request->purchase_id,
            'invoice_date' => $request->invoice_date,
            'total_amount' => $request->total_amount,
            'status' => $request->status ?? 'unpaid'
        ]);

        return redirect()->route('purchase.invoice.index')
            ->with('success', 'Invoice berhasil dibuat!');
    }

    /**
     * Show invoice details.
     */
    public function invoiceShow(Invoice $invoice)
    {
        $invoice->load('purchase.supplier', 'purchase.items.product');
        return view('purchase.invoice.show', compact('invoice'));
    }

    /**
     * Mark invoice as paid.
     */
    public function invoicePay(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $invoice->markAsPaid($request->notes);

        return redirect()->route('purchase.invoice.show', $invoice)
            ->with('success', 'Invoice berhasil dibayar!');
    }
}
