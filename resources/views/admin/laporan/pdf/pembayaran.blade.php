<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran</title>
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
        <p class="title">Laporan Pembayaran</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <?php
        $total = $paymentSummary['total_payments'] ?? 0;
        $paid = $paymentSummary['paid_payments'] ?? 0;
        $pending = $paymentSummary['pending_payments'] ?? 0;
        $dp = $paymentSummary['dp_payments'] ?? 0;
        $full = $paymentSummary['full_payments'] ?? 0;
        $successRate = $total > 0 ? ($paid / $total) * 100 : 0;
    ?>

    <div class="section-title">Ringkasan</div>
    <table class="summary">
        <tr>
            <td><strong>Total Pembayaran</strong></td>
            <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Pembayaran Berhasil</strong></td>
            <td class="text-right">Rp {{ number_format($paid, 0, ',', '.') }} ({{ number_format($successRate, 1) }}%)</td>
        </tr>
        <tr>
            <td><strong>Pembayaran Pending</strong></td>
            <td class="text-right">Rp {{ number_format($pending, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Down Payment</strong></td>
            <td class="text-right">Rp {{ number_format($dp, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Full Payment</strong></td>
            <td class="text-right">Rp {{ number_format($full, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="section-title">Rincian Ringkas</div>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total</td>
                <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Berhasil</td>
                <td class="text-right">Rp {{ number_format($paid, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pending</td>
                <td class="text-right">Rp {{ number_format($pending, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Down Payment</td>
                <td class="text-right">Rp {{ number_format($dp, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Full Payment</td>
                <td class="text-right">Rp {{ number_format($full, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p class="muted" style="margin-top: 10px;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>