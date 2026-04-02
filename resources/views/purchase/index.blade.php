@extends('layouts.app')

@section('title', 'Purchase')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Purchase
@endsection

@section('header-actions')
    <a href="{{ route('purchase.create') }}" class="btn btn-primary">+ New Purchase</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Purchase Order List</span>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Purchase #</th>
                    <th>Date</th>
                    <th>Branch</th>
                    <th>Supplier</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                <tr>
                    <td>PO-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($purchase->document_date)->format('d/m/Y') }}</td>
                    <td>{{ $purchase->branch }}</td>
                    <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $purchase->payment_method == 'cash' ? 'success' : 'warning' }}">
                            {{ ucfirst($purchase->payment_method) }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('purchase.show', $purchase->id) }}" class="btn btn-sm btn-secondary">View</a>
                            <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No purchases found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

