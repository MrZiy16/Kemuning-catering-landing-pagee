@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Produk: {{ $produk->nama_produk }}
                </h5>
                <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
            
            <div class="card-body">
                <form action="{{ route('admin.produk.update', $produk) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_produk') is-invalid @enderror" 
                                       id="nama_produk" 
                                       name="nama_produk" 
                                       value="{{ old('nama_produk', $produk->nama_produk) }}" 
                                       required>
                                @error('nama_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('harga') is-invalid @enderror" 
                                           id="harga" 
                                           name="harga" 
                                           value="{{ old('harga', $produk->harga) }}" 
                                           min="0" 
                                         
                                           required>
                                </div>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="kategori_produk" class="form-label">Kategori Produk <span class="text-danger">*</span></label>
                                <select class="form-select @error('kategori_produk') is-invalid @enderror" 
                                        id="kategori_produk" 
                                        name="kategori_produk" 
                                        onchange="toggleJumlahOrang()"
                                        required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="paket_box" {{ old('kategori_produk', $produk->kategori_produk) == 'paket_box' ? 'selected' : '' }}>Paket Box</option>
                                    <option value="prasmanan" {{ old('kategori_produk', $produk->kategori_produk) == 'prasmanan' ? 'selected' : '' }}>Prasmanan</option>
                                    <option value="pondokan" {{ old('kategori_produk', $produk->kategori_produk) == 'pondokan' ? 'selected' : '' }}>Pondokan</option>
                                    <option value="tumpeng" {{ old('kategori_produk', $produk->kategori_produk) == 'tumpeng' ? 'selected' : '' }}>Tumpeng</option>
                                </select>
                                @error('kategori_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3" id="jumlah_orang_field" style="display: {{ old('kategori_produk', $produk->kategori_produk) == 'prasmanan' ? 'block' : 'none' }};">
                                <label for="jumlah_orang" class="form-label">Jumlah Orang</label>
                                <input type="number" 
                                       class="form-control @error('jumlah_orang') is-invalid @enderror" 
                                       id="jumlah_orang" 
                                       name="jumlah_orang" 
                                       value="{{ old('jumlah_orang', $produk->jumlah_orang) }}" 
                                       min="1">
                                @error('jumlah_orang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="">Pilih Status</option>
                                    <option value="active" {{ old('status', $produk->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $produk->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3" 
                                  placeholder="Masukkan deskripsi produk...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="gambar" class="form-label">Gambar Produk</label>
                        
                        @if($produk->gambar)
                            <div class="mb-3">
                                <label class="form-label">Gambar Saat Ini:</label><br>
                                <img src="{{ asset('storage/produk/' . $produk->gambar) }}" 
                                     alt="{{ $produk->nama_produk }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 300px;">
                            </div>
                        @endif
                        
                        <input type="file" 
                               class="form-control @error('gambar') is-invalid @enderror" 
                               id="gambar" 
                               name="gambar" 
                               accept="image/*"
                               onchange="previewImage(this)">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Format: JPEG, PNG, JPG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.
                        </div>
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <!-- New Image Preview -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <label class="form-label">Preview Gambar Baru:</label><br>
                            <img id="preview" src="#" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                        </div>
                    </div>

                    <!-- Menu Items Section -->
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Menu Items <span class="text-danger">*</span></h6>
                            <small class="text-muted">Pilih menu yang akan disertakan dalam produk ini</small>
                        </div>
                        <div class="card-body">
                            <div id="menu-items-container">
                                <!-- Menu items will be populated from existing data -->
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addMenuItem()">
                                <i class="fas fa-plus me-1"></i>Tambah Menu
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let menuItemIndex = 0;
const menus = @json($menus);
const existingMenuItems = @json($produk->menuItems);

function toggleJumlahOrang() {
    const kategori = document.getElementById('kategori_produk').value;
    const jumlahOrangField = document.getElementById('jumlah_orang_field');
    
    if (kategori === 'prasmanan') {
        jumlahOrangField.style.display = 'block';
        document.getElementById('jumlah_orang').setAttribute('required', 'required');
    } else {
        jumlahOrangField.style.display = 'none';
        document.getElementById('jumlah_orang').removeAttribute('required');
        document.getElementById('jumlah_orang').value = '';
    }
}

function addMenuItem(selectedMenuId = '', selectedQty = '') {
    const container = document.getElementById('menu-items-container');
    const menuItemHtml = `
        <div class="row mb-3 menu-item-row" id="menu-item-${menuItemIndex}">
            <div class="col-md-6">
                <select name="menu_items[${menuItemIndex}][id_menu]" class="form-select" required>
                    <option value="">Pilih Menu</option>
                    ${Object.keys(menus).map(kategori => {
                        const menuOptions = menus[kategori].map(menu => 
                            `<option value="${menu.id_menu}" ${selectedMenuId == menu.id_menu ? 'selected' : ''}>${menu.nama_menu} - Rp ${new Intl.NumberFormat('id-ID').format(menu.harga_satuan)}</option>`
                        ).join('');
                        return `<optgroup label="${kategori.replace('_', ' ').toUpperCase()}">${menuOptions}</optgroup>`;
                    }).join('')}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" 
                       name="menu_items[${menuItemIndex}][qty]" 
                       class="form-control" 
                       placeholder="Qty" 
                       min="1" 
                       value="${selectedQty}"
                       required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeMenuItem(${menuItemIndex})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', menuItemHtml);
    menuItemIndex++;
}

function removeMenuItem(index) {
    document.getElementById(`menu-item-${index}`).remove();
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Load existing menu items on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add existing menu items
    existingMenuItems.forEach(item => {
        addMenuItem(item.id_menu, item.pivot.qty);
    });
    
    // If no existing items, add one empty item
    if (existingMenuItems.length === 0) {
        addMenuItem();
    }
});
</script>
@endpush