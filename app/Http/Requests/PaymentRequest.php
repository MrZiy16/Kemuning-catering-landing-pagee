<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

  // app/Http/Requests/PaymentRequest.php
public function rules()
{
    return [
        'transaction_id' => 'required|exists:master_transaction,no_penjualan', // ganti dari 'id' ke 'no_penjualan'
        'payment_type' => 'nullable|in:dp,full,remaining',
    ];
}

    public function messages()
    {
        return [
            'transaction_id.required' => 'ID transaksi diperlukan.',
            'transaction_id.exists' => 'Transaksi tidak ditemukan.',
            'payment_type.required' => 'Jenis pembayaran harus dipilih.',
            'payment_type.in' => 'Jenis pembayaran tidak valid.'
        ];
    }
}