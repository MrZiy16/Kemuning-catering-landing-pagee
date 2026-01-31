<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - {{ $order->id_transaksi }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            margin: 0;
     
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            margin-top: 5rem;
        }
        h1 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #2d6a4f;
        }
        .info {
            margin-bottom: 25px;
        }
        .info p {
            margin: 6px 0;
            font-size: 0.95rem;
            color: #333;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            font-size: 0.95rem;
        }
        th {
            background: #2d6a4f;
            color: #fff;
            text-align: left;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-paid { background: #d1e7dd; color: #0f5132; }
        .status-cancelled { background: #f8d7da; color: #842029; }
        .status-completed { background: #d1e7dd; color: #0f5132; }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
            flex-wrap: wrap;
        }
        
        .back-link {
            display: inline-block;
            padding: 10px 16px;
            background: #40916c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            background: #2d6a4f;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .invoice-btn {
            display: inline-block;
            padding: 10px 16px;
            background: #1f77b4;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }
        .invoice-btn:hover {
            background: #1558a3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .invoice-btn i::before {
            content: "📄 ";
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <x-navbar></x-navbar>
    <div class="container">
        <h1>Detail Pesanan {{ $order->id_transaksi }}</h1>

        <div class="info">
            <p><strong>Tanggal Transaksi:</strong> {{ \Carbon\Carbon::parse($order->tanggal_transaksi)->format('d M Y') }}</p>
            <p><strong>Nama Customer:</strong> {{ $order->customer->nama ?? '-' }}</p>
            <p><strong>Alamat Pengiriman:</strong> {{ $order->alamat_pengiriman }}</p>
            <p><strong>Status Pesanan:</strong> 
                <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </div>

        <h3>Pesanan</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Pesanan</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->detailTransaksi as $detail)
                        <tr>
                            <td>
                                {{ $detail->produk->nama_produk ?? $detail->menu->nama_menu ?? '-' }}
                            </td>
                            <td>{{ $detail->qty }}</td>
                            <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;">Belum ada detail pesanan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <h3>Pembayaran</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Metode</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->payments as $payment)
                        <tr>
                            <td>{{ ucfirst($payment->method) }}</td>
                            <td>{{ strtoupper($payment->type) }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td><span class="status-badge status-{{ $payment->payment_status }}">{{ ucfirst($payment->payment_status) }}</span></td>
                            <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">Belum ada pembayaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="button-group">
            <a href="{{ route('invoice.download', $order->id_transaksi) }}" class="invoice-btn">
                📥 Download Invoice
            </a>
            <a href="{{ route('pesanan.index') }}" class="back-link">
                ⬅ Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>
</body>
</html>