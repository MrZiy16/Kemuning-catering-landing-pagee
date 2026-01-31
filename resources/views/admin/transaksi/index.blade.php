@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- Modern Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="header-card p-3 p-md-4 rounded-4 shadow-sm">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <div class="ms-3">
                            <h2 class="mb-1 fw-bold text-white">Daftar Transaksi</h2>
                            <p class="text-white-50 mb-0 small">Kelola dan pantau semua transaksi pelanggan</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 w-100 w-md-auto">
                        <button class="btn btn-light rounded-pill px-3 px-md-4 flex-fill flex-md-grow-0" onclick="exportToPDF()">
                            <i class="fas fa-file-pdf me-2"></i><span class="d-none d-md-inline">Export </span>PDF
                        </button>
                        <button class="btn btn-success rounded-pill px-3 px-md-4 flex-fill flex-md-grow-0" onclick="exportToExcel()">
                            <i class="fas fa-file-excel me-2"></i><span class="d-none d-md-inline">Export </span>Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 g-md-4 mb-4">
        @php
            $statusStats = [
                ['key' => 'all', 'label' => 'Total', 'icon' => 'fa-chart-bar', 'color' => 'primary'],
                ['key' => 'pending', 'label' => 'Pending', 'icon' => 'fa-clock', 'color' => 'warning'],
                ['key' => 'confirmed', 'label' => 'Confirmed', 'icon' => 'fa-check-circle', 'color' => 'info'],
                ['key' => 'completed', 'label' => 'Completed', 'icon' => 'fa-check-double', 'color' => 'success']
            ];
        @endphp
        @foreach($statusStats as $stat)
        <div class="col-6 col-md-3">
            <div class="stat-card h-100 rounded-4 border-0 shadow-sm" data-status="{{ $stat['key'] }}">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon stat-icon-{{ $stat['color'] }}">
                            <i class="fas {{ $stat['icon'] }}"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="mb-0 fw-bold">{{ $statusCounts[$stat['key']] ?? 0 }}</h3>
                            <p class="mb-0 text-muted small">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter Card --}}
    <div class="card rounded-4 border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 p-3 p-md-4">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-filter me-2 text-primary"></i>Filter & Pencarian
            </h5>
        </div>
        <div class="card-body p-3 p-md-4">
            <form method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label fw-medium small">Status</label>
                        <select name="status" class="form-select form-select-sm rounded-3">
                            <option value="">Semua Status</option>
                            @foreach($statusCounts as $key => $count)
                                @if($key !== 'all')
                                    <option value="{{ $key }}" {{ request('status')==$key?'selected':'' }}>
                                        {{ ucfirst($key) }} ({{ $count }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-6 col-lg-2">
                        <label class="form-label fw-medium small">Dari Tanggal</label>
                        <input type="date" name="tanggal_mulai" class="form-control form-control-sm rounded-3" 
                               value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="col-6 col-md-6 col-lg-2">
                        <label class="form-label fw-medium small">Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" class="form-control form-control-sm rounded-3" 
                               value="{{ request('tanggal_akhir') }}">
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label fw-medium small">Pencarian</label>
                        <input type="text" name="search" class="form-control form-control-sm rounded-3" 
                               placeholder="Cari nama/no HP..." value="{{ request('search') }}">
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label class="form-label fw-medium small d-none d-lg-block">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm rounded-3 flex-fill">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 flex-fill">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Button --}}
    <div class="mb-4">
        <button class="btn btn-success rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
            <i class="fas fa-plus me-2"></i>Tambah Transaksi
        </button>
    </div>

    {{-- Table Card --}}
    <div class="card rounded-4 border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-table me-2 text-primary"></i>Data Transaksi
                </h5>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>Total: {{ $transaksis->total() }} transaksi
                </small>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="transactionTable">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 px-md-4">ID</th>
                            <th class="px-3 px-md-4 d-none d-md-table-cell">Customer</th>
                            <th class="px-3 px-md-4 d-none d-lg-table-cell">Tanggal Acara</th>
                            <th class="px-3 px-md-4">Total</th>
                            <th class="px-3 px-md-4">Status</th>
                            <th class="px-3 px-md-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $trx)
                        <tr id="trx-{{ $trx->id_transaksi }}" class="transaction-row">
                            <td class="px-3 px-md-4">
                                <span class="fw-bold text-primary">#{{ $trx->id_transaksi }}</span>
                                <div class="d-md-none mt-1">
                                    <small class="text-muted d-block">{{ $trx->customer->nama ?? '-' }}</small>
                                    <small class="text-muted d-block">{{ $trx->customer->no_hp ?? '' }}</small>
                                </div>
                            </td>
                            <td class="px-3 px-md-4 d-none d-md-table-cell">
                                <div class="fw-medium">{{ $trx->customer->nama ?? '-' }}</div>
                                <small class="text-muted">{{ $trx->customer->no_hp ?? '' }}</small>
                            </td>
                            <td class="px-3 px-md-4 d-none d-lg-table-cell">
                                <div class="small">{{ \Carbon\Carbon::parse($trx->tanggal_acara)->translatedFormat('d M Y') }}</div>
                                <small class="text-muted">{{ $trx->waktu_acara }}</small>
                            </td>
                            <td class="px-3 px-md-4">
                                <span class="fw-semibold">Rp {{ number_format($trx->total,0,',','.') }}</span>
                            </td>
                            <td class="px-3 px-md-4">
                                <span class="badge rounded-pill status-label
                                    @switch($trx->status)
                                        @case('draft') bg-secondary @break
                                        @case('pending') bg-warning text-dark @break
                                        @case('confirmed') bg-primary @break
                                        @case('preparing') bg-info text-dark @break
                                        @case('ready') bg-secondary @break
                                        @case('delivered') bg-dark @break
                                        @case('completed') bg-success @break
                                        @case('cancelled') bg-danger @break
                                    @endswitch
                                ">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            </td>
                            <td class="px-3 px-md-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.transaksi.show',$trx->id_transaksi) }}" 
                                       class="btn btn-sm btn-outline-info rounded-start-3" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if(in_array($trx->status, ['draft','pending','confirmed']))
                                    <a href="{{ route('admin.transaksi.edit',$trx->id_transaksi) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-outline-warning btn-update-status"
                                        data-id="{{ $trx->id_transaksi }}" data-status="{{ $trx->status }}"
                                        data-bs-toggle="modal" data-bs-target="#updateStatusModal" title="Update Status">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>

                                    <a href="{{ route('invoice.download',$trx->id_transaksi) }}" 
                                       class="btn btn-sm btn-outline-success rounded-end-3" title="Download Invoice">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                                    <p class="text-muted mb-0">Tidak ada transaksi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($transaksis->hasPages())
        <div class="card-footer bg-light border-0 p-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <small class="text-muted">
                    Menampilkan {{ $transaksis->firstItem() ?? 0 }} - {{ $transaksis->lastItem() ?? 0 }} dari {{ $transaksis->total() }}
                </small>
                <div>
                    {{ $transaksis->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning border-0">
                <h5 class="modal-title fw-bold">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="modal-transaksi-id">
                <div class="mb-3">
                    <label class="form-label fw-medium">Status Baru</label>
                    <select id="modal-status" class="form-select rounded-3">
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="preparing">Preparing</option>
                        <option value="ready">Ready</option>
                        <option value="delivered">Delivered</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="form-label fw-medium">Keterangan</label>
                    <textarea id="modal-keterangan" class="form-control rounded-3" rows="3" 
                              placeholder="Tambahkan keterangan (opsional)..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-warning rounded-pill px-4" id="save-status-btn">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Transaction Modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Transaksi Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addTransactionForm">
                @csrf
                <div class="modal-body p-4 modal-scroll">
                    <div class="alert alert-info border-0 rounded-3 mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Info:</strong> Masukkan email customer - jika sudah ada di sistem, data akan otomatis terisi.
                    </div>

                    {{-- Customer Email Input & Auto Fill --}}
                    <div class="mb-4">
                        <label for="customer_email" class="form-label fw-semibold">
                            <i class="fas fa-envelope text-primary me-2"></i>Email Customer <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control rounded-3" id="customer_email" name="customer_email" 
                               placeholder="Masukkan email customer..." maxlength="70">
                        <small class="text-muted d-block mt-1">Tekan Tab atau klik di luar field untuk cek email</small>
                    </div>

                    {{-- Customer Name --}}
                    <div class="mb-3">
                        <label for="customer_nama" class="form-label fw-semibold">
                            <i class="fas fa-user text-primary me-2"></i>Nama Customer <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control rounded-3" id="customer_nama" name="customer_nama" 
                               placeholder="Nama lengkap customer..." maxlength="100" required>
                    </div>

                    {{-- Customer Phone --}}
                    <div class="mb-3">
                        <label for="customer_no_hp" class="form-label fw-semibold">
                            <i class="fas fa-phone text-primary me-2"></i>No. HP <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control rounded-3" id="customer_no_hp" name="customer_no_hp" 
                               placeholder="Nomor telepon customer..." maxlength="15" required>
                    </div>

                    {{-- Customer Address --}}
                    <div class="mb-4">
                        <label for="customer_alamat" class="form-label fw-semibold">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>Alamat Customer
                        </label>
                        <textarea class="form-control rounded-3" id="customer_alamat" name="customer_alamat" 
                                  rows="2" placeholder="Alamat rumah/kantor customer (opsional)..."></textarea>
                    </div>

                    {{-- Shipping Zone Selection --}}
                    <div class="mb-4">
                        <label for="shipping_zone_id" class="form-label fw-semibold">
                            <i class="fas fa-truck text-info me-2"></i>Zona Pengiriman <span class="text-danger">*</span>
                        </label>
                        <select class="form-select rounded-3" id="shipping_zone_id" name="shipping_zone_id" required>
                            <option value="">Pilih Zona Pengiriman</option>
                            @if(isset($shippingZones))
                                @foreach($shippingZones as $zone)
                                <option value="{{ $zone->id }}" data-ongkir="{{ $zone->ongkir }}">
                                    {{ $zone->nama_zona }} - Rp {{ number_format($zone->ongkir, 0, ',', '.') }}
                                </option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-muted d-block mt-1">Pilih zona untuk menentukan ongkir</small>
                    </div>

                    {{-- Product/Menu Selection --}}
                    <div class="card bg-light border-0 rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-shopping-cart text-primary me-2"></i>Pilih Produk/Menu <span class="text-danger">*</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="items-container">
                                <div class="item-row mb-3" data-index="0">
                                    <div class="row g-2">
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small">Tipe</label>
                                            <select class="form-select form-select-sm rounded-3 item-type" name="items[0][type]" required>
                                                <option value="">Pilih</option>
                                                <option value="produk">Produk</option>
                                                <option value="menu">Menu</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <label class="form-label small">Item</label>
                                            <select class="form-select form-select-sm rounded-3 item-select" name="items[0][id]" required disabled>
                                                <option value="">Pilih tipe dulu</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-4">
                                            <label class="form-label small">Qty</label>
                                            <input type="number" class="form-control form-control-sm rounded-3 item-qty" 
                                                   name="items[0][qty]" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-3 col-8">
                                            <label class="form-label small">Subtotal</label>
                                            <input type="text" class="form-control form-control-sm rounded-3 item-subtotal" readonly>
                                            <input type="hidden" class="item-harga" name="items[0][harga]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" id="add-item-btn">
                                <i class="fas fa-plus me-1"></i>Tambah Item
                            </button>
                        </div>
                    </div>

                    {{-- Subtotal & Ongkir Display --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-primary border-0 rounded-3 mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-calculator me-2"></i>Subtotal:</strong>
                                    <h6 class="mb-0" id="subtotal-display">Rp 0</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info border-0 rounded-3 mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong><i class="fas fa-shipping-fast me-2"></i>Ongkir:</strong>
                                    <h6 class="mb-0" id="ongkir-display">Rp 0</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Display --}}
                    <div class="alert alert-success border-0 rounded-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong><i class="fas fa-money-bill-wave me-2"></i>Total Transaksi:</strong>
                            <h5 class="mb-0" id="total-display">Rp 0</h5>
                        </div>
                    </div>

                    {{-- Event Date & Time --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tanggal_acara" class="form-label fw-semibold">
                                <i class="fas fa-calendar-alt text-info me-2"></i>Tanggal Acara <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control rounded-3" id="tanggal_acara" name="tanggal_acara" 
                                   min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="waktu_acara" class="form-label fw-semibold">
                                <i class="fas fa-clock text-warning me-2"></i>Waktu Acara <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control rounded-3" id="waktu_acara" name="waktu_acara" required>
                        </div>
                    </div>

                    {{-- Delivery Address --}}
                    <div class="mb-3 mt-3">
                        <label for="alamat_pengiriman" class="form-label fw-semibold">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>Alamat Pengiriman <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control rounded-3" id="alamat_pengiriman" name="alamat_pengiriman" 
                                  rows="3" required placeholder="Masukkan alamat lengkap pengiriman..."></textarea>
                    </div>

                    {{-- Customer Notes --}}
                    <div class="mb-3">
                        <label for="catatan_customer" class="form-label fw-semibold">
                            <i class="fas fa-sticky-note text-secondary me-2"></i>Catatan Customer
                        </label>
                        <textarea class="form-control rounded-3" id="catatan_customer" name="catatan_customer" 
                                  rows="2" placeholder="Catatan khusus dari customer (opsional)..."></textarea>
                    </div>

                    {{-- Payment Type & Amount --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="payment_type" class="form-label fw-semibold">
                                <i class="fas fa-credit-card text-success me-2"></i>Tipe Pembayaran <span class="text-danger">*</span>
                            </label>
                            <select class="form-select rounded-3" id="payment_type" name="payment_type" required>
                                <option value="">Pilih Tipe</option>
                                <option value="full">Full Payment</option>
                                <option value="dp">Down Payment (DP)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="payment_amount" class="form-label fw-semibold">
                                <i class="fas fa-coins text-warning me-2"></i>Jumlah Bayar <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control rounded-3" id="payment_amount" name="payment_amount" 
                                   min="0" placeholder="Masukkan jumlah pembayaran..." required>
                            <small class="text-muted d-block mt-1">Full: sesuai total | DP: kurang dari total</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<style>
:root {
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
}

.header-card {
    background: var(--gradient-primary);
    border: none;
}

.icon-wrapper {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.icon-wrapper i {
    color: white;
    font-size: 1.5rem;
}

.stat-card {
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
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
    font-size: 1.25rem;
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

.card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.06);
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

.table-hover tbody tr {
    transition: all 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
}

.transaction-row:hover {
    background-color: rgba(0,123,255,0.05) !important;
}

.badge.rounded-pill {
    padding: 0.4em 0.8em;
    font-weight: 500;
    font-size: 0.75rem;
}

.empty-state {
    padding: 3rem 1rem;
}

.btn-group .btn {
    border-right: 1px solid rgba(0,0,0,0.1);
}

.btn-group .btn:last-child {
    border-right: none;
}

.modal-scroll {
    max-height: 70vh;
    overflow-y: auto;
    overflow-x: hidden;
}

/* Scrollbar */
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

/* Responsive */
@media (max-width: 768px) {
    .icon-wrapper {
        width: 40px;
        height: 40px;
    }
    
    .icon-wrapper i {
        font-size: 1.25rem;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    h3 {
        font-size: 1.25rem;
    }
    
    .table {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .modal-scroll {
        max-height: 60vh;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .header-card {
        padding: 1.5rem !important;
    }
    
    .stat-card .card-body {
        padding: 1rem !important;
    }
    
    .table td,
    .table th {
        padding: 0.5rem;
    }
    
    .badge {
        font-size: 0.65rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
}

.loading-spinner {
    display: inline-block;
    width: 12px;
    height: 12px;
    border: 2px solid rgba(102, 126, 234, 0.2);
    border-radius: 50%;
    border-top-color: #667eea;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
// Data produk dan menu
const produkData = @json($produk);
const menuData = @json($menus);
const shippingZonesData = @json(isset($shippingZones) ? $shippingZones : []);

document.addEventListener('DOMContentLoaded', () => {
    const baseUpdateUrl = "{{ url('admin/transaksi') }}";
    const saveBtn = document.getElementById('save-status-btn');
    const customerEmailInput = document.getElementById('customer_email');
    const shippingZoneSelect = document.getElementById('shipping_zone_id');

    // ===================== CUSTOMER AUTO-FILL LOGIC (API VERSION - WITH ADMIN PREFIX) =====================
    // Fetch customer data dari API endpoint
    
    if (customerEmailInput) {
        customerEmailInput.addEventListener('blur', async function() {
            const email = this.value.trim();
            
            console.log('📧 Email blurred:', email);
            
            if (!email) {
                // Clear customer data
                document.getElementById('customer_nama').value = '';
                document.getElementById('customer_no_hp').value = '';
                document.getElementById('customer_alamat').value = '';
                document.getElementById('customer_nama').removeAttribute('readonly');
                document.getElementById('customer_no_hp').removeAttribute('readonly');
                document.getElementById('customer_alamat').removeAttribute('readonly');
                return;
            }

            try {
                // 🔥 MENGGUNAKAN NAMED ROUTE DENGAN ADMIN PREFIX
                const apiUrl = "{{ route('admin.customers.check-email') }}?email=" + encodeURIComponent(email);
                
                console.log('📡 Calling API:', apiUrl);
                
                const response = await fetch(apiUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('✅ API Response:', data);

                if (data.exists) {
                    console.log('✅ CUSTOMER FOUND:', data);
                    
                    // Auto-fill fields - sesuai dengan response controller
                    const namaField = document.getElementById('customer_nama');
                    const noHpField = document.getElementById('customer_no_hp');
                    const alamatField = document.getElementById('customer_alamat');
                    
                    if (namaField) namaField.value = data.nama || '';
                    if (noHpField) noHpField.value = data.no_hp || '';
                    // Alamat tidak di-return dari controller, set kosong
                    if (alamatField) alamatField.value = '';
                    
                    // Set readonly untuk nama dan no_hp
                    if (namaField) namaField.setAttribute('readonly', 'readonly');
                    if (noHpField) noHpField.setAttribute('readonly', 'readonly');
                    // Alamat tetap editable

                    console.log('✅ Fields filled and set to readonly');

                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Data customer ditemukan! ✨',
                        text: 'Nama: ' + data.nama,
                        timer: 2000,
                        showConfirmButton: false,
                        position: 'top-end'
                    });
                } else {
                    console.log('ℹ️ Customer tidak ditemukan (customer baru)');
                    
                    // New customer - enable input & clear
                    const namaField = document.getElementById('customer_nama');
                    const noHpField = document.getElementById('customer_no_hp');
                    const alamatField = document.getElementById('customer_alamat');
                    
                    if (namaField) {
                        namaField.value = '';
                        namaField.removeAttribute('readonly');
                    }
                    if (noHpField) {
                        noHpField.value = '';
                        noHpField.removeAttribute('readonly');
                    }
                    if (alamatField) {
                        alamatField.value = '';
                        alamatField.removeAttribute('readonly');
                    }
                    
                    console.log('✅ Fields cleared and set to editable');
                }
            } catch (error) {
                console.error('❌ Error:', error);
                console.error('Error message:', error.message);
                
                // Enable fields on error
                document.getElementById('customer_nama').removeAttribute('readonly');
                document.getElementById('customer_no_hp').removeAttribute('readonly');
                document.getElementById('customer_alamat').removeAttribute('readonly');
                
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: 'Error saat fetch data',
                    text: error.message,
                    timer: 2000,
                    showConfirmButton: false,
                    position: 'top-end'
                });
            }
        });
    } else {
        console.error('❌ customer_email input not found!');
    }

    // ===================== SHIPPING ZONE & ONGKIR LOGIC =====================
    shippingZoneSelect.addEventListener('change', () => {
        calculateTotalWithOngkir();
    });

    function calculateTotalWithOngkir() {
        const selectedZone = shippingZoneSelect.options[shippingZoneSelect.selectedIndex];
        const ongkir = parseFloat(selectedZone.dataset.ongkir) || 0;
        
        document.getElementById('ongkir-display').textContent = `Rp ${formatNumber(ongkir)}`;
        calculateTotal();
    }

    // ===================== UPDATE STATUS MODAL =====================
    document.querySelectorAll('.btn-update-status').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelector('#modal-transaksi-id').value = btn.dataset.id;
            document.querySelector('#modal-status').value = btn.dataset.status;
            document.querySelector('#modal-keterangan').value = "";
        });
    });

    saveBtn.addEventListener('click', () => {
        const id = document.querySelector('#modal-transaksi-id').value;
        const newStatus = document.querySelector('#modal-status').value;
        const keterangan = document.querySelector('#modal-keterangan').value;

        fetch(`${baseUpdateUrl}/${id}/update-status`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ status: newStatus, keterangan })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const badge = document.querySelector(`#trx-${id} .status-label`);
                if (badge) badge.textContent = newStatus;

                bootstrap.Modal.getInstance(document.getElementById('updateStatusModal')).hide();
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Status berhasil diupdate',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });

    // ===================== ADD TRANSACTION FORM =====================
    const addTransactionForm = document.getElementById('addTransactionForm');
    if (addTransactionForm) {
        addTransactionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            
            fetch("{{ route('admin.transaksi.store') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('addTransactionModal')).hide();
                    addTransactionForm.reset();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    let errorMessage = data.message || 'Terjadi kesalahan';
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join('\n');
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menyimpan data'
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    // Reset form when modal is closed
    const addTransactionModal = document.getElementById('addTransactionModal');
    if (addTransactionModal) {
        addTransactionModal.addEventListener('hidden.bs.modal', function() {
            addTransactionForm.reset();
            resetItemsForm();
            addTransactionForm.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            addTransactionForm.querySelectorAll('.invalid-feedback').forEach(el => {
                el.remove();
            });
        });
    }

    initializeItemHandlers();
});

// ===================== ITEM MANAGEMENT =====================
let itemCounter = 1;

function initializeItemHandlers() {
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-type')) {
            handleTypeChange(e.target);
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-select')) {
            handleItemChange(e.target);
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-qty')) {
            calculateSubtotal(e.target);
        }
    });

    document.getElementById('add-item-btn').addEventListener('click', addNewItem);

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item-btn') || e.target.parentElement.classList.contains('remove-item-btn')) {
            removeItem(e.target.closest('.item-row'));
        }
    });
}

function handleTypeChange(typeSelect) {
    const itemRow = typeSelect.closest('.item-row');
    const itemSelect = itemRow.querySelector('.item-select');
    const type = typeSelect.value;
    
    itemSelect.innerHTML = '<option value="">Pilih item</option>';
    itemSelect.disabled = !type;
    
    if (type === 'produk') {
        produkData.forEach(produk => {
            const option = document.createElement('option');
            option.value = produk.id_produk;
            option.textContent = `${produk.nama_produk} - Rp ${formatNumber(produk.harga)}`;
            option.dataset.harga = produk.harga;
            itemSelect.appendChild(option);
        });
    } else if (type === 'menu') {
        menuData.forEach(menu => {
            const option = document.createElement('option');
            option.value = menu.id_menu;
            option.textContent = `${menu.nama_menu} - Rp ${formatNumber(menu.harga_satuan)}`;
            option.dataset.harga = menu.harga_satuan;
            itemSelect.appendChild(option);
        });
    }
    
    resetSubtotal(itemRow);
}

function handleItemChange(itemSelect) {
    const itemRow = itemSelect.closest('.item-row');
    const selectedOption = itemSelect.options[itemSelect.selectedIndex];
    const harga = selectedOption.dataset.harga || 0;
    
    itemRow.querySelector('.item-harga').value = harga;
    calculateSubtotal(itemRow.querySelector('.item-qty'));
}

function calculateSubtotal(qtyInput) {
    const itemRow = qtyInput.closest('.item-row');
    const qty = parseInt(qtyInput.value) || 0;
    const harga = parseFloat(itemRow.querySelector('.item-harga').value) || 0;
    const subtotal = qty * harga;
    
    itemRow.querySelector('.item-subtotal').value = `Rp ${formatNumber(subtotal)}`;
    calculateTotal();
}

function calculateTotal() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseInt(row.querySelector('.item-qty').value) || 0;
        const harga = parseFloat(row.querySelector('.item-harga').value) || 0;
        subtotal += qty * harga;
    });
    
    const selectedZone = document.getElementById('shipping_zone_id').options[document.getElementById('shipping_zone_id').selectedIndex];
    const ongkir = parseFloat(selectedZone.dataset.ongkir) || 0;
    const total = subtotal + ongkir;
    
    document.getElementById('subtotal-display').textContent = `Rp ${formatNumber(subtotal)}`;
    document.getElementById('total-display').textContent = `Rp ${formatNumber(total)}`;
}

