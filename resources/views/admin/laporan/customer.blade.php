@extends('layouts.app')

@section('title', 'Laporan Customer')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-users text-white fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Laporan Customer</h2>
                        <p class="text-muted mb-0">Analisis perilaku dan segmentasi pelanggan</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.laporan.customer', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="btn btn-danger">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-2">Total Customer</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $customerAnalysis['total_customers'] }}</h3>
                            <small class="text-primary">customer aktif</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-2">Customer Baru</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $customerAnalysis['new_customers'] }}</h3>
                            <small class="text-primary">periode ini</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-plus fa-2x text-primary"></i>
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
                            <h6 class="text-primary mb-2">Repeat Customer</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $customerAnalysis['repeat_customers'] }}</h3>
                            <small class="text-primary">customer loyal</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-heart fa-2x text-primary"></i>
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
                            <h6 class="text-primary mb-2">Avg. Order Value</h6>
                            @php
                                $avgOrderValue = $topCustomers->avg('avg_order_value') ?? 0;
                            @endphp
                            <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</h3>
                            <small class="text-primary">per transaksi</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-calculator fa-2x text-primary"></i>
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
                        <i class="fas fa-chart-bar text-info me-2"></i>Top 10 Customer
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="topCustomersChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie text-success me-2"></i>Segmentasi Customer
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="segmentationChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Customer Tables --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-table text-primary me-2"></i>Detail Customer
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Customer</th>
                                    <th>Kontak</th>
                                    <th>Total Orders</th>
                                    <th>Total Spent</th>
                                    <th>Avg Order Value</th>
                                    <th>Last Order</th>
                                    <th>Segment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers as $customer)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $customer->nama }}</strong><br>
                                            <small class="text-muted">ID: {{ $customer->id }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <small class="d-block">{{ $customer->email }}</small>
                                            <small class="text-muted">{{ $customer->no_hp }}</small>
                                        </div>
                                    </td>
                                    <td class="fw-bold">{{ $customer->total_orders }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($customer->avg_order_value, 0, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($customer->last_order_date)->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                            $segment = 'New Customer';
                                            $badgeClass = 'bg-info';
                                            if ($customer->total_orders > 5) {
                                                $segment = 'VIP Customer';
                                                $badgeClass = 'bg-warning';
                                            } elseif ($customer->total_orders > 1) {
                                                $segment = 'Regular Customer';
                                                $badgeClass = 'bg-success';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $segment }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Tidak ada data customer
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

    {{-- Segmentation Details --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-users-cog text-warning me-2"></i>Detail Segmentasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($customerSegmentation as $segment => $data)
                        <div class="col-md-4 mb-3">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        @if($segment === 'New Customer')
                                            <i class="fas fa-user-plus fa-3x text-info"></i>
                                        @elseif($segment === 'Regular Customer')
                                            <i class="fas fa-user-check fa-3x text-success"></i>
                                        @else
                                            <i class="fas fa-crown fa-3x text-warning"></i>
                                        @endif
                                    </div>
                                    <h5 class="fw-bold">{{ $segment }}</h5>
                                    <p class="mb-2">
                                        <strong>{{ $data['customer_count'] }}</strong> customers<br>
                                        <span class="text-success fw-bold">Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</span>
                                    </p>
                                    <small class="text-muted">
                                        Avg: Rp {{ number_format($data['customer_count'] > 0 ? $data['total_revenue'] / $data['customer_count'] : 0, 0, ',', '.') }} per customer
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
                    <i class="fas fa-filter me-2"></i>Filter Laporan Customer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.laporan.customer') }}">
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
    // Top Customers Chart
    const topCustomersCtx = document.getElementById('topCustomersChart').getContext('2d');
    const topCustomersData = @json($topCustomers->take(10));
    
    new Chart(topCustomersCtx, {
        type: 'bar',
        data: {
            labels: topCustomersData.map(item => item.nama),
            datasets: [{
                label: 'Total Spent (Rp)',
                data: topCustomersData.map(item => item.total_spent),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Spent (Rp)'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Total Spent: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Customer Segmentation Chart
    const segmentationCtx = document.getElementById('segmentationChart').getContext('2d');
    const segmentationData = @json($customerSegmentation);
    
    const segmentLabels = Object.keys(segmentationData);
    const segmentCounts = segmentLabels.map(key => segmentationData[key].customer_count);
    
    new Chart(segmentationCtx, {
        type: 'doughnut',
        data: {
            labels: segmentLabels,
            datasets: [{
                data: segmentCounts,
                backgroundColor: [
                    '#36A2EB',
                    '#4BC0C0',
                    '#FFCE56'
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
                            const segment = context.label;
                            const count = context.parsed;
                            const revenue = segmentationData[segment].total_revenue;
                            return segment + ': ' + count + ' customers (Rp ' + revenue.toLocaleString('id-ID') + ')';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush