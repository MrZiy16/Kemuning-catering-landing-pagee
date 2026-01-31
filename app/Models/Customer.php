<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'id_customer';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'no_hp',
        'alamat',
        'source'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================
    
    /**
     * Customer bisa punya user (untuk yang daftar online)
     * NULL kalau customer offline
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Customer punya banyak transaksi
     */
    public function transaksi()
    {
        return $this->hasMany(MasterTransaksi::class, 'id_customer', 'id_customer');
    }

    /**
     * Transaksi yang masih aktif
     */
    public function transaksiAktif()
    {
        return $this->hasMany(MasterTransaksi::class, 'id_customer', 'id_customer')
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    // ========================================
    // SCOPES
    // ========================================
    
    /**
     * Customer yang daftar online (punya akun)
     */
    public function scopeOnline($query)
    {
        return $query->whereNotNull('user_id')->where('source', 'online');
    }

    /**
     * Customer offline (input manual admin)
     */
    public function scopeOffline($query)
    {
        return $query->whereNull('user_id')->where('source', 'offline');
    }

    // ========================================
    // ACCESSORS
    // ========================================
    
    public function getSourceBadgeAttribute()
    {
        return $this->source === 'online' ? 'badge-primary' : 'badge-secondary';
    }

    public function getSourceTextAttribute()
    {
        return $this->source === 'online' ? 'Online' : 'Offline';
    }

    public function getIsOnlineAttribute()
    {
        return $this->user_id !== null;
    }

    // ========================================
    // HELPER METHODS
    // ========================================
    
    /**
     * Total transaksi customer
     */
    public function getTotalTransaksi()
    {
        return $this->transaksi()->count();
    }

    /**
     * Total belanja customer
     */
    public function getTotalBelanja()
    {
        return $this->transaksi()
                    ->whereNotIn('status', ['cancelled'])
                    ->sum('total');
    }
}