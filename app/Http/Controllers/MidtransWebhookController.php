<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Midtrans webhook received', $request->all());
        
        try {
            // Ambil data dari Midtrans
            $orderId = $request->order_id;
            $status = $request->transaction_status;
            
            // Cari payment
            $payment = Payment::where('midtrans_order_id', $orderId)->first();
            
            if (!$payment) {
                Log::error("Payment not found: {$orderId}");
                return response()->json(['message' => 'Payment not found'], 404);
            }
            
            // Simpan response dari Midtrans
            $payment->update([
                'midtrans_transaction_id' => $request->transaction_id,
                'midtrans_status' => json_encode($request->all()),
            ]);
            
            // Update status payment
            if (in_array($status, ['capture', 'settlement'])) {
                $payment->update([
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);
                
                // Update status transaksi
                $transaction = $payment->masterTransaction;
                $totalPaid = $transaction->payments()->where('payment_status', 'paid')->sum('amount');
                
                if ($totalPaid >= $transaction->total) {
                    $transaction->update(['status' => 'confirmed']);
                }
                
                Log::info("Payment {$payment->id} marked as PAID");
            } 
            elseif (in_array($status, ['cancel', 'deny', 'expire'])) {
                $payment->update(['payment_status' => 'failed']);
                Log::info("Payment {$payment->id} marked as FAILED");
            }
            
            return response()->json(['message' => 'OK']);
            
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}
