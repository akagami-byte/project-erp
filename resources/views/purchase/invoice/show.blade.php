@extends('layouts.app')

@section('title', 'View Invoice')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('purchase.index') }}">Purchase</a> / <a href="{{ route('purchase.invoice.index') }}">Invoice</a> / View Invoice
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Invoice Details</span>
        <a href="{{ route('purchase.invoice.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col col-6">
                <table class="table">
                    <tr>
                        <th width="40%">Invoice #</th>
                        <td>INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <th>Invoice Date</th>
                        <td>{{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Purchase Order</th>
                        <td>PO-{{ str_pad($invoice->purchase->id, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                </table>
            </div>
            <div class="col col-6">
                <table class="table">
                    <tr>
                        <th width="40%">Supplier</th>
                        <td>{{ $invoice->purchase->supplier->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td><strong>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($invoice->status == 'paid')
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-warning">Unpaid</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $invoice->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        @if($invoice->status == 'unpaid')
        <div class="d-flex gap-2">
            <form action="{{ route('purchase.invoice.pay', $invoice->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('Mark this invoice as paid?')">Mark as Paid</button>
            </form>
            <a href="{{ route('purchase.invoice.index') }}" class="btn btn-secondary">Back</a>
        </div>
        @else
        <div class="d-flex gap-2">
            <a href="{{ route('purchase.invoice.index') }}" class="btn btn-secondary">Back</a>
        </div>
        @endif
    </div>
</div>
@endsection

