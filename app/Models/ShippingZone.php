<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $table = 'shipping_zones';

    protected $fillable = [
        'nama_zona',
        'ongkir',
        'keterangan',
    ];

    /**
     * Relasi: 1 zona bisa dipakai banyak transaksi
     */
    public function masterTransaksis()
    {
        return $this->hasMany(MasterTransaksi::class, 'shipping_zone_id');
    }
}
