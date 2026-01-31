<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran #{{ $payment->id }}</title>
    <style>
        body { background: #f4f6f9; font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: 5rem auto; }
        .card { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; overflow: hidden; }
        .card-header { padding: 15px 20px; background: #28a745; color: #fff; font-size: 18px; font-weight: bold; }
        .card-body { padding: 20px; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .alert-info { background: #e9f5ff; border: 1px solid #b6e0fe; color: #084298; }
        .alert-success { background: #e6f7ed; border: 1px solid #a4e6c4; color: #0f5132; }
        .alert-warning { background: #fff4e5; border: 1px solid #ffd8a8; color: #664d03; }
        h5 { margin: 20px 0 10px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        td { padding: 8px; vertical-align: top; }
        td:first-child { width: 200px; font-weight: bold; color: #444; }
        .badge { display: inline-block; padding: 5px 10px; font-size: 13px; border-radius: 5px; font-weight: bold; color: #5f5808ff; }
        .btn { display: inline-block; margin: 5px; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold; transition: 0.3s; }
        .btn-primary { background: #007bff; color: #fff; }
        .btn-info { background: #17a2b8; color: #fff; }
        .btn-warning { background: #ffc107; color: #000; }
        .btn-success { background: #28a745; color: #fff; }
        .btn:hover { opacity: 0.9; }
        .text-success { color: #28a745; font-weight: bold; }
        .text-warning { color: #d39e00; font-weight: bold; }
        .text-center { text-align: center; margin-top: 20px; }
        
    </style>
</head>
<body>
<x-navbar></x-navbar>
<div class="container">
    <div class="card">
        <div class="card-header">
            @if($payment->method === 'offline')
                Pembayaran Offline Berhasil Disubmit
            @else
                Pembayaran 
            @endif
        </div>
        <div class="card-body">

            <!-- Alert -->
            @if($payment->method === 'offline')
                <div class="alert alert-info">
                    <strong>Informasi:</strong> Pembayaran offline Anda telah berhasil disubmit. 
                    Silakan lakukan transfer sesuai instruksi melalui WhatsApp 
                    dan kirim bukti transfer untuk konfirmasi.
                </div>
            @else
                <div class="alert alert-warning" style="font-weight:900; font-size:18px; padding:18px; border-radius:8px; text-align:center;">
                    <strong style="display:block; font-size:20px; margin-bottom:6px;">Mohon Konfirmasi Pembayaran Anda</strong>
                    <span style="font-weight:800;">Silakan hubungi kami dengan menekan tombol di bawah ini!</span>
                </div>
            @endif

            <!-- Detail Payment -->
            <h5>Detail Pembayaran</h5>
            <table>
                <tr><td>ID Pembayaran:</td><td>#{{ $payment->id }}</td></tr>
                <tr><td>ID Transaksi:</td><td>{{ $payment->master_transaction_id }}</td></tr>
                <tr><td>Jenis Pembayaran:</td><td>{{ $payment->type_label }}</td></tr>
                <tr><td>Metode:</td><td>{{ $payment->method_label }}</td></tr>
                <tr><td>Jumlah:</td><td class="text-success">{{ $payment->formatted_amount }}</td></tr>
                <tr><td>Status:</td><td><span class="badge {{ $payment->status_badge }}">{{ $payment->status_text }}</span></td></tr>
                <tr><td>Tanggal:</td><td>{{ $payment->created_at->format('d/m/Y H:i') }}</td></tr>
                @if($payment->isPaid() && $payment->paid_at)
                <tr><td>Dibayar pada:</td><td>{{ $payment->formatted_paid_at }}</td></tr>
                @endif
            </table>

            <!-- Detail Transaksi -->
            <h5>Detail Transaksi</h5>
            <table>
                <tr><td>Customer:</td><td>{{ $payment->masterTransaction->customer->nama }}</td></tr>
                <tr><td>Total Transaksi:</td><td>Rp {{ number_format($payment->masterTransaction->total, 0, ',', '.') }}</td></tr>
                <tr><td>Total Dibayar:</td><td>Rp {{ number_format($payment->masterTransaction->total_paid, 0, ',', '.') }}</td></tr>
                @if($payment->masterTransaction->remaining_amount > 0)
                <tr><td>Sisa Pembayaran:</td><td class="text-warning">Rp {{ number_format($payment->masterTransaction->remaining_amount, 0, ',', '.') }}</td></tr>
                @endif
                <tr><td>Status Transaksi:</td><td><span class="badge {{ $payment->masterTransaction->status_badge }}">{{ $payment->masterTransaction->status_text }}</span></td></tr>
                <tr><td>Tanggal Acara:</td><td>{{ date('d/m/Y', strtotime($payment->masterTransaction->tanggal_acara)) }}</td></tr>
            </table>

            <!-- Next Steps -->
            <h5>Langkah Selanjutnya</h5>
            @if($payment->method === 'offline')
                <div class="alert alert-warning">
                    <h6>Instruksi Pembayaran Offline</h6>
                    <ol>
                        <li>Lakukan transfer sesuai nominal di atas ke rekening yang telah diberikan</li>
                        <li>Kirim bukti transfer ke WhatsApp admin</li>
                        <li>Cantumkan ID Transaksi ({{ $payment->master_transaction_id }}) dan ID Payment (#{{ $payment->id }})</li>
                        <li>Tunggu konfirmasi dari admin (maksimal 1x24 jam)</li>
                    </ol>
                    <a href="https://api.whatsapp.com/send?phone=6282217463605&text=Halo%2C%20saya%20ingin%20konfirmasi%20pembayaran%20ID%3A%20{{ $payment->master_transaction_id }}" 
                       class="btn btn-success" target="_blank">
                        Hubungi Admin WhatsApp
                    </a>
                </div>
            @else
                @if($payment->masterTransaction->remaining_amount > 0)
                    <div class="alert alert-info">
                        <h6>Status belum berhasil</h6>
                        <p>Kami akan verifikasi pembayaran anda.</p>
                        <p>Apabila status belum berhasil, silakan lakukan pembayaran ulang di menu pesanan saya </p>
                    </div>
                @else
                    <div class="alert alert-success">
                        <h6>Transaksi Sudah Lunas</h6>
                        <p>Selamat! Transaksi Anda sudah lunas. Kami akan segera memproses pesanan Anda.</p>
                    </div>
                @endif
            @endif

            <!-- Action Buttons -->
            <div class="text-center">
                <a
                    href="https://wa.me/6282217463605?text={{ urlencode('Halo saya ingin mengonfirmasi pembayaran saya mengenai pesanan saya ' . $payment->master_transaction_id) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-success"
                    aria-label="Konfirmasi pembayaran via WhatsApp">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:middle; margin-right:8px;">
                        <path d="M20.52 3.48A11.91 11.91 0 0012 0C5.37 0 .02 5.35.02 12 0 14.12.54 16.14 1.56 17.86L0 24l6.38-1.66A11.94 11.94 0 0012 24c6.63 0 11.98-5.35 11.98-12a11.9 11.9 0 00-3.46-8.52zM12 21.5c-1.17 0-2.32-.26-3.36-.76l-.24-.12-3.78 1 1-3.69-.12-.24A8.5 8.5 0 013.5 12C3.5 7.59 7.09 4 11.5 4S19.5 7.59 19.5 12 15.91 21.5 11.5 21.5z"/>
                        <path d="M17.3 14.1c-.3-.15-1.76-.87-2.03-.97-.27-.1-.46-.15-.66.15s-.76.97-.93 1.17c-.17.19-.34.22-.63.07a6.32 6.32 0 01-1.86-1.15 7.89 7.89 0 01-1.46-1.82c-.15-.27 0-.42.11-.56.11-.11.24-.27.36-.4.12-.13.16-.22.25-.37.08-.15.04-.28-.02-.39-.07-.12-.62-1.5-.85-2.05-.22-.54-.45-.47-.62-.48-.16-.01-.35-.01-.54-.01-.19 0-.5.07-.76.33s-1 .97-1 .97-.36.34-.36.83.29 1.02.33 1.09c.04.07.56.86 1.37 1.4.81.54 1.5.72 1.63.8.13.08.89.53 1.86.81.98.28 1.42.24 1.95.15.53-.09 1.76-.72 2.01-1.41.25-.69.25-1.28.17-1.41-.08-.13-.3-.19-.6-.34z"/>
                    </svg>
                    Konfirmasi pembayaran via WhatsApp
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
