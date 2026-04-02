@extends('layouts.app')

@section('title', 'View Supplier')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('suppliers.index') }}">Suppliers</a> / View Supplier
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Supplier Details</span>
        <div class="actions">
            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning">Edit</a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-secondary">Back</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th width="30%">ID</th>
                <td>{{ $supplier->id }}</td>
            </tr>
            <tr>
                <th>Supplier Name</th>
                <td>{{ $supplier->name }}</td>
            </tr>
            <tr>
                <th>Currency</th>
                <td>{{ $supplier->currency }}</td>
            </tr>
            <tr>
                <th>Created At</th>
                <td>{{ $supplier->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Updated At</th>
                <td>{{ $supplier->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection

