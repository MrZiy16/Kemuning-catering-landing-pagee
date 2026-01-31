<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\MasterTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    // Konfigurasi WhatsApp dan Bank
    private const WHATSAPP_NUMBER = '6282217463605'; // Nomor WhatsApp admin/CS
    private const BANK_ACCOUNTS = [
        'BCA' => [
            'bank' => 'Bank BCA',
            'account_number' => '1234567890',
            'account_name' => 'PT. Catering Sejahtera'
        ],
        'MANDIRI' => [
            'bank' => 'Bank Mandiri',
            'account_number' => '9876543210',
            'account_name' => 'PT. Catering Sejahtera'
        ]
    ];

    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Menampilkan halaman pilihan pembayaran
     * Route: GET /payment/select/{transactionId}
     */
    public function selectPayment($transactionId)
    {
        $transaction = MasterTransaksi::with('payments')->where('id_transaksi', $transactionId)->firstOrFail();
        
          // Ambil customer dari user login
    $customer = Auth::user()->customer;

    if (!$customer || $transaction->id_customer !== $customer->id_customer) {
        abort(403, 'Anda tidak memiliki akses ke transaksi ini');
    }

    // Cek status transaksi
    if (in_array($transaction->status, ['completed', 'cancelled'])) {
        return redirect()->back()
            ->with('error', 'Transaksi ini sudah ' . $transaction->status);
    }

        // Hitung pembayaran yang sudah berhasil
        $paidPayments = $transaction->payments()->where('payment_status', 'paid')->get();
        $totalPaid = $paidPayments->sum('amount');
        
        // Cek apakah sudah lunas
        if ($totalPaid >= $transaction->total) {
            return redirect()->back()->with('info', 'Transaksi ini sudah lunas');
        }

        // Hitung sisa pembayaran
        $remainingAmount = $transaction->total - $totalPaid;
        
        // Cek apakah sudah ada DP yang dibayar
        $dpPayment = $paidPayments->where('type', 'dp')->first();
        $hasDP = $dpPayment !== null;
        
        // Hitung minimal DP (35% dari total) - hanya untuk pembayaran baru
        $minDpAmount = $transaction->total * 0.35;

        // Tentukan mode pembayaran berdasarkan kondisi
        $paymentMode = 'new'; // default: pembayaran baru
        
        if ($hasDP) {
            // Sudah ada DP, ini adalah pelunasan
            $paymentMode = 'remaining';
            $availableTypes = ['full']; // Hanya bisa bayar full (sisa)
            $defaultAmount = $remainingAmount;
            $paymentLabel = 'Pelunasan';
        } else if ($totalPaid > 0) {
            // Ada pembayaran partial tapi bukan DP
            $paymentMode = 'remaining'; 
            $availableTypes = ['full'];
            $defaultAmount = $remainingAmount;
            $paymentLabel = 'Sisa Pembayaran';
        } else {
            // Pembayaran pertama kali
            $paymentMode = 'new';
            $availableTypes = ['dp', 'full'];
            $defaultAmount = $minDpAmount;
            $paymentLabel = 'Pembayaran Baru';
        }

        return view('payment.select', compact(
            'transaction', 
            'hasDP', 
            'dpPayment', 
            'remainingAmount', 
            'totalPaid',
            'minDpAmount',
            'paymentMode',
            'availableTypes',
            'defaultAmount',
            'paymentLabel'
        ));
    }

    /**
     * Proses pembayaran offline - redirect ke WhatsApp
     * Route: POST /payment/offline
     */
   public function processOfflinePayment(Request $request)
{
    $request->validate([
        'transaction_id' => 'required|string|exists:master_transaksi,id_transaksi',
        'payment_type' => 'required|in:full,dp',
        'amount' => 'required|numeric|min:0',
    ]);

    try {
        DB::beginTransaction();

        $transaction = MasterTransaksi::where('id_transaksi', $request->transaction_id)->firstOrFail();
        
        // Ambil customer dari user login
        $customer = Auth::user()->customer;

        // Cek kepemilikan transaksi
        if (!$customer || $transaction->id_customer !== $customer->id_customer) {
            throw new \Exception('Anda tidak memiliki akses ke transaksi ini');
        }
        
        // Validasi amount dan status
        $this->validatePaymentRequest($transaction, $request->payment_type, $request->amount);

        // Insert payment record dengan status pending
        $payment = Payment::create([
            'master_transaction_id' => $transaction->id_transaksi,
            'method' => 'offline',
            'type' => $request->payment_type,
            'amount' => $request->amount,
            'payment_status' => 'pending',
        ]);

        DB::commit();

        // Generate WhatsApp message
        $whatsappMessage = $this->generateWhatsAppMessage($transaction, $payment);
        $whatsappUrl = "https://api.whatsapp.com/send?phone=" . self::WHATSAPP_NUMBER . "&text=" . urlencode($whatsappMessage);

        // Redirect ke WhatsApp
        return redirect($whatsappUrl);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Offline payment error: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', $e->getMessage())
            ->withInput();
    }
}
    /**
     * Proses pembayaran online via Midtrans
     * Route: POST /payment/online
     */
