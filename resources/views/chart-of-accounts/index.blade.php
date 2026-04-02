@extends('layouts.app')

@section('title', 'Master Chart of Accounts')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-between">
        <h2>Chart of Accounts</h2>
        <a href="{{ route('chart-of-accounts.create') }}" class="btn btn-primary">Create Account</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Account Name</th>
                        <th>Type</th>
                        <th>Parent</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $account)
                    <tr>
                        <td>{{ $account->account_code }}</td>
                        <td>{{ $account->account_name }}</td>
                        <td>
                            <span class="badge badge-primary">{{ ucfirst($account->account_type) }}</span>
                        </td>
                        <td>{{ $account->parent ? $account->parent->account_name : '-' }}</td>
                        <td>
                            @if($account->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-warning">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('chart-of-accounts.show', $account) }}" class="btn btn-sm btn-primary">View</a>
                                <a href="{{ route('chart-of-accounts.edit', $account) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('chart-of-accounts.destroy', $account) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this account?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                @if($account->is_active)
                                <form action="{{ route('chart-of-accounts.deactivate', $account) }}" method="POST" style="display:inline" onsubmit="return confirm('Deactivate this account?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-secondary">Deactivate</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No accounts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $accounts->links() }}
    </div>
</div>
@endsection

