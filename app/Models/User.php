<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'email',
        'password',
        'nama',
        'no_hp',
        'alamat',
        'role',
        'email_verified_at',
        'status'
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================
    
    /**
     * User punya 1 customer (untuk pelanggan)
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', 'id');
    }

    /**
     * Transaksi lewat customer
     */
    public function transaksi()
    {
        return $this->hasManyThrough(
            MasterTransaksi::class,
            Customer::class,
            'user_id',        // FK di customer
            'id_customer',    // FK di master_transaksi
            'id',             // PK di users
            'id_customer'     // PK di customer
        );
    }

    /**
     * Transaksi aktif lewat customer
     */
    public function transaksiAktif()
    {
        return $this->hasManyThrough(
            MasterTransaksi::class,
            Customer::class,
            'user_id',
            'id_customer',
            'id',
            'id_customer'
        )->whereNotIn('status', ['completed', 'cancelled']);
    }

    // ========================================
    // HELPER METHODS
    // ========================================
    
    /**
     * Cek apakah user adalah pelanggan
     */
    public function isPelanggan()
    {
        return $this->role === 'pelanggan';
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }
}