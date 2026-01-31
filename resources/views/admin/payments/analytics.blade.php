@extends('layouts.app')

@section('title', 'Analytics Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Analytics Pembayaran</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Pendapatan Hari Ini</span>
                            <h4 class="mb-3">
                                <span class="counter-value">Rp {{ number_format($analytics['daily']['today_revenue'], 0, ',', '.') }}</span>
                            </h4>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-soft-info text-info me-2">{{ $analytics['daily']['today_count'] }} transaksi</span>
                                <span class="badge bg-soft-{{ $growth >= 0 ? 'success' : 'danger' }} text-{{ $growth >= 0 ? 'success' : 'danger' }}">
                                    {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Pendapatan Bulan Ini</span>
                            <h4 class="mb-3">
                                <span class="counter-value">Rp {{ number_format($analytics['monthly']['this_month_revenue'], 0, ',', '.') }}</span>
                            </h4>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-soft-info text-info me-2">{{ $analytics['monthly']['this_month_count'] }} transaksi</span>
                                <span class="badge bg-soft-{{ $monthly_growth >= 0 ? 'success' : 'danger' }} text-{{ $monthly_growth >= 0 ? 'success' : 'danger' }}">
                                    {{ $monthly_growth >= 0 ? '+' : '' }}{{ number_format($monthly_growth, 1) }}%
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Tingkat Keberhasilan</span>
                            <h4 class="mb-3">
                                <span class="counter-value">{{ number_format($success_rate, 1) }}%</span>
                            </h4>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-soft-success text-success me-2">{{ $analytics['status_distribution']['paid'] }} berhasil</span>
                                <span class="badge bg-soft-danger text-danger">{{ $analytics['status_distribution']['failed'] }} gagal</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-info">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Rata-rata Transaksi</span>
                            <h4 class="mb-3">
                                <span class="counter-value">Rp {{ number_format($avg_transaction, 0, ',', '.') }}</span>
                            </h4>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-soft-primary text-primary">Per transaksi</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                <i class="fas fa-calculator text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Weekly Revenue Chart -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pendapatan 7 Hari Terakhir</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="weeklyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Distribusi Status</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 250px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-circle text-success"></i> Berhasil</span>
                            <strong>{{ $analytics['status_distribution']['paid'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-circle text-warning"></i> Pending</span>
                            <strong>{{ $analytics['status_distribution']['pending'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-circle text-danger"></i> Gagal</span>
                            <strong>{{ $analytics['status_distribution']['failed'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Methods -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3 bg-soft-primary rounded">
                                <h4 class="text-primary mb-2">{{ $analytics['method_distribution']['online'] }}</h4>
                                <p class="text-muted mb-1">Online Payment</p>
                                <span class="badge bg-primary">{{ number_format($online_percentage, 1) }}%</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-soft-secondary rounded">
                                <h4 class="text-secondary mb-2">{{ $analytics['method_distribution']['offline'] }}</h4>
                                <p class="text-muted mb-1">Transfer Manual</p>
                                <span class="badge bg-secondary">{{ number_format($offline_percentage, 1) }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar bg-primary" style="width: {{ $online_percentage }}%"></div>
                        <div class="progress-bar bg-secondary" style="width: {{ $offline_percentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Types -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tipe Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3 bg-soft-success rounded">
                                <h4 class="text-success mb-2">{{ $analytics['type_distribution']['full'] }}</h4>
                                <p class="text-muted mb-1">Bayar Penuh</p>
                                <span class="badge bg-success">{{ number_format($full_percentage, 1) }}%</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-soft-warning rounded">
                                <h4 class="text-warning mb-2">{{ $analytics['type_distribution']['dp'] }}</h4>
                                <p class="text-muted mb-1">Down Payment</p>
                                <span class="badge bg-warning">{{ number_format($dp_percentage, 1) }}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: {{ $full_percentage }}%"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $dp_percentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Insight Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Performa Hari Ini</h6>
                                @if($analytics['daily']['today_revenue'] > $analytics['daily']['yesterday_revenue'])
                                    <p class="mb-0">Pendapatan hari ini <strong>meningkat {{ number_format($growth, 1) }}%</strong> dibanding kemarin.</p>
                                @elseif($analytics['daily']['today_revenue'] < $analytics['daily']['yesterday_revenue'])
                                    <p class="mb-0">Pendapatan hari ini <strong>menurun {{ number_format(abs($growth), 1) }}%</strong> dibanding kemarin.</p>
                                @else
                                    <p class="mb-0">Pendapatan hari ini <strong>sama</strong> dengan kemarin.</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-{{ $success_rate >= 80 ? 'success' : ($success_rate >= 60 ? 'warning' : 'danger') }}">
                                <h6 class="alert-heading"><i class="fas fa-chart-bar"></i> Tingkat Keberhasilan</h6>
                                <p class="mb-0">
                                    @if($success_rate >= 80)
                                        Tingkat keberhasilan pembayaran <strong>sangat baik ({{ number_format($success_rate, 1) }}%)</strong>
                                    @elseif($success_rate >= 60)
                                        Tingkat keberhasilan pembayaran <strong>cukup baik ({{ number_format($success_rate, 1) }}%)</strong>
                                    @else
                                        Tingkat keberhasilan pembayaran <strong>perlu ditingkatkan ({{ number_format($success_rate, 1) }}%)</strong>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-secondary">
                                <h6 class="alert-heading"><i class="fas fa-credit-card"></i> Preferensi Pembayaran</h6>
                                <p class="mb-0">
                                    @if($analytics['method_distribution']['online'] > $analytics['method_distribution']['offline'])
                                        Customer lebih suka <strong>pembayaran online</strong> ({{ number_format($online_percentage, 1) }}%)
                                    @elseif($analytics['method_distribution']['offline'] > $analytics['method_distribution']['online'])
                                        Customer lebih suka <strong>transfer manual</strong> ({{ number_format($offline_percentage, 1) }}%)
                                    @else
                                        Customer menggunakan kedua metode pembayaran <strong>secara seimbang</strong>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-primary">
                                <h6 class="alert-heading"><i class="fas fa-coins"></i> Pola Pembayaran</h6>
                                <p class="mb-0">
                                    @if($analytics['type_distribution']['dp'] > $analytics['type_distribution']['full'])
                                        Mayoritas customer menggunakan <strong>sistem DP</strong> ({{ number_format($dp_percentage, 1) }}%)
                                    @elseif($analytics['type_distribution']['full'] > $analytics['type_distribution']['dp'])
                                        Mayoritas customer <strong>bayar langsung penuh</strong> ({{ number_format($full_percentage, 1) }}%)
                                    @else
                                        Penggunaan DP dan bayar penuh <strong>seimbang</strong>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Weekly Revenue Chart
const weeklyCtx = document.getElementById('weeklyRevenueChart').getContext('2d');
const weeklyData = @json($analytics['weekly_revenue']);

new Chart(weeklyCtx, {
    type: 'line',
    data: {
        labels: weeklyData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' });
        }),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: weeklyData.map(item => item.revenue),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        },
        elements: {
            point: {
                radius: 4,
                hoverRadius: 6
            }
        }
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusData = @json($analytics['status_distribution']);

new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Berhasil', 'Pending', 'Gagal'],
        datasets: [{
            data: [statusData.paid, statusData.pending, statusData.failed],
            backgroundColor: [
                '#28a745',
                '#ffc107',
                '#dc3545'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});
</script>
@endpush