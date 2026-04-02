@extends('layouts.app')

@section('title', 'New Goods Receipt')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('purchase.index') }}">Purchase</a> / <a href="{{ route('purchase.goods_receipt.index') }}">Goods Receipt</a> / New Goods Receipt
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Create New Goods Receipt</span>
    </div>
    <div class="card-body">
        <form action="{{ route('purchase.goods_receipt.store') }}" method="POST">
            @csrf
            
            <div class="row mb-4">
                <div class="col col-6">
                    <div class="form-group">
                        <label class="form-label">Purchase Order *</label>
                        <select name="purchase_id" class="form-control" id="purchase-select" onchange="loadPurchaseItems()" required>
                            <option value="">-- Select Purchase Order --</option>
                            @foreach($purchases as $purchase)
                                <option value="{{ $purchase->id }}" data-supplier="{{ $purchase->supplier->name ?? 'N/A' }}" {{ old('purchase_id') == $purchase->id ? 'selected' : '' }}>
                                    PO-{{ str_pad($purchase->id, 5, '0', STR_PAD_LEFT) }} - {{ $purchase->supplier->name ?? 'N/A' }}
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
                        <label class="form-label">Receipt Date *</label>
                        <input type="date" name="receipt_date" class="form-control" value="{{ old('receipt_date', date('Y-m-d')) }}" required>
                        @error('receipt_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col col-6">
                    <div class="form-group">
                        <label class="form-label">Location *</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col col-6">
                    <div class="form-group">
                        <label class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier-name" readonly>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <span>Receipt Items</span>
                </div>
                <div class="card-body">
                    <table class="table" id="items-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Ordered Qty</th>
                                <th>Received Qty</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody id="items-tbody">
                            <tr>
                                <td colspan="4" class="text-center">Please select a Purchase Order first</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Goods Receipt</button>
                <a href="{{ route('purchase.goods_receipt.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Build purchase items data from PHP data
    const purchaseItemsData = {};
    
    @foreach($purchases as $purchase)
    purchaseItemsData[{{ $purchase->id }}] = {};
        @foreach($purchase->items as $item)
        purchaseItemsData[{{ $purchase->id }}][{{ $item->product_id }}] = {
            product_name: "{{ $item->product->name ?? 'N/A' }}",
            product_sku: "{{ $item->product->sku ?? 'N/A' }}",
            qty: {{ $item->qty }},
            price: {{ $item->price }}
        };
        @endforeach
    @endforeach
    
    function loadPurchaseItems() {
        const purchaseId = document.getElementById('purchase-select').value;
        const supplierOption = document.getElementById('purchase-select').selectedOptions[0];
        const supplierName = supplierOption ? supplierOption.dataset.supplier : '';
        
        document.getElementById('supplier-name').value = supplierName;
        
        const tbody = document.getElementById('items-tbody');
        
        if (!purchaseId || !purchaseItemsData[purchaseId]) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Please select a Purchase Order first</td></tr>';
            return;
        }
        
        let html = '';
        const items = purchaseItemsData[purchaseId];
        
        for (const [productId, item] of Object.entries(items)) {
            html += `
                <tr>
                    <td>
                        ${item.product_name} (${item.product_sku})
                        <input type="hidden" name="items[${productId}][product_id]" value="${productId}">
                    </td>
                    <td>${item.qty}</td>
                    <td>
                        <input type="number" name="items[${productId}][qty_received]" class="form-control" min="0" max="${item.qty}" value="${item.qty}" required>
                    </td>
                    <td>${parseFloat(item.price).toFixed(2)}</td>
                </tr>
            `;
        }
        
        tbody.innerHTML = html;
    }
</script>
@endsection

