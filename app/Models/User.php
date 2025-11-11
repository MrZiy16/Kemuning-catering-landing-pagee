<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail; // custom notif (atau pakai bawaan laravel)

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    // User model code here
    use HasFactory;
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
  // Relationships
    public function transaksi()
    {
 return $this->hasMany(MasterTransaksi::class, 'id_customer', 'id');

    }

    public function transaksiAktif()
    {
        return $this->hasMany(MasterTransaksi::class, 'id_customer', 'id')
                   ->whereNotIn('status', ['completed', 'cancelled']);
    }
}
