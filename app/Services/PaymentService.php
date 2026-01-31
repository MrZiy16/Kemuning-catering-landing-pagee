<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\MasterTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function createPayment($masterTransactionId, $method, $type, $amount)
    {
        // $masterTransactionId sekarang adalah no_penjualan (string)
        $payment = Payment::create([
            'master_transaction_id' => $masterTransactionId, // ✅ Simpan no_penjualan ke kolom master_transaction_id
            'method' => $method,
            'type' => $type,
            'amount' => $amount,
            'payment_status' => 'pending',
        ]);

        if ($method === 'midtrans' || $method === 'online') {
            $payment->midtrans_order_id = $payment->generateMidtransOrderId();
            $payment->save();
        }

        return $payment;
    }

    public function updatePaymentStatus(Payment $payment, $status, $midtransData = null)
    {
        // ✅ GUNAKAN DB TRANSACTION UNTUK KONSISTENSI DATA
        DB::beginTransaction();
        
        try {
            // Update payment status
            $payment->payment_status = $status;
            
            if ($midtransData) {
                $payment->midtrans_transaction_id = $midtransData['transaction_id'] ?? null;
                $payment->midtrans_status = json_encode($midtransData); // ✅ Simpan sebagai JSON
            }

            if ($status === 'paid') {
                $payment->paid_at = now();
                $payment->save(); // Save payment dulu
                
                // ✅ KEMUDIAN UPDATE MASTER TRANSACTION
                $this->updateTransactionStatus($payment);
            } else {
                $payment->save();
            }

            DB::commit();
            
            Log::info("Payment status updated successfully", [
                'payment_id' => $payment->id,
                'status' => $status,
                'master_transaction_id' => $payment->master_transaction_id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update payment status', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }

        return $payment;
    }

    private function updateTransactionStatus(Payment $payment)
    {
        // ✅ Gunakan relationship
        $transaction = $payment->masterTransaction;
        
        if (!$transaction) {
            Log::error('Transaction not found for payment', [
                'payment_id' => $payment->id,
                'master_transaction_id' => $payment->master_transaction_id
            ]);
            throw new \Exception('Transaction not found for payment');
        }

        // ✅ HITUNG ULANG TOTAL PEMBAYARAN YANG SUDAH PAID (BUKAN INCREMENT!)
        $totalPaid = Payment::where('master_transaction_id', $payment->master_transaction_id)
            ->where('payment_status', 'paid')
            ->sum('amount');

        Log::info("Calculating total paid", [
            'transaction_id' => $transaction->no_penjualan,
            'current_payment_amount' => $payment->amount,
            'total_paid_calculated' => $totalPaid,
            'total_penjualan' => $transaction->total_penjualan
        ]);

        // ✅ UPDATE TOTAL PEMBAYARAN DENGAN NILAI YANG BENAR
        $transaction->total_pembayaran = $totalPaid;

        // ✅ UPDATE STATUS BERDASARKAN JENIS PEMBAYARAN DAN JUMLAH
        if ($payment->type === 'full' || $totalPaid >= $transaction->total_penjualan) {
            $transaction->status = 'paid';
            // ✅ Pastikan total pembayaran tidak melebihi total penjualan
            $transaction->total_pembayaran = min($totalPaid, $transaction->total_penjualan);
        } elseif ($payment->type === 'dp') {
            $transaction->status = 'dp_paid';
        }

        $transaction->save();
        
        Log::info("Master transaction updated", [
            'transaction_id' => $transaction->no_penjualan,
            'new_status' => $transaction->status,
            'new_total_pembayaran' => $transaction->total_pembayaran,
            'total_penjualan' => $transaction->total_penjualan
        ]);
    }

    public function generateWhatsAppUrl($transaction)
    {
        $waNumber = config('app.whatsapp_number');
        $message = "Saya telah mengisi form, saya akan ke lokasi catering guna pembayaran. No. Penjualan: " . $transaction->no_penjualan;
        
        return "https://wa.me/{$waNumber}?text=" . urlencode($message);
    }
}