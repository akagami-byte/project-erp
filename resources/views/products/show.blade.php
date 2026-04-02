@extends('layouts.app')

@section('title', 'View Product')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('products.index') }}">Products</a> / View Product
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Product Details</span>
        <div class="actions">
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">Back</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="30%">SKU</th>
                <td>{{ $product->sku }}</td>
            </tr>
            <tr>
                <th>Product Name</th>
                <td>{{ $product->name }}</td>
            </tr>
            <tr>
                <th>Price</th>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Stock</th>
                <td>
                    @if($product->stock < 10)
                        <span class="badge badge-danger">{{ $product->stock }}</span>
                    @else
                        <span class="badge badge-success">{{ $product->stock }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Created At</th>
                <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Updated At</th>
                <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection

