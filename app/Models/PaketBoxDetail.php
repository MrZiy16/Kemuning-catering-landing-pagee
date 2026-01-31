<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaketBoxDetail extends Model
{
    use HasFactory;

    protected $table = 'paket_box_detail';
    protected $primaryKey = ['id_produk', 'id_menu'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_produk',
        'id_menu',
        'qty'
    ];

    // Relationships
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function menu()
    {
        return $this->belongsTo(MasterMenu::class, 'id_menu', 'id_menu');
    }
}
