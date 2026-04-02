@extends('layouts.app')

@section('title', 'Edit ' . $chartOfAccount->account_name)

@section('breadcrumb')
    <a href="{{ route('chart-of-accounts.index') }}">Master COA</a> > Edit {{ $chartOfAccount->account_code }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Edit Account {{ $chartOfAccount->account_code }}</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('chart-of-accounts.update', $chartOfAccount) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Account Code *</label>
                        <input type="text" name="account_code" class="form-control @error('account_code') is-invalid @enderror" value="{{ old('account_code', $chartOfAccount->account_code) }}" required>
                        @error('account_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Account Name *</label>
                        <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ old('account_name', $chartOfAccount->account_name) }}" required>
                        @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Account Type *</label>
                        <select name="account_type" class="form-control @error('account_type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="asset" {{ old('account_type', $chartOfAccount->account_type) == 'asset' ? 'selected' : '' }}>Asset</option>
                            <option value="liability" {{ old('account_type', $chartOfAccount->account_type) == 'liability' ? 'selected' : '' }}>Liability</option>
                            <option value="equity" {{ old('account_type', $chartOfAccount->account_type) == 'equity' ? 'selected' : '' }}>Equity</option>
                            <option value="revenue" {{ old('account_type', $chartOfAccount->account_type) == 'revenue' ? 'selected' : '' }}>Revenue</option>
                            <option value="cos" {{ old('account_type', $chartOfAccount->account_type) == 'cos' ? 'selected' : '' }}>Cost of Sales</option>
                            <option value="expense" {{ old('account_type', $chartOfAccount->account_type) == 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                        @error('account_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Parent Account</label>
                        <select name="parent_id" class="form-control">
                            <option value="">-- No Parent --</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $chartOfAccount->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->account_code }} - {{ $parent->account_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $chartOfAccount->is_active) ? 'checked' : '' }}> Active
                </label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Account</button>
                <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