public function processOnlinePayment(Request $request)
{
    $request->validate([
        'transaction_id' => 'required|string|exists:master_transaksi,id_transaksi',
        'payment_type' => 'required|in:full,dp',
        'amount' => 'required|numeric|min:0',
    ]);

    try {
        DB::beginTransaction();

        $transaction = MasterTransaksi::where('id_transaksi', $request->transaction_id)->firstOrFail();

        // Cek kepemilikan transaksi
        $customer = Auth::user()->customer;

        if (!$customer || $transaction->id_customer !== $customer->id_customer) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini');
        }
        
        // Validasi amount dan status
        $this->validatePaymentRequest($transaction, $request->payment_type, $request->amount);

        // Amount yang dikirim dari frontend SUDAH termasuk biaya admin
        // Jadi jangan tambah fee lagi di sini!
        $totalAmount = $request->amount;

        // Insert payment record
        $payment = Payment::create([
            'master_transaction_id' => $transaction->id_transaksi,
            'method' => 'online',
            'type' => $request->payment_type,
            'amount' => $totalAmount,
            'payment_status' => 'pending',
        ]);

        // Generate Midtrans Order ID
        $midtransOrderId = $this->generateMidtransOrderId($payment);
        $payment->update(['midtrans_order_id' => $midtransOrderId]);

        // Prepare Midtrans transaction details
        $typeLabel = $this->getPaymentTypeLabel($request->payment_type);

        // Hitung base amount (tanpa fee)
        $baseAmount = $request->amount;
        $adminFee = 0;

        // Jika full payment dan amount > total, maka ada biaya admin
        if ($request->payment_type === 'full' && $request->amount > $transaction->total) {
            $baseAmount = $transaction->total;
            $adminFee = $request->amount - $baseAmount; // Biaya admin = selisihnya
        }

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $totalAmount, // Total yang sudah termasuk fee
            ],
            'customer_details' => [
                'first_name' => $transaction->customer->nama,
                'email' => $transaction->customer->email ?: 'customer@example.com',
                'phone' => $transaction->customer->no_hp,
            ],
            'item_details' => [
                [
                    'id' => $transaction->id_transaksi,
                    'price' => (int) $baseAmount,
                    'quantity' => 1,
                    'name' => $typeLabel . ' - Transaksi ' . $transaction->id_transaksi
                ]
            ],
            'callbacks' => [
                'finish' => route('payment.success', $payment->id),
                'unfinish' => route('payment.failed', $payment->id),
                'error' => route('payment.failed', $payment->id),
            ]
        ];

        // Tambahkan fee item HANYA jika ada biaya admin
        if ($adminFee > 0) {
            $params['item_details'][] = [
                'id' => 'FEE-ADMIN',
                'price' => (int) $adminFee,
                'quantity' => 1,
                'name' => 'Biaya Admin Bank Transfer'
            ];
        }

        $snapToken = Snap::getSnapToken($params);

        DB::commit();

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'payment_id' => $payment->id
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Online payment error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


    /**
     * Halaman sukses pembayaran
     * Route: GET /payment/{payment}/success
     */
