@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Detail Pembayaran</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Info -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID Pembayaran:</strong></td>
                                    <td>{{ $payment->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. Transaksi:</strong></td>
                                    <td>{{ $payment->master_transaction_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Metode Pembayaran:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $payment->method == 'online' ? 'info' : 'secondary' }}">
                                            {{ $payment->method_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe Pembayaran:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $payment->type == 'dp' ? 'warning' : 'primary' }}">
                                            {{ $payment->type_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah:</strong></td>
                                    <td><h5 class="text-primary">{{ $payment->formatted_amount }}</h5></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $payment->status_badge }} fs-6">
                                            {{ $payment->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $payment->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                @if($payment->paid_at)
                                <tr>
                                    <td><strong>Dibayar:</strong></td>
                                    <td>{{ $payment->paid_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                @endif
                                @if($payment->method == 'online' && $payment->midtrans_order_id)
                                <tr>
                                    <td><strong>Midtrans Order ID:</strong></td>
                                    <td><code>{{ $payment->midtrans_order_id }}</code></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($payment->payment_status == 'pending' && $payment->method == 'offline')
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading"><i class="fas fa-clock"></i> Pembayaran Manual Pending</h6>
                                <p class="mb-2">Pembayaran ini menunggu konfirmasi admin untuk pembayaran transfer manual.</p>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success btn-sm" onclick="confirmPayment({{ $payment->id }})">
                                        <i class="fas fa-check"></i> Konfirmasi Pembayaran
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="rejectPayment({{ $payment->id }})">
                                        <i class="fas fa-times"></i> Tolak Pembayaran
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Customer</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ $payment->masterTransaction->customer->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $payment->masterTransaction->customer->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. HP:</strong></td>
                                    <td>{{ $payment->masterTransaction->customer->no_hp }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Acara</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Tanggal Acara:</strong></td>
                                    <td>{{ $payment->masterTransaction->tanggal_acara->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu:</strong></td>
                                    <td>{{ $payment->masterTransaction->waktu_acara }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Pesanan:</strong></td>
                                    <td>
                                        <span class="badge {{ $payment->masterTransaction->status_badge }}">
                                            {{ $payment->masterTransaction->status_text }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h6 class="mt-3">Alamat Pengiriman</h6>
                    <p class="text-muted">{{ $payment->masterTransaction->alamat_pengiriman }}</p>

                    @if($payment->masterTransaction->catatan_customer)
                    <h6 class="mt-3">Catatan Customer</h6>
                    <p class="text-muted">{{ $payment->masterTransaction->catatan_customer }}</p>
                    @endif
                </div>
            </div>
            <!-- Bukti Pembayaran -->
            <div class="card mt-3">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        Bukti Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><strong>Tanggal:</strong></td>
                                    <td>{{ $payment->masterTransaction->tanggal_transaksi->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        @if($payment->proof_file != null)
                                            <strong>Bukti Pembayaran:</strong>
                                            <a href="{{ asset('storage/' . $payment->proof_file) }}" data-toggle="lightbox" data-title="Bukti Pembayaran">
                                                <img src="{{ asset('storage/' . $payment->proof_file) }}" alt="bukti pembayaran" class="img-fluid img-thumbnail">
                                            </a>
                                        @endif
                                    </td>

                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Tipe</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payment->masterTransaction->detailTransaksi as $detail)
                                <tr>
                                    <td>{{ $detail->item_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $detail->item_type == 'produk' ? 'primary' : 'info' }}">
                                            {{ ucfirst($detail->item_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $detail->qty }}</td>
                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total Pesanan:</th>
                                    <th>{{ $payment->masterTransaction->formatted_total }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Total Pesanan:</strong></td>
                            <td class="text-end">{{ $payment->masterTransaction->formatted_total }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Terbayar:</strong></td>
                            <td class="text-end text-success">{{ $payment->masterTransaction->formatted_total_paid }}</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Sisa Pembayaran:</strong></td>
                            <td class="text-end {{ $payment->masterTransaction->remaining_amount > 0 ? 'text-danger' : 'text-success' }}">
                                <strong>{{ $payment->masterTransaction->formatted_remaining_amount }}</strong>
                            </td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <span class="badge {{ $payment->masterTransaction->payment_status_badge }} fs-6">
                            {{ $payment->masterTransaction->payment_status_text }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- All Payments for this Transaction -->
            @if(count($payment->masterTransaction->payments) > 1)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Riwayat Pembayaran</h5>
                </div>
                <div class="card-body">
                    @foreach($payment->masterTransaction->payments->sortBy('created_at') as $p)
                    <div class="d-flex justify-content-between align-items-center mb-2 {{ $p->id == $payment->id ? 'bg-light p-2 rounded' : '' }}">
                        <div>
                            <span class="badge bg-{{ $p->type == 'dp' ? 'warning' : 'primary' }}">
                                {{ $p->type_label }}
                            </span>
                            <br>
                            <small class="text-muted">{{ $p->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="text-end">
                            <div>{{ $p->formatted_amount }}</div>
                            <span class="badge {{ $p->status_badge }}">{{ $p->status_text }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    @if($payment->payment_status == 'pending' && $payment->method == 'offline')
                    <button type="button" class="btn btn-success w-100 mb-2" onclick="confirmPayment({{ $payment->id }})">
                        <i class="fas fa-check"></i> Konfirmasi Pembayaran
                    </button>
                    <button type="button" class="btn btn-danger w-100 mb-2" onclick="rejectPayment({{ $payment->id }})">
                        <i class="fas fa-times"></i> Tolak Pembayaran
                    </button>
                    @endif

                    @if(!$payment->masterTransaction->is_fully_paid)
                    <button type="button" class="btn btn-warning w-100 mb-2" onclick="sendReminder('{{ $payment->master_transaction_id }}')">
                        <i class="fas fa-bell"></i> Kirim Reminder
                    </button>
                    @endif

                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-list"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Midtrans Details -->
             @if($payment->method == 'online' && $payment->midtrans_status)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Midtrans</h5>
                </div>
                <div class="card-body">
                    @php
                        // Perbaikan: Handle jika data sudah array atau masih string
                        $midtransData = $payment->midtrans_status;
                        
                        // Jika masih string, decode
                        if (is_string($midtransData)) {
                            $midtransData = json_decode($midtransData, true);
                        }
                        
                        // Jika hasil decode masih string (double encoded), decode lagi
                        if (is_string($midtransData)) {
                            $midtransData = json_decode($midtransData, true);
                        }
                        
                        // Ensure array
                        if (!is_array($midtransData)) {
                            $midtransData = [];
                        }
                    @endphp
                    
                    @if($midtransData && is_array($midtransData) && count($midtransData) > 0)
                    <table class="table table-borderless table-sm">
                        @if(isset($midtransData['transaction_id']))
                        <tr>
                            <td><strong>Transaction ID:</strong></td>
                            <td><small>{{ $midtransData['transaction_id'] }}</small></td>
                        </tr>
                        @endif
                        @if(isset($midtransData['payment_type']))
                        <tr>
                            <td><strong>Payment Type:</strong></td>
                            <td>{{ $midtransData['payment_type'] }}</td>
                        </tr>
                        @endif
                        @if(isset($midtransData['va_numbers']) && is_array($midtransData['va_numbers']) && count($midtransData['va_numbers']) > 0)
                        <tr>
                            <td><strong>VA Number:</strong></td>
                            <td><code>{{ $midtransData['va_numbers'][0]['va_number'] ?? '' }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Bank:</strong></td>
                            <td>{{ strtoupper($midtransData['va_numbers'][0]['bank'] ?? '') }}</td>
                        </tr>
                        @endif
                        @if(isset($midtransData['permata_va_number']))
                        <tr>
                            <td><strong>Permata VA:</strong></td>
                            <td><code>{{ $midtransData['permata_va_number'] }}</code></td>
                        </tr>
                        @endif
                        @if(isset($midtransData['transaction_time']))
                        <tr>
                            <td><strong>Transaction Time:</strong></td>
                            <td>{{ $midtransData['transaction_time'] }}</td>
                        </tr>
                        @endif
                        @if(isset($midtransData['settlement_time']))
                        <tr>
                            <td><strong>Settlement Time:</strong></td>
                            <td>{{ $midtransData['settlement_time'] }}</td>
                        </tr>
                        @endif
                        @if(isset($midtransData['transaction_status']))
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge bg-{{ in_array($midtransData['transaction_status'], ['settlement', 'capture']) ? 'success' : (in_array($midtransData['transaction_status'], ['pending']) ? 'warning' : 'danger') }}">
                                    {{ strtoupper($midtransData['transaction_status']) }}
                                </span>
                            </td>
                        </tr>
                        @endif
                    </table>
                    @else
                    <div class="alert alert-info mb-0">
                        <p class="mb-0">Data Midtrans tidak tersedia atau belum ada transaksi.</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmPayment(id) {
    if (confirm('Yakin ingin mengkonfirmasi pembayaran ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/payments/${id}/confirm`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectPayment(id) {
    const reason = prompt('Alasan penolakan:');
    if (reason) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/payments/${id}/reject`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reason';
        reasonInput.value = reason;
        
        form.appendChild(csrfToken);
        form.appendChild(reasonInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function sendReminder(transactionId) {
    if (confirm('Kirim reminder pembayaran ke customer?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/payments/reminder/${transactionId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush