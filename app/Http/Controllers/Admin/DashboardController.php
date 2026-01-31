<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterTransaksi;
use App\Models\DetailTransaksi;
use App\Models\TransaksiMenuCustom;
use App\Models\User;
use App\Models\MasterMenu;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
{
    try {
        // 1. Statistik Ringkas - dengan error handling
        $totalPelanggan = User::where('role', 'pelanggan')
            ->where('status', 1)
            ->count();
        $totalProduk = Produk::where('status', 'active')->count();
       
        $totalMenu = MasterMenu::where('status', 'active')->count();

        // ========== PEMBEDAAN ROLE ==========
        $transaksiBaseQuery = MasterTransaksi::query();
        if (auth()->user()->role === 'admin') {
            $transaksiBaseQuery->where('status', '!=', 'completed');
        }
        $totalTransaksi = $transaksiBaseQuery->clone()->count();

        // 2. Ringkasan Omzet - hanya dari transaksi completed
        $completedBaseQuery = MasterTransaksi::where('status', 'completed');
        if (auth()->user()->role === 'admin') {
            $completedBaseQuery->where('status', '!=', 'completed');
        }
        $totalOmzet = $completedBaseQuery->clone()->sum('total') ?? 0;
        $omzetBulanIni = $completedBaseQuery->clone()
            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->sum('total') ?? 0;

        $totalCompleted = $completedBaseQuery->clone()->count();
        $rataRataTransaksi = $totalCompleted > 0 ? $totalOmzet / $totalCompleted : 0;

        // Target bulanan (contoh: Rp 10,000,000)
        $targetBulanan = 10000000;
        $persentaseTarget = $targetBulanan > 0 ? min(($omzetBulanIni / $targetBulanan) * 100, 100) : 0;

        // 3. Data Grafik Transaksi Harian (30 hari terakhir)
        $transaksiHarian = collect();
        $startDate = Carbon::now()->subDays(29);
       
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $total = $transaksiBaseQuery->clone()
                ->whereDate('tanggal_transaksi', $date->format('Y-m-d'))
                ->count();
           
            $transaksiHarian->push([
                'tanggal' => $date->format('d/m'),
                'total' => $total
            ]);
        }

        // 4. Data Grafik Transaksi Bulanan (12 bulan terakhir)
        $transaksiBulanan = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $total = $transaksiBaseQuery->clone()
                ->whereMonth('tanggal_transaksi', $date->month)
                ->whereYear('tanggal_transaksi', $date->year)
                ->count();
           
            $transaksiBulanan->push([
                'bulan' => $date->format('M Y'),
                'total' => $total
            ]);
        }

        // 5. Status Order Data untuk Pie Chart
        $statusData = $transaksiBaseQuery->clone()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderBy('total', 'desc')
            ->get();

        // 6. Transaksi Terbaru (10 transaksi terakhir)
        $transaksiTerbaruQuery = MasterTransaksi::with('customer')
            ->select('id_transaksi', 'id_customer', 'total', 'status', 'tanggal_transaksi', 'created_at');

        // Jika ADMIN, filter hanya transaksi dengan status semua kecuali delivered
        if (auth()->user()->role === 'admin') {
            $transaksiTerbaruQuery->where('status', '!=', 'completed');
        }

        $transaksiTerbaru = $transaksiTerbaruQuery->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($transaksi) {
                return (object) [
                    'id_transaksi' => $transaksi->id_transaksi,
                    'total' => $transaksi->total,
                    'status' => $transaksi->status,
                    'tanggal_transaksi' => $transaksi->tanggal_transaksi,
                    'customer_nama' => $transaksi->customer->nama ?? 'Unknown'
                ];
            });

        // Debug untuk memastikan data tersedia
        \Log::info('Dashboard Data:', [
            'totalPelanggan' => $totalPelanggan,
            'totalProduk' => $totalProduk,
            'totalMenu' => $totalMenu,
            'totalTransaksi' => $totalTransaksi
        ]);

        return view('admin.dashboard', compact(
            'totalPelanggan',
            'totalProduk',
            'totalMenu',
            'totalTransaksi',
            'totalOmzet',
            'omzetBulanIni',
            'rataRataTransaksi',
            'persentaseTarget',
            'transaksiHarian',
            'transaksiBulanan',
            'statusData',
            'transaksiTerbaru'
        ));
    } catch (\Exception $e) {
        // Log error dan return dengan data default
        \Log::error('Dashboard Error: ' . $e->getMessage());
       
        // Data fallback
        $totalPelanggan = 0;
        $totalProduk = 0;
        $totalMenu = 0;
        $totalTransaksi = 0;
        $totalOmzet = 0;
        $omzetBulanIni = 0;
        $rataRataTransaksi = 0;
        $persentaseTarget = 0;
        $transaksiHarian = collect();
        $transaksiBulanan = collect();
        $statusData = collect();
        $transaksiTerbaru = collect();

        return view('admin.dashboard', compact(
            'totalPelanggan',
            'totalProduk',
            'totalMenu',
            'totalTransaksi',
            'totalOmzet',
            'omzetBulanIni',
            'rataRataTransaksi',
            'persentaseTarget',
            'transaksiHarian',
            'transaksiBulanan',
            'statusData',
            'transaksiTerbaru'
        ))->with('error', 'Terjadi kesalahan dalam memuat data dashboard.');
    }
}

    /**
     * Get updated dashboard data for AJAX requests
     */
  public function update()
{
    // Status Order Data untuk Pie Chart
    $transaksiBaseQuery = MasterTransaksi::query();
    if (auth()->user()->role === 'admin') {
        $transaksiBaseQuery->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
    }
    $statusData = $transaksiBaseQuery->clone()
        ->selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->orderBy('total', 'desc')
        ->get();

    // Transaksi Terbaru (10 transaksi terakhir)
    $transaksiTerbaruQuery = MasterTransaksi::with('customer')
        ->select('id_transaksi', 'id_customer', 'total', 'status', 'tanggal_transaksi', 'created_at');

    if (auth()->user()->role === 'admin') {
        $transaksiTerbaruQuery->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
    }

    $transaksiTerbaru = $transaksiTerbaruQuery->orderBy('created_at', 'desc')
        ->limit(10)
        ->get()
        ->map(function ($transaksi) {
            return (object) [
                'id_transaksi' => $transaksi->id_transaksi,
                'total' => $transaksi->total,
                'status' => $transaksi->status,
                'tanggal_transaksi' => $transaksi->tanggal_transaksi,
                'customer_nama' => $transaksi->customer->nama ?? 'Unknown'
            ];
        });

    return response()->json([
        'statusData' => $statusData,
        'transaksiTerbaru' => $transaksiTerbaru
    ]);
}

