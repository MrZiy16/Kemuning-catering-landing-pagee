@extends('layouts.app')

@section('title', 'Laporan Produk')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-box text-white fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Laporan Produk</h2>
                        <p class="text-muted mb-0">Analisis performa produk dan menu</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.laporan.produk', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Info --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-alt me-3 fs-5"></i>
                    <div>
                        <strong>Periode:</strong> 
                        {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        @if($kategori !== 'all')
                            <span class="badge bg-primary ms-2">{{ ucfirst($kategori) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        @php
            $totalProducts = $productPerformance->count();
            $totalRevenue = $productPerformance->sum('total_revenue');
            $totalQty = $productPerformance->sum('total_qty');
            $bestProduct = $productPerformance->first();
        @endphp
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-2">Total Produk Terjual</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $totalProducts }}</h3>
                            <small class="text-primary">jenis produk</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-box fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-2">Total Revenue</h6>
                            <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            <small class="text-primary">dari produk</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-2">Total Quantity</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalQty) }}</h3>
                            <small class="text-primary">unit terjual</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-2">Produk Terlaris</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $bestProduct->nama_produk ?? 'N/A' }}</h3>
                            <small class="text-primary">Rp {{ number_format($bestProduct->total_revenue ?? 0, 0, ',', '.') }}</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-trophy fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar text-success me-2"></i>Performa Produk
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="productPerformanceChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie text-primary me-2"></i>Kategori Produk
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Tables Row --}}
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-table text-info me-2"></i>Top Produk
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Qty</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productPerformance->take(10) as $product)
                                <tr>
                                    <td class="fw-bold">{{ $product->nama_produk }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($product->kategori_produk) }}</span>
                                    </td>
                                    <td>{{ number_format($product->total_qty) }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Tidak ada data produk
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-utensils text-warning me-2"></i>Top Menu
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Menu</th>
                                    <th>Kategori</th>
                                    <th>Qty</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menuPerformance->take(10) as $menu)
                                <tr>
                                    <td class="fw-bold">{{ $menu->nama_menu }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($menu->kategori_menu) }}</span>
                                    </td>
                                    <td>{{ number_format($menu->total_qty) }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Tidak ada data menu
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Modal --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">
                    <i class="fas fa-filter me-2"></i>Filter Laporan Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.laporan.produk') }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori Produk</label>
                        <select class="form-select" id="kategori" name="kategori">
                            <option value="all" {{ $kategori == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                            <option value="paket_box" {{ $kategori == 'paket_box' ? 'selected' : '' }}>Paket Box</option>
                            <option value="prasmanan" {{ $kategori == 'prasmanan' ? 'selected' : '' }}>Prasmanan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.bg-gradient-info { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.bg-gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.text-white-50 { color: rgba(255,255,255,0.5) !important; }
.text-white-75 { color: rgba(255,255,255,0.75) !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Product Performance Chart
    const productCtx = document.getElementById('productPerformanceChart').getContext('2d');
    const productData = @json($productPerformance->take(10));
    
    new Chart(productCtx, {
        type: 'bar',
        data: {
            labels: productData.map(item => item.nama_produk),
            datasets: [{
                label: 'Revenue (Rp)',
                data: productData.map(item => item.total_revenue),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }, {
                label: 'Quantity',
                data: productData.map(item => item.total_qty),
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue (Rp)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Quantity'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = @json($categoryAnalysis);
    
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => item.kategori_produk),
            datasets: [{
                data: categoryData.map(item => item.total_revenue),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush