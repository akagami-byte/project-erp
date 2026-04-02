@extends('layouts.app')

@section('title', 'Goods Receipt')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('purchase.index') }}">Purchase</a> / Goods Receipt
@endsection

@section('header-actions')
    <a href="{{ route('purchase.goods_receipt.create') }}" class="btn btn-primary">+ New Goods Receipt</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Goods Receipt List</span>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>GR #</th>
                    <th>Date</th>
                    <th>Purchase #</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($goodsReceipts as $gr)
                <tr>
                    <td>GR-{{ str_pad($gr->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($gr->receipt_date)->format('d/m/Y') }}</td>
                    <td>PO-{{ str_pad($gr->purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $gr->location }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('purchase.goods_receipt.show', $gr->id) }}" class="btn btn-sm btn-secondary">View</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No goods receipts found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

