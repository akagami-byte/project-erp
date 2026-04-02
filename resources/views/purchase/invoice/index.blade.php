@extends('layouts.app')

@section('title', 'Invoice')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('purchase.index') }}">Purchase</a> / Invoice
@endsection

@section('header-actions')
    <a href="{{ route('purchase.invoice.create') }}" class="btn btn-primary">+ New Invoice</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Invoice List</span>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Purchase #</th>
                    <th>Supplier</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                    <td>PO-{{ str_pad($invoice->purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $invoice->purchase->supplier->name ?? 'N/A' }}</td>
                    <td>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    <td>
                        @if($invoice->status == 'paid')
                            <span class="badge badge-success">Paid</span>
                        @else
                            <span class="badge badge-warning">Unpaid</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('purchase.invoice.show', $invoice->id) }}" class="btn btn-sm btn-secondary">View</a>
                            @if($invoice->status == 'unpaid')
                                <form action="{{ route('purchase.invoice.pay', $invoice->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this invoice as paid?')">Pay</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No invoices found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

