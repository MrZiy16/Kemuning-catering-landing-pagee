<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\MasterTransaksi;
use App\Models\DetailTransaksi;
use App\Models\Customer;
use App\Models\StatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Produk;
use App\Models\MasterMenu;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select('id','nama','email')->get();
        $produk = Produk::all();
        
        // Get existing transactions for the modal
        $transactions = MasterTransaksi::with('customer')
                                      ->where('status', '!=', 'completed')
                                      ->where('status', '!=', 'cancelled')
                                      ->orderBy('tanggal_transaksi', 'desc')
                                      ->get();

        $query = Payment::with(['masterTransaction.customer'])
                        ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Filter by method
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('master_transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('masterTransaction.customer', function($subQ) use ($search) {
                      $subQ->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->paginate(20);

        // Dashboard statistics
        $today = Carbon::today();
        $stats = [
            'today_total' => Payment::paid()->whereDate('paid_at', $today)->sum('amount'),
            'today_count' => Payment::paid()->whereDate('paid_at', $today)->count(),
            'pending_count' => Payment::pending()->count(),
            'monthly_total' => Payment::paid()->whereMonth('paid_at', now()->month)->sum('amount'),
            'status_counts' => [
                'paid' => Payment::paid()->count(),
                'pending' => Payment::pending()->count(),
                'failed' => Payment::failed()->count(),
            ]
        ];

        return view('admin.payments.index', compact('payments','stats','users','produk','transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'existing_transaction' => 'nullable|exists:master_transaksi,id_transaksi',
            'amount' => 'required|numeric|min:1000',
            'payment_type' => 'required|in:full,dp',
            'payment_method' => 'required|in:cash,bank_transfer,debit_card,credit_card,e_wallet',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            // Optional fields for new transaction
            'tanggal_acara' => 'nullable|date',
            'waktu_acara' => 'nullable|string',
            'alamat_pengiriman' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.produk_id' => 'nullable|exists:produk,id_produk',
            'items.*.qty' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $transaction = null;
            
            // Check if this is for existing transaction or new transaction
            if ($request->filled('existing_transaction')) {
                // Payment for existing transaction
                $transaction = MasterTransaksi::findOrFail($request->existing_transaction);
                
                // Validate payment amount based on transaction and payment type
                $validation = $this->validatePaymentAmount($transaction, $request->amount, $request->payment_type);
                if (!$validation['valid']) {
                    return redirect()->back()->with('error', $validation['message']);
                }
                
            } else {
                // Create new transaction with payment
                if (!$request->filled(['tanggal_acara', 'waktu_acara', 'alamat_pengiriman', 'items'])) {
                    return redirect()->back()->with('error', 'Data transaksi baru harus lengkap');
                }
                
                $transaction = $this->createNewTransaction($request);
            }

            // Create payment record
            $payment = Payment::create([
                'master_transaction_id' => $transaction->id_transaksi,
                'method' => 'offline', // Manual payment is always offline
                'type' => $request->payment_type,
                'amount' => $request->amount,
                'payment_status' => 'paid', // Manual payment is immediately marked as paid
                'paid_at' => Carbon::parse($request->payment_date),
                'midtrans_status' => json_encode([
                    'payment_method' => $request->payment_method,
                    'reference_number' => $request->reference_number,
                    'notes' => $request->notes,
                    'created_by' => auth()->user()->nama ?? 'Admin'
                ])
            ]);

            // Update transaction status if fully paid
            if ($transaction->getIsFullyPaidAttribute()) {
                $transaction->update(['status' => 'confirmed']);
                
                StatusLog::create([
                    'id_transaksi' => $transaction->id_transaksi,
                    'status_from' => $transaction->status,
                    'status_to' => 'confirmed',
                    'keterangan' => 'Pembayaran manual dikonfirmasi oleh admin',
                    'created_by' => auth()->id()
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage());
        }
    }

    private function validatePaymentAmount($transaction, $amount, $type)
    {
        if ($type === 'dp') {
            return $transaction->validateDownPaymentAmount($amount);
        } else {
            return $transaction->validateFullPaymentAmount($amount);
        }
    }

    private function createNewTransaction(Request $request)
    {
        // Generate transaction ID
        $transactionId = 'TRX-' . date('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        // Calculate total from items
        $total = 0;
        if ($request->filled('items')) {
            foreach ($request->items as $item) {
                if (!empty($item['produk_id']) && !empty($item['qty'])) {
                    $produk = Produk::find($item['produk_id']);
                    if ($produk) {
                        $total += $produk->harga * $item['qty'];
                    }
                }
            }
        }
        
        // If no items provided, use the payment amount as total
        if ($total == 0) {
            $total = $request->amount;
        }

        // Create master transaction
        $transaction = MasterTransaksi::create([
            'id_transaksi' => $transactionId,
            'id_customer' => $request->customer_id,
            'tanggal_transaksi' => now()->toDateString(),
            'tanggal_acara' => $request->tanggal_acara,
            'waktu_acara' => $request->waktu_acara,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'total' => $total,
            'status' => 'draft',
            'catatan_admin' => $request->notes
        ]);

        // Create detail transactions if items provided
        if ($request->filled('items')) {
            foreach ($request->items as $item) {
                if (!empty($item['produk_id']) && !empty($item['qty'])) {
                    $produk = Produk::find($item['produk_id']);
                    if ($produk) {
                        DetailTransaksi::create([
                            'id_transaksi' => $transaction->id_transaksi,
                            'id_produk' => $item['produk_id'],
                            'id_menu' => null,
                            'qty' => $item['qty'],
                            'harga' => $produk->harga,
                            'subtotal' => $produk->harga * $item['qty']
                        ]);
                    }
                }
            }
        }

        return $transaction;
    }


    public function searchCustomers(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $customers = User::where('nama', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%")
                            ->orWhere('no_hp', 'like', "%{$query}%")
                            ->limit(20)
                            ->get(['id', 'nama', 'email', 'no_hp']);
        
        return response()->json($customers);
    }

    public function getTransactionDetails($transactionId)
    {
        try {
            $transaction = MasterTransaksi::with(['customer', 'payments'])
                                         ->findOrFail($transactionId);
            
            $paymentSummary = $transaction->getPaymentSummary();
            
            return response()->json([
                'success' => true,
                'transaction' => [
                    'id' => $transaction->id_transaksi,
                    'customer_name' => $transaction->customer->nama,
                    'total' => $transaction->total,
                    'formatted_total' => $transaction->getFormattedTotalAttribute(),
                    'total_paid' => $paymentSummary['total_paid'],
                    'formatted_total_paid' => $transaction->getFormattedTotalPaidAttribute(),
                    'remaining' => $paymentSummary['remaining'],
                    'formatted_remaining' => $transaction->getFormattedRemainingAmountAttribute(),
                    'is_fully_paid' => $paymentSummary['is_fully_paid'],
                    'has_dp' => $paymentSummary['has_dp'],
                    'can_dp' => $paymentSummary['can_dp'],
                    'can_full' => $paymentSummary['can_full'],
                    'can_remainder' => $paymentSummary['can_remainder'],
                    'min_dp' => $paymentSummary['min_dp'],
                    'formatted_min_dp' => 'Rp ' . number_format($paymentSummary['min_dp'], 0, ',', '.'),
                    'payment_status' => $paymentSummary['payment_status'],
                    'tanggal_acara' => $transaction->tanggal_acara->format('d/m/Y'),
                    'status' => $transaction->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }
    }

    public function show($id)
    {
        $payment = Payment::with(['masterTransaction.customer', 'masterTransaction.detailTransaksi.produk', 
                                'masterTransaction.detailTransaksi.menu', 'masterTransaction.payments'])
                          ->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    public function confirm(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $payment = Payment::findOrFail($id);
            
            // Validasi hanya bisa konfirmasi payment offline yang pending
            if ($payment->payment_status !== 'pending') {
                return redirect()->back()->with('error', 'Payment tidak dapat dikonfirmasi');
            }

            // Update payment status
            $payment->update([
                'payment_status' => 'paid',
                'paid_at' => now()
            ]);

            // Update transaction status jika sudah lunas
            $transaction = $payment->masterTransaction;
            if ($transaction->getIsFullyPaidAttribute()) {
                $transaction->update(['status' => 'confirmed']);
                
                // Log status change
                StatusLog::create([
                    'id_transaksi' => $transaction->id_transaksi,
                    'status_from' => $transaction->status,
                    'status_to' => 'confirmed',
                    'keterangan' => 'Pembayaran dikonfirmasi oleh admin',
                    'created_by' => auth()->id()
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal konfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $payment = Payment::findOrFail($id);
            
            if ($payment->payment_status !== 'pending') {
                return redirect()->back()->with('error', 'Payment tidak dapat ditolak');
            }

            $payment->update([
                'payment_status' => 'failed',
                'midtrans_status' => json_encode(['rejection_reason' => $request->reason])
            ]);

             $transaction = $payment->masterTransaction;
           
                $transaction->update(['status' => 'cancelled']);
                
                // Log status change
                StatusLog::create([
                    'id_transaksi' => $transaction->id_transaksi,
                    'status_from' => $transaction->status,
                    'status_to' => 'confirmed',
                    'keterangan' => 'Pembayaran dikonfirmasi oleh admin',
                    'created_by' => auth()->id()
                ]);
            

            DB::commit();

            return redirect()->back()->with('success', 'Pembayaran berhasil ditolak');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    public function bulkConfirm(Request $request)
    {
        $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'exists:payments,id'
        ]);

        try {
            DB::beginTransaction();

            $payments = Payment::whereIn('id', $request->payment_ids)
                              ->where('method', 'offline')
                              ->where('payment_status', 'pending')
                              ->get();

            $confirmed = 0;
            foreach ($payments as $payment) {
                $payment->update([
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);

                // Update transaction status if fully paid
                $transaction = $payment->masterTransaction;
                if ($transaction->getIsFullyPaidAttribute() && $transaction->status === 'draft') {
                    $transaction->update(['status' => 'confirmed']);
                    
                    StatusLog::create([
                        'id_transaksi' => $transaction->id_transaksi,
                        'status_from' => 'draft',
                        'status_to' => 'confirmed',
                        'keterangan' => 'Pembayaran dikonfirmasi oleh admin (bulk)',
                        'created_by' => auth()->id()
                    ]);
                }

                $confirmed++;
            }

            DB::commit();

            return redirect()->back()->with('success', "Berhasil konfirmasi {$confirmed} pembayaran");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal konfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    public function analytics()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $analytics = [
            'daily' => [
                'today_revenue' => Payment::paid()->whereDate('paid_at', $today)->sum('amount'),
                'today_count' => Payment::paid()->whereDate('paid_at', $today)->count(),
                'yesterday_revenue' => Payment::paid()->whereDate('paid_at', $today->copy()->subDay())->sum('amount'),
                'yesterday_count' => Payment::paid()->whereDate('paid_at', $today->copy()->subDay())->count(),
            ],
            'monthly' => [
                'this_month_revenue' => Payment::paid()->where('paid_at', '>=', $thisMonth)->sum('amount'),
                'this_month_count' => Payment::paid()->where('paid_at', '>=', $thisMonth)->count(),
                'last_month_revenue' => Payment::paid()
                    ->whereBetween('paid_at', [$lastMonth, $thisMonth->copy()->subSecond()])
                    ->sum('amount'),
            ],
            'status_distribution' => [
                'paid' => Payment::paid()->count(),
                'pending' => Payment::pending()->count(),
                'failed' => Payment::failed()->count(),
            ],
            'method_distribution' => [
                'online' => Payment::online()->count(),
                'offline' => Payment::offline()->count(),
            ],
            'type_distribution' => [
                'full' => Payment::fullPayment()->count(),
                'dp' => Payment::downPayment()->count(),
            ],
            'weekly_revenue' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $analytics['weekly_revenue'][] = [
                'date' => $date->format('Y-m-d'),
                'revenue' => Payment::paid()->whereDate('paid_at', $date)->sum('amount')
            ];
        }

        // Derived metrics (supaya blade tidak perlu menghitung lagi)
        $total_methods = $analytics['method_distribution']['online'] + $analytics['method_distribution']['offline'];
        $online_percentage = $total_methods > 0 ? ($analytics['method_distribution']['online'] / $total_methods) * 100 : 0;
        $offline_percentage = $total_methods > 0 ? ($analytics['method_distribution']['offline'] / $total_methods) * 100 : 0;

        $total_types = $analytics['type_distribution']['full'] + $analytics['type_distribution']['dp'];
        $full_percentage = $total_types > 0 ? ($analytics['type_distribution']['full'] / $total_types) * 100 : 0;
        $dp_percentage = $total_types > 0 ? ($analytics['type_distribution']['dp'] / $total_types) * 100 : 0;

        $total_payments = array_sum($analytics['status_distribution']);
        $success_rate = $total_payments > 0 ? ($analytics['status_distribution']['paid'] / $total_payments) * 100 : 0;

        $yesterday_revenue = $analytics['daily']['yesterday_revenue'] ?? 0;
        $today_revenue = $analytics['daily']['today_revenue'] ?? 0;
        $growth = $yesterday_revenue > 0 ? (($today_revenue - $yesterday_revenue) / $yesterday_revenue) * 100 : 0;

        $last_month_revenue = $analytics['monthly']['last_month_revenue'] ?? 0;
        $this_month_revenue = $analytics['monthly']['this_month_revenue'] ?? 0;
        $monthly_growth = $last_month_revenue > 0 ? (($this_month_revenue - $last_month_revenue) / $last_month_revenue) * 100 : 0;

        $avg_transaction = $analytics['monthly']['this_month_count'] > 0
            ? $this_month_revenue / $analytics['monthly']['this_month_count']
            : 0;

        return view('admin.payments.analytics', compact(
            'analytics',
            'online_percentage',
            'offline_percentage',
            'full_percentage',
            'dp_percentage',
            'success_rate',
            'growth',
            'monthly_growth',
            'avg_transaction'
        ));
    }

    public function sendReminder($transactionId)
    {
        try {
            $transaction = MasterTransaksi::with('customer')->findOrFail($transactionId);
            
            // Logic untuk kirim reminder (bisa via email, WhatsApp, dll)
            // Implementasi sesuai kebutuhan
            
            return redirect()->back()->with('success', 'Reminder berhasil dikirim ke customer');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal kirim reminder: ' . $e->getMessage());
        }
    }

    // =============================
    // Edit & Update & Delete
    // =============================
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        // Hanya boleh hapus jika status failed/pending/cancelled
        if (!in_array($payment->payment_status, ['failed', 'pending', 'cancelled'])) {
            return redirect()->back()->with('error', 'Hanya pembayaran dengan status failed/pending/cancelled yang dapat dihapus');
        }

        try {
            $payment->delete();
            return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }
}
