<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            padding-top: 72px;
        }
        h1 {
            color: #2d6a4f;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            text-align: left;
        }
        th {
            background: #2d6a4f;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        tr:hover {
            background: #f1f8f5;
        }
        .order-number {
            font-weight: bold;
            color: #40916c;
        }
        .order-link {
            text-decoration: none;
            margin-right: 10px;
            font-size: 1.1rem;
            transition: transform 0.2s;
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
            font-size: 0.8rem;
            font-weight: bold;
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
            margin: 2px;
        }
        .btn-primary { 
            background: #40916c; 
            color: white; 
        }
        .btn-primary:hover {
            background: #2d6a4f;
            transform: translateY(-1px);
        }
        .btn-warning { 
            background: #ffc107; 
            color: #212529; 
        }
        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }
        .btn-danger { 
            background: #dc3545; 
            color: white; 
        }
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-1px);
        }
        .btn-success { 
            background: #198754; 
            color: white; 
        }
        .btn-success:hover {
            background: #157347;
            transform: translateY(-1px);
        }
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
        }

        .tips-section ul {
            margin: 0;
            padding-left: 20px;
        }

        .tips-section li {
            margin-bottom: 5px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 60px;
                padding-left: 10px;
                padding-right: 10px;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 10px 8px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }

            .btn {
                font-size: 0.75rem;
                padding: 6px 10px;
                width: 100%;
                text-align: center;
            }

            .tips-section {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<x-navbar></x-navbar>
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <h1>📋 Daftar Pesanan</h1>
        <table>
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
                                    <!-- Ada payment pending, tampilkan tombol lanjutkan -->
                                    <a href="{{ route('payment.continue', $order->id_transaksi) }}" 
                                       class="btn btn-warning" title="Lanjutkan pembayaran yang tertunda">
                                        ⏰ Lanjutkan Pembayaran
                                    </a>
                                @elseif($hasSuccessfulPayment)
                                    <!-- Ada payment berhasil tapi belum lunas, bayar sisa -->
                                    <a href="{{ route('payment.select', $order->id_transaksi) }}" 
                                       class="btn btn-primary" title="Bayar sisa pembayaran">
                                        💰 Bayar Sisa
                                    </a>
                                @else
                                    <!-- Belum ada payment sama sekali -->
                                    <a href="{{ route('payment.select', $order->id_transaksi) }}" 
                                       class="btn btn-primary" title="Mulai pembayaran">
                                        💳 Bayar Sekarang
                                    </a>
                                @endif
                                
                                <!-- Tombol batalkan untuk pesanan yang belum confirmed -->
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
                                    <!-- Sudah confirmed tapi belum lunas -->
                                    <a href="{{ route('payment.select', $order->id_transaksi) }}" 
                                       class="btn btn-primary" title="Selesaikan pembayaran">
                                        💰 Lunasi Sekarang
                                    </a>
                                @else
                                    <!-- Sudah lunas dan confirmed -->
                                    <button class="btn btn-disabled" title="Pesanan sudah dikonfirmasi dan lunas">
                                        ✅ Dikonfirmasi
                                    </button>
                                @endif
                            @elseif($order->status == 'delivered')
                                <!-- Status delivered - tampilkan tombol konfirmasi penerimaan -->
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

            <!-- Summary Section -->
            @php
                $totalOrders = $orders->count();
                $pendingOrders = $orders->where('status', 'pending')->count();
                $confirmedOrders = $orders->where('status', 'confirmed')->count();
                $deliveredOrders = $orders->where('status', 'delivered')->count();
                $completedOrders = $orders->where('status', 'completed')->count();
            @endphp
            
            @if($totalOrders > 1)
            <div style="margin-top: 20px; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h4 style="color: #2d6a4f; margin: 0 0 10px 0;">📊 Ringkasan Pesanan</h4>
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div>📋 <strong>Total:</strong> {{ $totalOrders }} pesanan</div>
                    @if($pendingOrders > 0)<div>⏳ <strong>Pending:</strong> {{ $pendingOrders }} pesanan</div>@endif
                    @if($confirmedOrders > 0)<div>✅ <strong>Dikonfirmasi:</strong> {{ $confirmedOrders }} pesanan</div>@endif
                    @if($deliveredOrders > 0)<div>🚚 <strong>Dikirim:</strong> {{ $deliveredOrders }} pesanan</div>@endif
                    @if($completedOrders > 0)<div>🎉 <strong>Selesai:</strong> {{ $completedOrders }} pesanan</div>@endif
                </div>
            </div>
            @endif
        @endif
    </div>
</body>
</html>