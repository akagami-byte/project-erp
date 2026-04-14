@extends('layouts.app')

@section('title', 'Simple Purchases')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Simple Purchases
@endsection

@section('header-actions')
    <a href="{{ route('simple-purchases.create') }}" class="btn btn-primary">+ New Simple Purchase</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Simple Purchases List</span>
    </div>
    <div class="card-body">
        @if($simplePurchases->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Payment Status</th>
                            <th>Receipt Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($simplePurchases as $sp)
                            <tr>
                                <td>SP-{{ str_pad($sp->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $sp->supplier->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($sp->date)->format('d/m/Y') }}</td>
                                <td>Rp {{ number_format($sp->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($sp->payment_status == 'PAID')
                                        <span class="badge bg-success">{{ $sp->payment_status }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ $sp->payment_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sp->receipt_status == 'RECEIVED')
                                        <span class="badge bg-success">{{ $sp->receipt_status }}</span>
                                    @elseif($sp->receipt_status == 'PARTIAL')
                                        <span class="badge bg-warning text-dark">{{ $sp->receipt_status }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $sp->receipt_status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('simple-purchases.show', $sp) }}" class="btn btn-sm btn-secondary">View</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted">No simple purchases found</p>
            </div>
        @endif
    </div>
</div>
@endsection

