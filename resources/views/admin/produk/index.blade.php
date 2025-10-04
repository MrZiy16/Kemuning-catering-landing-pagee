@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        <i class="fas fa-box me-2"></i>Produk
    </h1>
    <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Produk
    </a>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.produk.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="paket_box" {{ request('kategori') == 'paket_box' ? 'selected' : '' }}>Paket Box</option>
                        <option value="prasmanan" {{ request('kategori') == 'prasmanan' ? 'selected' : '' }}>Prasmanan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@if($produks->count() > 0)
    <!-- Grid View -->
    <div class="row">
        @foreach($produks as $produk)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                @if($produk->gambar)
                    <img src="{{ asset('storage/' . $produk->gambar) }}" 
                         class="card-img-top" alt="{{ $produk->nama_produk }}"
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                         style="height: 200px;">
                        <i class="fas fa-box text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $produk->nama_produk }}</h5>
                    <p class="card-text text-muted small">
                        {{ Str::limit($produk->deskripsi, 80) }}
                    </p>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge {{ $produk->kategori_produk == 'paket_box' ? 'bg-primary' : 'bg-success' }}">
                                {{ $produk->kategori_produk == 'paket_box' ? 'Paket Box' : 'Prasmanan' }}
                            </span>
                            <span class="badge {{ $produk->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $produk->status == 'active' ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        @if($produk->jumlah_orang)
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i>{{ $produk->jumlah_orang }} orang
                            </small>
                        </div>
                        @endif
                        
                        <div class="mb-2">
                            <small class="text-muted">Menu items:</small>
                            @if($produk->menuItems && $produk->menuItems->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($produk->menuItems->take(3) as $menu)
                                        <span class="badge bg-light text-dark">{{ $menu->nama_menu }}</span>
                                    @endforeach
                                    @if($produk->menuItems->count() > 3)
                                        <span class="badge bg-light text-dark">+{{ $produk->menuItems->count() - 3 }} lainnya</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-primary h5 mb-0">Rp {{ number_format($produk->harga, 0, ',', '.') }}</strong>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.produk.show', $produk->slug) }}" 
                                   class="btn btn-sm btn-outline-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.produk.edit', $produk->slug) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.produk.destroy', $produk->slug) }}" 
                                      method="POST" 
                                      style="display: inline-block;"
                                      onsubmit="event.preventDefault(); confirmDelete(this);">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $produks->withQueryString()->links() }}
    </div>

@else
    <div class="text-center py-5">
        <i class="fas fa-box fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">Belum ada data produk</h5>
        <p class="text-muted">Mulai dengan menambahkan produk pertama Anda</p>
        <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Produk
        </a>
    </div>
@endif
@endsection