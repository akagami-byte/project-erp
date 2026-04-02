@extends('layouts.app')

@section('title', 'View Purchase')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('purchase.index') }}">Purchase</a> / View Purchase
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Purchase Order Details</span>
        <a href="{{ route('purchase.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col col-6">
                <table class="table">
                    <tr>
                        <th width="40%">Purchase #</th>
                        <td>PO-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <td>{{ $purchase->branch }}</td>
                    </tr>
                    <tr>
                        <th>Document Date</th>
                        <td>{{ \Carbon\Carbon::parse($purchase->document_date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Required Date</th>
                        <td>{{ \Carbon\Carbon::parse($purchase->required_date)->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col col-6">
                <table class="table">
                    <tr>
                        <th width="40%">Supplier</th>
                        <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td>
                            <span class="badge badge-{{ $purchase->payment_method == 'cash' ? 'success' : 'warning' }}">
                                {{ ucfirst($purchase->payment_method) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td><strong>Rp {{ number_format($purchase->total, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <h5>Purchase Items</h5>
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
                @forelse($purchase->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->product->sku ?? 'N/A' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}</td>
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
                    <td><strong>Rp {{ number_format($purchase->total, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

