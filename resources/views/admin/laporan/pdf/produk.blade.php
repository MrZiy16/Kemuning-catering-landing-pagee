<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Produk</title>
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
        <p class="title">Laporan Produk</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <?php
        $totalRevenue = $productPerformance->sum('total_revenue');
        $totalQty = $productPerformance->sum('total_qty');
        $bestProduct = $productPerformance->sortByDesc('total_revenue')->first();
    ?>

    <div class="section-title">Ringkasan</div>
    <table class="summary">
        <tr>
            <td><strong>Total Revenue</strong></td>
            <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Quantity</strong></td>
            <td class="text-right">{{ number_format($totalQty, 0, ',', '.') }} unit</td>
        </tr>
        <tr>
            <td><strong>Produk Terlaris</strong></td>
            <td class="text-right">{{ $bestProduct->nama_produk ?? 'N/A' }} (Rp {{ number_format($bestProduct->total_revenue ?? 0, 0, ',', '.') }})</td>
        </tr>
    </table>

    <div class="section-title">Detail Performa Produk</div>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productPerformance as $product)
                <tr>
                    <td>{{ $product->nama_produk }}</td>
                    <td>{{ ucfirst($product->kategori_produk) }}</td>
                    <td class="text-right">{{ number_format($product->total_qty, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center muted">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="muted" style="margin-top: 10px;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>