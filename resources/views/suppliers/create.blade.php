@extends('layouts.app')

@section('title', 'Add Supplier')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('suppliers.index') }}">Suppliers</a> / Add Supplier
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Add New Supplier</span>
    </div>
    <div class="card-body">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col col-6">
                    <div class="form-group">
                        <label class="form-label">Supplier Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col col-6">
                    <div class="form-group">
                        <label class="form-label">Currency *</label>
                        <select name="currency" class="form-control" required>
                            <option value="IDR" {{ old('currency') == 'IDR' ? 'selected' : '' }}>IDR (Rupiah)</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                        </select>
                        @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Supplier</button>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

