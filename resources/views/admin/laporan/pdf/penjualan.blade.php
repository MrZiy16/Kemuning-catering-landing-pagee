<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        .header { text-align: center; margin-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; }
        .subtitle { font-size: 12px; color: #555; margin: 2px 0 0; }
        .section-title { font-size: 14px; font-weight: bold; margin: 14px 0 6px; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .summary td { padding: 6px 8px; border: 1px solid #ccc; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; }
        th { background: #f5f5f5; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Laporan Penjualan</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <?php
        $totalRevenue = $salesData->sum('revenue');
        $totalTransactions = $salesData->sum('transactions');
        $avgTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        $bestPeriod = $salesData->sortByDesc('revenue')->first();
    ?>

    <div class="section-title">Ringkasan</div>
    <table class="summary">
        <tr>
            <td><strong>Total Revenue</strong></td>
            <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Transaksi</strong></td>
            <td class="text-right">{{ number_format($totalTransactions, 0, ',', '.') }} transaksi</td>
        </tr>
        <tr>
            <td><strong>Rata-rata Transaksi</strong></td>
            <td class="text-right">Rp {{ number_format($avgTransaction, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Periode Terbaik</strong></td>
            <td class="text-right">{{ $bestPeriod->period ?? 'N/A' }} (Rp {{ number_format($bestPeriod->revenue ?? 0, 0, ',', '.') }})</td>
        </tr>
    </table>

    <div class="section-title">Detail Penjualan per Periode</div>
    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th class="text-right">Revenue</th>
                <th class="text-right">Transaksi</th>
                <th class="text-right">Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesData as $data)
                <tr>
                    <td>{{ $data->period }}</td>
                    <td class="text-right">Rp {{ number_format($data->revenue, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($data->transactions, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data->avg_transaction ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            @if($salesData->isEmpty())
                <tr>
                    <td colspan="4" class="text-center muted">Tidak ada data</td>
                </tr>
            @endif
        </tbody>
    </table>

    <p class="muted" style="margin-top: 10px;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>