public function success($payment)
{
    $payment = Payment::with('masterTransaction.customer')->findOrFail($payment);

    $customer = Auth::user()->customer;

    if (!$customer || $payment->masterTransaction->id_customer !== $customer->id_customer) {
        abort(403);
    }

    return view('payment.success', compact('payment'));
}


    /**
     * Halaman gagal pembayaran
     * Route: GET /payment/{payment}/failed
     */
    public function failed($payment)
    {
        $payment = Payment::with('masterTransaction.customer')->findOrFail($payment);
        
        // Cek kepemilikan
        if ($payment->masterTransaction->id_customer !== Auth::id()) {
            abort(403);
        }

        return view('payment.failed', compact('payment'));
    }

    /**
     * Menampilkan daftar pembayaran pending user
     * Route: GET /payment/my-orders
     */
    public function pending()
    {
        $user = Auth::user();
        
        $payments = Payment::whereHas('masterTransaction', function ($query) use ($user) {
                $query->where('id_customer', $user->id);
            })
            ->where('payment_status', 'pending')
            ->with(['masterTransaction'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payment.pending', compact('payments'));
    }

    /**
     * Pembayaran sisa (pelunasan) setelah DP - redirect ke payment select
     * Route: GET /payment/{transactionId}/remaining
     */
    public function payRemaining($transactionId)
    {
        $transaction = MasterTransaksi::with('payments')->where('id_transaksi', $transactionId)->firstOrFail();
        
        
    // Cek kepemilikan - ambil customer dari user login
    $customer = Auth::user()->customer;
    if (!$customer || $transaction->id_customer !== $customer->id_customer) {
        abort(403, 'Anda tidak memiliki akses ke transaksi ini');
    }

        // Hitung pembayaran yang sudah berhasil
        $paidPayments = $transaction->payments()->where('payment_status', 'paid')->get();
        $totalPaid = $paidPayments->sum('amount');
        
        // Cek apakah sudah lunas
        if ($totalPaid >= $transaction->total) {
            return redirect()->back()->with('error', 'Transaksi sudah lunas');
        }

        // Cek apakah ada pembayaran yang berhasil
        if ($totalPaid == 0) {
            return redirect()->back()->with('error', 'Belum ada pembayaran yang berhasil');
        }

        // Redirect ke payment select (akan otomatis show sisa pembayaran)
        return redirect()->route('payment.select', $transaction->id_transaksi)
                        ->with('info', 'Silakan bayar sisa pembayaran sebesar Rp ' . number_format($transaction->total - $totalPaid, 0, ',', '.'));
    }

    /**
     * Lanjutkan pembayaran yang pending - redirect ke payment select
     * Route: GET /payment/{transactionId}/continue
     */
    public function continuePayment($transactionId)
    {
        try {
            // Ambil transaksi dengan relasi payments
            $transaction = MasterTransaksi::with(['payments'])
                              ->where('id_transaksi', $transactionId)
                              ->first();

            if (!$transaction) {
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
            }

             $customer = Auth::user()->customer;
        if (!$customer || $transaction->id_customer !== $customer->id_customer) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini');
        }

            // Cek status transaksi
            if (in_array($transaction->status, ['completed', 'cancelled'])) {
                return redirect()->back()->with('error', 'Transaksi sudah ' . $transaction->status . ', tidak dapat melanjutkan pembayaran.');
            }

            // Cari payment yang masih pending
            $pendingPayment = $transaction->payments()
                                ->where('payment_status', 'pending')
                                ->orderBy('created_at', 'desc')
                                ->first();

            if ($pendingPayment) {
                // Ada pending payment - hapus yang pending dan arahkan ke select
                $pendingPayment->update(['payment_status' => 'cancelled']);
                
                return redirect()->route('payment.select', $transaction->id_transaksi)
                               ->with('info', 'Pembayaran sebelumnya dibatalkan. Silakan pilih metode pembayaran baru.');
            } else {
                // Tidak ada payment pending, arahkan ke select biasa
                return redirect()->route('payment.select', $transaction->id_transaksi);
            }

        } catch (\Exception $e) {
            Log::error('Error continuing payment: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Gagal melanjutkan pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Proses lanjutan pembayaran pending (dipanggil dari view continue)
     * Method ini akan dipanggil via AJAX dari form di view payment.continue
     */
    public function processContinuePayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'payment_method' => 'required|in:online,offline'
        ]);

        try {
            DB::beginTransaction();
            
            $payment = Payment::with('masterTransaction.customer')->findOrFail($request->payment_id);
            
            // Cek kepemilikan
            if ($payment->masterTransaction->id_customer !== Auth::id()) {
                abort(403);
            }

            // Cek apakah payment masih pending
            if ($payment->payment_status !== 'pending') {
                return redirect()->back()->with('error', 'Payment sudah diproses sebelumnya');
            }

            if ($request->payment_method === 'online') {
                // Proses pembayaran online via Midtrans
                return $this->processPendingOnlinePayment($payment);
            } else {
                // Proses pembayaran offline via WhatsApp
                return $this->processPendingOfflinePayment($payment);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing continue payment: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan pembayaran pending
     */
    public function cancelPendingPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id'
        ]);

        try {
            DB::beginTransaction();
            
            $payment = Payment::with('masterTransaction')->findOrFail($request->payment_id);
            
            // Cek kepemilikan
            if ($payment->masterTransaction->id_customer !== Auth::id()) {
                abort(403);
            }

            // Cek apakah payment masih pending
            if ($payment->payment_status !== 'pending') {
                return redirect()->back()->with('error', 'Payment sudah diproses, tidak dapat dibatalkan');
            }

            // Update status ke cancelled
            $payment->update([
                'payment_status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            DB::commit();

            return redirect()->route('pesanan.index')
                           ->with('success', 'Pembayaran berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling pending payment: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Gagal membatalkan pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Midtrans callback handler
     * Route: POST /midtrans/callback
     */
  /**
 * Midtrans callback handler
 * Route: GET|POST /midtrans/callback
 */
public function midtransCallback(Request $request)
{
    try {
        // Handle GET request (health check from Midtrans)
        if ($request->isMethod('get')) {
            Log::info('Midtrans callback health check (GET)');
            return response()->json([
                'status' => 'success',
                'message' => 'Callback endpoint is ready'
            ], 200);
        }

        // Handle POST request (actual notification)
        $notification = new Notification();
        
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? '';
        
        Log::info('Midtrans callback received', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'raw_data' => $request->all()
        ]);

        // Cari payment berdasarkan midtrans_order_id
        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        
        if (!$payment) {
            Log::warning('Payment not found for order_id: ' . $orderId . ' (probably test notification)');
            
            // ✅ FIX: Return 200 OK untuk test notification
            // Agar Midtrans tidak terus retry
            return response()->json([
                'status' => 'success', 
                'message' => 'Notification received but payment not found in database'
            ], 200);
        }

        // Update payment dengan data dari Midtrans
        $payment->update([
            'midtrans_transaction_id' => $notification->transaction_id,
            'midtrans_status' => json_encode($notification->getResponse())
        ]);

        // Update status berdasarkan transaction_status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $payment->update(['payment_status' => 'pending']);
                Log::info("Payment {$orderId} status: pending (fraud challenge)");
            } elseif ($fraudStatus == 'accept') {
                $this->markPaymentAsPaid($payment);
                Log::info("Payment {$orderId} marked as PAID");
            }
        } elseif ($transactionStatus == 'settlement') {
            $this->markPaymentAsPaid($payment);
            Log::info("Payment {$orderId} marked as PAID (settlement)");
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $payment->update(['payment_status' => 'failed']);
            Log::info("Payment {$orderId} marked as FAILED");
        } elseif ($transactionStatus == 'pending') {
            $payment->update(['payment_status' => 'pending']);
            Log::info("Payment {$orderId} status: pending");
        }

        return response()->json(['status' => 'success'], 200);

    } catch (\Exception $e) {
        Log::error('Midtrans callback error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);
        
        // ✅ FIX: Tetap return 200 meski error
        // Agar Midtrans tidak spam retry
        return response()->json([
            'status' => 'error', 
            'message' => $e->getMessage()
        ], 200);
    }
}

    // ============= PRIVATE HELPER METHODS =============

    /**
     * Proses pending payment untuk online (Midtrans)
     */
    private function processPendingOnlinePayment(Payment $payment)
    {
        try {
            $transaction = $payment->masterTransaction;
            
            // Generate Midtrans Order ID jika belum ada
            if (!$payment->midtrans_order_id) {
                $midtransOrderId = $this->generateMidtransOrderId($payment);
                $payment->update(['midtrans_order_id' => $midtransOrderId]);
            }

            // Prepare Midtrans transaction details
            $typeLabel = $this->getPaymentTypeLabel($payment->type);
            
            $params = [
                'transaction_details' => [
                    'order_id' => $payment->midtrans_order_id,
                    'gross_amount' => (int) $payment->amount,
                ],
                'customer_details' => [
                    'first_name' => $transaction->customer->nama,
                    'email' => $transaction->customer->email ?: 'customer@example.com',
                    'phone' => $transaction->customer->no_hp,
                ],
                'item_details' => [
                    [
                        'id' => $transaction->id_transaksi,
                        'price' => (int) $payment->amount,
                        'quantity' => 1,
                        'name' => $typeLabel . ' - Transaksi ' . $transaction->id_transaksi
                    ]
                ],
                'callbacks' => [
                    'finish' => route('payment.success', $payment->id),
                    'unfinish' => route('payment.failed', $payment->id),
                    'error' => route('payment.failed', $payment->id),
                ]
            ];

            $snapToken = Snap::getSnapToken($params);
            
            // Update method ke online
            $payment->update(['method' => 'online']);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'payment_id' => $payment->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing pending online payment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Proses pending payment untuk offline (WhatsApp)
     */
    private function processPendingOfflinePayment(Payment $payment)
    {
        try {
            $transaction = $payment->masterTransaction;
            
            // Update method ke offline
            $payment->update(['method' => 'offline']);
            
            DB::commit();

            // Generate WhatsApp message
            $whatsappMessage = $this->generateWhatsAppMessage($transaction, $payment);
            $whatsappUrl = "https://api.whatsapp.com/send?phone=" . self::WHATSAPP_NUMBER . "&text=" . urlencode($whatsappMessage);

            // Redirect ke WhatsApp
            return redirect($whatsappUrl);

        } catch (\Exception $e) {
            Log::error('Error processing pending offline payment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate WhatsApp message untuk offline payment
     */
    private function generateWhatsAppMessage($transaction, $payment)
    {
        $paymentTypeText = $payment->type == 'dp' ? 'Down Payment (DP)' : 'Pembayaran';
        
        $message = "🍽️ *KONFIRMASI PEMBAYARAN CATERING*\n\n";
        $message .= "Halo, saya ingin melakukan pembayaran untuk:\n\n";
        $message .= "📋 *Detail Transaksi:*\n";
        $message .= "• ID Transaksi: *{$transaction->id_transaksi}*\n";
        $message .= "• Nama Customer: *{$transaction->customer->nama}*\n";
        $message .= "• Tanggal Acara: *" . date('d/m/Y', strtotime($transaction->tanggal_acara)) . "*\n";
        $message .= "• Total Transaksi: *Rp " . number_format($transaction->total, 0, ',', '.') . "*\n\n";
        
        $message .= "💰 *Detail Pembayaran:*\n";
        $message .= "• Jenis: *{$paymentTypeText}*\n";
        $message .= "• Jumlah: *Rp " . number_format($payment->amount, 0, ',', '.') . "*\n";
        $message .= "• ID Payment: *{$payment->id}*\n\n";
        
        $message .= "🏦 *Rekening Tujuan:*\n";
        foreach (self::BANK_ACCOUNTS as $bank) {
            $message .= "• {$bank['bank']}: *{$bank['account_number']}*\n";
            $message .= "  a.n. {$bank['account_name']}\n\n";
        }
        
        $message .= "📝 *Instruksi:*\n";
        $message .= "1. Silakan transfer sesuai nominal di atas\n";
        $message .= "2. Kirim bukti transfer ke nomor ini\n";
        $message .= "3. Cantumkan ID Transaksi dan ID Payment\n";
        $message .= "4. Pembayaran akan dikonfirmasi dalam 1x24 jam\n\n";
        
        $message .= "Terima kasih! 🙏";
        
        return $message;
    }

    public function manualTransfer(Request $request)
{
    $request->validate([
        'transaction_id' => 'required|exists:master_transaksi,id_transaksi',
        'payment_type'   => 'required|in:full,dp',
        'amount'         => 'required|numeric|min:1',
        'bank_name'      => 'required|string|max:50',
        'proof'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // Upload file bukti transfer
    $file = $request->file('proof');
    $filename = time() . '_' . $file->getClientOriginalName();
    $path = $file->storeAs('payment_proofs', $filename, 'public');

    // Buat record pembayaran manual
    $payment = Payment::create([
        'master_transaction_id' => $request->transaction_id,
        'method'                => 'manual_transfer',
        'type'                  => $request->payment_type,
        'amount'                => $request->amount,
        'payment_status'        => 'pending',
        'bank_name'             => $request->bank_name,
        'proof_file'            => $path,
        'created_at'            => now(),
        'updated_at'            => now(),
    ]);

    // (Opsional) update status transaksi jadi "pending"
    MasterTransaksi::where('id_transaksi', $request->transaction_id)
        ->update(['status' => 'pending']);

    return redirect()->back()->with('success', 'Bukti transfer berhasil dikirim. Menunggu verifikasi admin.');
}
    private function generateMidtransOrderId($payment)
    {
        return 'PAY-' . $payment->id . '-' . time();
    }

    /**
     * Validasi request pembayaran
     */
  private function validatePaymentRequest(MasterTransaksi $transaction, string $type, float $amount)
{
    // Cek status transaksi
    if (in_array($transaction->status, ['completed', 'cancelled'])) {
        throw new \Exception('Transaksi sudah ' . $transaction->status);
    }

    $paidPayments = $transaction->payments()->where('payment_status', 'paid')->get();
    $totalPaid = $paidPayments->sum('amount');

    // Cek apakah sudah lunas
    if ($totalPaid >= $transaction->total) {
        throw new \Exception('Transaksi sudah lunas');
    }

    switch ($type) {
        case 'full':
            // Jika ada DP yang sudah dibayar, ini adalah pelunasan
            $dpPayment = $paidPayments->where('type', 'dp')->first();
            
            if ($dpPayment) {
                // Ini adalah pelunasan setelah DP
                $expectedAmount = $transaction->total - $totalPaid;
                // Abaikan biaya admin untuk validasi sisa pembayaran
                $amountWithoutFee = $amount - 4440; // Kurangi fee jika ada
                
                if ($amountWithoutFee != $expectedAmount && $amount != $expectedAmount) {
                    throw new \Exception('Jumlah pelunasan harus Rp ' . number_format($expectedAmount, 0, ',', '.'));
                }
            } else {
                // Ini adalah pembayaran penuh tanpa DP
                if ($paidPayments->count() > 0) {
                    throw new \Exception('Transaksi sudah memiliki pembayaran yang berhasil');
                }
                
                // Validasi: amount bisa sama dengan total, atau total + 4440 (jika ada fee)
                if ($amount != $transaction->total && $amount != ($transaction->total + 4440)) {
                    throw new \Exception('Jumlah pembayaran harus Rp ' . number_format($transaction->total, 0, ',', '.') . ' atau Rp ' . number_format($transaction->total + 4440, 0, ',', '.') . ' (termasuk biaya admin)');
                }
            }
            break;

        case 'dp':
            // Cek apakah sudah ada DP yang dibayar
            $dpPayment = $paidPayments->where('type', 'dp')->first();
            if ($dpPayment) {
                throw new \Exception('DP sudah dibayar sebelumnya');
            }
            
            // Validasi minimal DP 35% (tanpa fee)
            $minDpAmount = $transaction->total * 0.35;
            // Amount bisa sama dengan minDp, atau minDp (tanpa fee)
            if ($amount < $minDpAmount) {
                throw new \Exception('Minimal DP adalah 35% dari total transaksi (Rp ' . number_format($minDpAmount, 0, ',', '.') . ')');
            }
            
            if ($amount >= $transaction->total) {
                throw new \Exception('Jumlah DP tidak boleh sama atau lebih besar dari total transaksi');
            }
            break;
    }
}

    /**
     * Tandai pembayaran sebagai berhasil dan update status transaksi
     */
    private function markPaymentAsPaid(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $payment->update([
                'payment_status' => 'paid',
                'paid_at' => now()
            ]);

            // Update status master transaction
            $transaction = $payment->masterTransaction;
            
            // Hitung total yang sudah dibayar
            $totalPaid = $transaction->payments()->where('payment_status', 'paid')->sum('amount');

            // Update status transaksi berdasarkan pembayaran
            if ($totalPaid >= $transaction->total) {
                // Lunas - update ke confirmed (bisa disesuaikan dengan business logic)
                if (in_array($transaction->status, ['draft', 'pending'])) {
                    $transaction->update(['status' => 'confirmed']);
                }
            } else {
                // Partial payment - update dari draft/pending ke confirmed
                if (in_array($transaction->status, ['draft', 'pending'])) {
                    $transaction->update(['status' => 'confirmed']);
                }
            }
               try {
            $adminNumber = env('ADMIN_WA');
            $message = "🔔 *NOTIFIKASI PEMBAYARAN BERHASIL*\n\n";
            $message .= "ID Transaksi: {$transaction->id_transaksi}\n";
            $message .= "Nama Customer: {$transaction->customer->nama}\n";
            $message .= "Jenis Pembayaran: " . ($payment->type == 'dp' ? 'Down Payment (DP)' : 'Pelunasan / Full') . "\n";
            $message .= "Jumlah: Rp " . number_format($payment->amount, 0, ',', '.') . "\n";
            $message .= "Status Transaksi: {$transaction->status}";

            \App\Helpers\WhatsappHelper::kirimPesan($adminNumber, $message);

        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi WA: ' . $e->getMessage());
        }
        });
    }

    /**
     * Get payment type label
     */
    private function getPaymentTypeLabel(string $type): string
    {
        $labels = [
            'full' => 'Pembayaran',
            'dp' => 'Down Payment (DP)'
        ];

        return $labels[$type] ?? 'Pembayaran';
    }
}
