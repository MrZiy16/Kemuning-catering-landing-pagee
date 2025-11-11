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

            $totalTransaksi = MasterTransaksi::count();

            // 2. Ringkasan Omzet - hanya dari transaksi completed
            $totalOmzet = MasterTransaksi::where('status', 'completed')
                ->sum('total') ?? 0;

            $omzetBulanIni = MasterTransaksi::where('status', 'completed')
                ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                ->whereYear('tanggal_transaksi', Carbon::now()->year)
                ->sum('total') ?? 0;

            $rataRataTransaksi = $totalTransaksi > 0 ? $totalOmzet / $totalTransaksi : 0;

            // Target bulanan (contoh: Rp 10,000,000)
            $targetBulanan = 10000000;
            $persentaseTarget = $targetBulanan > 0 ? min(($omzetBulanIni / $targetBulanan) * 100, 100) : 0;

            // 3. Data Grafik Transaksi Harian (30 hari terakhir)
            $transaksiHarian = collect();
            $startDate = Carbon::now()->subDays(29);
            
            for ($i = 0; $i < 30; $i++) {
                $date = $startDate->copy()->addDays($i);
                $total = MasterTransaksi::whereDate('tanggal_transaksi', $date->format('Y-m-d'))
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
                $total = MasterTransaksi::whereMonth('tanggal_transaksi', $date->month)
                    ->whereYear('tanggal_transaksi', $date->year)
                    ->count();
                
                $transaksiBulanan->push([
                    'bulan' => $date->format('M Y'),
                    'total' => $total
                ]);
            }

            // 5. Status Order Data untuk Pie Chart
            $statusData = MasterTransaksi::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->orderBy('total', 'desc')
                ->get();

            // 6. Transaksi Terbaru (10 transaksi terakhir)
            $transaksiTerbaru = MasterTransaksi::with('customer')
                ->select('id_transaksi', 'id_customer', 'total', 'status', 'tanggal_transaksi', 'created_at')
                ->orderBy('created_at', 'desc')
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
        try {
            // Data real-time untuk update AJAX
            $statusData = MasterTransaksi::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->get();

            $totalTransaksiHariIni = MasterTransaksi::whereDate('tanggal_transaksi', Carbon::today())
                ->count();

            $omzetHariIni = MasterTransaksi::whereDate('tanggal_transaksi', Carbon::today())
                ->where('status', 'completed')
                ->sum('total') ?? 0;

            $transaksiTerbaru = MasterTransaksi::with('customer')
                ->select('id_transaksi', 'id_customer', 'total', 'status', 'tanggal_transaksi', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
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
                'totalTransaksiHariIni' => $totalTransaksiHariIni,
                'omzetHariIni' => $omzetHariIni,
                'transaksiTerbaru' => $transaksiTerbaru,
                'lastUpdate' => Carbon::now()->format('H:i:s')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memperbarui data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get chart data based on period
     */
    public function getChartData(Request $request)
    {
        try {
            $period = $request->get('period', 'daily'); // daily or monthly
            
            if ($period === 'monthly') {
                $data = collect();
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $total = MasterTransaksi::whereMonth('tanggal_transaksi', $date->month)
                        ->whereYear('tanggal_transaksi', $date->year)
                        ->count();
                    
                    $omzet = MasterTransaksi::whereMonth('tanggal_transaksi', $date->month)
                        ->whereYear('tanggal_transaksi', $date->year)
                        ->where('status', 'completed')
                        ->sum('total') ?? 0;
                    
                    $data->push([
                        'label' => $date->format('M Y'),
                        'transaksi' => $total,
                        'omzet' => $omzet
                    ]);
                }
            } else {
                // Daily data (default)
                $data = collect();
                $startDate = Carbon::now()->subDays(29);
                
                for ($i = 0; $i < 30; $i++) {
                    $date = $startDate->copy()->addDays($i);
                    $total = MasterTransaksi::whereDate('tanggal_transaksi', $date->format('Y-m-d'))
                        ->count();
                    
                    $omzet = MasterTransaksi::whereDate('tanggal_transaksi', $date->format('Y-m-d'))
                        ->where('status', 'completed')
                        ->sum('total') ?? 0;
                    
                    $data->push([
                        'label' => $date->format('d/m'),
                        'transaksi' => $total,
                        'omzet' => $omzet
                    ]);
                }
            }

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memuat data chart',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get top products/menu performance
     */
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