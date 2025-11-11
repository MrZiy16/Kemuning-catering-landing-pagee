<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Pesanan</title>
    <style>
        .status-step { margin: 1rem 0; padding: 1rem; border-left: 4px solid #3498db; }
        .status-step strong { color: #2c3e50; }
        .status-step small { color: #7f8c8d; }
    </style>
</head>
<body>
    <x-navbar></x-navbar>

    <div class="container my-5">
        <h2>Tracking Pesanan</h2>

        <p><strong>ID Transaksi:</strong> {{ $transaksi->id_transaksi }}</p>
        <p><strong>Status Saat Ini:</strong> {{ ucfirst($transaksi->status) }}</p>

        <h4>Riwayat Status:</h4>
        @forelse($logs as $log)
            <div class="status-step">
                <strong>{{ ucfirst($log->status_to) }}</strong>
                <br>
                <small>{{ $log->created_at }} oleh {{ $log->created_by }}</small>
                <br>
                <em>{{ $log->keterangan }}</em>
            </div>
        @empty
            <p>Belum ada riwayat status.</p>
        @endforelse
    </div>
</body>
</html>
