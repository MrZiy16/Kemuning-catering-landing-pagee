<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterTransaksi extends Model
{
    use HasFactory;

    protected $table = 'master_transaksi';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
        
    protected $fillable = [
        'id_transaksi',
        'id_customer',
        'tanggal_transaksi',
        'tanggal_acara',
        'waktu_acara',
        'alamat_pengiriman',
        'total',
        'status',
        'catatan_customer',
        'catatan_admin'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'tanggal_acara' => 'date',
        'total' => 'decimal:2'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'id_customer', 'id');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function transaksiMenuCustom()
    {
        return $this->hasMany(TransaksiMenuCustom::class, 'id_transaksi', 'id_transaksi');
    }

    public function statusLog()
    {
        return $this->hasMany(StatusLog::class, 'id_transaksi', 'id_transaksi')
                    ->orderBy('created_at', 'asc');
    }

    public function latestStatus()
    {
        return $this->hasOne(StatusLog::class, 'id_transaksi', 'id_transaksi')
                    ->latest();
    }

    // Payment Relationships
    public function payments()
    {
        return $this->hasMany(Payment::class, 'master_transaction_id', 'id_transaksi');
    }

    public function paidPayments()
    {
        return $this->hasMany(Payment::class, 'master_transaction_id', 'id_transaksi')
                    ->where('payment_status', 'paid');
    }

    public function pendingPayments()
    {
        return $this->hasMany(Payment::class, 'master_transaction_id', 'id_transaksi')
                    ->where('payment_status', 'pending');
    }

    public function failedPayments()
    {
        return $this->hasMany(Payment::class, 'master_transaction_id', 'id_transaksi')
                    ->where('payment_status', 'failed');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_acara', today());
    }

    public function scopeAktif($query)
    {
        return $query->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeNeedPayment($query)
    {
        return $query->whereIn('status', ['draft', 'pending', 'confirmed'])
                     ->whereHas('payments', function($q) {
                         $q->where('payment_status', '!=', 'paid');
                     }, '<', 1);
    }

    public function scopePartialPaid($query)
    {
        return $query->whereHas('paidPayments')
                     ->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE master_transaction_id = master_transaksi.id_transaksi AND payment_status = "paid") < total');
    }

    public function scopeFullyPaid($query)
    {
        return $query->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE master_transaction_id = master_transaksi.id_transaksi AND payment_status = "paid") >= total');
    }

    // Mutators & Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'badge-secondary',
            'pending' => 'badge-warning',
            'confirmed' => 'badge-info',
            'preparing' => 'badge-primary',
            'ready' => 'badge-success',
            'delivered' => 'badge-success',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'draft' => 'Draft',
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'preparing' => 'Sedang Dipersiapkan',
            'ready' => 'Siap Kirim',
            'delivered' => 'Dalam Perjalanan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $texts[$this->status] ?? 'Unknown';
    }

    // Payment Accessors
    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('payment_status', 'paid')->sum('amount') ?? 0;
    }

    public function getRemainingAmountAttribute()
    {
        $totalPaid = $this->getTotalPaidAttribute();
        $remaining = $this->total - $totalPaid;
        return $remaining > 0 ? $remaining : 0;
    }

    public function getIsFullyPaidAttribute()
    {
        return $this->getTotalPaidAttribute() >= $this->total;
    }

    public function getHasDownPaymentAttribute()
    {
        return $this->payments()
                    ->where('type', 'dp')
                    ->where('payment_status', 'paid')
                    ->exists();
    }

    public function getHasPendingPaymentAttribute()
    {
        return $this->payments()
                    ->where('payment_status', 'pending')
                    ->exists();
    }

    public function getPaymentStatusAttribute()
    {
        $totalPaid = $this->getTotalPaidAttribute();
        
        if ($totalPaid <= 0) {
            return 'unpaid';
        } elseif ($totalPaid >= $this->total) {
            return 'fully_paid';
        } else {
            return 'partially_paid';
        }
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'unpaid' => 'badge-danger',
            'partially_paid' => 'badge-warning',
            'fully_paid' => 'badge-success',
        ];

        return $badges[$this->payment_status] ?? 'badge-secondary';
    }

    public function getPaymentStatusTextAttribute()
    {
        $texts = [
            'unpaid' => 'Belum Dibayar',
            'partially_paid' => 'Dibayar Sebagian',
            'fully_paid' => 'Lunas',
        ];

        return $texts[$this->payment_status] ?? 'Unknown';
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getFormattedTotalPaidAttribute()
    {
        return 'Rp ' . number_format($this->getTotalPaidAttribute(), 0, ',', '.');
    }

    public function getFormattedRemainingAmountAttribute()
    {
        return 'Rp ' . number_format($this->getRemainingAmountAttribute(), 0, ',', '.');
    }

    // Helper Methods
    public static function generateTransactionId($prefix = 'TRX')
    {
        $date = now()->format('Ymd');
        $count = self::where('id_transaksi', 'like', $prefix . '-' . $date . '%')->count() + 1;
        return $prefix . '-' . $date . str_pad($count, 1, '0', STR_PAD_LEFT);
    }

    public function getTotalItems()
    {
        return $this->detailTransaksi()->sum('qty') + $this->transaksiMenuCustom()->sum('qty');
    }

    public function canUpdateStatus()
    {
        return !in_array($this->status, ['completed', 'cancelled']);
    }

    // Payment Helper Methods
    public function isPaidOff()
    {
        return $this->getIsFullyPaidAttribute();
    }

    public function hasDownPayment()
    {
        return $this->getHasDownPaymentAttribute();
    }

    public function getDownPayment()
    {
        return $this->payments()
                    ->where('type', 'dp')
                    ->where('payment_status', 'paid')
                    ->first();
    }

    public function getLastPayment()
    {
        return $this->payments()
                    ->where('payment_status', 'paid')
                    ->latest('paid_at')
                    ->first();
    }

    public function getPendingPayments()
    {
        return $this->payments()
                    ->where('payment_status', 'pending')
                    ->get();
    }

    public function canMakePayment()
    {
        return !$this->isPaidOff() && $this->canUpdateStatus();
    }

    public function canMakeDownPayment()
    {
        return !$this->hasDownPayment() && $this->canMakePayment();
    }

    public function canMakeFullPayment()
    {
        return $this->canMakePayment();
    }

    public function canMakeRemainderPayment()
    {
        return $this->hasDownPayment() && !$this->isPaidOff() && $this->canMakePayment();
    }

    public function getMinimumDownPayment()
    {
        return $this->total * 0.35; // 35% minimal DP
    }

    public function validateDownPaymentAmount($amount)
    {
        if ($this->hasDownPayment()) {
            return [
                'valid' => false,
                'message' => 'DP sudah dibayar sebelumnya'
            ];
        }

        $minDp = $this->getMinimumDownPayment();
        
        if ($amount < $minDp) {
            return [
                'valid' => false,
                'message' => 'Minimal DP adalah 35% dari total transaksi (Rp ' . number_format($minDp, 0, ',', '.') . ')'
            ];
        }
        
        if ($amount >= $this->total) {
            return [
                'valid' => false,
                'message' => 'Jumlah DP tidak boleh sama atau lebih besar dari total transaksi'
            ];
        }

        return [
            'valid' => true,
            'message' => 'Jumlah DP valid'
        ];
    }

    public function validateFullPaymentAmount($amount)
    {
        if ($this->hasDownPayment()) {
            // Ini adalah pelunasan
            $expectedAmount = $this->getRemainingAmountAttribute();
            if ($amount != $expectedAmount) {
                return [
                    'valid' => false,
                    'message' => 'Jumlah pelunasan harus Rp ' . number_format($expectedAmount, 0, ',', '.')
                ];
            }
        } else {
            // Ini adalah pembayaran penuh tanpa DP
            if ($this->getTotalPaidAttribute() > 0) {
                return [
                    'valid' => false,
                    'message' => 'Transaksi sudah memiliki pembayaran yang berhasil'
                ];
            }
            
            if ($amount != $this->total) {
                return [
                    'valid' => false,
                    'message' => 'Jumlah pembayaran penuh harus sama dengan total transaksi'
                ];
            }
        }

        return [
            'valid' => true,
            'message' => 'Jumlah pembayaran valid'
        ];
    }

    public function getPaymentSummary()
    {
        return [
            'total' => $this->total,
            'total_paid' => $this->getTotalPaidAttribute(),
            'remaining' => $this->getRemainingAmountAttribute(),
            'is_fully_paid' => $this->getIsFullyPaidAttribute(),
            'has_dp' => $this->getHasDownPaymentAttribute(),
            'has_pending' => $this->getHasPendingPaymentAttribute(),
            'payment_status' => $this->getPaymentStatusAttribute(),
            'can_pay' => $this->canMakePayment(),
            'can_dp' => $this->canMakeDownPayment(),
            'can_full' => $this->canMakeFullPayment(),
            'can_remainder' => $this->canMakeRemainderPayment(),
            'min_dp' => $this->getMinimumDownPayment(),
        ];
    }
}