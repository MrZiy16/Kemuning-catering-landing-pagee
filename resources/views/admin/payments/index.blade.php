@extends('layouts.app')

@section('title', 'Management Pembayaran')

@section('content')
<div class="container-fluid px-4">
    {{-- Simple Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Management Pembayaran</h2>
                    <p class="text-muted mb-0">Kelola semua pembayaran customer</p>
                </div>
                <a href="{{ route('admin.payments.analytics') }}" class="btn btn-outline-primary">
                    <i class="fas fa-chart-bar me-2"></i>Analytics
                </a>
            </div>
        </div>
    </div>

    {{-- Simple Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-money-bill-wave text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($stats['today_total'], 0, ',', '.') }}</h4>
                            <small class="text-muted">Pendapatan Hari Ini</small>
                            <div class="mt-1">
                                <span class="badge bg-success bg-opacity-10 text-success">{{ $stats['today_count'] }} transaksi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-clock text-warning fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">{{ $stats['pending_count'] }}</h4>
                            <small class="text-muted">Perlu Konfirmasi</small>
                            <div class="mt-1">
                                <span class="badge bg-warning bg-opacity-10 text-warning">Pembayaran Manual</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-calendar-alt text-info fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($stats['monthly_total'], 0, ',', '.') }}</h4>
                            <small class="text-muted">Total Bulan Ini</small>
                            <div class="mt-1">
                                <span class="badge bg-info bg-opacity-10 text-info">{{ date('F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-check-circle text-success fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block mb-1">Status Pembayaran</small>
                            <div class="d-flex gap-1 mb-2">
                                <span class="badge bg-success">{{ $stats['status_counts']['paid'] }}</span>
                                <span class="badge bg-warning">{{ $stats['status_counts']['pending'] }}</span>
                                <span class="badge bg-danger">{{ $stats['status_counts']['failed'] }}</span>
                            </div>
                            <small class="text-muted">Lunas | Pending | Gagal</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Simple Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payments.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="method" class="form-select">
                            <option value="">Semua Metode</option>
                            <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="offline" {{ request('method') == 'offline' ? 'selected' : '' }}>Transfer Manual</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Dari Tanggal">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Sampai Tanggal">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari...">
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary flex-fill">
                                <i class="fas fa-refresh"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Payment Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">Daftar Pembayaran</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i class="fas fa-plus me-1"></i>Tambah Payment
                    </button>
                    <button type="button" class="btn btn-success" onclick="bulkConfirm()" id="bulkConfirmBtn" style="display: none;">
                        <i class="fas fa-check me-1"></i>Konfirmasi Terpilih
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th class="px-3">No. Transaksi</th>
                            <th class="px-3">Customer</th>
                            <th class="px-3">Metode</th>
                            <th class="px-3">Jumlah</th>
                            <th class="px-3">Status</th>
                            <th class="px-3">Tanggal</th>
                            <th class="px-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td class="px-3">
                                @if($payment->payment_status == 'pending' && $payment->method == 'offline')
                                <input type="checkbox" name="payment_ids[]" value="{{ $payment->id }}" class="form-check-input payment-checkbox">
                                @endif
                            </td>
                            <td class="px-3">
                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="text-decoration-none fw-semibold">
                                    {{ $payment->master_transaction_id }}
                                </a>
                            </td>
                            <td class="px-3">
                                <div>
                                    <div class="fw-medium">{{ $payment->masterTransaction->customer->nama }}</div>
                                    <small class="text-muted">{{ $payment->masterTransaction->customer->email }}</small>
                                </div>
                            </td>
                            <td class="px-3">
                                <span class="badge bg-{{ $payment->method == 'online' ? 'info' : 'secondary' }}">
                                    {{ $payment->method_label }}
                                </span>
                            </td>
                            <td class="px-3">
                                <span class="fw-semibold">{{ $payment->formatted_amount }}</span>
                                @if($payment->type == 'dp')
                                    <br><small class="text-warning">Down Payment</small>
                                @endif
                            </td>
                            <td class="px-3">
                                <span class="badge rounded-pill 
                                    @switch($payment->payment_status)
                                        @case('paid') bg-success @break
                                        @case('pending') bg-warning @break
                                        @case('failed') bg-danger @break
                                        @default bg-secondary
                                    @endswitch
                                ">
                                    {{ $payment->status_text }}
                                </span>
                            </td>
                            <td class="px-3">
                                <div class="text-sm">
                                    {{ $payment->created_at->format('d/m/Y H:i') }}
                                    @if($payment->paid_at)
                                    <br><small class="text-success">Dibayar: {{ $payment->paid_at->format('d/m/Y H:i') }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                       class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($payment->payment_status == 'pending' && $payment->method == 'offline')
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            onclick="confirmPayment({{ $payment->id }})" title="Konfirmasi">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="rejectPayment({{ $payment->id }})" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif

                                    @if(auth()->check() && auth()->user()->peran === 'super_admin' && in_array($payment->payment_status, ['pending','failed','cancelled']))
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                onclick="deletePayment({{ $payment->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif

                                    @if(!$payment->masterTransaction->is_fully_paid)
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            onclick="sendReminder('{{ $payment->master_transaction_id }}')" title="Reminder">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-3 opacity-50"></i>
                                <div>Tidak ada data pembayaran</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-top px-3 py-2">
                @php
                    $hasPagination = method_exists($payments, 'links');
                    $hasLengthAware = method_exists($payments, 'firstItem') && method_exists($payments, 'lastItem') && method_exists($payments, 'total');
                @endphp
                @if($hasPagination)
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                        @if($hasLengthAware)
                            <div class="text-muted small">
                                Menampilkan {{ $payments->firstItem() }}-{{ $payments->lastItem() }} dari {{ $payments->total() }} pembayaran
                            </div>
                        @endif
                        <div class="mb-0">
                            {{ $payments->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Simple Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-primary text-white">
                <h5 class="modal-title fw-semibold" id="addPaymentModalLabel">
                    <i class="fas fa-plus me-2"></i>Tambah Payment Manual
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addPaymentForm" method="POST" action="{{ route('admin.payments.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Form untuk mencatat pembayaran customer yang datang langsung atau pembayaran offline.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Customer <span class="text-danger">*</span></label>
                            <select id="customer_search" name="customer_id" class="form-select" required>
                                <option value="">Pilih Customer</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Transaksi Existing (Opsional)</label>
                            <select class="form-select" id="existing_transaction" name="existing_transaction">
                                <option value="">Buat Transaksi Baru</option>
                                @foreach($transactions as $transaction)
                                    <option value="{{ $transaction->id_transaksi }}" 
                                            data-customer="{{ $transaction->customer->nama }}"
                                            data-total="{{ $transaction->total }}"
                                            data-paid="{{ $transaction->total_paid }}"
                                            data-remaining="{{ $transaction->remaining_amount }}">
                                        {{ $transaction->id_transaksi }} - {{ $transaction->customer->nama }} 
                                        ({{ $transaction->formatted_total }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Transaction Info Display -->
                    <div id="transaction-info" class="alert alert-success border-0 mt-3" style="display: none;">
                        <h6 class="fw-semibold mb-2"><i class="fas fa-info-circle me-1"></i>Info Transaksi</h6>
                        <div id="transaction-details"></div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Jumlah Pembayaran <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       min="1000" step="100" required placeholder="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipe Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment_type" name="payment_type" required>
                                <option value="full">Bayar Penuh</option>
                                <option value="dp">Down Payment</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="cash">Cash/Tunai</option>
                                <option value="bank_transfer">Transfer Bank</option>
                                <option value="debit_card">Kartu Debit</option>
                                <option value="credit_card">Kartu Kredit</option>
                                <option value="e_wallet">E-Wallet</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="payment_date" name="payment_date" 
                                   value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-medium">No. Referensi/Bukti</label>
                        <input type="text" class="form-control" id="reference_number" name="reference_number" 
                               placeholder="No. struk, no. transfer, dll">
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-medium">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" 
                                  placeholder="Catatan tambahan..."></textarea>
                    </div>

                    <!-- Simple Product Selection -->
                    <div class="card bg-light border-0 mt-3">
                        <div class="card-header bg-transparent border-0">
                            <h6 class="mb-0 fw-medium">Detail Pesanan (Opsional - hanya jika transaksi baru)</h6>
                        </div>
                        <div class="card-body">
                            <div id="order-items">
                                <div class="row order-item g-2">
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm" name="items[0][produk_id]">
                                            <option value="">Pilih Produk</option>
                                            @foreach($produk as $p)
                                                <option value="{{ $p->id_produk }}" data-price="{{ $p->harga }}">
                                                    {{ $p->nama_produk }} - Rp {{ number_format($p->harga, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control form-control-sm" name="items[0][qty]" 
                                               placeholder="Qty" min="1" value="1">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm price-display" value="-" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeOrderItem(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addOrderItem()">
                                <i class="fas fa-plus me-1"></i>Tambah Item
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Simple Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-success text-white">
                <h5 class="modal-title fw-semibold">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-check-circle text-success fa-3x mb-2"></i>
                    <p class="mb-0">Yakin ingin mengkonfirmasi pembayaran ini sebagai <strong>BERHASIL</strong>?</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirmBtn">
                    <i class="fas fa-check me-1"></i>Ya, Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Simple Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-danger text-white">
                <h5 class="modal-title fw-semibold">Tolak Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x mb-2"></i>
                        <p>Yakin ingin menolak pembayaran ini?</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectReason" name="reason" rows="3" 
                                  placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Select All Checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.payment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkButton();
});

document.querySelectorAll('.payment-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkButton);
});

function deletePayment(id) {
    if (!confirm('Yakin ingin menghapus pembayaran ini?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/payments/${id}`;
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    const method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(csrf);
    form.appendChild(method);
    document.body.appendChild(form);
    form.submit();
}

function toggleBulkButton() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    const bulkBtn = document.getElementById('bulkConfirmBtn');
    
    if (checkedBoxes.length > 0) {
        bulkBtn.style.display = 'block';
    } else {
        bulkBtn.style.display = 'none';
    }
}

let currentPaymentId = null;

function confirmPayment(id) {
    currentPaymentId = id;
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    confirmModal.show();
}

function rejectPayment(id) {
    currentPaymentId = id;
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
    document.getElementById('rejectForm').action = `/admin/payments/${id}/reject`;
    document.getElementById('rejectReason').value = '';
    rejectModal.show();
}

document.getElementById('confirmBtn').addEventListener('click', function() {
    if (currentPaymentId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/payments/${currentPaymentId}/confirm`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
});

function bulkConfirm() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Pilih pembayaran yang ingin dikonfirmasi');
        return;
    }
    
    if (confirm(`Yakin ingin mengkonfirmasi ${checkedBoxes.length} pembayaran?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.payments.bulk-confirm") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        checkedBoxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'payment_ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });
        
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

// Transaction Selection Handler
document.getElementById('existing_transaction').addEventListener('change', function() {
    const transactionId = this.value;
    const transactionInfo = document.getElementById('transaction-info');
    const transactionDetails = document.getElementById('transaction-details');
    const newTransactionFields = document.querySelector('.card.bg-light');
    
    if (transactionId) {
        transactionInfo.style.display = 'block';
        newTransactionFields.style.display = 'none';
        
        fetch(`{{ url('/admin/payments/transaction') }}/${transactionId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const transaction = data.transaction;
                    transactionDetails.innerHTML = `
                        <div class="row text-sm">
                            <div class="col-6">
                                <p class="mb-1"><strong>ID:</strong> ${transaction.id}</p>
                                <p class="mb-1"><strong>Customer:</strong> ${transaction.customer_name}</p>
                                <p class="mb-0"><strong>Total:</strong> ${transaction.formatted_total}</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Dibayar:</strong> ${transaction.formatted_total_paid}</p>
                                <p class="mb-0"><strong>Sisa:</strong> ${transaction.formatted_remaining}</p>
                            </div>
                        </div>
                    `;
                    updatePaymentTypeOptions(transaction);
                } else {
                    transactionDetails.innerHTML = `<p class="text-danger">${data.message}</p>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                transactionDetails.innerHTML = '<p class="text-danger">Error loading transaction details</p>';
            });
    } else {
        transactionInfo.style.display = 'none';
        newTransactionFields.style.display = 'block';
        resetPaymentTypeOptions();
    }
});

function updatePaymentTypeOptions(transaction) {
    const paymentTypeSelect = document.getElementById('payment_type');
    paymentTypeSelect.innerHTML = '';
    
    if (transaction.can_dp) {
        paymentTypeSelect.innerHTML += '<option value="dp">Down Payment</option>';
    }
    if (transaction.can_full) {
        paymentTypeSelect.innerHTML += '<option value="full">Bayar Penuh</option>';
    }
    if (transaction.can_remainder) {
        paymentTypeSelect.innerHTML += '<option value="full">Pelunasan</option>';
    }
    
    const amountInput = document.getElementById('amount');
    if (transaction.can_remainder && !transaction.can_dp) {
        amountInput.value = transaction.remaining;
        amountInput.readOnly = true;
    } else {
        amountInput.readOnly = false;
        if (transaction.can_dp) {
            amountInput.placeholder = `Min. ${transaction.min_dp}`;
        }
    }
}

function resetPaymentTypeOptions() {
    const paymentTypeSelect = document.getElementById('payment_type');
    paymentTypeSelect.innerHTML = `
        <option value="full">Bayar Penuh</option>
        <option value="dp">Down Payment</option>
    `;
    
    const amountInput = document.getElementById('amount');
    amountInput.readOnly = false;
    amountInput.placeholder = '0';
    amountInput.value = '';
}

// Order Items Management
let itemCounter = 1;

function addOrderItem() {
    const container = document.getElementById('order-items');
    const newItem = document.createElement('div');
    newItem.className = 'row order-item g-2 mt-2';
    newItem.innerHTML = `
        <div class="col-md-6">
            <select class="form-select form-select-sm" name="items[${itemCounter}][produk_id]">
                <option value="">Pilih Produk</option>
                @foreach($produk as $p)
                    <option value="{{ $p->id_produk }}" data-price="{{ $p->harga }}">
                        {{ $p->nama_produk }} - Rp {{ number_format($p->harga, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control form-control-sm" name="items[${itemCounter}][qty]" 
                   placeholder="Qty" min="1" value="1">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm price-display" value="-" readonly>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeOrderItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newItem);
    itemCounter++;
}

function removeOrderItem(button) {
    const orderItems = document.getElementById('order-items');
    if (orderItems.children.length > 1) {
        button.closest('.order-item').remove();
    } else {
        alert('Minimal harus ada 1 item');
    }
}

// Product price calculation
document.addEventListener('change', function(e) {
    if (e.target.matches('select[name*="[produk_id]"]')) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const price = selectedOption.dataset.price || 0;
        const orderItem = e.target.closest('.order-item');
        const qtyInput = orderItem.querySelector('input[name*="[qty]"]');
        const priceDisplay = orderItem.querySelector('.price-display');
        
        const qty = parseInt(qtyInput.value) || 1;
        const total = price * qty;
        
        priceDisplay.value = total > 0 ? `Rp ${new Intl.NumberFormat('id-ID').format(total)}` : '-';
    }
    
    if (e.target.matches('input[name*="[qty]"]')) {
        const orderItem = e.target.closest('.order-item');
        const productSelect = orderItem.querySelector('select[name*="[produk_id]"]');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = selectedOption.dataset.price || 0;
        const priceDisplay = orderItem.querySelector('.price-display');
        
        const qty = parseInt(e.target.value) || 1;
        const total = price * qty;
        
        priceDisplay.value = total > 0 ? `Rp ${new Intl.NumberFormat('id-ID').format(total)}` : '-';
    }
});

// Form validation
document.getElementById('addPaymentForm').addEventListener('submit', function(e) {
    const amount = document.getElementById('amount').value;
    if (!amount || amount < 1000) {
        e.preventDefault();
        alert('Jumlah pembayaran minimal Rp 1.000');
        return;
    }
});
</script>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.modal-content {
    border-radius: 0.5rem;
}

.alert {
    border-radius: 0.375rem;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
}
</style>
@endpush