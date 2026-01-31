<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Berhasil</title>
</head>
<body>
    <x-navbar></x-navbar>

    <div class="container my-5">
        <h2>Pemesanan Berhasil 🎉</h2>
        <p>Terima kasih, pesanan Anda sudah tercatat.</p>

        <p><strong>ID Transaksi:</strong> {{ $transaksi->id_transaksi }}</p>
        <p><strong>Status:</strong> {{ ucfirst($transaksi->status) }}</p>

        <a href="{{ route('pemesanan.tracking', $transaksi->id_transaksi) }}" class="btn">
            Lihat Tracking Pesanan
        </a>
    </div>
</body>
</html>
