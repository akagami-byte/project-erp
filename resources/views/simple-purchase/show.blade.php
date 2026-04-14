@extends('layouts.app')

@section('title', 'Simple Purchase Detail')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a> / <a href="{{ route('simple-purchases.index') }}">Simple Purchases</a> / View
@endsection

@section('content')

{{-- Success / Error Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="me-1">✅</i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="me-1">❌</i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span>Simple Purchase #SP-{{ str_pad($simplePurchase->id, 5, '0', STR_PAD_LEFT) }}</span>
        <div>
            <a href="{{ route('simple-purchases.index') }}" class="btn btn-sm btn-secondary">Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th>Supplier</th>
                        <td>{{ $simplePurchase->supplier->name }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ \Carbon\Carbon::parse($simplePurchase->date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td>{{ ucfirst($simplePurchase->payment_method) }}</td>
                    </tr>
                    <tr>
                        <th>Payment Status</th>
                        <td>
                            @if($simplePurchase->payment_status == 'PAID')
                                <span class="badge bg-success">{{ $simplePurchase->payment_status }}</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ $simplePurchase->payment_status }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Receipt Status</th>
                        <td>
                            @if($simplePurchase->receipt_status == 'RECEIVED')
                                <span class="badge bg-success">{{ $simplePurchase->receipt_status }}</span>
                            @elseif($simplePurchase->receipt_status == 'PARTIAL')
                                <span class="badge bg-warning text-dark">{{ $simplePurchase->receipt_status }}</span>
                            @else
                                <span class="badge bg-danger">{{ $simplePurchase->receipt_status }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td><strong>Rp {{ number_format($simplePurchase->total_price, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <span>Items ({{ $simplePurchase->items->count() }})</span>

                {{-- Pay Button — Midtrans Snap --}}
                @if($simplePurchase->payment_status == 'UNPAID')
                    <button id="pay-btn"
                            class="btn btn-sm btn-success"
                            data-charge-url="{{ route('simple-purchases.midtrans.charge', $simplePurchase) }}"
                            data-client-key="{{ config('midtrans.client_key') }}">
                        <i class="me-1">💰</i> Bayar
                    </button>
                @else
                    <span class="badge bg-success fs-6">PAID</span>
                @endif

                {{-- Update Receipt Button --}}
                @if($simplePurchase->receipt_status != 'RECEIVED')
                    <button type="button" class="btn btn-sm btn-primary" onclick="toggleReceiptForm()">
                        <i class="me-1">📦</i> Update Receipt
                    </button>
                @endif
            </div>
            <div class="card-body">
                <form id="receipt-form" action="{{ route('simple-purchases.updateReceipt', $simplePurchase) }}" method="POST" style="display: none;">
                    @csrf
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty Order</th>
                                <th>Qty Received</th>
                                <th>Qty Receive Now</th>
                                <th>Remaining</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($simplePurchase->items as $item)
                                @php
                                    $remaining = $item->qty_order - $item->qty_received;
                                @endphp
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->qty_order }}</td>
                                    <td>{{ $item->qty_received }}</td>
                                    <td>
                                        <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                        <input type="number" name="items[{{ $loop->index }}][received_now]" value="0" min="0" max="{{ $remaining }}" class="form-control form-control-sm" style="width: 100px;" {{ $remaining === 0 ? 'readonly' : '' }}>
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ $remaining }}</span></td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="me-1">✓</i> Simpan Receipt
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="toggleReceiptForm()">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Midtrans Snap.js --}}
@if($simplePurchase->payment_status == 'UNPAID')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    document.getElementById('pay-btn').addEventListener('click', function () {
        const btn = this;
        const chargeUrl = btn.getAttribute('data-charge-url');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';

        fetch(chargeUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept':       'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
                btn.disabled = false;
                btn.innerHTML = '💰 Bayar';
                return;
            }

            // Open Midtrans Snap popup
            window.snap.pay(data.snap_token, {
                onSuccess: function (result) {
                    alert('✅ Pembayaran berhasil! Status akan diperbarui.');
                    
                    // Create a form to manually call the pay route (since webhook doesn't work on localhost without ngrok)
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('simple-purchases.pay', $simplePurchase) }}';
                    form.style.display = 'none';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                },
                onPending: function (result) {
                    alert('⏳ Pembayaran pending. Silakan selesaikan pembayaran Anda.');
                    window.location.reload();
                },
                onError: function (result) {
                    alert('❌ Pembayaran gagal: ' + (result.status_message || 'Terjadi kesalahan.'));
                    btn.disabled = false;
                    btn.innerHTML = '💰 Bayar';
                },
                onClose: function () {
                    btn.disabled = false;
                    btn.innerHTML = '💰 Bayar';
                },
            });
        })
        .catch(err => {
            alert('Terjadi kesalahan jaringan. Coba lagi.'  + err.message);
            btn.disabled = false;
            btn.innerHTML = '💰 Bayar';
        });
    });
</script>
@endif

<script>
    function toggleReceiptForm() {
        const form = document.getElementById('receipt-form');
        if (form.style.display === 'none') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>
@endsection
