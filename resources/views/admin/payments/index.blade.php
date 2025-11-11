@extends('layouts.app')

@section('title', 'Management Pembayaran')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- Modern Header with Gradient --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="header-card p-4 rounded-4 shadow-sm">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h2 class="fw-bold text-primary mb-2">Management Pembayaran</h2>
                        <p class="text-primary-50 mb-0">Kelola dan monitor semua transaksi pembayaran</p>
                    </div>
                    <a href="{{ route('admin.payments.analytics') }}" class="btn btn-light rounded-pill px-4">
                        <i class="fas fa-chart-line me-2"></i>Lihat Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modern Stats Cards Grid --}}
    <div class="row g-3 g-md-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card h-100 rounded-4 border-0 shadow-sm overflow-hidden">
                <div class="card-body p-3 p-md-4 position-relative">
                    <div class="stat-icon stat-icon-primary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-content">
                        <small class="text-muted d-block mb-1">Pendapatan Hari Ini</small>
                        <h4 class="fw-bold mb-2 text-truncate">Rp {{ number_format($stats['today_total'], 0, ',', '.') }}</h4>
                        <span class="badge badge-success-soft">
                            <i class="fas fa-arrow-up me-1"></i>{{ $stats['today_count'] }} transaksi
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card h-100 rounded-4 border-0 shadow-sm overflow-hidden">
                <div class="card-body p-3 p-md-4 position-relative">
                    <div class="stat-icon stat-icon-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <small class="text-muted d-block mb-1">Perlu Konfirmasi</small>
                        <h4 class="fw-bold mb-2">{{ $stats['pending_count'] }}</h4>
                        <span class="badge badge-warning-soft">Pembayaran Manual</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card h-100 rounded-4 border-0 shadow-sm overflow-hidden">
                <div class="card-body p-3 p-md-4 position-relative">
                    <div class="stat-icon stat-icon-info">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <small class="text-muted d-block mb-1">Total Bulan Ini</small>
                        <h4 class="fw-bold mb-2 text-truncate">Rp {{ number_format($stats['monthly_total'], 0, ',', '.') }}</h4>
                        <span class="badge badge-info-soft">{{ date('F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="stat-card h-100 rounded-4 border-0 shadow-sm overflow-hidden">
                <div class="card-body p-3 p-md-4 position-relative">
                    <div class="stat-icon stat-icon-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <small class="text-muted d-block mb-1">Status Pembayaran</small>
                        <div class="d-flex gap-2 flex-wrap mb-2">
                            <span class="badge bg-success">{{ $stats['status_counts']['paid'] }}</span>
                            <span class="badge bg-warning">{{ $stats['status_counts']['pending'] }}</span>
                            <span class="badge bg-danger">{{ $stats['status_counts']['failed'] }}</span>
                        </div>
                        <small class="text-muted small">Lunas | Pending | Gagal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modern Filter Card --}}
    <div class="card rounded-4 border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('admin.payments.index') }}">
                <div class="row g-2 g-md-3">
                    <div class="col-6 col-md-4 col-lg-2">
                        <select name="status" class="form-select form-select-sm rounded-3">
                            <option value="">Semua Status</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <select name="method" class="form-select form-select-sm rounded-3">
                            <option value="">Semua Metode</option>
                            <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="offline" {{ request('method') == 'offline' ? 'selected' : '' }}>Manual</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <input type="date" name="date_from" class="form-control form-control-sm rounded-3" 
                               value="{{ request('date_from') }}" placeholder="Dari">
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <input type="date" name="date_to" class="form-control form-control-sm rounded-3" 
                               value="{{ request('date_to') }}" placeholder="Sampai">
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <input type="text" name="search" class="form-control form-control-sm rounded-3" 
                               value="{{ request('search') }}" placeholder="Cari transaksi...">
                    </div>
                    <div class="col-12 col-md-4 col-lg-1">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm rounded-3 flex-fill">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 flex-fill">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modern Payment Table Card --}}
    <div class="card rounded-4 border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <h5 class="mb-0 fw-bold">Daftar Pembayaran</h5>
                <div class="d-flex gap-2 w-100 w-md-auto">
                    <button type="button" class="btn btn-primary rounded-pill px-4 flex-fill flex-md-grow-0" 
                            data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i class="fas fa-plus me-2"></i><span class="d-none d-md-inline">Tambah </span>Payment
                    </button>
                    <button type="button" class="btn btn-success rounded-pill px-4 flex-fill flex-md-grow-0" 
                            onclick="bulkConfirm()" id="bulkConfirmBtn" style="display: none;">
                        <i class="fas fa-check me-2"></i><span class="d-none d-md-inline">Konfirmasi</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 px-md-4">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th class="px-3 px-md-4">No. Transaksi</th>
                            <th class="px-3 px-md-4 d-none d-md-table-cell">Customer</th>
                            <th class="px-3 px-md-4">Metode</th>
                            <th class="px-3 px-md-4">Jumlah</th>
                            <th class="px-3 px-md-4">Status</th>
                            <th class="px-3 px-md-4 d-none d-lg-table-cell">Tanggal</th>
                            <th class="px-3 px-md-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td class="px-3 px-md-4">
                                @if($payment->payment_status == 'pending' && $payment->method == 'offline')
                                <input type="checkbox" name="payment_ids[]" value="{{ $payment->id }}" 
                                       class="form-check-input payment-checkbox">
                                @endif
                            </td>
                            <td class="px-3 px-md-4">
                                <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                   class="text-decoration-none fw-semibold text-primary">
                                    {{ $payment->master_transaction_id }}
                                </a>
                                <div class="d-md-none mt-1">
                                    <div class="small text-muted">{{ $payment->masterTransaction->customer->nama }}</div>
                                </div>
                            </td>
                            <td class="px-3 px-md-4 d-none d-md-table-cell">
                                <div class="fw-medium">{{ $payment->masterTransaction->customer->nama }}</div>
                                <small class="text-muted">{{ $payment->masterTransaction->customer->email }}</small>
                            </td>
                            <td class="px-3 px-md-4">
                                <span class="badge rounded-pill bg-{{ $payment->method == 'online' ? 'info' : 'secondary' }}">
                                    {{ $payment->method_label }}
                                </span>
                            </td>
                            <td class="px-3 px-md-4">
                                <div class="fw-semibold">{{ $payment->formatted_amount }}</div>
                                @if($payment->type == 'dp')
                                    <small class="badge badge-warning-soft">DP</small>
                                @endif
                            </td>
                            <td class="px-3 px-md-4">
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
                            <td class="px-3 px-md-4 d-none d-lg-table-cell">
                                <div class="small">{{ $payment->created_at->format('d/m/Y H:i') }}</div>
                                @if($payment->paid_at)
                                <small class="text-success">✓ {{ $payment->paid_at->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td class="px-3 px-md-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                       class="btn btn-sm btn-outline-info rounded-start-3" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if(in_array($payment->payment_status, ['pending']) && in_array($payment->method, ['offline','manual_transfer']))
                               
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            onclick="confirmPayment({{ $payment->id }})" title="Konfirmasi">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-end-3" 
                                            onclick="rejectPayment({{ $payment->id }})" title="Tolak">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif

                                    @if(auth()->check() && auth()->user()->peran === 'super_admin' && in_array($payment->payment_status, ['pending','failed','cancelled']))
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-end-3" 
                                                onclick="deletePayment({{ $payment->id }})" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif

                                    @if(!$payment->masterTransaction->is_fully_paid)
                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-end-3" 
                                            onclick="sendReminder('{{ $payment->master_transaction_id }}')" title="Reminder">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                                    <p class="text-muted mb-0">Tidak ada data pembayaran</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @php
                $hasPagination = method_exists($payments, 'links');
                $hasLengthAware = method_exists($payments, 'firstItem') && method_exists($payments, 'lastItem') && method_exists($payments, 'total');
            @endphp
            @if($hasPagination)
            <div class="border-top px-3 px-md-4 py-3">
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
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modern Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header gradient-header border-0 text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Payment Manual
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addPaymentForm" method="POST" action="{{ route('admin.payments.store') }}">
                @csrf
                <div class="modal-body p-4 modal-body-scroll">
                    <div class="alert alert-info border-0 rounded-3 mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Form untuk mencatat pembayaran customer yang datang langsung atau pembayaran offline.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Customer <span class="text-danger">*</span></label>
                            <select id="customer_search" name="customer_id" class="form-select rounded-3" required>
                                <option value="">Pilih Customer</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Transaksi Existing (Opsional)</label>
                            <select class="form-select rounded-3" id="existing_transaction" name="existing_transaction">
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

                    <div id="transaction-info" class="alert alert-success border-0 rounded-3 mt-3" style="display: none;">
                        <h6 class="fw-semibold mb-2"><i class="fas fa-info-circle me-1"></i>Info Transaksi</h6>
                        <div id="transaction-details"></div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Jumlah Pembayaran <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-3">Rp</span>
                                <input type="number" class="form-control rounded-end-3" id="amount" name="amount" 
                                       min="1000" step="100" required placeholder="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tipe Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select rounded-3" id="payment_type" name="payment_type" required>
                                <option value="full">Bayar Penuh</option>
                                <option value="dp">Down Payment</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select rounded-3" id="payment_method" name="payment_method" required>
                                <option value="cash">Cash/Tunai</option>
                                <option value="bank_transfer">Transfer Bank</option>
                                <option value="debit_card">Kartu Debit</option>
                                <option value="credit_card">Kartu Kredit</option>
                                <option value="e_wallet">E-Wallet</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control rounded-3" id="payment_date" name="payment_date" 
                                   value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-medium">No. Referensi/Bukti</label>
                        <input type="text" class="form-control rounded-3" id="reference_number" name="reference_number" 
                               placeholder="No. struk, no. transfer, dll">
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-medium">Catatan</label>
                        <textarea class="form-control rounded-3" id="notes" name="notes" rows="2" 
                                  placeholder="Catatan tambahan..."></textarea>
                    </div>

                    <div class="card bg-light border-0 rounded-3 mt-3">
                        <div class="card-header bg-transparent border-0">
                            <h6 class="mb-0 fw-medium">Detail Pesanan (Opsional - hanya jika transaksi baru)</h6>
                        </div>
                        <div class="card-body">
                            <div id="order-items">
                                <div class="row order-item g-2">
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm rounded-3" name="items[0][produk_id]">
                                            <option value="">Pilih Produk</option>
                                            @foreach($produk as $p)
                                                <option value="{{ $p->id_produk }}" data-price="{{ $p->harga }}">
                                                    {{ $p->nama_produk }} - Rp {{ number_format($p->harga, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control form-control-sm rounded-3" name="items[0][qty]" 
                                               placeholder="Qty" min="1" value="1">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control form-control-sm rounded-3 price-display" value="-" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm rounded-3" onclick="removeOrderItem(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill mt-2" onclick="addOrderItem()">
                                <i class="fas fa-plus me-1"></i>Tambah Item
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save me-1"></i>Simpan Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modern Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-success text-white">
                <h5 class="modal-title fw-bold">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <div class="icon-success mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p class="mb-0">Yakin ingin mengkonfirmasi pembayaran ini sebagai <strong>BERHASIL</strong>?</p>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success rounded-pill px-4" id="confirmBtn">
                    <i class="fas fa-check me-1"></i>Ya, Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modern Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-danger text-white">
                <h5 class="modal-title fw-bold">Tolak Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <div class="icon-warning mb-3">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <p>Yakin ingin menolak pembayaran ini?</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control rounded-3" id="rejectReason" name="reason" rows="3" 
                                  placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="fas fa-times me-1"></i>Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
    --shadow-lg: 0 8px 32px rgba(0,0,0,0.16);
}

.header-card {
    background: var(--gradient-primary);
    border: none;
}

.stat-card {
    background: white;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.06);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.stat-icon-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-icon-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.stat-icon-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.stat-icon-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.stat-content {
    flex: 1;
}

.badge-success-soft {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
    font-weight: 500;
}

.badge-warning-soft {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    font-weight: 500;
}

.badge-info-soft {
    background-color: rgba(13, 202, 240, 0.1);
    color: #0dcaf0;
    font-weight: 500;
}

.card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.06);
}

.card:hover {
    box-shadow: var(--shadow-md);
}

.form-control:focus, 
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
}

.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    border: none;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(67, 233, 123, 0.4);
}

