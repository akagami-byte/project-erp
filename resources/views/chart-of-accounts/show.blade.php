@extends('layouts.app')

@section('title', $chartOfAccount->account_name)

@section('breadcrumb')
    <a href="{{ route('chart-of-accounts.index') }}">Master COA</a> > {{ $chartOfAccount->account_code }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h2>{{ $chartOfAccount->account_code }} - {{ $chartOfAccount->account_name }}</h2>
            </div>
            <div class="card-body">
                <p><strong>Type:</strong> <span class="badge badge-primary">{{ ucfirst($chartOfAccount->account_type) }}</span></p>
                @if($chartOfAccount->parent)
                    <p><strong>Parent:</strong> {{ $chartOfAccount->parent->account_code }} - {{ $chartOfAccount->parent->account_name }}</p>
                @endif
                <p><strong>Status:</strong> 
                    @if($chartOfAccount->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-warning">Inactive</span>
                    @endif
                </p>
                <p><strong>Created:</strong> {{ $chartOfAccount->created_at->format('d M Y') }}</p>
                <div class="mt-4">
                    <a href="{{ route('chart-of-accounts.edit', $chartOfAccount) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3>Child Accounts</h3>
            </div>
            <div class="card-body">
                @if($chartOfAccount->children->count() > 0)
                    <ul>
                        @foreach($chartOfAccount->children as $child)
                            <li>{{ $child->account_code }} - {{ $child->account_name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No child accounts.</p>
                @endif
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h3>Recent Journal Usage</h3>
            </div>
            <div class="card-body">
                @if($chartOfAccount->journalDetails->count() > 0)
                    @foreach($chartOfAccount->journalDetails->take(5) as $detail)
                        <div class="d-flex justify-between small mb-1">
                            <span>{{ $detail->journalEntry->reference_code }}</span>
                            <span>Dr: {{ number_format($detail->debit,0,',','.') }} Cr: {{ number_format($detail->credit,0,',','.') }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No journal entries yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

