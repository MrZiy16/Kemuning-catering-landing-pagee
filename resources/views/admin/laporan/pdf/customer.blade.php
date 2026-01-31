<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Customer</title>
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
        <p class="title">Laporan Customer</p>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <?php
        $totalCustomers = $customerAnalysis['total_customers'] ?? 0;
        $newCustomers = $customerAnalysis['new_customers'] ?? 0;
        $repeatCustomers = $customerAnalysis['repeat_customers'] ?? 0;
        $avgOrderValue = $topCustomers->avg('avg_order_value') ?? 0;
    ?>

    <div class="section-title">Ringkasan</div>
    <table class="summary">
        <tr>
            <td><strong>Total Customer</strong></td>
            <td class="text-right">{{ number_format($totalCustomers, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Customer Baru</strong></td>
            <td class="text-right">{{ number_format($newCustomers, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Repeat Customer</strong></td>
            <td class="text-right">{{ number_format($repeatCustomers, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Rata-rata Order</strong></td>
            <td class="text-right">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="section-title">Top Customer</div>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th class="text-right">Total Orders</th>
                <th class="text-right">Total Spent</th>
                <th class="text-right">Avg Order</th>
                <th class="text-center">Last Order</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topCustomers as $customer)
                <tr>
                    <td>{{ $customer->nama }}</td>
                    <td>{{ $customer->email }}</td>
                    <td class="text-right">{{ number_format($customer->total_orders, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($customer->avg_order_value, 0, ',', '.') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($customer->last_order_date)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center muted">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="muted" style="margin-top: 10px;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>