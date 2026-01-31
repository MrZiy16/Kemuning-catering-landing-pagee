@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-chart-bar text-white fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Laporan Penjualan</h2>
                        <p class="text-muted mb-0">Analisis revenue dan performa penjualan</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.laporan.penjualan', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="btn btn-danger">
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
                        <span class="badge bg-primary ms-2">{{ ucfirst($groupBy) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        @php
            $totalRevenue = $salesData->sum('revenue');
            $totalTransactions = $salesData->sum('transactions');
            $avgTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
            $bestPeriod = $salesData->sortByDesc('revenue')->first();
        @endphp
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-primary">
                <div class="card-body">
                    <div class="text-primary d-flex align-items-center">
                        <div class="text-primary flex-grow-1">
                            <h6 class="text-50 mb-2">Total Revenue</h6>
                            <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            <small class="text-primary">{{ $totalTransactions }} transaksi</small>
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
                            <h6 class="text-primary mb-2">Periode Terbaik</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $bestPeriod->period ?? 'N/A' }}</h3>
                            <small class="text-primary">Rp {{ number_format($bestPeriod->revenue ?? 0, 0, ',', '.') }}</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-trophy fa-2x text-primary"></i>
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
                            <h6 class="text-primary mb-2">Growth Rate</h6>
                            @php
                                $firstPeriod = $salesData->first();
                                $lastPeriod = $salesData->last();
                                $growth = 0;
                                if ($firstPeriod && $lastPeriod && $firstPeriod->revenue > 0) {
                                    $growth = (($lastPeriod->revenue - $firstPeriod->revenue) / $firstPeriod->revenue) * 100;
                                }
                            @endphp
                            <h3 class="mb-0 fw-bold text-primary">{{ number_format($growth, 1) }}%</h3>
                            <small class="text-primary">vs periode awal</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line fa-2x text-primary"></i>
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
                    <canvas id="salesTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie text-success me-2"></i>Metode Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Tables Row --}}
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-table text-info me-2"></i>Detail Penjualan per Periode
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Periode</th>
                                    <th>Revenue</th>
                                    <th>Transaksi</th>
                                    <th>Rata-rata</th>
                                    <th>Growth</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesData as $index => $data)
                                @php
                                    $prevData = $index > 0 ? $salesData[$index - 1] : null;
                                    $growth = 0;
                                    if ($prevData && $prevData->revenue > 0) {
                                        $growth = (($data->revenue - $prevData->revenue) / $prevData->revenue) * 100;
                                    }
                                @endphp
                                <tr>
                                    <td class="fw-bold">{{ $data->period }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($data->revenue, 0, ',', '.') }}</td>
                                    <td>{{ $data->transactions }}</td>
                                    <td>Rp {{ number_format($data->avg_transaction, 0, ',', '.') }}</td>
                                    <td>
                                        @if($growth > 0)
                                            <span class="badge bg-success">+{{ number_format($growth, 1) }}%</span>
                                        @elseif($growth < 0)
                                            <span class="badge bg-danger">{{ number_format($growth, 1) }}%</span>
                                        @else
                                            <span class="badge bg-secondary">0%</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-tasks text-warning me-2"></i>Status Transaksi
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($statusAnalysis as $status)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="badge 
                                @switch($status->status)
                                    @case('pending') bg-warning text-dark @break
                                    @case('confirmed') bg-info @break
                                    @case('preparing') bg-primary @break
                                    @case('completed') bg-success @break
                                    @case('cancelled') bg-danger @break
                                    @default bg-secondary @break
                                @endswitch
                            ">
                                {{ ucfirst($status->status) }}
                            </span>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">{{ $status->count }} transaksi</div>
                            <small class="text-muted">Rp {{ number_format($status->total_amount, 0, ',', '.') }}</small>
                        </div>
                    </div>
                    @endforeach
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
                    <i class="fas fa-filter me-2"></i>Filter Laporan Penjualan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.laporan.penjualan') }}">
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
                        <label for="group_by" class="form-label">Group By</label>
                        <select class="form-select" id="group_by" name="group_by">
                            <option value="daily" {{ $groupBy == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="weekly" {{ $groupBy == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ $groupBy == 'monthly' ? 'selected' : '' }}>Bulanan</option>
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
    // Sales Trend Chart
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    const salesData = @json($salesData);
    
    new Chart(salesTrendCtx, {
        type: 'line',
        data: {
            labels: salesData.map(item => item.period),
            datasets: [{
                label: 'Revenue (Rp)',
                data: salesData.map(item => item.revenue),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Transactions',
                data: salesData.map(item => item.transactions),
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
                        text: 'Transactions'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Payment Method Chart
    const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    const paymentMethods = @json($paymentMethods);
    
    new Chart(paymentMethodCtx, {
        type: 'doughnut',
        data: {
            labels: paymentMethods.map(item => item.method === 'online' ? 'Online' : 'Manual Transfer'),
            datasets: [{
                data: paymentMethods.map(item => item.total_amount),
                backgroundColor: [
                    '#36A2EB',
                    '#FF6384',
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