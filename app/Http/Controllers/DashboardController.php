<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Invoice;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Get statistics
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $totalPurchases = Purchase::count();
        $totalSales = Sale::count();
        
        // Get pending invoices
        $pendingInvoices = Invoice::where('status', 'unpaid')
            ->with('purchase.supplier')
            ->get();
        
        // Get recent sales
        $recentSales = Sale::with('items.product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get low stock products
        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->get();
        
        return view('dashboard', compact(
            'totalProducts',
            'totalSuppliers', 
            'totalPurchases',
            'totalSales',
            'pendingInvoices',
            'recentSales',
            'lowStockProducts'
        ));
    }
}

