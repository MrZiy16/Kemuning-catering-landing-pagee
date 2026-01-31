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
    $customers = Customer::where('source', ['offline','online'])->orderBy('nama')->get();
    $produk = Produk::all();
    
    // Get existing transactions for the modal
    $transactions = MasterTransaksi::with('customer')
                                  ->where('status', '!=', 'completed')
                                  ->where('status', '!=', 'cancelled')
                                  ->orderBy('tanggal_transaksi', 'desc')
                                  ->get();

    // ========== PEMBEDAAN ROLE ==========
    $query = Payment::with(['masterTransaction.customer'])
                    ->orderBy('created_at', 'desc');

    // Jika ADMIN (bukan super_admin), filter hanya transaksi ACTIVE
    if (auth()->user()->role === 'admin') {
        $query->whereHas('masterTransaction', function($q) {
            $q->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
        });
    }
    // Jika SUPER_ADMIN, lihat SEMUA pembayaran

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

    // Dashboard statistics - SESUAIKAN DENGAN ROLE
    $today = Carbon::today();
    
    if (auth()->user()->role === 'admin') {
        // Admin hanya lihat stats dari transaksi active
        $stats = [
            'today_total' => Payment::paid()
                ->whereDate('paid_at', $today)
                ->whereHas('masterTransaction', function($q) {
                    $q->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
                })
                ->sum('amount'),
            'today_count' => Payment::paid()
                ->whereDate('paid_at', $today)
                ->whereHas('masterTransaction', function($q) {
                    $q->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
                })
                ->count(),
            'pending_count' => Payment::pending()
                ->whereHas('masterTransaction', function($q) {
                    $q->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
                })
                ->count(),
            'monthly_total' => Payment::paid()
                ->whereMonth('paid_at', now()->month)
                ->whereHas('masterTransaction', function($q) {
                    $q->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
                })
                ->sum('amount'),
            'status_counts' => [
                'paid' => Payment::paid()
                    ->whereHas('masterTransaction', function($q) {
                        $q->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
                    })
                    ->count(),
                'pending' => Payment::pending()
                    ->whereHas('masterTransaction', function($q) {
                        $q->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
                    })
                    ->count(),
                'failed' => Payment::failed()
                    ->whereHas('masterTransaction', function($q) {
                        $q->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivered']);
                    })
                    ->count(),
            ]
        ];
    } else {
        // Super admin lihat SEMUA stats
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
    }

    return view('admin.payments.index', compact('payments','stats','customers','produk','transactions'));
}
 // Tambahkan ini di PaymentsController.php method store()

public function store(Request $request)
{
    // DEBUG 1: Log semua request data
    \Log::info('=== PAYMENT STORE DEBUG ===');
    \Log::info('Request all data:', $request->all());
    
    $request->validate([
        'id_customer' => 'required|exists:customer,id_customer',
        'existing_transaction' => 'nullable|exists:master_transaksi,id_transaksi',
        'amount' => 'required|numeric|min:1000',
        'payment_type' => 'required|in:full,dp',
        'payment_date' => 'required|date',
        'payment_status' => 'required|in:pending,paid,remaining,failed,cancelled',
        'reference_number' => 'nullable|string|max:100',
        'notes' => 'nullable|string|max:500',
        'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        'tanggal_acara' => 'nullable|date',
        'waktu_acara' => 'nullable|string',
        'alamat_pengiriman' => 'nullable|string',
        'items' => 'nullable|array',
        'items.*.produk_id' => 'nullable|exists:produk,id_produk',
        'items.*.qty' => 'nullable|integer|min:1',
    ]);
    
    \Log::info('Validation passed');

    DB::beginTransaction();
    try {
        \Log::info('Starting transaction...');
        
        $transaction = null;
        
        if ($request->filled('existing_transaction')) {
            \Log::info('Using existing transaction: ' . $request->existing_transaction);
            $transaction = MasterTransaksi::findOrFail($request->existing_transaction);
            
            $validation = $this->validatePaymentAmount($transaction, $request->amount, $request->payment_type);
            if (!$validation['valid']) {
                \Log::warning('Payment amount validation failed: ' . $validation['message']);
                return redirect()->back()->with('error', $validation['message']);
            }
            
        } else {
            \Log::info('Creating new transaction...');
            
            if (!$request->filled(['tanggal_acara', 'waktu_acara', 'alamat_pengiriman', 'items'])) {
                \Log::warning('New transaction data incomplete');
                return redirect()->back()->with('error', 'Data transaksi baru harus lengkap');
            }
            
            $transaction = $this->createNewTransaction($request);
            \Log::info('New transaction created: ' . $transaction->id_transaksi);
        }

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('payments/proof', 'public');
            \Log::info('File uploaded: ' . $proofPath);
        }

        // DEBUG 2: Log payment data sebelum save
        $paymentData = [
            'master_transaction_id' => $transaction->id_transaksi,
            'method' => 'offline',
            'type' => $request->payment_type,
            'amount' => $request->amount,
            'payment_status' => $request->payment_status,
            'proof_file' => $proofPath,
            'paid_at' => $request->payment_status === 'paid' ? Carbon::parse($request->payment_date) : null,
            'midtrans_status' => json_encode([
                'payment_method' => $request->payment_method ?? 'manual',
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'created_by' => auth()->user()->nama ?? 'Admin'
            ])
        ];
        
        \Log::info('Payment data to save:', $paymentData);
        
        $payment = Payment::create($paymentData);
        \Log::info('Payment created with ID: ' . $payment->id);

        if ($request->payment_status === 'paid' && $transaction->getIsFullyPaidAttribute()) {
            $transaction->update(['status' => 'confirmed']);
            \Log::info('Transaction status updated to confirmed');
            
            StatusLog::create([
                'id_transaksi' => $transaction->id_transaksi,
                'status_from' => $transaction->status,
                'status_to' => 'confirmed',
                'keterangan' => 'Pembayaran manual dikonfirmasi oleh admin',
                'created_by' => auth()->id()
            ]);
        }

        DB::commit();
        \Log::info('=== PAYMENT SAVED SUCCESSFULLY ===');
        
        return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan!');
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Payment save error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
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
           'id_customer' => $request->id_customer,
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
        
        // Ambil dari Customer, join dengan users jika perlu data email/no_hp dari users
        $customers = Customer::with('user') // Asumsi relasi 'user' di model Customer: public function user() { return $this->belongsTo(User::class, 'user_id'); }
                             ->where('nama', 'like', "%{$query}%")
                             ->orWhereHas('user', function($subQ) use ($query) {
                                 $subQ->where('email', 'like', "%{$query}%")
                                      ->orWhere('no_hp', 'like', "%{$query}%");
                             })
                             ->limit(20)
                             ->get(['id_customer as id', 'nama', 'email', 'no_hp']); // Gunakan id_customer sebagai id
        
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
