@extends('layouts.app')

@section('title', 'Journal #' . $journalEntry->reference_code)

@section('breadcrumb')
    <a href="{{ route('accounting.index') }}">General Journal</a> > {{ $journalEntry->reference_code }}
@endsection

@section('content')
<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h2>Journal Detail #{{ $journalEntry->reference_code }}</h2>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <strong>Date:</strong> {{ $journalEntry->journal_date->format('d M Y') }}
                    </div>
                    <div class="col-md-3">
                        <strong>Type:</strong> {{ ucfirst($journalEntry->transaction_type) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Reference:</strong> {{ $journalEntry->reference_code }}
                    </div>
                    <div class="col-md-3">
                        <strong>Created:</strong> {{ $journalEntry->created_at->format('d M Y H:i') }}
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Description</th>
                            <th class="text-right">Debit</th>
                            <th class="text-right">Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($journalEntry->details as $detail)
                        <tr>
                            <td>
                                {{ $detail->account->account_code }} - {{ $detail->account->account_name }}
                                @if($detail->account->parent)
                                    <small class="text-muted">({{ $detail->account->parent->account_name }})</small>
                                @endif
                            </td>
                            <td>{{ $detail->description }}</td>
                            <td class="text-right">{{ $detail->debit > 0 ? number_format($detail->debit, 0, ',', '.') : '-' }}</td>
                            <td class="text-right">{{ $detail->credit > 0 ? number_format($detail->credit, 0, ',', '.') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td colspan="2">TOTAL</td>
                            <td class="text-right">{{ number_format($journalEntry->total_debit, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($journalEntry->total_credit, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>

                @if(!$journalEntry->is_balanced)
                    <div class="alert alert-warning">
                        ⚠️ This journal is not balanced!
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <h3>Summary</h3>
            </div>
            <div class="card-body text-center">
                <h1>{{ number_format($journalEntry->total_debit, 0, ',', '.') }}</h1>
                <p><strong>Total Amount</strong></p>
                <span class="badge {{ $journalEntry->is_balanced ? 'badge-success' : 'badge-danger' }}">
                    {{ $journalEntry->is_balanced ? 'Balanced' : 'Unbalanced' }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