.table-hover tbody tr {
    transition: all 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transform: scale(1.01);
}

.badge.rounded-pill {
    padding: 0.4em 0.8em;
    font-weight: 500;
    font-size: 0.75rem;
}

.modal-content {
    border: none;
    overflow: hidden;
}

.gradient-header {
    background: var(--gradient-primary);
}

.modal-dialog-scrollable .modal-body {
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

.modal-dialog-scrollable {
    max-height: calc(100vh - 3.5rem);
}

.modal-dialog-scrollable .modal-content {
    max-height: calc(100vh - 3.5rem);
    overflow: hidden;
}

/* Fix untuk mobile */
@media (max-width: 576px) {
    .modal-dialog-scrollable .modal-body {
        max-height: calc(100vh - 150px);
    }
    
    .modal-dialog-scrollable {
        max-height: calc(100vh - 1rem);
        margin: 0.5rem;
    }
}

.icon-success, 
.icon-warning {
    font-size: 3.5rem;
    animation: iconPulse 2s infinite;
}

.icon-success i {
    color: #198754;
}

.icon-warning i {
    color: #ffc107;
}

@keyframes iconPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.empty-state {
    padding: 3rem 1rem;
}

.pagination {
    margin: 0;
    gap: 0.25rem;
}

.page-link {
    border-radius: 0.375rem;
    border: 1px solid rgba(0,0,0,0.1);
    color: #667eea;
    transition: all 0.2s ease;
}

.page-link:hover {
    background-color: #667eea;
    color: white;
    transform: translateY(-2px);
}

.page-item.active .page-link {
    background-color: #667eea;
    border-color: #667eea;
}

.btn-group .btn {
    border-right: 1px solid rgba(0,0,0,0.1);
}

.btn-group .btn:last-child {
    border-right: none;
}

.alert {
    border: none;
    border-left: 4px solid;
}

.alert-info {
    background-color: rgba(13, 202, 240, 0.1);
    border-left-color: #0dcaf0;
    color: #055160;
}

.alert-success {
    background-color: rgba(25, 135, 84, 0.1);
    border-left-color: #198754;
    color: #0a3622;
}

.input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    font-weight: 500;
}

