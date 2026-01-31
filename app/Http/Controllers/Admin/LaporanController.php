<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterTransaksi;
use App\Models\Payment;
use App\Models\DetailTransaksi;
use App\Models\User;
use App\Models\Produk;
use App\Models\MasterMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default periode (bulan ini)
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $periode = $request->get('periode', 'monthly');

        // Summary Cards Data
        $summaryData = $this->getSummaryData($startDate, $endDate);
        
        // Chart Data
        $chartData = $this->getChartData($startDate, $endDate, $periode);
        
        // Top Products/Menus
        $topProducts = $this->getTopProducts($startDate, $endDate);
        
        // Recent Transactions
        $recentTransactions = $this->getRecentTransactions();

        return view('admin.laporan.index', compact(
            'summaryData', 
            'chartData', 
            'topProducts', 
            'recentTransactions',
            'startDate',
            'endDate',
            'periode'
        ));
    }

    public function penjualan(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $groupBy = $request->get('group_by', 'daily');

        // Sales Data
        $salesData = $this->getSalesData($startDate, $endDate, $groupBy);
        
        // Payment Method Analysis
        $paymentMethods = $this->getPaymentMethodAnalysis($startDate, $endDate);
        
        // Status Analysis
        $statusAnalysis = $this->getStatusAnalysis($startDate, $endDate);

        if ($request->get('export') === 'pdf') {
            return $this->exportPenjualanPDF($salesData, $startDate, $endDate);
        }

        return view('admin.laporan.penjualan', compact(
            'salesData',
            'paymentMethods',
            'statusAnalysis',
            'startDate',
            'endDate',
            'groupBy'
        ));
    }

    public function produk(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $kategori = $request->get('kategori', 'all');

        // Product Performance
        $productPerformance = $this->getProductPerformance($startDate, $endDate, $kategori);
        
        // Category Analysis
        $categoryAnalysis = $this->getCategoryAnalysis($startDate, $endDate);
        
        // Menu Performance
        $menuPerformance = $this->getMenuPerformance($startDate, $endDate);

        if ($request->get('export') === 'pdf') {
            return $this->exportProdukPDF($productPerformance, $startDate, $endDate);
        }

        return view('admin.laporan.produk', compact(
            'productPerformance',
            'categoryAnalysis',
            'menuPerformance',
            'startDate',
            'endDate',
            'kategori'
        ));
    }

    public function customer(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Top Customers
        $topCustomers = $this->getTopCustomers($startDate, $endDate);
        
        // Customer Analysis
        $customerAnalysis = $this->getCustomerAnalysis($startDate, $endDate);
        
        // New vs Returning Customers
        $customerSegmentation = $this->getCustomerSegmentation($startDate, $endDate);

        if ($request->get('export') === 'pdf') {
            return $this->exportCustomerPDF($topCustomers, $customerAnalysis, $startDate, $endDate);
        }

        return view('admin.laporan.customer', compact(
            'topCustomers',
            'customerAnalysis',
            'customerSegmentation',
            'startDate',
            'endDate'
        ));
    }

    public function pembayaran(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Payment Summary
        $paymentSummary = $this->getPaymentSummary($startDate, $endDate);
        
        // Outstanding Payments
        $outstandingPayments = $this->getOutstandingPayments();
        
        // Payment Trends
        $paymentTrends = $this->getPaymentTrends($startDate, $endDate);

        if ($request->get('export') === 'pdf') {
            return $this->exportPembayaranPDF($paymentSummary, $startDate, $endDate);
        }

        return view('admin.laporan.pembayaran', compact(
            'paymentSummary',
            'outstandingPayments',
            'paymentTrends',
            'startDate',
            'endDate'
        ));
    }

    public function operasional(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Operational Summary
        $operationalSummary = $this->getOperationalSummary($startDate, $endDate);
        
        // Event Schedule
        $eventSchedule = $this->getEventSchedule($startDate, $endDate);
        
        // Area Analysis
        $areaAnalysis = $this->getAreaAnalysis($startDate, $endDate);

        return view('admin.laporan.operasional', compact(
            'operationalSummary',
            'eventSchedule',
            'areaAnalysis',
            'startDate',
            'endDate'
        ));
    }

    // Helper Methods
    private function getSummaryData($startDate, $endDate)
    {
        return [
            'total_revenue' => MasterTransaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])->sum('total') ?? 0,
            'total_transactions' => MasterTransaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])->count(),
            'completed_transactions' => MasterTransaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])->where('status', 'completed')->count(),
            'pending_transactions' => MasterTransaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])->where('status', 'pending')->count(),
            'average_transaction' => MasterTransaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])->avg('total') ?? 0,
            'total_customers' => MasterTransaksi::whereBetween('tanggal_transaksi', [$startDate, $endDate])->distinct('id_customer')->count(),
        ];
    }

    private function getChartData($startDate, $endDate, $periode)
    {
        $format = match($periode) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            'yearly' => '%Y',
            default => '%Y-%m-%d'
        };

        return MasterTransaksi::selectRaw("
                DATE_FORMAT(tanggal_transaksi, '{$format}') as period,
                COALESCE(SUM(total), 0) as revenue,
                COUNT(*) as transactions
            ")
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    private function getTopProducts($startDate, $endDate, $limit = 5)
    {
        // Gabungkan data produk dan menu
        $produkData = DetailTransaksi::select([
                DB::raw('COALESCE(produk.nama_produk, "Unknown Product") as nama_item'),
                DB::raw('"produk" as tipe'),
                DB::raw('SUM(detail_transaksi.qty) as total_qty'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_revenue')
            ])
            ->leftJoin('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->leftJoin('master_transaksi', 'detail_transaksi.id_transaksi', '=', 'master_transaksi.id_transaksi')
            ->whereBetween('master_transaksi.tanggal_transaksi', [$startDate, $endDate])
            ->whereNotNull('detail_transaksi.id_produk')
            ->groupBy('detail_transaksi.id_produk', 'produk.nama_produk')
            ->orderBy('total_revenue', 'desc');

        $menuData = DetailTransaksi::select([
                DB::raw('COALESCE(master_menu.nama_menu, "Unknown Menu") as nama_item'),
                DB::raw('"menu" as tipe'),
                DB::raw('SUM(detail_transaksi.qty) as total_qty'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_revenue')
            ])
            ->leftJoin('master_menu', 'detail_transaksi.id_menu', '=', 'master_menu.id_menu')
            ->leftJoin('master_transaksi', 'detail_transaksi.id_transaksi', '=', 'master_transaksi.id_transaksi')
            ->whereBetween('master_transaksi.tanggal_transaksi', [$startDate, $endDate])
            ->whereNotNull('detail_transaksi.id_menu')
            ->groupBy('detail_transaksi.id_menu', 'master_menu.nama_menu')
            ->orderBy('total_revenue', 'desc');

        // Union dan ambil top items
        $results = $produkData->union($menuData)
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get();

        // Transform untuk compatibility dengan view
        return $results->map(function($item) {
            return (object)[
                'nama_produk' => $item->tipe === 'produk' ? $item->nama_item : null,
                'nama_menu' => $item->tipe === 'menu' ? $item->nama_item : null,
                'total_qty' => $item->total_qty,
                'total_revenue' => $item->total_revenue
            ];
        });
    }

    private function getRecentTransactions($limit = 10)
    {
        return MasterTransaksi::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getSalesData($startDate, $endDate, $groupBy)
    {
        $format = match($groupBy) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            default => '%Y-%m-%d'
        };

        return MasterTransaksi::selectRaw("
                DATE_FORMAT(tanggal_transaksi, '{$format}') as period,
                COALESCE(SUM(total), 0) as revenue,
                COUNT(*) as transactions,
                COALESCE(AVG(total), 0) as avg_transaction
            ")
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    private function getPaymentMethodAnalysis($startDate, $endDate)
    {
        return Payment::selectRaw('
                method,
                COUNT(*) as count,
                COALESCE(SUM(amount), 0) as total_amount
            ')
            ->whereHas('masterTransaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })
            ->where('payment_status', 'paid')
            ->groupBy('method')
            ->get();
    }

    private function getStatusAnalysis($startDate, $endDate)
    {
        return MasterTransaksi::selectRaw('
                status,
                COUNT(*) as count,
                COALESCE(SUM(total), 0) as total_amount
            ')
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('status')
            ->get();
    }

    private function getProductPerformance($startDate, $endDate, $kategori)
    {
        $query = DetailTransaksi::select([
                'produk.nama_produk',
                'produk.kategori_produk',
                'produk.harga',
                DB::raw('SUM(detail_transaksi.qty) as total_qty'),
                DB::raw('COALESCE(SUM(detail_transaksi.subtotal), 0) as total_revenue'),
                DB::raw('COUNT(DISTINCT detail_transaksi.id_transaksi) as total_orders')
            ])
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->join('master_transaksi', 'detail_transaksi.id_transaksi', '=', 'master_transaksi.id_transaksi')
            ->whereBetween('master_transaksi.tanggal_transaksi', [$startDate, $endDate])
            ->whereNotNull('detail_transaksi.id_produk');

        if ($kategori !== 'all') {
            $query->where('produk.kategori_produk', $kategori);
        }

        return $query->groupBy('detail_transaksi.id_produk', 'produk.nama_produk', 'produk.kategori_produk', 'produk.harga')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    private function getCategoryAnalysis($startDate, $endDate)
    {
        return DetailTransaksi::select([
                'produk.kategori_produk',
                DB::raw('SUM(detail_transaksi.qty) as total_qty'),
                DB::raw('COALESCE(SUM(detail_transaksi.subtotal), 0) as total_revenue'),
                DB::raw('COUNT(DISTINCT detail_transaksi.id_transaksi) as total_orders')
            ])
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id_produk')
            ->join('master_transaksi', 'detail_transaksi.id_transaksi', '=', 'master_transaksi.id_transaksi')
            ->whereBetween('master_transaksi.tanggal_transaksi', [$startDate, $endDate])
            ->whereNotNull('detail_transaksi.id_produk')
            ->groupBy('produk.kategori_produk')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    private function getMenuPerformance($startDate, $endDate)
    {
        return DetailTransaksi::select([
                'master_menu.nama_menu',
                'master_menu.kategori_menu',
                'master_menu.harga_satuan',
                DB::raw('SUM(detail_transaksi.qty) as total_qty'),
                DB::raw('COALESCE(SUM(detail_transaksi.subtotal), 0) as total_revenue'),
                DB::raw('COUNT(DISTINCT detail_transaksi.id_transaksi) as total_orders')
            ])
            ->join('master_menu', 'detail_transaksi.id_menu', '=', 'master_menu.id_menu')
            ->join('master_transaksi', 'detail_transaksi.id_transaksi', '=', 'master_transaksi.id_transaksi')
            ->whereBetween('master_transaksi.tanggal_transaksi', [$startDate, $endDate])
            ->whereNotNull('detail_transaksi.id_menu')
            ->groupBy('detail_transaksi.id_menu', 'master_menu.nama_menu', 'master_menu.kategori_menu', 'master_menu.harga_satuan')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    private function getTopCustomers($startDate, $endDate, $limit = 20)
    {
        return User::select([
                'users.id',
                'users.nama',
                'users.no_hp',
                'users.email',
                DB::raw('COUNT(master_transaksi.id_transaksi) as total_orders'),
                DB::raw('COALESCE(SUM(master_transaksi.total), 0) as total_spent'),
                DB::raw('COALESCE(AVG(master_transaksi.total), 0) as avg_order_value'),
                DB::raw('MAX(master_transaksi.tanggal_transaksi) as last_order_date')
            ])
            ->join('master_transaksi', 'users.id', '=', 'master_transaksi.id_customer')
            ->whereBetween('master_transaksi.tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('users.id', 'users.nama', 'users.no_hp', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getCustomerAnalysis($startDate, $endDate)
    {
        $totalCustomers = User::whereHas('transaksi', function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        })->count();

        $newCustomers = User::whereHas('transaksi', function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        })->whereDoesntHave('transaksi', function($q) use ($startDate) {
            $q->where('tanggal_transaksi', '<', $startDate);
        })->count();

        $repeatCustomers = User::whereHas('transaksi', function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        }, '>', 1)->count();

        return [
            'total_customers' => $totalCustomers,
            'new_customers' => $newCustomers,
            'repeat_customers' => $repeatCustomers,
        ];
    }

    private function getCustomerSegmentation($startDate, $endDate)
    {
        $segmentData = User::selectRaw('
                users.id,
                COUNT(master_transaksi.id_transaksi) as transaction_count,
                COALESCE(SUM(master_transaksi.total), 0) as total_revenue,
                CASE 
                    WHEN COUNT(master_transaksi.id_transaksi) = 1 THEN "New Customer"
                    WHEN COUNT(master_transaksi.id_transaksi) BETWEEN 2 AND 5 THEN "Regular Customer"
                    WHEN COUNT(master_transaksi.id_transaksi) > 5 THEN "VIP Customer"
                    ELSE "Unknown"
                END as segment
            ')
            ->join('master_transaksi', 'users.id', '=', 'master_transaksi.id_customer')
            ->whereBetween('master_transaksi.tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('users.id')
            ->get();

        return $segmentData->groupBy('segment')->map(function($group) {
            return [
                'customer_count' => $group->count(),
                'total_revenue' => $group->sum('total_revenue')
            ];
        });
    }

    private function getPaymentSummary($startDate, $endDate)
    {
        $totalPayments = Payment::whereHas('masterTransaction', function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
        })->sum('amount') ?? 0;

        $paidPayments = Payment::where('payment_status', 'paid')
            ->whereHas('masterTransaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })->sum('amount') ?? 0;

        $pendingPayments = Payment::where('payment_status', 'pending')
            ->whereHas('masterTransaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })->sum('amount') ?? 0;

        $dpPayments = Payment::where('type', 'dp')
            ->where('payment_status', 'paid')
            ->whereHas('masterTransaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })->sum('amount') ?? 0;

        $fullPayments = Payment::where('type', 'full')
            ->where('payment_status', 'paid')
            ->whereHas('masterTransaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })->sum('amount') ?? 0;

        return [
            'total_payments' => $totalPayments,
            'paid_payments' => $paidPayments,
            'pending_payments' => $pendingPayments,
            'dp_payments' => $dpPayments,
            'full_payments' => $fullPayments,
        ];
    }

    private function getOutstandingPayments()
    {
        return MasterTransaksi::with(['customer', 'payments'])
            ->where(function($query) {
                $query->whereHas('payments', function($q) {
                    $q->where('payment_status', 'pending');
                })->orWhereDoesntHave('payments');
            })
            ->orderBy('tanggal_acara', 'asc')
            ->limit(20)
            ->get();
    }

    private function getPaymentTrends($startDate, $endDate)
    {
        return Payment::selectRaw('
                DATE(created_at) as date,
                method,
                type,
                COUNT(*) as count,
                COALESCE(SUM(amount), 0) as total_amount
            ')
            ->whereHas('masterTransaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            })
            ->where('payment_status', 'paid')
            ->groupBy('date', 'method', 'type')
            ->orderBy('date')
            ->get();
    }

    private function getOperationalSummary($startDate, $endDate)
    {
        return MasterTransaksi::selectRaw('
                status,
                COUNT(*) as count,
                COALESCE(SUM(total), 0) as total_amount
            ')
            ->whereBetween('tanggal_acara', [$startDate, $endDate])
            ->groupBy('status')
            ->get();
    }

    private function getEventSchedule($startDate, $endDate)
    {
        return MasterTransaksi::with('customer')
            ->whereBetween('tanggal_acara', [$startDate, $endDate])
            ->whereIn('status', ['confirmed', 'preparing', 'ready'])
            ->orderBy('tanggal_acara', 'asc')
            ->orderBy('waktu_acara', 'asc')
            ->get();
    }

    private function getAreaAnalysis($startDate, $endDate)
    {
        return MasterTransaksi::selectRaw('
                alamat_pengiriman,
                COUNT(*) as total_orders,
                COALESCE(SUM(total), 0) as total_revenue
            ')
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('alamat_pengiriman')
            ->orderBy('total_orders', 'desc')
            ->limit(10)
            ->get();
    }

    // Export Methods
    private function exportPenjualanPDF($salesData, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('admin.laporan.pdf.penjualan', compact('salesData', 'startDate', 'endDate'));
        return $pdf->download('laporan-penjualan-' . $startDate . '-to-' . $endDate . '.pdf');
    }

    private function exportProdukPDF($productPerformance, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('admin.laporan.pdf.produk', compact('productPerformance', 'startDate', 'endDate'));
        return $pdf->download('laporan-produk-' . $startDate . '-to-' . $endDate . '.pdf');
    }

    private function exportCustomerPDF($topCustomers, $customerAnalysis, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('admin.laporan.pdf.customer', compact('topCustomers', 'customerAnalysis', 'startDate', 'endDate'));
        return $pdf->download('laporan-customer-' . $startDate . '-to-' . $endDate . '.pdf');
    }

    private function exportPembayaranPDF($paymentSummary, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('admin.laporan.pdf.pembayaran', compact('paymentSummary', 'startDate', 'endDate'));
        return $pdf->download('laporan-pembayaran-' . $startDate . '-to-' . $endDate . '.pdf');
    }
}