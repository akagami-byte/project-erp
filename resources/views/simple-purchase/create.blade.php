@extends('layouts.app')

@section('title', 'New Simple Purchase')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('simple-purchases.index') }}">Simple Purchases</a> / New Simple Purchase
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Create New Simple Purchase (Prepaid)</span>
    </div>
    <div class="card-body">
        <form action="{{ route('simple-purchases.store') }}" method="POST" id="simple-purchase-form">
            @csrf

            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                            <option value="">Pilih Metode</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <span>Purchase Items</span>
                    <button type="button" class="btn btn-sm btn-success shadow-sm" onclick="addItemRow()">
                        <i class="me-1">+</i> Tambah Item
                    </button>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive" style="border-radius: 0.5rem; border: 1px solid #dee2e6; overflow: hidden;">
                        <table class="table items-table table-hover table-sm mb-0" id="items-table" style="table-layout: fixed; width: 100%; margin-bottom: 0;">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-2" style="width: 40%; font-weight: 600; border-bottom: 2px solid #dee2e6;">Product</th>
                                    <th class="py-3 px-2 text-center" style="width: 12%; font-weight: 600; border-bottom: 2px solid #dee2e6;">Qty</th>
                                    <th class="py-3 px-2 text-end" style="width: 18%; font-weight: 600; border-bottom: 2px solid #dee2e6;">Price (Rp)</th>
                                    <th class="py-3 px-2 text-end" style="width: 18%; font-weight: 600; border-bottom: 2px solid #dee2e6;">Subtotal</th>
                                    <th class="py-3 px-2 text-center" style="width: 12%; font-weight: 600; border-bottom: 2px solid #dee2e6;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="items-tbody">
                            <tr class="item-row">
                                <td>
                                    <select name="items[0][product_id]" class="form-control product-select" onchange="updatePrice(this)" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                {{ $product->name }} ({{ $product->sku ?? '' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="items[0][qty_order]" class="form-control qty-input" min="1" value="1" onchange="calculateItemTotal(this.closest('tr'))" required>
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
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong id="grand-total">0</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top justify-content-start">
                <a href="{{ route('simple-purchases.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan Simple Purchase
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const allProducts = @json($products->toArray());
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
        row.querySelector('.subtotal-input').value = total.toFixed(0);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            grandTotal += parseFloat(row.querySelector('.subtotal-input').value) || 0;
        });
        document.getElementById('grand-total').textContent = new Intl.NumberFormat('id-ID').format(Math.round(grandTotal));
    }

    function addItemRow() {
        const tbody = document.getElementById('items-tbody');
        const row = document.createElement('tr');
        row.className = 'item-row';
        
        let optionsHtml = '<option value="">-- Select Product --</option>';
        allProducts.forEach(p => {
            optionsHtml += '<option value="' + p.id + '" data-price="' + p.price + '">' + p.name + ' (' + (p.sku || '') + ')</option>';
        });
        
        let html = '<td>' +
            '<select name="items[' + itemCount + '][product_id]" class="form-control product-select" onchange="updatePrice(this)" required>' +
            optionsHtml +
            '</select>' +
            '</td>' +
            '<td>' +
            '<input type="number" name="items[' + itemCount + '][qty_order]" class="form-control qty-input" min="1" value="1" onchange="calculateItemTotal(this.closest(\'tr\'))" required>' +
            '</td>' +
            '<td>' +
            '<input type="number" name="items[' + itemCount + '][price]" class="form-control price-input" min="0" step="0.01" value="0" onchange="calculateItemTotal(this.closest(\'tr\'))" required>' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control subtotal-input" value="0" readonly>' +
            '</td>' +
            '<td>' +
            '<button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)">×</button>' +
            '</td>';
        
        row.innerHTML = html;
        tbody.appendChild(row);
        itemCount++;
    }

    function removeItemRow(button) {
        if (document.querySelectorAll('.item-row').length > 1) {
            button.closest('tr').remove();
            calculateGrandTotal();
        }
    }
</script>
@endsection

