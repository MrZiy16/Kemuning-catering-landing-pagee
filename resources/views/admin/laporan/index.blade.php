@extends('layouts.app')

@section('title', 'Dashboard Laporan')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-chart-line text-white fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Dashboard Laporan</h2>
                        <p class="text-muted mb-0">Analisis dan laporan bisnis catering</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Filter Periode
                    </button>
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
                        <strong>Periode Laporan:</strong> 
                        {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        <span class="badge bg-primary ms-2">{{ ucfirst($periode) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-2">Total Pendapatan</h6>
                            <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($summaryData['total_revenue'], 0, ',', '.') }}</h3>
                            <small class="text-primary">{{ $summaryData['total_transactions'] }} transaksi</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-2">Transaksi Selesai</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $summaryData['completed_transactions'] }}</h3>
                            <small class="text-primary">dari {{ $summaryData['total_transactions'] }} total</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-2">Total Customer</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $summaryData['total_customers'] }}</h3>
                            <small class="text-primary">customer aktif</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x text-primary"></i>
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
                        <i class="fas fa-chart-area text-primary me-2"></i>Tren Penjualan
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie text-success me-2"></i>Produk Terlaris
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="topProductsChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Reports --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-tachometer-alt text-info me-2"></i>Laporan Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.laporan.penjualan') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <strong>Laporan Penjualan</strong>
                                <small class="text-muted">Analisis revenue & transaksi</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.laporan.produk') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="fas fa-box fa-2x mb-2"></i>
                                <strong>Laporan Produk</strong>
                                <small class="text-muted">Performa produk & menu</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.laporan.customer') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <strong>Laporan Customer</strong>
                                <small class="text-muted">Analisis pelanggan</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.laporan.pembayaran') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="fas fa-credit-card fa-2x mb-2"></i>
                                <strong>Laporan Pembayaran</strong>
                                <small class="text-muted">Status & metode bayar</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history text-secondary me-2"></i>Transaksi Terbaru
                        </h5>
                        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td class="fw-bold text-primary">#{{ $transaction->id_transaksi }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $transaction->customer->nama ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $transaction->customer->no_hp ?? '' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $transaction->tanggal_transaksi->format('d/m/Y') }}</td>
                                    <td class="fw-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge 
                                            @switch($transaction->status)
                                                @case('pending') bg-warning text-dark @break
                                                @case('confirmed') bg-info @break
                                                @case('preparing') bg-primary @break
                                                @case('completed') bg-success @break
                                                @case('cancelled') bg-danger @break
                                                @default bg-secondary @break
                                            @endswitch
                                        ">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Tidak ada transaksi terbaru
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
                    <i class="fas fa-filter me-2"></i>Filter Periode Laporan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.laporan.index') }}">
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
                        <label for="periode" class="form-label">Periode Grouping</label>
                        <select class="form-select" id="periode" name="periode">
                            <option value="daily" {{ $periode == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="weekly" {{ $periode == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ $periode == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="yearly" {{ $periode == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label">Quick Select:</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-secondary quick-filter" data-days="7">7 Hari</button>
                                <button type="button" class="btn btn-outline-secondary quick-filter" data-days="30">30 Hari</button>
                                <button type="button" class="btn btn-outline-secondary quick-filter" data-days="90">90 Hari</button>
                                <button type="button" class="btn btn-outline-secondary quick-filter" data-days="365">1 Tahun</button>
                            </div>
                        </div>
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
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const chartData = @json($chartData);
    
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: chartData.map(item => item.period),
            datasets: [{
                label: 'Revenue',
                data: chartData.map(item => item.revenue),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Transactions',
                data: chartData.map(item => item.transactions),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.4,
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
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Top Products Chart
    const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
    const topProducts = @json($topProducts);
    
    new Chart(topProductsCtx, {
        type: 'doughnut',
        data: {
            labels: topProducts.map(item => item.nama_produk || item.nama_menu),
            datasets: [{
                data: topProducts.map(item => item.total_revenue),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Quick filter buttons
    document.querySelectorAll('.quick-filter').forEach(button => {
        button.addEventListener('click', function() {
            const days = parseInt(this.dataset.days);
            const endDate = new Date();
            const startDate = new Date();
            startDate.setDate(endDate.getDate() - days);
            
            document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
            document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
        });
    });
});
</script>
@endpush