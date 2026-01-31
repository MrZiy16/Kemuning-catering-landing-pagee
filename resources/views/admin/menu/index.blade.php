    @extends('layouts.app')

    @section('title', 'Master Menu')

    @section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-list me-2"></i>Master Menu
        </h1>
        <a href="{{ route('admin.menu.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Menu
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.menu.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $kategori)) }}
                                </option>
                            @endforeach
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
                        <input type="text" name="search" class="form-control" placeholder="Cari menu..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($menus->count() > 0)
        <!-- Grid View -->
        <div class="row">
            @foreach($menus as $menu)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($menu->gambar)
                        <img src="{{ asset('storage/menu/' . $menu->gambar) }}" 
                            class="card-img-top" alt="{{ $menu->nama_menu }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
                            <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $menu->nama_menu }}</h6>
                        <p class="card-text text-muted small">
                            {{ Str::limit($menu->deskripsi, 80) }}
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">
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
                                </small>
                                <span class="badge status-toggle {{ $menu->status == 'active' ? 'bg-success' : 'bg-danger' }}" 
                                    id="status-{{ $menu->id_menu }}"
                                    onclick="toggleStatus({{ $menu->id_menu }})">
                                    {{ $menu->status == 'active' ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-primary">Rp {{ number_format($menu->harga_satuan, 0, ',', '.') }}</strong>
                                <div class="btn-group" role="group">
                                <a href="{{ route('admin.menu.show', $menu->slug) }}" class="btn btn-sm btn-outline-info" title="Detail">
        <i class="fas fa-eye"></i>
    </a>

                                <a href="{{ route('admin.menu.edit', $menu->slug) }}" class="btn btn-sm btn-outline-warning">
        <i class="fas fa-edit"></i> Edit
    </a>

                                    <form action="{{ route('admin.menu.destroy', $menu->slug) }}" 
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
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-3">
            <div class="text-muted small mb-2 mb-md-0">
                @if(method_exists($menus, 'firstItem'))
                    Menampilkan {{ $menus->firstItem() }}-{{ $menus->lastItem() }} dari {{ $menus->total() }} menu
                @endif
            </div>
            <nav aria-label="Menu pagination">
                {{ $menus->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
            </nav>
        </div>

    @else
        <div class="text-center py-5">
            <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada data menu</h5>
            <p class="text-muted">Mulai dengan menambahkan menu pertama Anda</p>
            <a href="{{ route('admin.menu.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Menu
            </a>
        </div>
    @endif
    @endsection