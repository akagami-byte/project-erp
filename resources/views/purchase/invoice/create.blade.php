@extends('layouts.app')

@section('title', 'New Invoice')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('purchase.index') }}">Purchase</a> / <a href="{{ route('purchase.invoice.index') }}">Invoice</a> / New Invoice
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Create New Invoice</span>
    </div>
    <div class="card-body">
        <form action="{{ route('purchase.invoice.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col col-6">
                    <div class="form-group">
                        <label class="form-label">Purchase Order *</label>
                        <select name="purchase_id" class="form-control" id="purchase-select" onchange="loadPurchaseTotal()" required>
                            <option value="">-- Select Purchase Order --</option>
                            @foreach($purchases as $purchase)
                                <option value="{{ $purchase->id }}" data-total="{{ $purchase->total }}">
                                    PO-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }} - {{ $purchase->supplier->name ?? 'N/A' }} - Rp {{ number_format($purchase->total, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('purchase_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col col-6">
                    <div class="form-group">
                        <label class="form-label">Invoice Date *</label>
                        <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                        @error('invoice_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col col-6">
                    <div class="form-group">
                        <label class="form-label">Total Amount *</label>
                        <input type="number" name="total_amount" class="form-control" id="total-amount" value="{{ old('total_amount') }}" min="0" step="0.01" required>
                        @error('total_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col col-6">
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Invoice</button>
                <a href="{{ route('purchase.invoice.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function loadPurchaseTotal() {
        const purchaseId = document.getElementById('purchase-select').value;
        const option = document.getElementById('purchase-select').selectedOptions[0];
        const total = option ? option.dataset.total : 0;
        
        document.getElementById('total-amount').value = total;
    }
</script>
@endsection

