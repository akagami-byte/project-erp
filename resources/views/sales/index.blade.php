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
                    <th>Payment</th>
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
                        @if($sale->payment_status === 'PAID')
                            <span class="badge bg-success">Paid</span>
                        @else
                            <button type="button"
                                    class="btn btn-sm btn-success pay-btn"
                                    data-charge-url="{{ route('sales.midtrans.charge', $sale) }}"
                                    data-pay-url="{{ route('sales.pay', $sale) }}">
                                Pay
                            </button>
                        @endif
                    </td>
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
                    <td colspan="7" class="text-center">No sales found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($sales->where('payment_status', 'UNPAID')->count() > 0)
    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.querySelectorAll('.pay-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const btn = this;
                const chargeUrl = btn.dataset.chargeUrl;
                const payUrl = btn.dataset.payUrl;

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';

                fetch(chargeUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                        btn.disabled = false;
                        btn.innerHTML = 'Pay';
                        return;
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: function () {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = payUrl;
                            form.style.display = 'none';

                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            form.appendChild(csrfToken);

                            document.body.appendChild(form);
                            form.submit();
                        },
                        onPending: function () {
                            alert('Pembayaran pending. Silakan lanjutkan pembayaran Anda.');
                            btn.disabled = false;
                            btn.innerHTML = 'Pay';
                        },
                        onError: function (result) {
                            alert('Pembayaran gagal: ' + (result.status_message || 'Terjadi kesalahan.'));
                            btn.disabled = false;
                            btn.innerHTML = 'Pay';
                        },
                        onClose: function () {
                            btn.disabled = false;
                            btn.innerHTML = 'Pay';
                        }
                    });
                })
                .catch(function(error) {
                    alert('Terjadi kesalahan jaringan. Coba lagi. ' + error.message);
                    btn.disabled = false;
                    btn.innerHTML = 'Pay';
                });
            });
        });
    </script>
@endif
@endsection

