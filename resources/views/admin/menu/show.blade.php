@extends('layouts.app')

@section('title', 'Detail Menu')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Detail Menu
                </h5>
                <div class="btn-group">
                    <a href="{{ route('admin.menu.edit', $menu) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($menu->gambar)
                            <img src="{{ asset('storage/menu/' . $menu->gambar) }}" 
                                 alt="{{ $menu->nama_menu }}" 
                                 class="img-fluid rounded shadow-sm">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                 style="height: 250px;">
                                <i class="fas fa-image text-muted" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-8">
                        <h3 class="mb-3">{{ $menu->nama_menu }}</h3>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Kategori:</strong>
                            </div>
                            <div class="col-sm-8">
                                @switch($menu->kategori_menu)
                                    @case('makanan_utama')
                                        <span class="badge bg-primary fs-6">Makanan Utama</span>
                                        @break
                                    @case('sayuran')
                                        <span class="badge bg-success fs-6">Sayuran</span>
                                        @break
                                    @case('lauk')
                                        <span class="badge bg-warning fs-6">Lauk</span>
                                        @break
                                    @case('minuman')
                                        <span class="badge bg-info fs-6">Minuman</span>
                                        @break
                                    @case('dessert')
                                        <span class="badge bg-secondary fs-6">Dessert</span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Harga Satuan:</strong>
                            </div>
                            <div class="col-sm-8">
                                <h4 class="text-primary mb-0">Rp {{ number_format($menu->harga_satuan, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Status:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge {{ $menu->status == 'active' ? 'bg-success' : 'bg-danger' }} fs-6">
                                    {{ $menu->status == 'active' ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        
                        @if($menu->deskripsi)
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Deskripsi:</strong>
                            </div>
                            <div class="col-sm-8">
                                <p class="mb-0">{{ $menu->deskripsi }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong>Dibuat:</strong>
                            </div>
                            <div class="col-sm-8">
                              {{ \Carbon\Carbon::parse($menu->created_at)->format('d/m/Y') }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <form action="{{ route('admin.menu.destroy', $menu) }}" 
                          method="POST" 
                          onsubmit="event.preventDefault(); confirmDelete(this);">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Hapus Menu
                        </button>
                    </form>
                    
                    <div class="btn-group">
                        <a href="{{ route('admin.menu.edit', $menu) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit Menu
                        </a>
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-1"></i>Semua Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection