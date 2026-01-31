<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

  protected $fillable = [
    'master_transaction_id',
    'method',
    'type',
    'amount',
    'payment_status',
    'bank_name',
    'proof_file',
    'midtrans_order_id',
    'midtrans_transaction_id',
    'midtrans_status',
    'paid_at',
];
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'midtrans_status' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship dengan MasterTransaksi
     */
    public function masterTransaction()
    {
        return $this->belongsTo(MasterTransaksi::class, 'master_transaction_id', 'id_transaksi');
    }

    /**
     * Generate Midtrans Order ID
     */
    public function generateMidtransOrderId()
    {
        return 'PAY-' . $this->id . '-' . time();
    }

    /**
     * Check if payment is paid
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Get payment method label
     */
    public function getMethodLabelAttribute()
    {
        $labels = [
            'offline' => 'Transfer Manual',
            'online' => 'Online Payment'
        ];

        return $labels[$this->method] ?? $this->method;
    }

    /**
     * Get payment type label
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'full' => 'Pembayaran Penuh',
            'dp' => 'Down Payment (DP)'
        ];

        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Get payment status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge-warning',
            'paid' => 'badge-success',
            'failed' => 'badge-danger',
            'cancelled' => 'badge-secondary'
        ];

        return $badges[$this->payment_status] ?? 'badge-secondary';
    }

    /**
     * Get payment status text
     */
    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Berhasil',
            'failed' => 'Gagal',
            'cancelled' => 'Dibatalkan',
            
        ];

        return $texts[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get formatted paid date
     */
    public function getFormattedPaidAtAttribute()
    {
        return $this->paid_at ? $this->paid_at->format('d/m/Y H:i') : null;
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /**
     * Scope untuk filter berdasarkan method
     */
    public function scopeOffline($query)
    {
        return $query->where('method', 'offline');
    }

    public function scopeOnline($query)
    {
        return $query->where('method', 'online');
    }

    /**
     * Scope untuk filter berdasarkan type
     */
    public function scopeFullPayment($query)
    {
        return $query->where('type', 'full');
    }

    public function scopeDownPayment($query)
    {
        return $query->where('type', 'dp');
    }
}
