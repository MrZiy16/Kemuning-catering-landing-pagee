@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Header Section --}}
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="fas fa-receipt text-white fs-4"></i>
            </div>
        </div>
        <div>
            <h3 class="mb-1 fw-bold text-dark">Detail Transaksi</h3>
            <span class="badge bg-primary fs-6 px-3 py-2">#{{ $transaksi->id_transaksi }}</span>
        </div>
    </div>

    {{-- Info Utama --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-user text-info fs-5"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold text-dark">Informasi Customer</h5>
                    </div>
                    <div class="customer-info">
                        <div class="info-item mb-3">
                            <span class="text-muted small d-block">Nama Customer</span>
                            <span class="fw-semibold text-dark">{{ $transaksi->customer->nama }}</span>
                        </div>
                        <div class="info-item mb-3">
                            <span class="text-muted small d-block">No HP</span>
                            <span class="fw-semibold text-dark">
                                <i class="fas fa-phone-alt text-success me-1"></i>
                                {{ $transaksi->customer->no_hp }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="text-muted small d-block">Alamat Pengiriman</span>
                            <span class="fw-semibold text-dark">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                {{ $transaksi->alamat_pengiriman }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-chart-line text-success fs-5"></i>
                        </div>
                        <h5 class="card-title mb-0 fw-bold text-dark">Ringkasan Transaksi</h5>
                    </div>
                    <div class="transaction-summary">
                        <div class="info-item mb-3">
                            <span class="text-muted small d-block">Status Pesanan</span>
                            <span class="badge fs-6 px-3 py-2
                                @switch($transaksi->status)
                                    @case('pending') bg-warning text-dark @break
                                    @case('draft') bg-secondary text-light @break
                                    @case('confirmed') bg-primary @break
                                    @case('preparing') bg-info text-dark @break
                                    @case('ready') bg-dark @break
                                    @case('delivered') bg-success @break
                                    @case('completed') bg-success @break
                                    @case('cancelled') bg-danger @break
                                    @default bg-light text-dark
                                @endswitch
                            ">
                                <i class="fas fa-circle me-1"></i>
                                {{ ucfirst($transaksi->status) }}
                            </span>
                        </div>
                        <div class="info-item mb-3">
                            <span class="text-muted small d-block">Tanggal Acara</span>
                            <span class="fw-semibold text-dark">
                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_acara)->format('d-m-Y') }} {{ $transaksi->waktu_acara}}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="text-muted small d-block">Total Pembayaran</span>
                            <span class="fw-bold text-success fs-5">
                                <i class="fas fa-rupiah-sign me-1"></i>
                                Rp {{ number_format($transaksi->total,0,',','.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Item --}}
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header bg-gradient border-0 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex align-items-center">
                <i class="fas fa-shopping-bag text-white me-2 fs-5"></i>
                <h5 class="mb-0 text-white fw-bold">Detail Item Pesanan</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3 px-4 text-muted fw-semibold">
                                <i class="fas fa-utensils me-2"></i>Produk/Menu
                            </th>
                            <th class="border-0 py-3 text-center text-muted fw-semibold">
                                <i class="fas fa-sort-numeric-up me-2"></i>Qty
                            </th>
                            <th class="border-0 py-3 text-end text-muted fw-semibold">
                                <i class="fas fa-tag me-2"></i>Harga
                            </th>
                            <th class="border-0 py-3 text-end text-muted fw-semibold">
                                <i class="fas fa-calculator me-2"></i>Subtotal
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->detailTransaksi as $d)
                        <tr class="border-bottom">
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-hamburger text-primary"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $d->produk->nama_produk ?? $d->menu->nama_menu }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-secondary px-3 py-2">{{ $d->qty }}</span>
                            </td>
                            <td class="py-3 text-end fw-semibold text-dark">Rp {{ number_format($d->harga,0,',','.') }}</td>
                            <td class="py-3 text-end fw-bold text-success">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Menu Custom --}}
    @if($transaksi->transaksiMenuCustom->count())
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header bg-gradient border-0 py-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="d-flex align-items-center">
                <i class="fas fa-magic text-white me-2 fs-5"></i>
                <h5 class="mb-0 text-white fw-bold">Menu Custom</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3 px-4 text-muted fw-semibold">
                                <i class="fas fa-star me-2"></i>Menu
                            </th>
                            <th class="border-0 py-3 text-center text-muted fw-semibold">
                                <i class="fas fa-sort-numeric-up me-2"></i>Qty
                            </th>
                            <th class="border-0 py-3 text-end text-muted fw-semibold">
                                <i class="fas fa-tag me-2"></i>Harga
                            </th>
                            <th class="border-0 py-3 text-end text-muted fw-semibold">
                                <i class="fas fa-calculator me-2"></i>Subtotal
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->transaksiMenuCustom as $c)
                        <tr class="border-bottom">
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-crown text-warning"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $c->menu->nama_menu }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-secondary px-3 py-2">{{ $c->qty }}</span>
                            </td>
                            <td class="py-3 text-end fw-semibold text-dark">Rp {{ number_format($c->harga,0,',','.') }}</td>
                            <td class="py-3 text-end fw-bold text-success">Rp {{ number_format($c->subtotal,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Log Status --}}
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header bg-gradient border-0 py-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="d-flex align-items-center">
                <i class="fas fa-history text-white me-2 fs-5"></i>
                <h5 class="mb-0 text-white fw-bold">Riwayat Status</h5>
            </div>
        </div>
        <div class="card-body p-4">
            @if($transaksi->statusLog->count())
                <div class="timeline">
                    @foreach($transaksi->statusLog as $log)
                        <div class="timeline-item d-flex align-items-center mb-3 p-3 bg-light rounded-3">
                            <div class="timeline-marker bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-arrow-right text-white"></i>
                            </div>
                            <div class="timeline-content flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="badge bg-light text-dark border px-2 py-1">{{ $log->status_from ?? '---' }}</span>
                                    <i class="fas fa-arrow-right text-muted"></i>
                                    <span class="badge bg-secondary px-2 py-1">{{ $log->status_to }}</span>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-comment-alt me-1"></i>
                                    {{ $log->keterangan }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-info-circle text-muted fs-1 mb-3"></i>
                    <p class="text-muted mb-0">Belum ada riwayat perubahan status.</p>
                </div>
            @endif
        </div>
    </div>


    {{-- Tombol Edit & Hapus --}}
    {{-- Tombol Edit --}}
@if(in_array($transaksi->status, ['pending','draft','confirmed']))
<div class="card border-0 shadow-lg mb-4">
    <div class="card-body p-4">
        <h6 class="fw-bold text-dark mb-3">
            <i class="fas fa-tools text-warning me-2"></i>Aksi Transaksi
        </h6>
        <div class="d-flex gap-3">
            <a href="{{ route('admin.transaksi.edit',$transaksi->id_transaksi)}}" 
               class="btn btn-warning btn-lg px-4 shadow-sm">
                <i class="fas fa-edit me-2"></i>Edit Transaksi
            </a>
        </div>
        <small class="text-muted mt-2 d-block">
            <i class="fas fa-info-circle me-1"></i>
            Edit hanya tersedia untuk status: Pending, Draft, dan Confirmed
        </small>
    </div>
</div>
@endif

{{-- Tombol Delete --}}
@if(auth()->check() && auth()->user()->peran === 'super_admin' && $transaksi->status === 'cancelled')
<div class="card border-0 shadow-lg">
    <div class="card-body p-4">
        <h6 class="fw-bold text-dark mb-3">
            <i class="fas fa-trash-alt text-danger me-2"></i>Aksi Hapus
        </h6>
        <form method="POST" action="{{ route('admin.transaksi.destroy', $transaksi->id_transaksi) }}" 
              onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Data tidak dapat dikembalikan!');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-lg px-4 shadow-sm">
                <i class="fas fa-trash me-2"></i>Hapus Transaksi
            </button>
        </form>
        <small class="text-muted mt-2 d-block">
            <i class="fas fa-info-circle me-1"></i>
            Hapus hanya tersedia jika status transaksi sudah Cancelled
        </small>
    </div>
</div>
@endif

</div>

<style>
    .info-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0 !important;
    }
    
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .btn {
        transition: all 0.2s ease-in-out;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,0.05);
    }
    
    .bg-gradient {
        background-image: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-secondary) 100%) !important;
    }
</style>
@endsection
