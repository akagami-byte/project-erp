@extends('layouts.app')

@section('title', 'General Journal')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>General Journal</h2>
        <div class="actions">
            <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary btn-sm">Master COA</a>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-3">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-3">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-control" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Goods Receipt" {{ request('type') == 'Goods Receipt' ? 'selected' : '' }}>Goods Receipt</option>
                    <option value="Sales" {{ request('type') == 'Sales' ? 'selected' : '' }}>Sales</option>
                    <option value="Payment" {{ request('type') == 'Payment' ? 'selected' : '' }}>Payment</option>
                </select>
            </div>
            <div class="col-2">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference/Description" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-2">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('accounting.index') }}" class="btn btn-secondary btn-sm">Clear</a>
                </div>
            </div>
        </div>
        <form method="GET">
            @csrf
        </form>

        <!-- Journals Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Journal Date</th>
                        <th>Transaction Type</th>
                        <th>Reference Code</th>
                        <th>Description</th>
                        <th>Debit Total</th>
                        <th>Credit Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($journals as $journal)
                    <tr>
                        <td>{{ $journal->journal_date->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($journal->transaction_type) }}</td>
                        <td><a href="{{ route('accounting.show', $journal) }}">{{ $journal->reference_code }}</a></td>
                        <td>{{ Str::limit($journal->description, 50) }}</td>
                        <td class="text-right">{{ number_format($journal->total_debit, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($journal->total_credit, 0, ',', '.') }}</td>
                        <td>
                            @if($journal->is_balanced)
                                <span class="badge badge-success">Balanced</span>
                            @else
                                <span class="badge badge-danger">Unbalanced</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('accounting.show', $journal) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No journals found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-between mt-4">
            {{ $journals->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

