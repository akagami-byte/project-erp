@extends('layouts.app')

@section('title', 'New Sales')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('sales.index') }}">Sales</a> / New Sales
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Create New Sales Order</span>
    </div>
    <div class="card-body">
        <form action="{{ route('sales.store') }}" method="POST" id="sales-form">
            @csrf
            
            <div class="row mb-4">
                <div class="col col-4">
                    <div class="form-group">
                        <label class="form-label">Branch *</label>
                        <input type="text" name="branch" class="form-control" value="{{ old('branch') }}" required>
                        @error('branch')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col col-4">
                    <div class="form-group">
                        <label class="form-label">Sales Date *</label>
                        <input type="date" name="sales_date" class="form-control" value="{{ old('sales_date', date('Y-m-d')) }}" required>
                        @error('sales_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col col-4">
                    <div class="form-group">
                        <label class="form-label">Customer Name *</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header d-flex justify-between">
                    <span>Sales Items</span>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addItemRow()">+ Add Item</button>
                </div>
                <div class="card-body">
                    <table class="table items-table" id="items-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-tbody">
                            <tr class="item-row">
                                <td>
                                    <select name="items[0][product_id]" class="form-control product-select" onchange="updatePrice(this)" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                                                {{ $product->name }} (SKU: {{ $product->sku }}) - Stock: {{ $product->stock }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="items[0][qty]" class="form-control qty-input" min="1" value="1" onchange="calculateItemTotal(this.closest('tr'))" required>
                                </td>
                                <td>
                                    <input type="number" name="items[0][price]" class="form-control price-input" min="0" step="0.01" value="0" onchange="calculateItemTotal(this.closest('tr'))" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control subtotal-input" value="0" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)">×</button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                <td><strong id="grand-total">0.00</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Sales</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let itemCount = 1;
    
    function updatePrice(select) {
        const option = select.options[select.selectedIndex];
        const price = option.dataset.price || 0;
        const row = select.closest('tr');
        row.querySelector('.price-input').value = price;
        calculateItemTotal(row);
    }
    
    function calculateItemTotal(row) {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = qty * price;
        row.querySelector('.subtotal-input').value = total.toFixed(2);
        calculateGrandTotal();
    }
    
    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            grandTotal += qty * price;
        });
        document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
    }
    
    function addItemRow() {
        const tbody = document.getElementById('items-tbody');
        const row = document.createElement('tr');
        row.className = 'item-row';
        row.innerHTML = `
            <td>
                <select name="items[${itemCount}][product_id]" class="form-control product-select" onchange="updatePrice(this)" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                            {{ $product->name }} (SKU: {{ $product->sku }}) - Stock: {{ $product->stock }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="items[${itemCount}][qty]" class="form-control qty-input" min="1" value="1" onchange="calculateItemTotal(this.closest('tr'))" required>
            </td>
            <td>
                <input type="number" name="items[${itemCount}][price]" class="form-control price-input" min="0" step="0.01" value="0" onchange="calculateItemTotal(this.closest('tr'))" required>
            </td>
            <td>
                <input type="number" class="form-control subtotal-input" value="0" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)">×</button>
            </td>
        `;
        tbody.appendChild(row);
        itemCount++;
    }
    
    function removeItemRow(button) {
        const tbody = document.getElementById('items-tbody');
        if (tbody.children.length > 1) {
            button.closest('tr').remove();
            calculateGrandTotal();
        }
    }
</script>
@endsection

