@extends('layouts.app')

@section('title', 'View Goods Receipt')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('purchase.index') }}">Purchase</a> / <a href="{{ route('purchase.goods_receipt.index') }}">Goods Receipt</a> / View Goods Receipt
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Goods Receipt Details</span>
        <a href="{{ route('purchase.goods_receipt.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col col-6">
                <table class="table">
                    <tr>
                        <th width="40%">Goods Receipt #</th>
                        <td>GR-{{ str_pad($goodsReceipt->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Purchase Order</th>
                        <td>PO-{{ str_pad($goodsReceipt->purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Receipt Date</th>
                        <td>{{ \Carbon\Carbon::parse($goodsReceipt->receipt_date)->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col col-6">
                <table class="table">
                    <tr>
                        <th width="40%">Location</th>
                        <td>{{ $goodsReceipt->location }}</td>
                    </tr>
                    <tr>
                        <th>Supplier</th>
                        <td>{{ $goodsReceipt->purchase->supplier->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $goodsReceipt->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <h5>Receipt Items</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Quantity Received</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($goodsReceipt->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->product->sku ?? 'N/A' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No items found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

