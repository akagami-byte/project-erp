@extends('layouts.app')

@section('title', 'Stock Opname')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Stock Opname
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Stock Opname - Physical Inventory Check</span>
    </div>
    <div class="card-body">
        <form action="{{ route('stock_opname.store') }}" method="POST">
            @csrf
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Current Stock (System)</th>
                        <th>Actual Stock (Physical)</th>
                        <th>Difference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>
                            <span class="badge badge-primary">{{ $product->stock }}</span>
                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $product->id }}">
                            <input type="hidden" name="items[{{ $index }}][system_stock]" value="{{ $product->stock }}" class="system-stock">
                        </td>
                        <td>
                            <input type="number" name="items[{{ $index }}][actual_stock]" class="form-control actual-stock" min="0" value="{{ $product->stock }}" onchange="calculateDifference(this)" required>
                        </td>
                        <td>
                            <span class="difference">0</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Stock Adjustment</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function calculateDifference(input) {
        const row = input.closest('tr');
        const systemStock = parseInt(row.querySelector('.system-stock').value) || 0;
        const actualStock = parseInt(input.value) || 0;
        const difference = actualStock - systemStock;
        
        const diffSpan = row.querySelector('.difference');
        diffSpan.textContent = (difference > 0 ? '+' : '') + difference;
        
        if (difference > 0) {
            diffSpan.className = 'badge badge-success';
        } else if (difference < 0) {
            diffSpan.className = 'badge badge-danger';
        } else {
            diffSpan.className = 'badge badge-primary';
        }
    }
</script>
@endsection

