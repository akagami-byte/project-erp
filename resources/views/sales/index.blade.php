@extends('layouts.app')

@section('title', 'Sales')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / Sales
@endsection

@section('header-actions')
    <a href="{{ route('sales.create') }}" class="btn btn-primary">+ New Sales</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <span>Sales Order List</span>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Sales #</th>
                    <th>Date</th>
                    <th>Branch</th>
                    <th>Customer Name</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td>SO-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sales_date)->format('d/m/Y') }}</td>
                    <td>{{ $sale->branch }}</td>
                    <td>{{ $sale->customer_name }}</td>
                    <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-secondary">View</a>
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will restore stock.')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No sales found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