::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #764ba2;
}

@media (max-width: 768px) {
    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }
    
    .stat-card .card-body {
        padding: 1rem !important;
    }
    
    h4 {
        font-size: 1.25rem;
    }
    
    .header-card {
        padding: 1.5rem !important;
    }
    
    .table {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.3em 0.5em;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .stat-content small {
        font-size: 0.7rem;
    }
    
    .table td, .table th {
        padding: 0.5rem;
    }
}

@media print {
    .btn, .modal, .card-header {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Select All Functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.payment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    toggleBulkButton();
});

// Individual Checkbox Change
document.querySelectorAll('.payment-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', toggleBulkButton);
});

// Toggle Bulk Confirm Button
function toggleBulkButton() {
    const checkedBoxes = document.querySelectorAll('.payment-checkbox:checked');
    const bulkBtn = document.getElementById('bulkConfirmBtn');
    bulkBtn.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
}

// Delete Payment
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

// Global variable for current payment ID
let currentPaymentId = null;

// Confirm Payment
function confirmPayment(id) {
    currentPaymentId = id;
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    confirmModal.show();
}

// Reject Payment
function rejectPayment(id) {
    currentPaymentId = id;
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
    document.getElementById('rejectForm').action = `/admin/payments/${id}/reject`;
    document.getElementById('rejectReason').value = '';
    rejectModal.show();
}

// Confirm Button Click Handler
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

// Bulk Confirm
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

// Send Reminder
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

// Update Payment Type Options
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

// Reset Payment Type Options
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
            <select class="form-select form-select-sm rounded-3" name="items[${itemCounter}][produk_id]">
                <option value="">Pilih Produk</option>
                @foreach($produk as $p)
                    <option value="{{ $p->id_produk }}" data-price="{{ $p->harga }}">
                        {{ $p->nama_produk }} - Rp {{ number_format($p->harga, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control form-control-sm rounded-3" name="items[${itemCounter}][qty]" 
                   placeholder="Qty" min="1" value="1">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control form-control-sm rounded-3 price-display" value="-" readonly>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm rounded-3" onclick="removeOrderItem(this)">
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

// Product Price Calculation
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

// Form Validation
document.getElementById('addPaymentForm').addEventListener('submit', function(e) {
    const amount = document.getElementById('amount').value;
    if (!amount || amount < 1000) {
        e.preventDefault();
        alert('Jumlah pembayaran minimal Rp 1.000');
        return;
    }
});
</script>
@endpush