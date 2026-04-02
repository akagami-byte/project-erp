@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
@endsection

@section('content')
<div class="row">
    <!-- Total Products -->
    <div class="col col-3">
        <div class="stats-card">
            <h3>{{ $totalProducts }}</h3>
            <p>Total Products</p>
        </div>
    </div>
    
    <!-- Total Suppliers -->
    <div class="col col-3">
        <div class="stats-card">
            <h3>{{ $totalSuppliers }}</h3>
            <p>Total Suppliers</p>
        </div>
    </div>
    
    <!-- Total Purchases -->
    <div class="col col-3">
        <div class="stats-card">
            <h3>{{ $totalPurchases }}</h3>
            <p>Total Purchases</p>
        </div>
    </div>
    
    <!-- Total Sales -->
    <div class="col col-3">
        <div class="stats-card">
            <h3>{{ $totalSales }}</h3>
            <p>Total Sales</p>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Pending Invoices -->
    <div class="col col-6">
        <div class="card">
            <div class="card-header">
                <span>Pending Invoices</span>
            </div>
            <div class="card-body">
                @if($pendingInvoices->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Supplier</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingInvoices->take(5) as $invoice)
                            <tr>
                                <td>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $invoice->purchase->supplier->name ?? 'N/A' }}</td>
                                <td>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center" style="color: #6c757d;">No pending invoices</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Sales -->
    <div class="col col-6">
        <div class="card">
            <div class="card-header">
                <span>Recent Sales</span>
            </div>
            <div class="card-body">
                @if($recentSales->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sales #</th>
                                <th>Customer</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                            <tr>
                                <td>SO-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $sale->customer_name }}</td>
                                <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center" style="color: #6c757d;">No recent sales</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Low Stock Products -->
    <div class="col col-6">
        <div class="card">
            <div class="card-header">
                <span>Low Stock Products (Stock < 10)</span>
            </div>
            <div class="card-body">
                @if($lowStockProducts->count() > 0)
                    <div style="overflow-x: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Product Name</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <span class="badge badge-danger">{{ $product->stock }}</span>
                                    </td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center" style="color: #6c757d;">All products have sufficient stock</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Purchases (Placeholder for balance) -->
    <div class="col col-6">
        <div class="card">
            <div class="card-header">
                <span>Recent Purchases</span>
            </div>
            <div class="card-body">
                <p class="text-center" style="color: #6c757d; font-style: italic;">Coming soon: Recent purchase orders and goods receipts</p>
            </div>
        </div>
    </div>
</div>
@endsection

