@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-list-alt text-white fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Daftar Transaksi</h2>
                        <p class="text-muted mb-0">Kelola dan pantau semua transaksi pelanggan</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success btn-lg shadow-sm" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </button>
                    <button class="btn btn-primary btn-lg shadow-sm" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </button>
                    
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-4">
        @php
            $statusStats = [
                ['key' => 'all', 'label' => 'Total', 'icon' => 'fa-chart-bar', 'color' => 'primary'],
                ['key' => 'pending', 'label' => 'Pending', 'icon' => 'fa-clock', 'color' => 'warning'],
                ['key' => 'confirmed', 'label' => 'Confirmed', 'icon' => 'fa-check-circle', 'color' => 'info'],
                ['key' => 'completed', 'label' => 'Completed', 'icon' => 'fa-check-double', 'color' => 'success']
            ];
        @endphp
        @foreach($statusStats as $stat)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 stat-card" data-status="{{ $stat['key'] }}">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-{{ $stat['color'] }} bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }} fs-4"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-bold text-{{ $stat['color'] }}">{{ $statusCounts[$stat['key']] ?? 0 }}</h3>
                                <p class="mb-0 text-muted small">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header bg-gradient border-0 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex align-items-center">
                <i class="fas fa-filter text-white me-2 fs-5"></i>
                <h5 class="mb-0 text-white fw-bold">Filter & Pencarian</h5>
            </div>
        </div>
        <div class="card-body p-4">
            <form method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-dark"><i class="fas fa-tasks me-2 text-primary"></i>Status</label>
                        <select name="status" class="form-select border-0 shadow-sm">
                            <option value="">🔍 Semua Status</option>
                            @foreach($statusCounts as $key => $count)
                                @if($key !== 'all')
                                    <option value="{{ $key }}" {{ request('status')==$key?'selected':'' }}>
                                        {{ ucfirst($key) }} ({{ $count }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-dark"><i class="fas fa-calendar-alt me-2 text-success"></i>Dari</label>
                        <input type="date" name="tanggal_mulai" class="form-control border-0 shadow-sm" value="{{ request('tanggal_mulai') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-dark"><i class="fas fa-calendar-check me-2 text-danger"></i>Sampai</label>
                        <input type="date" name="tanggal_akhir" class="form-control border-0 shadow-sm" value="{{ request('tanggal_akhir') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-dark"><i class="fas fa-search me-2 text-info"></i>Pencarian</label>
                        <input type="text" name="search" class="form-control border-0 shadow-sm" placeholder="Cari nama customer/no HP..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary shadow-sm flex-fill"><i class="fas fa-search me-1"></i>Filter</button>
                        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary shadow-sm ms-2 flex-fill"><i class="fas fa-undo me-1"></i>Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
<button class="btn btn-success btn-lg shadow-sm mb-4" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
    <i class="fas fa-plus me-2"></i>Tambah Transaksi
</button>
    {{-- Table --}}
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-gradient border-0 py-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-table text-white me-2 fs-5"></i>
                    <h5 class="mb-0 text-white fw-bold">Data Transaksi</h5>
                </div>
                <div class="text-white">
                    <small><i class="fas fa-info-circle me-1"></i>Total: {{ $transaksis->total() }} transaksi</small>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="transactionTable">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Tanggal Acara</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $trx)
                        <tr id="trx-{{ $trx->id_transaksi }}" class="transaction-row">
                            <td class="fw-bold text-primary">#{{ $trx->id_transaksi }}</td>
                            <td>{{ $trx->customer->nama ?? '-' }}<br><small>{{ $trx->customer->no_hp ?? '' }}</small></td>
                            <td>{{ \Carbon\Carbon::parse($trx->tanggal_acara)->translatedFormat('d F Y') }} {{ $trx->waktu_acara }}</td>
                            <td>Rp {{ number_format($trx->total,0,',','.') }}</td>
                            <td>
                                <span class="badge status-label
                                    @switch($trx->status)
                                                   @case('draft') bg-warning text-dark @break
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
                           <td>
    {{-- Detail --}}
    <a href="{{ route('admin.transaksi.show',$trx->id_transaksi) }}" 
       class="btn btn-sm btn-info">
       <i class="fas fa-eye"></i>
    </a>

    {{-- Edit transaksi hanya kalau status draft/pending/confirmed --}}
    @if(in_array($trx->status, ['draft','pending','confirmed']))
        <a href="{{ route('admin.transaksi.edit',$trx->id_transaksi) }}" 
           class="btn btn-sm btn-primary">
           <i class="fas fa-edit"></i>
        </a>
    @endif

    {{-- Update Status --}}
    <button type="button" class="btn btn-sm btn-warning btn-update-status"
        data-id="{{ $trx->id_transaksi }}" data-status="{{ $trx->status }}"
        data-bs-toggle="modal" data-bs-target="#updateStatusModal">
        <i class="fas fa-sync-alt"></i>
    </button>

    {{-- Download Invoice --}}
     <a href="{{ route('admin.invoice.download',$trx->id_transaksi) }}" 
           class="btn btn-sm btn-primary">
           <i class="fas fa-download"></i>
        </a>
</td>

                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5">Tidak ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($transaksis->hasPages())
        <div class="card-footer bg-light border-0">
            <div class="d-flex justify-content-between">
                <small>Menampilkan {{ $transaksis->firstItem() ?? 0 }} - {{ $transaksis->lastItem() ?? 0 }} dari {{ $transaksis->total() }}</small>
                {{ $transaksis->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Update Status -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-transaksi-id">
                <select id="modal-status" class="form-select mb-3">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="delivered">Delivered</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <textarea id="modal-keterangan" class="form-control" placeholder="Keterangan..."></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="save-status-btn">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
<div class="modal-dialog modal-sm modal-dialog-centered">
<div class="modal-content border-0 bg-transparent text-center">
<div class="modal-body">
<div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem"></div>
<h6 class="text-white">Memproses...</h6>
</div>
</div>
</div>
</div>

<!-- Add Transaction Modal -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header bg-success text-white">
<h5 class="modal-title" id="addTransactionModalLabel">
<i class="fas fa-plus me-2"></i>Tambah Transaksi Baru
</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form id="addTransactionForm">
@csrf
<div class="modal-body">
<div class="alert alert-info">
<i class="fas fa-info-circle me-2"></i>
<strong>Info:</strong> Form ini untuk membuat transaksi baru. Detail produk/menu dapat ditambahkan setelah transaksi dibuat.
</div>

<div class="row">
<!-- Customer Selection -->
<div class="col-md-12">
<div class="mb-3">
<label for="customer_id" class="form-label fw-semibold">
<i class="fas fa-user text-primary me-2"></i>Customer <span class="text-danger">*</span>
</label>
<select class="form-select" id="customer_id" name="id_customer" required>
<option value="">Pilih Customer</option>
@foreach($customers as $customer)
<option value="{{ $customer->id }}">
{{ $customer->nama }} - {{ $customer->no_hp }}
</option>
@endforeach
</select>
</div>
</div>
</div>

<!-- Product/Menu Selection -->
<div class="card bg-light mb-3">
<div class="card-header">
<h6 class="mb-0"><i class="fas fa-shopping-cart text-primary me-2"></i>Pilih Produk/Menu <span class="text-danger">*</span></h6>
</div>
<div class="card-body">
<div id="items-container">
<div class="item-row mb-3" data-index="0">
<div class="row">
<div class="col-md-4">
<label class="form-label">Tipe</label>
<select class="form-select item-type" name="items[0][type]" required>
<option value="">Pilih Tipe</option>
<option value="produk">Produk</option>
<option value="menu">Menu</option>
</select>
</div>
<div class="col-md-4">
<label class="form-label">Item</label>
<select class="form-select item-select" name="items[0][id]" required disabled>
<option value="">Pilih tipe dulu</option>
</select>
</div>
<div class="col-md-2">
<label class="form-label">Qty</label>
<input type="number" class="form-control item-qty" name="items[0][qty]" min="1" value="1" required>
</div>
<div class="col-md-2">
<label class="form-label">Subtotal</label>
<div class="input-group">
<span class="input-group-text">Rp</span>
<input type="text" class="form-control item-subtotal" readonly>
</div>
<input type="hidden" class="item-harga" name="items[0][harga]">
</div>
</div>
</div>
</div>
<button type="button" class="btn btn-sm btn-secondary" id="add-item-btn">
<i class="fas fa-plus me-1"></i>Tambah Item
</button>
</div>
</div>

<!-- Total Display -->
<div class="alert alert-success">
<div class="d-flex justify-content-between align-items-center">
<strong><i class="fas fa-calculator me-2"></i>Total Transaksi:</strong>
<h5 class="mb-0" id="total-display">Rp 0</h5>
</div>
</div>

<div class="row">
<!-- Event Date -->
<div class="col-md-6">
<div class="mb-3">
<label for="tanggal_acara" class="form-label fw-semibold">
<i class="fas fa-calendar-alt text-info me-2"></i>Tanggal Acara <span class="text-danger">*</span>
</label>
<input type="date" class="form-control" id="tanggal_acara" name="tanggal_acara" 
min="{{ date('Y-m-d') }}" required>
</div>
</div>

<!-- Event Time -->
<div class="col-md-6">
<div class="mb-3">
<label for="waktu_acara" class="form-label fw-semibold">
<i class="fas fa-clock text-warning me-2"></i>Waktu Acara <span class="text-danger">*</span>
</label>
<input type="time" class="form-control" id="waktu_acara" name="waktu_acara" required>
</div>
</div>
</div>

<!-- Delivery Address -->
<div class="mb-3">
<label for="alamat_pengiriman" class="form-label fw-semibold">
<i class="fas fa-map-marker-alt text-danger me-2"></i>Alamat Pengiriman <span class="text-danger">*</span>
</label>
<textarea class="form-control" id="alamat_pengiriman" name="alamat_pengiriman" 
rows="3" required placeholder="Masukkan alamat lengkap pengiriman..."></textarea>
</div>

<!-- Customer Notes -->
<div class="mb-3">
<label for="catatan_customer" class="form-label fw-semibold">
<i class="fas fa-sticky-note text-secondary me-2"></i>Catatan Customer
</label>
<textarea class="form-control" id="catatan_customer" name="catatan_customer" 
rows="2" placeholder="Catatan khusus dari customer (opsional)..."></textarea>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
<i class="fas fa-times me-2"></i>Batal
</button>
<button type="submit" class="btn btn-success">
<i class="fas fa-save me-2"></i>Simpan Transaksi
</button>
</div>
</form>
</div>
</div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<style>
    .transaction-row:hover { background-color: rgba(0,123,255,0.05) !important; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important; }
</style>

<script>
// Data produk dan menu dari server
const produkData = @json($produk);
const menuData = @json($menus);

document.addEventListener('DOMContentLoaded', () => {
    const baseUpdateUrl = "{{ url('admin/transaksi') }}";
    const saveBtn = document.getElementById('save-status-btn');

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

                Swal.fire({toast:true,position:'top-end',icon:'success',title:'Status diupdate',showConfirmButton:false,timer:1500});
            }
        });
    });

    // Handle Add Transaction Form
    const addTransactionForm = document.getElementById('addTransactionForm');
    if (addTransactionForm) {
        addTransactionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
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
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('addTransactionModal')).hide();
                    
                    // Reset form
                    addTransactionForm.reset();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        // Reload page to show new transaction
                        window.location.reload();
                    });
                } else {
                    // Show error message
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
                // Reset button state
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
            // Clear any validation errors
            addTransactionForm.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            addTransactionForm.querySelectorAll('.invalid-feedback').forEach(el => {
                el.remove();
            });
        });
    }

    // Initialize item form handlers
    initializeItemHandlers();
});

