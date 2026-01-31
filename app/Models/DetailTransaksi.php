<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;
    
    protected $fillable = [
        'id_transaksi',
        'id_produk',
        'id_menu',
        'qty',
        'harga',
        'subtotal'
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

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function menu()
    {
        return $this->belongsTo(MasterMenu::class, 'id_menu', 'id_menu');
    }

    // Accessors
    public function getItemNameAttribute()
    {
        return $this->produk ? $this->produk->nama_produk : $this->menu->nama_menu;
    }

    public function getItemTypeAttribute()
    {
        return $this->produk ? 'produk' : 'menu';
    }
}