function addNewItem() {
    const container = document.getElementById('items-container');
    const newItemHtml = `
        <div class="item-row mb-3" data-index="${itemCounter}">
            <div class="row g-2">
                <div class="col-md-3 col-6">
                    <label class="form-label small">Tipe</label>
                    <select class="form-select form-select-sm rounded-3 item-type" name="items[${itemCounter}][type]" required>
                        <option value="">Pilih</option>
                        <option value="produk">Produk</option>
                        <option value="menu">Menu</option>
                    </select>
                </div>
                <div class="col-md-4 col-6">
                    <label class="form-label small">Item</label>
                    <select class="form-select form-select-sm rounded-3 item-select" name="items[${itemCounter}][id]" required disabled>
                        <option value="">Pilih tipe dulu</option>
                    </select>
                </div>
                <div class="col-md-2 col-4">
                    <label class="form-label small">Qty</label>
                    <input type="number" class="form-control form-control-sm rounded-3 item-qty" 
                           name="items[${itemCounter}][qty]" min="1" value="1" required>
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label small">Subtotal</label>
                    <input type="text" class="form-control form-control-sm rounded-3 item-subtotal" readonly>
                    <input type="hidden" class="item-harga" name="items[${itemCounter}][harga]">
                </div>
                <div class="col-md-1 col-2">
                    <label class="form-label small d-none d-md-block">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm rounded-3 remove-item-btn w-100">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', newItemHtml);
    itemCounter++;
}

function removeItem(itemRow) {
    if (document.querySelectorAll('.item-row').length > 1) {
        itemRow.remove();
        calculateTotal();
    } else {
        alert('Minimal harus ada 1 item');
    }
}

function resetSubtotal(itemRow) {
    itemRow.querySelector('.item-subtotal').value = '';
    itemRow.querySelector('.item-harga').value = '';
    calculateTotal();
}

function resetItemsForm() {
    const container = document.getElementById('items-container');
    container.innerHTML = `
        <div class="item-row mb-3" data-index="0">
            <div class="row g-2">
                <div class="col-md-3 col-6">
                    <label class="form-label small">Tipe</label>
                    <select class="form-select form-select-sm rounded-3 item-type" name="items[0][type]" required>
                        <option value="">Pilih</option>
                        <option value="produk">Produk</option>
                        <option value="menu">Menu</option>
                    </select>
                </div>
                <div class="col-md-4 col-6">
                    <label class="form-label small">Item</label>
                    <select class="form-select form-select-sm rounded-3 item-select" name="items[0][id]" required disabled>
                        <option value="">Pilih tipe dulu</option>
                    </select>
                </div>
                <div class="col-md-2 col-4">
                    <label class="form-label small">Qty</label>
                    <input type="number" class="form-control form-control-sm rounded-3 item-qty" 
                           name="items[0][qty]" min="1" value="1" required>
                </div>
                <div class="col-md-3 col-8">
                    <label class="form-label small">Subtotal</label>
                    <input type="text" class="form-control form-control-sm rounded-3 item-subtotal" readonly>
                    <input type="hidden" class="item-harga" name="items[0][harga]">
                </div>
            </div>
        </div>
    `;
    itemCounter = 1;
    calculateTotal();
}

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

// ===================== EXPORT FUNCTIONS =====================
function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text('Laporan Transaksi', 20, 20);
    doc.autoTable({ html: '#transactionTable', startY: 30 });
    doc.save('laporan-transaksi.pdf');
    Swal.fire({
        toast: true,
        icon: 'success',
        title: 'PDF berhasil didownload!',
        timer: 2000,
        showConfirmButton: false,
        position: 'top-end'
    });
}

function exportToExcel() {
    const table = document.getElementById('transactionTable');
    let csv = '';
    table.querySelectorAll('tr').forEach(row => {
        let cols = row.querySelectorAll('th,td');
        let rowData = [];
        cols.forEach(c => rowData.push(c.innerText));
        csv += rowData.join(',') + '\n';
    });
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'laporan-transaksi.csv';
    link.click();
    Swal.fire({
        toast: true,
        icon: 'success',
        title: 'Excel berhasil didownload!',
        timer: 2000,
        showConfirmButton: false,
        position: 'top-end'
    });
}
</script>
@endsection
