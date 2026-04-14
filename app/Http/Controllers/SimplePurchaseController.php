<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SimplePurchase;
use App\Models\SimplePurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;

class SimplePurchaseController extends Controller
{
    /**
     * Display a listing of simple purchases.
     */
    public function index()
    {
        $simplePurchases = SimplePurchase::with(['supplier', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('simple-purchase.index', compact('simplePurchases'));
    }

    /**
     * Show the form for creating a new simple purchase.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('simple-purchase.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created simple purchase.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'payment_method' => 'required|string|in:cash,transfer,qris',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_order' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0.01'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request) {
            // Create simple purchase - UNPAID/NOT_RECEIVED
            $simplePurchase = SimplePurchase::create([
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'UNPAID',
                'receipt_status' => 'NOT_RECEIVED',
                'total_price' => 0
            ]);

            $total = 0;

            // Create items - qty_received = 0, NO STOCK UPDATE
            foreach ($request->items as $item) {
                $subtotal = $item['qty_order'] * $item['price'];
                $total += $subtotal;

                SimplePurchaseItem::create([
                    'simple_purchase_id' => $simplePurchase->id,
                    'product_id' => $item['product_id'],
                    'qty_order' => $item['qty_order'],
                    'qty_received' => 0,
                    'price' => $item['price'],
                    'subtotal' => $subtotal
                ]);
            }

            $simplePurchase->update(['total_price' => $total]);
        });

        return redirect()->route('simple-purchases.index')
            ->with('success', 'Simple Purchase berhasil dibuat! Bayar dan terima barang di halaman detail.');
    }

    /**
     * Display the specified simple purchase.
     */
    public function show(SimplePurchase $simplePurchase)
    {
        $simplePurchase->load(['supplier', 'items.product']);
        return view('simple-purchase.show', compact('simplePurchase'));
    }

    /**
     * Pay simple purchase.
     */
    public function pay(SimplePurchase $simplePurchase)
    {
        DB::transaction(function () use ($simplePurchase) {
            if ($simplePurchase->payment_status === 'PAID') {
                return redirect()->back()->with('error', 'Sudah dibayar!');
            }

            $simplePurchase->update([
                'payment_status' => 'PAID'
            ]);
        });

        return redirect()->route('simple-purchases.show', $simplePurchase)
            ->with('success', 'Pembayaran berhasil dicatat!');
    }

    /**
     * Update receipt with editable qty_received.
     */
    public function updateReceipt(Request $request, SimplePurchase $simplePurchase)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:simple_purchase_items,id',
            'items.*.qty_received' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request, $simplePurchase) {
            foreach ($request->items as $itemData) {
                $item = SimplePurchaseItem::findOrFail($itemData['id']);
                
                $oldQtyReceived = $item->qty_received;
                $newQtyReceived = (int) $itemData['qty_received'];

                // Validate qty_received tidak boleh melebihi qty_order
                if ($newQtyReceived > $item->qty_order) {
                    throw new \Exception('Qty received tidak boleh melebihi qty order untuk ' . $item->product->name);
                }

                // Validation: no negative diff that makes stock negative
                $diff = $newQtyReceived - $oldQtyReceived;
                if ($item->product->stock + $diff < 0) {
                    throw new \Exception('Stock tidak boleh negatif untuk ' . $item->product->name);
                }

                // Update stock with diff
                $item->product->stock += $diff;
                $item->product->save();

                // Update item
                $item->qty_received = $newQtyReceived;
                $item->save();
            }

            // Update receipt_status
            $totalReceived = $simplePurchase->items->sum('qty_received');
            $totalOrder = $simplePurchase->items->sum('qty_order');

            if ($totalReceived == 0) {
                $simplePurchase->receipt_status = 'NOT_RECEIVED';
            } elseif ($totalReceived < $totalOrder) {
                $simplePurchase->receipt_status = 'PARTIAL';
            } else {
                $simplePurchase->receipt_status = 'RECEIVED';
            }
            $simplePurchase->save();
        });

        return redirect()->route('simple-purchases.show', $simplePurchase)
            ->with('success', 'Receipt berhasil diupdate! Stok sudah disesuaikan.');
    }

    /**
     * Create Midtrans Snap token for payment.
     */
    public function midtransCharge(SimplePurchase $simplePurchase)
    {
        \Log::info('midtransCharge called for purchase ID: ' . $simplePurchase->id);
        if ($simplePurchase->payment_status === 'PAID') {
            return response()->json(['error' => 'Invoice sudah dibayar.'], 400);
        }

        // Configure Midtrans
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        // MidtransConfig::$clientKey    = config('midtrans.client_key'); // Unnecessary on backend
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized  = config('midtrans.is_sanitized');
        MidtransConfig::$is3ds        = config('midtrans.is_3ds');

        // Mengatasi masalah SSL verifikasi di local (cURL Error: SSL certificate problem)
        // CURLOPT_HTTPHEADER harus disertakan (kosong) agar Midtrans SDK tidak crash
        // di ApiRequestor.php:117 yang mengakses key ini tanpa isset()
        MidtransConfig::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => [],
        ];

        $orderId = 'SP-' . str_pad($simplePurchase->id, 5, '0', STR_PAD_LEFT) . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $simplePurchase->total_price,
            ],
            'customer_details' => [
                'first_name' => 'Buyer',
                'last_name'  => '',
                'email'      => 'buyer@example.com',
                'phone'      => '08111111111',
            ],
            'item_details' => $simplePurchase->items->map(function ($item) {
                return [
                    'id'       => (string) $item->id,
                    'price'    => (int) $item->price,
                    'quantity' => (int) $item->qty_order,
                    'name'     => $item->product->name,
                ];
            })->toArray(),
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Store orderId to match with callback later
            $simplePurchase->update(['midtrans_order_id' => $orderId]);

            return response()->json([
                'snap_token' => $snapToken,
                'client_key' => config('midtrans.client_key'),
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans charge error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghubungi payment gateway: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine()], 500);
        }
    }

    /**
     * Handle Midtrans webhook notification.
     * This route is CSRF-exempt (see routes/web.php).
     */
    public function midtransCallback(Request $request)
    {
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');

        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId           = $notification->order_id;
            $fraudStatus       = $notification->fraud_status;

            Log::info('Midtrans callback', [
                'order_id' => $orderId,
                'status'   => $transactionStatus,
                'fraud'    => $fraudStatus,
            ]);

            $simplePurchase = SimplePurchase::where('midtrans_order_id', $orderId)->first();

            if (!$simplePurchase) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Mark as PAID on settlement or capture with no fraud
            if (
                ($transactionStatus === 'settlement') ||
                ($transactionStatus === 'capture' && $fraudStatus === 'accept')
            ) {
                $simplePurchase->update(['payment_status' => 'PAID']);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                // Leave as UNPAID – optionally log
                Log::info('Midtrans: payment not successful for ' . $orderId);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