public function chart(Request $request)
{
    $period = $request->period ?? 'daily';
    $transaksiBaseQuery = MasterTransaksi::query();
    if (auth()->user()->role === 'admin') {
        $transaksiBaseQuery->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
    }

    $data = collect();
    if ($period === 'daily') {
        $startDate = Carbon::now()->subDays(29);
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $total = $transaksiBaseQuery->clone()
                ->whereDate('tanggal_transaksi', $date->format('Y-m-d'))
                ->count();
            $data->push([
                'label' => $date->format('d/m'),
                'transaksi' => $total
            ]);
        }
    } else {
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $total = $transaksiBaseQuery->clone()
                ->whereMonth('tanggal_transaksi', $date->month)
                ->whereYear('tanggal_transaksi', $date->year)
                ->count();
            $data->push([
                'label' => $date->format('M Y'),
                'transaksi' => $total
            ]);
        }
    }

    return response()->json($data);
}

public function getTopPerformance()
    {
        try {
            // Top 5 Menu berdasarkan transaksi
            $topMenu = TransaksiMenuCustom::with('menu')
                ->selectRaw('id_menu, SUM(qty) as total_qty, SUM(subtotal) as total_omzet')
                ->groupBy('id_menu')
                ->orderBy('total_qty', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'nama_menu' => $item->menu->nama_menu ?? 'Unknown',
                        'total_qty' => $item->total_qty,
                        'total_omzet' => $item->total_omzet
                    ];
                });

            // Top 5 Produk berdasarkan transaksi
            $topProduk = DetailTransaksi::with('produk')
                ->whereNotNull('id_produk')
                ->selectRaw('id_produk, SUM(qty) as total_qty, SUM(subtotal) as total_omzet')
                ->groupBy('id_produk')
                ->orderBy('total_qty', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'nama_produk' => $item->produk->nama_produk ?? 'Unknown',
                        'total_qty' => $item->total_qty,
                        'total_omzet' => $item->total_omzet
                    ];
                });

            return response()->json([
                'topMenu' => $topMenu,
                'topProduk' => $topProduk
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memuat data performance',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