// Item management functions
let itemCounter = 1;

function initializeItemHandlers() {
    // Handle type change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-type')) {
            handleTypeChange(e.target);
        }
    });

    // Handle item selection change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-select')) {
            handleItemChange(e.target);
        }
    });

    // Handle quantity change
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-qty')) {
            calculateSubtotal(e.target);
        }
    });

    // Add item button
    document.getElementById('add-item-btn').addEventListener('click', addNewItem);

    // Remove item handler
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
    
    // Clear previous options
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
    
    // Reset subtotal
    resetSubtotal(itemRow);
}

function handleItemChange(itemSelect) {
    const itemRow = itemSelect.closest('.item-row');
    const selectedOption = itemSelect.options[itemSelect.selectedIndex];
    const harga = selectedOption.dataset.harga || 0;
    
    // Set hidden price field
    itemRow.querySelector('.item-harga').value = harga;
    
    // Calculate subtotal
    calculateSubtotal(itemRow.querySelector('.item-qty'));
}

function calculateSubtotal(qtyInput) {
    const itemRow = qtyInput.closest('.item-row');
    const qty = parseInt(qtyInput.value) || 0;
    const harga = parseFloat(itemRow.querySelector('.item-harga').value) || 0;
    const subtotal = qty * harga;
    
    // Update subtotal display
    itemRow.querySelector('.item-subtotal').value = formatNumber(subtotal);
    
    // Update total
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseInt(row.querySelector('.item-qty').value) || 0;
        const harga = parseFloat(row.querySelector('.item-harga').value) || 0;
        total += qty * harga;
    });
    
    document.getElementById('total-display').textContent = `Rp ${formatNumber(total)}`;
}

