<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaksi->id_transaksi }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #f5f5f5;
        }
        
        .invoice-container {
            max-width: 900px;
            margin: 10px auto;
            background: #fff;
            padding: 25px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        /* Header */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #2d6a4f;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        
        .company-info h1 {
            font-size: 18px;
            color: #2d6a4f;
            margin-bottom: 3px;
            letter-spacing: 1px;
        }
        
        .company-info p {
            font-size: 10px;
            color: #666;
            margin: 1px 0;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h2 {
            font-size: 24px;
            color: #2d6a4f;
            margin-bottom: 3px;
        }
        
        .invoice-number {
            background: #f0f0f0;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            color: #2d6a4f;
        }
        
        /* Invoice Details */
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
            padding: 12px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        
        .detail-group h3 {
            font-size: 10px;
            text-transform: uppercase;
            color: #2d6a4f;
            font-weight: bold;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }
        
        .detail-group p {
            font-size: 11px;
            margin-bottom: 3px;
            line-height: 1.3;
        }
        
        .detail-group strong {
            color: #2d6a4f;
        }
        
        .detail-label {
            color: #888;
            font-size: 10px;
            display: block;
            margin-bottom: 1px;
        }
        
        /* Table Styles */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .items-table thead {
            background: #2d6a4f;
            color: #fff;
        }
        
        .items-table th {
            padding: 6px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #2d6a4f;
        }
        
        .items-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        
        .items-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        /* Summary Section */
        .summary-section {
            float: right;
            width: 280px;
            margin-bottom: 10px;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 6px 8px;
            border: none;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        
        .summary-table td:last-child {
            text-align: right;
            font-weight: bold;
            color: #2d6a4f;
        }
        
        .summary-table tr:last-child td {
            border-top: 2px solid #2d6a4f;
            border-bottom: 2px solid #2d6a4f;
            padding: 8px;
            font-size: 12px;
            background: #f9f9f9;
        }
        
        .summary-label {
            color: #666;
            font-weight: normal;
        }
        
        .payment-status {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
        }
        
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        
        .status-partial {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-unpaid {
            background: #f8d7da;
            color: #721c24;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Shipping Info */
        .shipping-section {
            clear: both;
            margin-top: 10px;
            padding: 10px;
            background: #f0f8f5;
            border-left: 3px solid #2d6a4f;
            border-radius: 3px;
        }
        
        .shipping-section h3 {
            font-size: 10px;
            text-transform: uppercase;
            color: #2d6a4f;
            font-weight: bold;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }
        
        .shipping-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .shipping-item p {
            font-size: 11px;
            margin-bottom: 2px;
        }
        
        .shipping-item .label {
            color: #888;
            font-size: 10px;
        }
        
        .shipping-item .value {
            color: #2d6a4f;
            font-weight: bold;
            font-size: 12px;
        }
        
        /* Footer */
        .footer-section {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            color: #888;
            font-size: 10px;
        }
        
        .footer-section p {
            margin-bottom: 2px;
        }
        
        .thank-you {
            font-size: 11px;
            color: #2d6a4f;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: #fff;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header-section">
            <div class="company-info">
                <h1>🍽 KEMUNING CATERING</h1>
                <p><strong>Jawa Tengah, Indonesia</strong></p>
                <p>Email: kemuningcatering7@gmail.com</p>
             
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">{{ $transaksi->id_transaksi }}</div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="detail-group">
                <h3>📍 Informasi Pengiriman</h3>
                <p>
                    <span class="detail-label">Nama Customer</span>
                    <strong>{{ $transaksi->customer->nama }}</strong>
                </p>
                <p>
                    <span class="detail-label">Nomor Telepon</span>
                    <strong>{{ $transaksi->customer->no_hp }}</strong>
                </p>
                <p>
                    <span class="detail-label">Alamat Pengiriman</span>
                    <strong>{{ $transaksi->alamat_pengiriman }}</strong>
                </p>
            </div>
            <div class="detail-group">
                <h3>📅 Informasi Pesanan</h3>
                <p>
                    <span class="detail-label">Tanggal Pesanan</span>
                    <strong>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y') }}</strong>
                </p>
                <p>
                    <span class="detail-label">Tanggal & Waktu Acara</span>
                    <strong>{{ \Carbon\Carbon::parse($transaksi->tanggal_acara)->translatedFormat('d F Y') }} {{ $transaksi->waktu_acara }}</strong>
                </p>
                <p>
                    <span class="detail-label">Status Pesanan</span>
                    <strong>{{ ucfirst($transaksi->status) }}</strong>
                </p>
            </div>
        </div>

        <!-- Items Table -->
        <h3 style="font-size: 11px; color: #2d6a4f; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.3px;">📦 Detail Item Pesanan</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk/Menu</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0; @endphp
                @foreach($items as $item)
                    @php $subtotal += $item->subtotal; @endphp
                    <tr>
                        <td>{{ $item->nama_item }}</td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga,0,',','.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary & Shipping -->
        <div class="clearfix">
            <div class="summary-section">
                <table class="summary-table">
                    <tr>
                        <td class="summary-label">Subtotal Pesanan</td>
                        <td>Rp {{ number_format($subtotal,0,',','.') }}</td>
                    </tr>
                    <tr>
                        <td class="summary-label">Biaya Pengiriman</td>
                        <td>Rp {{ number_format($transaksi->ongkir ?? 0,0,',','.') }}</td>
                    </tr>
                    <tr>
                        <td class="summary-label">Total Pesanan</td>
                        <td>Rp {{ number_format($transaksi->total,0,',','.') }}</td>
                    </tr>
                    <tr>
                        <td class="summary-label">Telah Dibayar</td>
                        <td>Rp {{ number_format($total_paid,0,',','.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Tagihan</td>
                        <td>Rp {{ number_format($transaksi->total - $total_paid,0,',','.') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Shipping Info -->
      
        </div>

        <!-- Status Pembayaran -->
        <div style="margin-top: 12px; text-align: right;">
            <p style="font-size: 10px; color: #888; margin-bottom: 4px;">Status Pembayaran:</p>
            <div class="payment-status 
                @if($payment_status === 'paid' || $payment_status === 'fully_paid')
                    status-paid
                @elseif($payment_status === 'partially_paid')
                    status-partial
                @else
                    status-unpaid
                @endif">
                {{ ucfirst(str_replace('_', ' ', $payment_status)) }}
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <p class="thank-you">✓ Terima kasih telah mempercayai Kemuning Catering</p>
            <p>Untuk informasi lebih lanjut, silakan hubungi customer service kami.</p>
            <p style="margin-top: 15px; font-size: 11px; color: #ccc;">Invoice ini dihasilkan secara otomatis oleh sistem Kemuning Catering</p>
        </div>
    </div>
</body>
</html>