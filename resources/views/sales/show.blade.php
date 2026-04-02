@extends('layouts.app')

@section('title', 'View Sales')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('sales.index') }}">Sales</a> / View Sales
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Sales Order Details</span>
        <a href="{{ route('sales.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col col-6">
                <table class="table">
                    <tr>
                        <th width="40%">Sales #</th>
                        <td>SO-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <td>{{ $sale->branch }}</td>
                    </tr>
                    <tr>
                        <th>Sales Date</th>
                        <td>{{ \Carbon\Carbon::parse($sale->sales_date)->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col col-6">
                <table class="table">
                    <tr>
                        <th width="40%">Customer Name</th>
                        <td>{{ $sale->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td><strong>Rp {{ number_format($sale->total, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <h5>Sales Items</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->product->sku ?? 'N/A' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No items found</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                    <td><strong>Rp {{ number_format($sale->total, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

