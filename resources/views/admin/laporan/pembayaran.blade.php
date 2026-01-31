@extends('layouts.app')

@section('title', 'Laporan Pembayaran')

@section('content')
<div class="container-fluid">
    @php
        $successRate = $paymentSummary['total_payments'] > 0
            ? ($paymentSummary['paid_payments'] / $paymentSummary['total_payments']) * 100
            : 0;
        $pendingRate = $paymentSummary['total_payments'] > 0
            ? ($paymentSummary['pending_payments'] / $paymentSummary['total_payments']) * 100
            : 0;
        $dpRate = $paymentSummary['paid_payments'] > 0
            ? ($paymentSummary['dp_payments'] / $paymentSummary['paid_payments']) * 100
            : 0;
    @endphp
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-credit-card text-white fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Laporan Pembayaran</h2>
                        <p class="text-muted mb-0">Analisis status dan metode pembayaran</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.laporan.pembayaran', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="btn btn-danger">
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
            <div class="card border-0 shadow-sm h-100 bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-primary mb-2">Total Pembayaran</h6>
                            <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($paymentSummary['total_payments'], 0, ',', '.') }}</h3>
                            <small class="text-primary">semua status</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
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
                            <h6 class="text-primary mb-2">Pembayaran Berhasil</h6>
                            <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($paymentSummary['paid_payments'], 0, ',', '.') }}</h3>
                            <small class="text-primary">{{ number_format($successRate, 1) }}% success rate</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x text-primary"></i>
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
                            <h6 class="text-primary mb-2">Pembayaran Pending</h6>
                            <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($paymentSummary['pending_payments'], 0, ',', '.') }}</h3>
                            <small class="text-primary">perlu tindak lanjut</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x text-primary"></i>
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
                            <h6 class="text-primary mb-2">Down Payment</h6>
                            <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($paymentSummary['dp_payments'], 0, ',', '.') }}</h3>
                            <small class="text-primary">dari DP</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-hand-holding-usd fa-2x text-primary"></i>
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
                        <i class="fas fa-chart-line text-warning me-2"></i>Tren Pembayaran Harian
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentTrendsChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie text-primary me-2"></i>Tipe Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentTypeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Outstanding Payments --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>Pembayaran Tertunggak
                        </h5>
                        <span class="badge bg-danger">{{ $outstandingPayments->count() }} transaksi</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Customer</th>
                                    <th>Tanggal Acara</th>
                                    <th>Total</th>
                                    <th>Dibayar</th>
                                    <th>Sisa</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outstandingPayments as $transaction)
                                <tr>
                                    <td class="fw-bold text-primary">#{{ $transaction->id_transaksi }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $transaction->customer->nama ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $transaction->customer->no_hp ?? '' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $transaction->tanggal_acara?->format('d/m/Y') }}</td>
                                    <td class="fw-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                    <td class="text-success">Rp {{ number_format($transaction->total_paid, 0, ',', '.') }}</td>
                                    <td class="text-danger fw-bold">Rp {{ number_format($transaction->remaining_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge 
                                            @switch($transaction->status)
                                                @case('pending') bg-warning text-dark @break
                                                @case('confirmed') bg-info @break
                                                @case('preparing') bg-primary @break
                                                @default bg-secondary @break
                                            @endswitch
                                        ">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.transaksi.show', $transaction->id_transaksi) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="sendReminder('{{ $transaction->id_transaksi }}')">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-check-circle fa-2x mb-2 d-block text-success"></i>
                                        Tidak ada pembayaran tertunggak
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

    {{-- Payment Summary by Type --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-bar text-success me-2"></i>Ringkasan Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary fw-bold">Rp {{ number_format($paymentSummary['dp_payments'], 0, ',', '.') }}</h4>
                                <p class="text-muted mb-0">Down Payment</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success fw-bold">Rp {{ number_format($paymentSummary['full_payments'], 0, ',', '.') }}</h4>
                            <p class="text-muted mb-0">Full Payment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-percentage text-info me-2"></i>Statistik Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Success Rate</span>
                            <span class="fw-bold">{{ number_format($successRate, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $successRate }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Pending Rate</span>
                            <span class="fw-bold">{{ number_format($pendingRate, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: {{ $pendingRate }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between">
                            <span>DP vs Full Payment</span>
                            <span class="fw-bold">{{ number_format($dpRate, 1) }}% DP</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ $dpRate }}%"></div>
                        </div>
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
                    <i class="fas fa-filter me-2"></i>Filter Laporan Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.laporan.pembayaran') }}">
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
    // Payment Trends Chart
    const trendsCtx = document.getElementById('paymentTrendsChart').getContext('2d');
    const trendsData = @json($paymentTrends);
    
    // Group data by date
    const groupedData = {};
    trendsData.forEach(item => {
        if (!groupedData[item.date]) {
            groupedData[item.date] = { total: 0, count: 0 };
        }
        groupedData[item.date].total += parseFloat(item.total_amount);
        groupedData[item.date].count += parseInt(item.count);
    });
    
    const dates = Object.keys(groupedData).sort();
    const amounts = dates.map(date => groupedData[date].total);
    const counts = dates.map(date => groupedData[date].count);
    
    new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: dates.map(date => new Date(date).toLocaleDateString('id-ID')),
            datasets: [{
                label: 'Amount (Rp)',
                data: amounts,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Count',
                data: counts,
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
                        text: 'Amount (Rp)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Count'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Payment Type Chart
    const typeCtx = document.getElementById('paymentTypeChart').getContext('2d');
    const dpAmount = {{ $paymentSummary['dp_payments'] }};
    const fullAmount = {{ $paymentSummary['full_payments'] }};
    
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Down Payment', 'Full Payment'],
            datasets: [{
                data: [dpAmount, fullAmount],
                backgroundColor: [
                    '#36A2EB',
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

function sendReminder(transactionId) {
    if (confirm('Kirim reminder pembayaran ke customer?')) {
        // Implement reminder functionality
        alert('Reminder akan dikirim ke customer untuk transaksi ' + transactionId);
    }
}
</script>
@endpush