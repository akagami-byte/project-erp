@extends('layouts.app')

@section('title', 'Master Product')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Master Product
@endsection

@section('header-actions')
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Product List</span>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>
                        @if($product->stock < 10)
                            <span class="badge badge-danger">{{ $product->stock }}</span>
                        @else
                            <span class="badge badge-success">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-secondary">View</a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No products found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

