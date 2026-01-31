<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrasmananDetail extends Model
{
    use HasFactory;

    protected $table = 'prasmanan_detail';
    protected $primaryKey = null; // kalau composite PK, biarin null
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
