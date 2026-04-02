@extends('layouts.app')

@section('title', 'Suppliers')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Suppliers
@endsection

@section('header-actions')
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">+ Add Supplier</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Supplier List</span>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supplier Name</th>
                    <th>Currency</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->currency }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-sm btn-secondary">View</a>
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No suppliers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