function addNewItem() {
    const container = document.getElementById('items-container');
    const newItemHtml = `
        <div class="item-row mb-3" data-index="${itemCounter}">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Tipe</label>
                    <select class="form-select item-type" name="items[${itemCounter}][type]" required>
                        <option value="">Pilih Tipe</option>
                        <option value="produk">Produk</option>
                        <option value="menu">Menu</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Item</label>
                    <select class="form-select item-select" name="items[${itemCounter}][id]" required disabled>
                        <option value="">Pilih tipe dulu</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Qty</label>
                    <input type="number" class="form-control item-qty" name="items[${itemCounter}][qty]" min="1" value="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Subtotal</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control item-subtotal" readonly>
                    </div>
                    <input type="hidden" class="item-harga" name="items[${itemCounter}][harga]">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm remove-item-btn d-block">
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
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Tipe</label>
                    <select class="form-select item-type" name="items[0][type]" required>
                        <option value="">Pilih Tipe</option>
                        <option value="produk">Produk</option>
                        <option value="menu">Menu</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Item</label>
                    <select class="form-select item-select" name="items[0][id]" required disabled>
                        <option value="">Pilih tipe dulu</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Qty</label>
                    <input type="number" class="form-control item-qty" name="items[0][qty]" min="1" value="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Subtotal</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control item-subtotal" readonly>
                    </div>
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

// Export PDF
function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text('Laporan Transaksi', 20, 20);
    doc.autoTable({ html: '#transactionTable', startY: 30 });
    doc.save('laporan-transaksi.pdf');
    Swal.fire({toast:true,icon:'success',title:'PDF berhasil didownload!',timer:2000,showConfirmButton:false});
}

// Export Excel
function exportToExcel() {
    const table = document.getElementById('transactionTable');
    let csv = '';
    table.querySelectorAll('tr').forEach(row => {
        let cols = row.querySelectorAll('th,td');
        let rowData = [];
        cols.forEach(c => rowData.push(c.innerText));
        csv += rowData.join(',') + '\\n';
    });
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'laporan-transaksi.csv';
    link.click();
    Swal.fire({toast:true,icon:'success',title:'Excel berhasil didownload!',timer:2000,showConfirmButton:false});
}


</script>
@endsection
