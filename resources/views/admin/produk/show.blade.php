@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Detail Produk
                </h5>
                <div class="btn-group">
                    <a href="{{ route('admin.produk.edit', $produk) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($produk->gambar)
                            <img src="{{ asset('storage/' . $produk->gambar) }}"
                                 alt="{{ $produk->nama_produk }}" 
                                 class="img-fluid rounded shadow-sm">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                 style="height: 300px;">
                                <i class="fas fa-box text-muted" style="font-size: 5rem;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-8">
                        <h3 class="mb-3">{{ $produk->nama_produk }}</h3>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Kategori:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge {{ $produk->kategori_produk == 'paket_box' ? 'bg-primary' : 'bg-success' }} fs-6">
                                    {{ $produk->kategori_produk == 'paket_box' ? 'Paket Box' : 'Prasmanan' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Harga:</strong>
                            </div>
                            <div class="col-sm-8">
                                <h4 class="text-primary mb-0">Rp {{ number_format($produk->harga, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        
                        @if($produk->jumlah_orang)
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Jumlah Orang:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-users me-1"></i>{{ $produk->jumlah_orang }} orang
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Status:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge {{ $produk->status == 'active' ? 'bg-success' : 'bg-danger' }} fs-6">
                                    {{ $produk->status == 'active' ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        
                        @if($produk->deskripsi)
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Deskripsi:</strong>
                            </div>
                            <div class="col-sm-8">
                                <p class="mb-0">{{ $produk->deskripsi }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Dibuat:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ \Carbon\Carbon::parse($produk->created_at)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Menu Items Section -->
                @if($produk->menuItems && $produk->menuItems->count() > 0)
                <hr class="my-4">
                <h5 class="mb-3">
                    <i class="fas fa-utensils me-2"></i>Menu Items
                </h5>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Menu</th>
                                <th>Kategori</th>
                                <th>Harga Satuan</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($produk->menuItems as $index => $menu)
                            @php 
                                $qty = $menu->pivot->qty;
                                $subtotal = $menu->harga_satuan * $qty;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $menu->nama_menu }}</strong>
                                    @if($menu->deskripsi)
                                        <br><small class="text-muted">{{ Str::limit($menu->deskripsi, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @switch($menu->kategori_menu)
                                        @case('makanan_utama')
                                            <span class="badge bg-primary">Makanan Utama</span>
                                            @break
                                        @case('sayuran')
                                            <span class="badge bg-success">Sayuran</span>
                                            @break
                                        @case('lauk')
                                            <span class="badge bg-warning">Lauk</span>
                                            @break
                                        @case('minuman')
                                            <span class="badge bg-info">Minuman</span>
                                            @break
                                        @case('dessert')
                                            <span class="badge bg-secondary">Dessert</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>Rp {{ number_format($menu->harga_satuan, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $qty }}</span>
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <th colspan="5">Total Menu Items:</th>
                                <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <hr class="my-4">
                <div class="text-center py-4">
                    <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Belum ada menu items</h6>
                    <p class="text-muted">Tambahkan menu items untuk produk ini</p>
                    <a href="{{ route('admin.produk.edit', $produk) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Produk
                    </a>
                </div>
                @endif
            </div>
            
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <form action="{{ route('admin.produk.destroy', $produk) }}" 
                          method="POST" 
                          onsubmit="event.preventDefault(); confirmDelete(this);">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Hapus Produk
                        </button>
                    </form>
                    
                    <div class="btn-group">
                        <a href="{{ route('admin.produk.edit', $produk) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Produk
                        </a>
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-1"></i>Semua Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection