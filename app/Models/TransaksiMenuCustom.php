<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiMenuCustom extends Model
{
    use HasFactory;

    protected $table = 'transaksi_menu_custom';
    protected $primaryKey = ['id_transaksi', 'id_menu'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_transaksi',
        'id_menu',
        'qty',
        'harga',
        'subtotal',
        'catatan'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    // Relationships
    public function transaksi()
    {
        return $this->belongsTo(MasterTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function menu()
    {
        return $this->belongsTo(MasterMenu::class, 'id_menu', 'id_menu');
    }
}

