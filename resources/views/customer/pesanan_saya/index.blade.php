<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            padding: 72px 20px 20px;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: #2d6a4f;
            margin-bottom: 20px;
            font-size: clamp(1.5rem, 4vw, 2rem);
        }

        /* Desktop Table View */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .orders-table th,
        .orders-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            text-align: left;
        }

        .orders-table th {
            background: #2d6a4f;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .orders-table tr:hover {
            background: #f1f8f5;
        }

        /* Mobile Card View */
        .orders-cards {
            display: none;
        }

        .order-card {
            background: white;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e9ecef;
        }

        .order-card-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 8px 0;
            border-bottom: 1px solid #f1f3f5;
        }

        .order-card-row:last-child {
            border-bottom: none;
        }

        .order-card-label {
            font-weight: bold;
            color: #6c757d;
            font-size: 0.85rem;
            flex-shrink: 0;
            margin-right: 12px;
        }

        .order-card-value {
            text-align: right;
            flex-grow: 1;
        }

        .order-number {
            font-weight: bold;
            color: #40916c;
            font-size: 1.1rem;
        }

        .order-link {
            text-decoration: none;
            font-size: 1.3rem;
            margin-right: 8px;
            transition: transform 0.2s;
            display: inline-block;
        }

        .order-link:hover {
            transform: scale(1.2);
        }

        .amount {
            font-weight: bold;
            color: #2d6a4f;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-paid { background: #d1e7dd; color: #0f5132; }
        .status-failed { background: #f8d7da; color: #842029; }
        .status-cancelled { background: #f8d7da; color: #842029; }
        .status-completed { background: #d1e7dd; color: #0f5132; }
        .status-confirmed { background: #d1e7dd; color: #0f5132; }
        .status-delivered { background: #cff4fc; color: #055160; }
        .status-draft { background: #e2e3e5; color: #383d41; }
        
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: bold;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin: 4px;
            text-align: center;
        }

        .btn-primary { background: #40916c; color: white; }
        .btn-primary:hover { background: #2d6a4f; transform: translateY(-1px); }
        
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-warning:hover { background: #e0a800; transform: translateY(-1px); }
        
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; transform: translateY(-1px); }
        
        .btn-success { background: #198754; color: white; }
        .btn-success:hover { background: #157347; transform: translateY(-1px); }
        
        .btn-disabled { 
            background: #ced4da; 
            color: #6c757d; 
            cursor: not-allowed; 
        }
        
        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
            background: white;
            border-radius: 8px;
        }

        .tips-section {
            margin-top: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #40916c;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .tips-section h4 {
            color: #2d6a4f;
            margin: 0 0 10px 0;
            font-size: 1rem;
        }

        .tips-section ul {
            margin: 0;
            padding-left: 20px;
        }

        .tips-section li {
            margin-bottom: 8px;
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .summary-section {
            margin-top: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .summary-section h4 {
            color: #2d6a4f;
            margin: 0 0 10px 0;
            font-size: 1rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .summary-item {
            font-size: 0.9rem;
        }

        /* Tablet & Mobile Responsive */
        @media (max-width: 968px) {
            .orders-table {
                display: none;
            }

            .orders-cards {
                display: block;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 60px 15px 15px;
            }

            .btn {
                font-size: 0.75rem;
                padding: 8px 10px;
            }

            .action-buttons {
                width: 100%;
            }

            .action-buttons .btn {
                flex: 1;
                min-width: 120px;
            }

            .tips-section,
            .summary-section {
                font-size: 0.85rem;
            }

            .summary-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 55px 10px 10px;
            }

            h1 {
                font-size: 1.3rem;
                margin-bottom: 15px;
            }

            .order-card {
                padding: 12px;
            }

            .order-number {
                font-size: 0.95rem;
            }

            .order-link {
                font-size: 1.2rem;
            }

            .btn {
                font-size: 0.7rem;
                padding: 7px 8px;
                margin: 2px;
            }

            .action-buttons .btn {
                min-width: 100px;
            }

            .status-badge {
                font-size: 0.7rem;
                padding: 4px 8px;
            }

            .tips-section,
            .summary-section {
                padding: 12px;
            }

            .tips-section li {
                font-size: 0.8rem;
                margin-bottom: 6px;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 10px;
            }

            .btn, .order-link {
                display: none;
            }

            .order-card,
            .tips-section,
            .summary-section {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
<x-navbar></x-navbar>
    <div class="container">
        <h1>📋 Daftar Pesanan</h1>
        
        <!-- Desktop Table View -->
        <table class="orders-table">
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status Pembayaran</th>
                    <th>Status Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('pesanan.detail', $order->id_transaksi) }}" 
                           class="order-link" title="Lihat Detail Pesanan">👁️</a>
                        <span class="order-number">{{ $order->id_transaksi }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($order->tanggal_transaksi)->format('d M Y') }}</td>
                    <td><span class="amount">Rp {{ number_format($order->total, 0, ',', '.') }}</span></td>
                    <td>
                        @php
                            $paidPayments = $order->payments->where('payment_status', 'paid');
                            $totalPaid = $paidPayments->sum('amount');
                        @endphp
                        @if($totalPaid > 0)
                            <span class="amount">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                        @else
                            <span class="amount">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $latestPayment = $order->payments->sortByDesc('created_at')->first();
                            $pendingPayment = $order->payments->where('payment_status', 'pending')->first();
                        @endphp
                        
                        @if($pendingPayment)
                            <span class="status-badge status-pending">⏰ Pending</span>
                        @elseif($latestPayment && $latestPayment->payment_status === 'paid')
                            @if($totalPaid >= $order->total)
                                <span class="status-badge status-paid">✅ Lunas</span>
                            @else
                                <span class="status-badge status-paid">💰 Partial</span>
                            @endif
                        @elseif($latestPayment)
                            <span class="status-badge status-{{ $latestPayment->payment_status }}">
                                @switch($latestPayment->payment_status)
                                    @case('failed')
                                        ❌ Gagal
                                        @break
                                    @case('cancelled')
                                        🚫 Dibatalkan
                                        @break
                                    @default
                                        {{ ucfirst($latestPayment->payment_status) }}
                                @endswitch
                            </span>
                        @else
                            <span class="status-badge status-pending">💳 Belum Bayar</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-{{ $order->status }}">
                            @switch($order->status)
                                @case('draft')
                                    📝 Draft
                                    @break
                                @case('pending')
                                    ⏳ Pending
                                    @break
                                @case('confirmed')
                                    ✅ Dikonfirmasi
                                    @break
                                @case('delivered')
                                    🚚 Dikirim
                                    @break
                                @case('completed')
                                    🎉 Selesai
                                    @break
                                @case('cancelled')
                                    ❌ Dibatalkan
                                    @break
                                @default
                                    {{ ucfirst($order->status) }}
                            @endswitch
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            @php
                                $hasSuccessfulPayment = $paidPayments->count() > 0;
                                $isFullyPaid = $totalPaid >= $order->total;
                                $hasPendingPayment = $pendingPayment !== null;
                            @endphp
                            
                            @if(in_array($order->status, ['pending', 'draft']) && !$isFullyPaid)
                                @if($hasPendingPayment)
                                    <a href="{{ route('payment.continue', $order->id_transaksi) }}" 
                                       class="btn btn-warning" title="Lanjutkan pembayaran yang tertunda">
                                        ⏰ Lanjutkan Pembayaran
                                    </a>
                                @elseif($hasSuccessfulPayment)
                                    <a href="{{ route('payment.select', $order->id_transaksi) }}" 
                                       class="btn btn-primary" title="Bayar sisa pembayaran">
                                        💰 Bayar Sisa
                                    </a>
                                @else
                                    <a href="{{ route('payment.select', $order->id_transaksi) }}" 
                                       class="btn btn-primary" title="Mulai pembayaran">
                                        💳 Bayar Sekarang
                                    </a>
                                @endif
                                
                                @if(!$hasSuccessfulPayment)
                                    <form action="{{ route('pemesanan.cancel', $order->id_transaksi) }}" 
                                          method="POST" style="display: inline-block; margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('❌ Apakah Anda yakin ingin membatalkan pesanan ini?\n\nPesanan yang dibatalkan tidak dapat dikembalikan.')" 
                                                title="Batalkan pesanan">
                                            ❌ Batalkan
                                        </button>
                                    </form>
                                @endif
                            @elseif($order->status == 'confirmed')
                                @if(!$isFullyPaid)
                                    <a href="{{ route('payment.select', $order->id_transaksi) }}" 
                                       class="btn btn-primary" title="Selesaikan pembayaran">
                                        💰 Lunasi Sekarang
                                    </a>
                                @else
                                    <button class="btn btn-disabled" title="Pesanan sudah dikonfirmasi dan lunas">
                                        ✅ Dikonfirmasi
                                    </button>
                                @endif
                            @elseif($order->status == 'delivered')
                                <form action="{{ route('pesanan.confirm-delivery', $order->id_transaksi) }}" 
                                      method="POST" style="display: inline-block; margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success" 
                                            onclick="return confirm('✅ Konfirmasi bahwa Anda telah menerima pesanan ini?\n\nSetelah dikonfirmasi, status akan berubah menjadi Selesai.')" 
                                            title="Konfirmasi penerimaan pesanan">
                                        ✅ Konfirmasi Diterima
                                    </button>
                                </form>
                            @elseif($order->status == 'completed')
                                <button class="btn btn-disabled" title="Pesanan sudah selesai">
                                    🎉 Selesai
                                </button>
                            @elseif($order->status == 'cancelled')
                                <button class="btn btn-disabled" title="Pesanan dibatalkan">
                                    ❌ Dibatalkan
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="no-data">
                        <div style="font-size: 48px; margin-bottom: 10px;">📝</div>
                        <strong>Belum ada pesanan</strong><br>
                        <small>Pesanan Anda akan muncul di sini setelah melakukan pemesanan catering</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Mobile Card View -->
        <div class="orders-cards">
            @forelse($orders as $order)
                @php
                    $paidPayments = $order->payments->where('payment_status', 'paid');
                    $totalPaid = $paidPayments->sum('amount');
                    $latestPayment = $order->payments->sortByDesc('created_at')->first();
                    $pendingPayment = $order->payments->where('payment_status', 'pending')->first();
                    $hasSuccessfulPayment = $paidPayments->count() > 0;
                    $isFullyPaid = $totalPaid >= $order->total;
                    $hasPendingPayment = $pendingPayment !== null;
                @endphp
                
                <div class="order-card">
                    <div class="order-card-header">
                        <div>
                            <a href="{{ route('pesanan.detail', $order->id_transaksi) }}" 
                               class="order-link" title="Lihat Detail">👁️</a>
                            <span class="order-number">{{ $order->id_transaksi }}</span>
                        </div>
                        <span class="status-badge status-{{ $order->status }}">
                            @switch($order->status)
                                @case('draft') 📝 Draft @break
                                @case('pending') ⏳ Pending @break
                                @case('confirmed') ✅ Dikonfirmasi @break
                                @case('delivered') 🚚 Dikirim @break
                                @case('completed') 🎉 Selesai @break
                                @case('cancelled') ❌ Dibatalkan @break
                                @default {{ ucfirst($order->status) }}
                            @endswitch
                        </span>
                    </div>

                    <div class="order-card-row">
                        <span class="order-card-label">Tanggal:</span>
                        <span class="order-card-value">{{ \Carbon\Carbon::parse($order->tanggal_transaksi)->format('d M Y') }}</span>
                    </div>

                    <div class="order-card-row">
                        <span class="order-card-label">Total:</span>
                        <span class="order-card-value amount">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>

                    <div class="order-card-row">
                        <span class="order-card-label">Pembayaran:</span>
                        <span class="order-card-value amount">
                            @if($totalPaid > 0)
                                Rp {{ number_format($totalPaid, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>

                    <div class="order-card-row">
                        <span class="order-card-label">Status Bayar:</span>
                        <span class="order-card-value">
                            @if($pendingPayment)
                                <span class="status-badge status-pending">⏰ Pending</span>
                            @elseif($latestPayment && $latestPayment->payment_status === 'paid')
                                @if($totalPaid >= $order->total)
                                    <span class="status-badge status-paid">✅ Lunas</span>
                                @else
                                    <span class="status-badge status-paid">💰 Partial</span>
                                @endif
                            @elseif($latestPayment)
                                <span class="status-badge status-{{ $latestPayment->payment_status }}">
                                    @switch($latestPayment->payment_status)
                                        @case('failed') ❌ Gagal @break
                                        @case('cancelled') 🚫 Dibatalkan @break
                                        @default {{ ucfirst($latestPayment->payment_status) }}
                                    @endswitch
                                </span>
                            @else
                                <span class="status-badge status-pending">💳 Belum Bayar</span>
                            @endif
                        </span>
                    </div>

                    <div class="order-card-row">
                        <div class="action-buttons" style="width: 100%;">
                            @if(in_array($order->status, ['pending', 'draft']) && !$isFullyPaid)
                                @if($hasPendingPayment)
                                    <a href="{{ route('payment.continue', $order->id_transaksi) }}" 
                                       class="btn btn-warning">⏰ Lanjutkan Pembayaran</a>
                                @elseif($hasSuccessfulPayment)
                                    <a href="{{ route('payment.select', $order->id_transaksi) }}" 
                                       class="btn btn-primary">💰 Bayar Sisa</a>
                                @else
                                    <a href="{{ route('payment.select', $order->id_transaksi) }}" 
                                       class="btn btn-primary">💳 Bayar Sekarang</a>
                                @endif
                                
                                @if(!$hasSuccessfulPayment)
                                    <form action="{{ route('pemesanan.cancel', $order->id_transaksi) }}" 
                                          method="POST" style="display: inline-block; margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('❌ Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                            ❌ Batalkan
                                        </button>
                                    </form>
                                @endif
                            @elseif($order->status == 'confirmed')
                                @if(!$isFullyPaid)
                                    <a href="{{ route('payment.select', $order->id_transaksi) }}" 
                                       class="btn btn-primary">💰 Lunasi Sekarang</a>
                                @else
                                    <button class="btn btn-disabled">✅ Dikonfirmasi</button>
                                @endif
                            @elseif($order->status == 'delivered')
                                <form action="{{ route('pesanan.confirm-delivery', $order->id_transaksi) }}" 
                                      method="POST" style="width: 100%; margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success" style="width: 100%;"
                                            onclick="return confirm('✅ Konfirmasi bahwa Anda telah menerima pesanan ini?')">
                                        ✅ Konfirmasi Diterima
                                    </button>
                                </form>
                            @elseif($order->status == 'completed')
                                <button class="btn btn-disabled" style="width: 100%;">🎉 Selesai</button>
                            @elseif($order->status == 'cancelled')
                                <button class="btn btn-disabled" style="width: 100%;">❌ Dibatalkan</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="no-data">
                    <div style="font-size: 48px; margin-bottom: 10px;">📝</div>
                    <strong>Belum ada pesanan</strong><br>
                    <small>Pesanan Anda akan muncul di sini setelah melakukan pemesanan catering</small>
                </div>
            @endforelse
        </div>

        @if($orders->count() > 0)
            <div class="tips-section">
                <h4>💡 Tips & Informasi</h4>
                <ul>
                    <li><strong>👁️ Ikon Mata:</strong> Klik untuk melihat detail lengkap pesanan</li>
                    <li><strong>⏰ Status Pending:</strong> Ada pembayaran yang belum diselesaikan, klik "Lanjutkan Pembayaran"</li>
                    <li><strong>💰 Status Partial:</strong> Sebagian sudah dibayar (DP), lanjutkan untuk melunasi</li>
                    <li><strong>✅ Status Dikonfirmasi:</strong> Pesanan sudah diproses oleh tim catering</li>
                    <li><strong>🚚 Status Dikirim:</strong> Pesanan sudah dikirim, klik "Konfirmasi Diterima" setelah menerima</li>
                    <li><strong>🎉 Status Selesai:</strong> Pesanan telah selesai dilaksanakan</li>
                </ul>
            </div>

            @php
                $totalOrders = $orders->count();
                $pendingOrders = $orders->where('status', 'pending')->count();
                $confirmedOrders = $orders->where('status', 'confirmed')->count();
                $deliveredOrders = $orders->where('status', 'delivered')->count();
                $completedOrders = $orders->where('status', 'completed')->count();
            @endphp
            
            @if($totalOrders > 1)
            <div class="summary-section">
                <h4>📊 Ringkasan Pesanan</h4>
                <div class="summary-grid">
                    <div class="summary-item">📋 <strong>Total:</strong> {{ $totalOrders }} pesanan</div>
                    @if($pendingOrders > 0)<div class="summary-item">⏳ <strong>Pending:</strong> {{ $pendingOrders }}</div>@endif
                    @if($confirmedOrders > 0)<div class="summary-item">✅ <strong>Dikonfirmasi:</strong> {{ $confirmedOrders }}</div>@endif
                    @if($deliveredOrders > 0)<div class="summary-item">🚚 <strong>Dikirim:</strong> {{ $deliveredOrders }}</div>@endif
                    @if($completedOrders > 0)<div class="summary-item">🎉 <strong>Selesai:</strong> {{ $completedOrders }}</div>@endif
                </div>
            </div>
            @endif
        @endif
    </div>
</body>
</html>