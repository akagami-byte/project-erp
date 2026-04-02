<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    // ============ SALES METHODS ============

    /**
     * Display a listing of sales.
     */
    public function index()
    {
        $sales = Sale::with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $products = Product::all();
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch' => 'required|string|max:255',
            'sales_date' => 'required|date',
            'customer_name' => 'required|string|max:255',
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

        // Check stock availability
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product->stock < $item['qty']) {
                return redirect()->back()
                    ->with('error', 'Stok produk "' . $product->name . '" tidak cukup! Stok tersedia: ' . $product->stock)
                    ->withInput();
            }
        }

        DB::transaction(function () use ($request) {
            // Create sale
            $sale = Sale::create([
                'branch' => $request->branch,
                'sales_date' => $request->sales_date,
                'customer_name' => $request->customer_name,
                'total' => 0
            ]);

            $total = 0;

            // Create sale items and reduce stock
            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['price'];
                $total += $subtotal;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal
                ]);

                // Reduce product stock
                $product = Product::find($item['product_id']);
                $product->stock -= $item['qty'];
                $product->save();
            }

            // Update total
            $sale->update(['total' => $total]);
        });

        return redirect()->route('sales.index')
            ->with('success', 'Sales berhasil dibuat dan stok sudah dikurangi!');
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        $sale->load('items.product');
        return view('sales.show', compact('sale'));
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Sale $sale)
    {
        // Restore stock before deleting
        foreach ($sale->items as $item) {
            $product = Product::find($item->product_id);
            $product->stock += $item->qty;
            $product->save();
        }

        $sale->items()->delete();
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sales berhasil dihapus dan stok sudah dikembalikan!');
    }

    // ============ STOCK OPNAME METHODS ============

    /**
     * Display stock opname list.
     */
    public function stockOpnameIndex()
    {
        $products = Product::orderBy('name')->get();
        return view('inventory.stock_opname.index', compact('products'));
    }

    /**
     * Store stock opname results.
     */
    public function stockOpnameStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.actual_stock' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request) {
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $systemStock = $product->stock;
                $actualStock = $item['actual_stock'];
                $difference = $actualStock - $systemStock;

                // Update stock if there's a difference
                if ($difference != 0) {
                    $product->stock = $actualStock;
                    $product->save();
                }
            }
        });

        return redirect()->route('stock_opname.index')
            ->with('success', 'Stock opname berhasil disimpan dan stok sudah disesuaikan!');
    }
}
