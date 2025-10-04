<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice {{ $transaksi->id_transaksi }}</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 13px; }
    .invoice-box { padding: 20px; }
    .header { display: flex; justify-content: space-between; border-bottom: 2px solid #2d6a4f; padding-bottom: 10px; margin-bottom: 20px; }
    .header h2 { margin: 0; color: #2d6a4f; }
    .company { font-weight: bold; color: #2d6a4f; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #2d6a4f; color: #fff; padding: 8px; text-align: left; }
    td { padding: 8px; border: 1px solid #ddd; }
    .summary { margin-top: 20px; float: right; width: 300px; }
    .summary td { padding: 6px; }
    .summary tr:last-child td { font-weight: bold; font-size: 15px; border-top: 2px solid #2d6a4f; }
    .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #666; }
  </style>
</head>
<body>
  <div class="invoice-box">
    <div class="header">
      <h2>INVOICE</h2>
      <div class="company">🍽 Kemuning Catering</div>
    </div>

    <p><strong>No Invoice:</strong> {{ $transaksi->id_transaksi }}</p>
    <p><strong>Tanggal:</strong> {{ $transaksi->tanggal_transaksi }}</p>
    <p><strong>Customer:</strong> {{ $transaksi->nama }} ({{ $transaksi->no_hp }})</p>
    <p><strong>Alamat:</strong> {{ $transaksi->alamat_pengiriman }}</p>
    <p><strong>Status:</strong> {{ ucfirst($transaksi->tanggal_acara) }}</p>

    <h3>Detail Pesanan</h3>
    <table>
      <tr>
        <th>Produk/Menu</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Subtotal</th>
      </tr>
      @php $total = 0; @endphp
      @foreach($items as $item)
        @php $total += $item->subtotal; @endphp
        <tr>
          <td>{{ $item->nama_item }}</td>
          <td>{{ $item->qty }}</td>
          <td>Rp {{ number_format($item->harga,0,',','.') }}</td>
          <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
        </tr>
      @endforeach
    </table>

    <div class="summary">
      <table>
        <tr>
          <td>Total</td>
          <td>Rp {{ number_format($total,0,',','.') }}</td>
        </tr>
        @if($payment)
        <tr>
          <td>Bayar</td>
          <td>Rp {{ number_format($payment->amount,0,',','.') }}</td>
        </tr>
        <tr>
          <td>Status Pembayaran</td>
          <td>{{ ucfirst($payment->payment_status) }}</td>
        </tr>
        @endif
      </table>
    </div>

    <div class="footer">
      <p>Terima kasih telah memesan di Kemuning Catering 🍽</p>
    </div>
  </div>
</body>
</html>
