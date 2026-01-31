@extends('layouts.app')

@section('title', 'Laporan Operasional')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-cogs text-white fs-4"></i>
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark">Laporan Operasional</h2>
                        <p class="text-muted mb-0">Analisis operasional dan jadwal acara</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
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
        @php
            $totalEvents = $operationalSummary->sum('count');
            $confirmedEvents = $operationalSummary->where('status', 'confirmed')->first()->count ?? 0;
            $preparingEvents = $operationalSummary->where('status', 'preparing')->first()->count ?? 0;
            $readyEvents = $operationalSummary->where('status', 'ready')->first()->count ?? 0;
        @endphp
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-50 mb-2">Total Acara</h6>
                            <h3 class="mb-0 fw-bold">{{ $totalEvents }}</h3>
                            <small class="text-white-75">periode ini</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-50 mb-2">Dikonfirmasi</h6>
                            <h3 class="mb-0 fw-bold">{{ $confirmedEvents }}</h3>
                            <small class="text-white-75">acara confirmed</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-50 mb-2">Dalam Persiapan</h6>
                            <h3 class="mb-0 fw-bold">{{ $preparingEvents }}</h3>
                            <small class="text-white-75">sedang dipersiapkan</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-utensils fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-white-50 mb-2">Siap Kirim</h6>
                            <h3 class="mb-0 fw-bold">{{ $readyEvents }}</h3>
                            <small class="text-white-75">ready to deliver</small>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-truck fa-2x text-white-50"></i>
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
                        <i class="fas fa-chart-bar text-secondary me-2"></i>Status Operasional
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="operationalChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>Area Terpopuler
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="areaChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Event Schedule --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>Jadwal Acara Mendatang
                        </h5>
                        <span class="badge bg-primary">{{ $eventSchedule->count() }} acara</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Customer</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Alamat</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($eventSchedule as $event)
                                <tr>
                                    <td class="fw-bold text-primary">#{{ $event->id_transaksi }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $event->customer->nama ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $event->customer->no_hp ?? '' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $event->tanggal_acara->format('d/m/Y') }}</strong><br>
                                            <small class="text-muted">{{ $event->waktu_acara ?? 'Belum ditentukan' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($event->alamat_pengiriman, 50) }}</small>
                                    </td>
                                    <td class="fw-bold">Rp {{ number_format($event->total, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge 
                                            @switch($event->status)
                                                @case('confirmed') bg-info @break
                                                @case('preparing') bg-warning text-dark @break
                                                @case('ready') bg-success @break
                                                @default bg-secondary @break
                                            @endswitch
                                        ">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.transaksi.show', $event->id_transaksi) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($event->status === 'confirmed')
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="updateStatus('{{ $event->id_transaksi }}', 'preparing')">
                                                <i class="fas fa-utensils"></i>
                                            </button>
                                            @elseif($event->status === 'preparing')
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="updateStatus('{{ $event->id_transaksi }}', 'ready')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                                        Tidak ada acara yang dijadwalkan
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

    {{-- Area Analysis --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-map text-success me-2"></i>Analisis Area Pengiriman
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Area</th>
                                    <th>Total Orders</th>
                                    <th>Total Revenue</th>
                                    <th>Avg per Order</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalOrders = $areaAnalysis->sum('total_orders');
                                    $totalRevenue = $areaAnalysis->sum('total_revenue');
                                @endphp
                                @forelse($areaAnalysis as $area)
                                <tr>
                                    <td class="fw-bold">{{ $area->alamat_pengiriman }}</td>
                                    <td>{{ $area->total_orders }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($area->total_revenue, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($area->total_orders > 0 ? $area->total_revenue / $area->total_orders : 0, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $percentage = $totalOrders > 0 ? ($area->total_orders / $totalOrders) * 100 : 0;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <small class="fw-bold">{{ number_format($percentage, 1) }}%</small>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-map-marked-alt fa-2x mb-2 d-block"></i>
                                        Tidak ada data area pengiriman
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
                    <i class="fas fa-filter me-2"></i>Filter Laporan Operasional
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('admin.laporan.operasional') }}">
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
    // Operational Status Chart
    const operationalCtx = document.getElementById('operationalChart').getContext('2d');
    const operationalData = @json($operationalSummary);
    
    new Chart(operationalCtx, {
        type: 'bar',
        data: {
            labels: operationalData.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
            datasets: [{
                label: 'Jumlah Acara',
                data: operationalData.map(item => item.count),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
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
                        text: 'Jumlah Acara'
                    }
                }
            }
        }
    });

    // Area Chart
    const areaCtx = document.getElementById('areaChart').getContext('2d');
    const areaData = @json($areaAnalysis->take(5));
    
    new Chart(areaCtx, {
        type: 'doughnut',
        data: {
            labels: areaData.map(item => item.alamat_pengiriman.length > 20 ? 
                item.alamat_pengiriman.substring(0, 20) + '...' : 
                item.alamat_pengiriman),
            datasets: [{
                data: areaData.map(item => item.total_orders),
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const area = areaData[context.dataIndex];
                            return context.label + ': ' + context.parsed + ' orders (Rp ' + 
                                area.total_revenue.toLocaleString('id-ID') + ')';
                        }
                    }
                }
            }
        }
    });
});

function updateStatus(transactionId, newStatus) {
    const statusText = {
        'preparing': 'Sedang Dipersiapkan',
        'ready': 'Siap Kirim'
    };
    
    if (confirm(`Ubah status transaksi ${transactionId} menjadi "${statusText[newStatus]}"?`)) {
        // Implement status update functionality
        alert('Status akan diupdate untuk transaksi ' + transactionId);
        // You can add AJAX call here to update the status
    }
}
</script>
@endpush