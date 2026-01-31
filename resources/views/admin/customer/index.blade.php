@extends('layouts.app')

@section('title', 'Manajemen Customer')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="mb-1 fw-bold text-dark">
                        <i class="fas fa-users me-2 text-primary"></i>Manajemen Customer
                    </h2>
                    <p class="text-muted mb-0 small">Kelola data pelanggan bisnis Anda</p>
                </div>
                <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahCustomer">
                    <i class="fas fa-plus me-2"></i>Tambah Customer
                </button>
            </div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 p-md-4">
            <form class="form-inline" method="GET" action="{{ route('admin.customers.index') }}">
                <div class="input-group w-100">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-lg rounded-start-3" placeholder="Cari nama, email atau telepon...">
                    <button class="btn btn-primary rounded-end-3" type="submit">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent border-0 p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-table me-2 text-primary"></i>Data Customer
                </h5>
                <small class="text-muted">
                    Total: <strong>{{ $customers->total() }}</strong> customer
                </small>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 px-md-4 py-3">#</th>
                            <th class="px-3 px-md-4 py-3">Nama</th>
                            <th class="px-3 px-md-4 py-3 d-none d-md-table-cell">Email</th>
                            <th class="px-3 px-md-4 py-3 d-none d-lg-table-cell">Telepon</th>
                            <th class="px-3 px-md-4 py-3 d-none d-lg-table-cell">Source</th>
                            <th class="px-3 px-md-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $index => $customer)
                            <tr class="border-bottom">
                                <td class="px-3 px-md-4 py-3">
                                    <span class="text-muted">{{ $customers->firstItem() + $index }}</span>
                                </td>
                                <td class="px-3 px-md-4 py-3">
                                    <div class="fw-semibold text-dark">{{ $customer->nama }}</div>
                                    <div class="d-md-none">
                                        <small class="text-muted d-block">{{ $customer->email }}</small>
                                        <small class="text-muted d-block">{{ $customer->no_hp ?? '-' }}</small>
                                    </div>
                                </td>
                                <td class="px-3 px-md-4 py-3 d-none d-md-table-cell">
                                    <small class="text-muted">{{ $customer->email }}</small>
                                </td>
                                <td class="px-3 px-md-4 py-3 d-none d-lg-table-cell">
                                    <small>{{ $customer->no_hp ?? '-' }}</small>
                                </td>
                                <td class="px-3 px-md-4 py-3 d-none d-lg-table-cell">
                                    @if($customer->source === 'online')
                                        <span class="badge bg-primary">Online</span>
                                    @else
                                        <span class="badge bg-secondary">Offline</span>
                                    @endif
                                </td>
                                <td class="px-3 px-md-4 py-3 text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalLihatCustomer{{ $customer->id_customer }}" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                     
                                      
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-inbox text-muted fs-1 mb-3 d-block opacity-25"></i>
                                    <p class="text-muted mb-0">Tidak ada data customer</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
                    </tbody>
                </table>
            </div>
        </div>
        @if($customers->hasPages())
        <div class="card-footer bg-light border-0 p-3 p-md-4 rounded-bottom-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <small class="text-muted">
                    Menampilkan <strong>{{ $customers->firstItem() }}</strong> - <strong>{{ $customers->lastItem() }}</strong> dari <strong>{{ $customers->total() }}</strong>
                </small>
                <nav>
                    {{ $customers->withQueryString()->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Render All Modals After Table --}}
