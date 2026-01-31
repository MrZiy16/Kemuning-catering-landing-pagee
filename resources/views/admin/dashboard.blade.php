@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4 py-3">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1">Dashboard</h2>
            <div class="text-muted">Ringkasan aktivitas dan performa operasional</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i>{{ now()->format('d M Y, H:i') }}</span>
            <button class="btn btn-sm btn-outline-primary" id="refresh-data"><i class="fas fa-rotate me-1"></i>Refresh</button>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- KPI Cards Row 1 --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted text-uppercase small">Pelanggan</div>
                        <div class="fw-bold fs-5">{{ number_format($totalPelanggan ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted text-uppercase small">Produk</div>
                        <div class="fw-bold fs-5">{{ number_format($totalProduk ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width:48px;height:48px">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted text-uppercase small">Menu</div>
                        <div class="fw-bold fs-5">{{ number_format($totalMenu ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width:48px;height:48px">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted text-uppercase small">Transaksi</div>
                        <div class="fw-bold fs-5">{{ number_format($totalTransaksi ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@if(auth()->user()->role === 'super_admin')
    {{-- KPI Cards Row 2 --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-muted text-uppercase small">Total Omzet</div>
                        <div class="fw-bold fs-5">Rp {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width:48px;height:48px">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted text-uppercase small">Omzet Bulan Ini</div>
                            <div class="fw-bold fs-5">Rp {{ number_format($omzetBulanIni ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="progress" style="height:8px">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $persentaseTarget ?? 0 }}%" aria-valuenow="{{ $persentaseTarget ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="text-muted small mt-1">Pencapaian: {{ number_format($persentaseTarget ?? 0, 1) }}%</div>
                </div>
            </div>
        </div>
    @endif
    </div>

    {{-- Charts --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Grafik Transaksi</h6>
                    <select class="form-select form-select-sm" id="chartPeriod" style="width:auto">
                        <option value="daily">30 Hari Terakhir</option>
                        <option value="monthly">12 Bulan Terakhir</option>
                    </select>
                </div>
                <div class="card-body">
                    <div style="height:320px"><canvas id="myAreaChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Distribusi Status Transaksi</h6>
                </div>
                <div class="card-body">
                    <div style="height:280px"><canvas id="myPieChart"></canvas></div>
                    <div class="mt-3 small">
                        @if(isset($statusData) && $statusData->count() > 0)
                            @foreach($statusData as $status)
                                <div class="d-inline-block me-2 mb-1">
                                    <span class="badge bg-light text-secondary border"><i class="fas fa-circle text-primary me-1"></i>{{ ucfirst($status->status) }} ({{ $status->total }})</span>
                                </div>
                            @endforeach
                        @else
                            <span class="text-muted">Tidak ada data status</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent & Top Performance --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Transaksi Terbaru</h6>
                    <a href="{{ route('admin.transaksi.index') }}" class="small">Lihat semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="recent-transactions">
                                @if($transaksiTerbaru && $transaksiTerbaru->count() > 0)
                                    @foreach($transaksiTerbaru as $transaksi)
                                        <tr>
                                            <td class="fw-semibold">{{ $transaksi->id_transaksi }}</td>
                                            <td>{{ $transaksi->customer_nama ?? 'Unknown' }}</td>
                                            <td>Rp {{ number_format($transaksi->total ?? 0, 0, ',', '.') }}</td>
                                            <td>
                                                @switch($transaksi->status ?? 'unknown')
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-success">Completed</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                        @break
                                                    @case('processing')
                                                        <span class="badge bg-info">Processing</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($transaksi->status ?? 'Unknown') }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Tidak ada data transaksi</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if(auth()->user()->role === 'super_admin')
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Top Performance</h6>
                </div>
                
                    <h6 class="text-info">Top Produk</h6>
                    <div id="top-produk" class="small">
                        <div class="text-muted">Memuat...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-semibold">Kelola Transaksi</div>
                        <div class="text-muted small">Pantau & proses pesanan</div>
                    </div>
                    <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-primary">Buka</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-semibold">Kelola Produk</div>
                        <div class="text-muted small">Atur katalog produk</div>
                    </div>
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-sm btn-primary">Buka</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="fw-semibold">Manajemen Pembayaran</div>
                        <div class="text-muted small">Konfirmasi & ringkas</div>
                    </div>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-primary">Buka</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        initializeCharts();
        loadTopPerformance();

        // Auto refresh every 30 seconds
        setInterval(updateDashboardData, 30000);
        
        // Manual refresh button
        $('#refresh-data').on('click', function() {
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');
            updateDashboardData();
            setTimeout(() => {
                $(this).prop('disabled', false).html('<i class="fas fa-rotate me-1"></i>Refresh');
            }, 2000);
        });
        
        // Chart period change
        $('#chartPeriod').on('change', function(){ 
            loadChartData($(this).val()); 
        });
    });

    let myAreaChart, myPieChart;

    function initializeCharts() {
        // Area Chart
        const ctx = document.getElementById('myAreaChart');
        if (ctx) {
            myAreaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($transaksiHarian && $transaksiHarian->count() > 0 ? $transaksiHarian->pluck('tanggal') : ['No Data']) !!},
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: {!! json_encode($transaksiHarian && $transaksiHarian->count() > 0 ? $transaksiHarian->pluck('total') : [0]) !!},
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(13,110,253,0.1)',
                        borderColor: 'rgba(13,110,253,1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: 'rgba(13,110,253,1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        x: { 
                            grid: { display: false },
                            ticks: { color: '#6c757d' }
                        },
                        y: { 
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { color: '#6c757d' },
                            beginAtZero: true
                        }
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(13,110,253,1)',
                            borderWidth: 1
                        }
                    }
                }
            });
        }

        // Pie Chart
        const ctx2 = document.getElementById('myPieChart');
        if (ctx2) {
            const statusLabels = {!! json_encode($statusData && $statusData->count() > 0 ? $statusData->pluck('status') : ['No Data']) !!};
            const statusValues = {!! json_encode($statusData && $statusData->count() > 0 ? $statusData->pluck('total') : [1]) !!};
            
            myPieChart = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusValues,
                        backgroundColor: [
                            '#0d6efd', // pending - blue
                            '#198754', // completed - green
                            '#dc3545', // cancelled - red
                            '#ffc107', // processing - yellow
                            '#6f42c1', // confirmed - purple
                            '#fd7e14', // preparing - orange
                            '#20c997'  // delivered - teal
                        ],
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverBorderWidth: 3
                    }]
                },
                options: { 
                    responsive: true,
                    maintainAspectRatio: false, 
                    cutout: '60%',
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    }
                }
            });
        }
    }

    function updateDashboardData() {
        $.get('{{ route("admin.dashboard.update") }}', function(response){
            // Pie chart
            myPieChart.data.labels = response.statusData.map(x => x.status);
            myPieChart.data.datasets[0].data = response.statusData.map(x => x.total);
            myPieChart.update();

            // Recent transactions
            updateRecentTransactions(response.transaksiTerbaru);
        });
    }

    function loadChartData(period) {
        $.get('{{ route("admin.dashboard.chart") }}', { period }, function(response){
            myAreaChart.data.labels = response.map(x => x.label);
            myAreaChart.data.datasets[0].data = response.map(x => x.transaksi);
            myAreaChart.update();
        });
    }

    function loadTopPerformance() {
        $.get('{{ route("admin.dashboard.top-performance") }}', function(response){
            // Top Menu
            let menuHtml = '';
            (response.topMenu || []).forEach(function(menu){
                menuHtml += `<div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>${menu.nama_menu}</strong>
                                    <div class="text-muted small">Qty: ${menu.total_qty}</div>
                                </div>
                                <span class="badge bg-success">Rp ${formatNumber(menu.total_omzet)}</span>
                             </div>`;
            });
            $('#top-menu').html(menuHtml || '<div class="text-muted">Tidak ada data</div>');

            // Top Produk
            let produkHtml = '';
            (response.topProduk || []).forEach(function(produk){
                produkHtml += `<div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>${produk.nama_produk}</strong>
                                    <div class="text-muted small">Qty: ${produk.total_qty}</div>
                                </div>
                                <span class="badge bg-info">Rp ${formatNumber(produk.total_omzet)}</span>
                             </div>`;
            });
            $('#top-produk').html(produkHtml || '<div class="text-muted">Tidak ada data</div>');
        });
    }

    function updateRecentTransactions(list) {
        let html = '';
        (list || []).forEach(function(t){
            html += `<tr>
                        <td class="fw-semibold">${t.id_transaksi}</td>
                        <td>${t.customer_nama ?? 'Unknown'}</td>
                        <td>Rp ${formatNumber(t.total)}</td>
                        <td>${statusBadge(t.status)}</td>
                        <td>${formatDate(t.tanggal_transaksi)}</td>
                     </tr>`;
        });
        $('#recent-transactions').html(html || `<tr><td colspan="5" class="text-center text-muted">Tidak ada data transaksi</td></tr>`);
    }

    function statusBadge(status){
        switch(status){
            case 'pending': return '<span class="badge bg-warning">Pending</span>';
            case 'completed': return '<span class="badge bg-success">Completed</span>';
            case 'cancelled': return '<span class="badge bg-danger">Cancelled</span>';
            case 'processing': return '<span class="badge bg-info">Processing</span>';
            default: return `<span class="badge bg-secondary">${(status||'Unknown').toString().charAt(0).toUpperCase() + (status||'Unknown').toString().slice(1)}</span>`;
        }
    }

    function formatNumber(x){
        if(x === null || x === undefined) return '0';
        x = parseFloat(x);
        return x.toLocaleString('id-ID');
    }

    function formatDate(dateStr){
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID');
    }
</script>
@endpush
