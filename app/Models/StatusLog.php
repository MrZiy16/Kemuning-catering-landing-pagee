<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusLog extends Model
{
    use HasFactory;

    protected $table = 'status_log';
    protected $primaryKey = 'id_log';
    public $timestamps = false;
    
    protected $fillable = [
        'id_transaksi',
        'status_from',
        'status_to',
        'keterangan',
        'created_by'
    ];

    // Relationships

public function user()
{
    return $this->belongsTo(User::class, 'created_by', 'id');
}

    public function transaksi()
    {
        return $this->belongsTo(MasterTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    // Accessors
    public function getStatusIconAttribute()
    {
        $icons = [
            'draft' => '📝',
            'pending' => '⏳',
            'confirmed' => '✅',
            'preparing' => '👨‍🍳',
            'ready' => '📦',
            'delivered' => '🚚',
            'completed' => '🎉',
            'cancelled' => '❌',
        ];

        return $icons[$this->status_to] ?? '📋';
    }

    public function getStatusDescriptionAttribute()
    {
        $descriptions = [
            'draft' => 'Pesanan dibuat',
            'pending' => 'Menunggu konfirmasi',
            'confirmed' => 'Pesanan dikonfirmasi',
            'preparing' => 'Sedang dipersiapkan',
            'ready' => 'Siap untuk dikirim',
            'delivered' => 'Sedang dalam perjalanan',
            'completed' => 'Pesanan selesai',
            'cancelled' => 'Pesanan dibatalkan',
        ];

        return $descriptions[$this->status_to] ?? 'Status tidak diketahui';
    }
}