@foreach($customers as $customer)
    <!-- Modal Lihat Customer -->
    <div class="modal fade" id="modalLihatCustomer{{ $customer->id_customer }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-light border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold">Detail Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Nama</label>
                            <p class="fw-semibold text-dark">{{ $customer->nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Email</label>
                            <p class="fw-semibold text-dark">{{ $customer->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Telepon</label>
                            <p class="fw-semibold text-dark">{{ $customer->no_hp ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Source</label>
                            <p>
                                @if($customer->source === 'online')
                                    <span class="badge bg-primary">Online</span>
                                @else
                                    <span class="badge bg-secondary">Offline</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Alamat</label>
                        <p class="text-dark">{{ $customer->alamat ?? '-' }}</p>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Total Transaksi</label>
                            <p class="fw-semibold text-primary fs-6">{{ $customer->getTotalTransaksi() ?? 0 }} transaksi</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Total Belanja</label>
                            <p class="fw-semibold text-success fs-6">Rp {{ number_format($customer->getTotalBelanja() ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Customer -->
    <div class="modal fade" id="modalEditCustomer{{ $customer->id_customer }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-light border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.customers.update', $customer->id_customer) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="nama{{ $customer->id_customer }}" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3 @error('nama') is-invalid @enderror" id="nama{{ $customer->id_customer }}" name="nama" value="{{ old('nama', $customer->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email{{ $customer->id_customer }}" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email{{ $customer->id_customer }}" name="email" value="{{ old('email', $customer->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="no_hp{{ $customer->id_customer }}" class="form-label fw-semibold">Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3 @error('no_hp') is-invalid @enderror" id="no_hp{{ $customer->id_customer }}" name="no_hp" value="{{ old('no_hp', $customer->no_hp) }}" required>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat{{ $customer->id_customer }}" class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control rounded-3 @error('alamat') is-invalid @enderror" id="alamat{{ $customer->id_customer }}" name="alamat" rows="3" required>{{ old('alamat', $customer->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="source{{ $customer->id_customer }}" class="form-label fw-semibold">Source <span class="text-danger">*</span></label>
                                <select class="form-select rounded-3 @error('source') is-invalid @enderror" id="source{{ $customer->id_customer }}" name="source" required>
                                    <option value="">-- Pilih Source --</option>
                                    <option value="online" {{ old('source', $customer->source) === 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ old('source', $customer->source) === 'offline' ? 'selected' : '' }}>Offline</option>
                                </select>
                                @error('source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="user_id{{ $customer->id_customer }}" class="form-label fw-semibold">User (Opsional)</label>
                                <select class="form-select rounded-3 @error('user_id') is-invalid @enderror" id="user_id{{ $customer->id_customer }}" name="user_id">
                                    <option value="">-- Pilih User --</option>
                                    @foreach($users ?? [] as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $customer->user_id) == $user->id ? 'selected' : '' }}>{{ $user->nama }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 rounded-bottom-4">
                        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
<div class="modal fade" id="modalTambahCustomer" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header bg-light border-0 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-user-plus text-success me-2"></i>Tambah Customer Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nama_baru" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3 @error('nama') is-invalid @enderror" id="nama_baru" name="nama" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email_baru" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email_baru" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="no_hp_baru" class="form-label fw-semibold">Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3 @error('no_hp') is-invalid @enderror" id="no_hp_baru" name="no_hp" value="{{ old('no_hp') }}" required>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat_baru" class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control rounded-3 @error('alamat') is-invalid @enderror" id="alamat_baru" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="source_baru" class="form-label fw-semibold">Source <span class="text-danger">*</span></label>
                            <select class="form-select rounded-3 @error('source') is-invalid @enderror" id="source_baru" name="source" required>
                                <option value="">-- Pilih Source --</option>
                                <option value="online" {{ old('source') === 'online' ? 'selected' : '' }}>Online</option>
                                <option value="offline" {{ old('source') === 'offline' ? 'selected' : '' }}>Offline</option>
                            </select>
                            @error('source')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="user_id_baru" class="form-label fw-semibold">User (Opsional)</label>
                            <select class="form-select rounded-3 @error('user_id') is-invalid @enderror" id="user_id_baru" name="user_id">
                                <option value="">-- Pilih User --</option>
                                @foreach($users ?? [] as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->nama }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-3">Tambah Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .modal {
        z-index: 9999 !important;
    }
    
    .modal-backdrop {
        z-index: 9998 !important;
    }
    
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }
    
    .btn-group-sm .btn {
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem;
    }
    
    .form-control, .form-select, .form-control:focus, .form-select:focus {
        border-color: #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
    }
</style>

<script>
function hapusCustomer(id) {
    Swal.fire({
        title: 'Hapus Customer?',
        text: 'Tindakan ini tidak dapat dibatalkan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/customers/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection