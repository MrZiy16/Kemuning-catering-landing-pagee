<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class MasterMenu extends Model
{
    use HasFactory;

    protected $table = 'master_menu';
    protected $primaryKey = 'id_menu';
    public $timestamps = false;

    protected $fillable = [
        'nama_menu',
        'deskripsi',
        'harga_satuan',
        'kategori_menu',
        'status',
        'gambar',
        'slug'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2'
    ];

    protected static function booted()
    {
        static::creating(function ($menu) {
            $menu->slug = Str::slug($menu->nama_menu);
        });

        static::updating(function ($menu) {
            $menu->slug = Str::slug($menu->nama_menu);
        });
    }

    // This tells Laravel to use 'slug' for route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Add this method to resolve the model by slug
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->first() ?? abort(404);
    }

    // Relationships
    public function paketBoxDetail()
    {
        return $this->hasMany(PaketBoxDetail::class, 'id_menu', 'id_menu');
    }

    public function prasmananDetail()
    {
        return $this->hasMany(PrasmananDetail::class, 'id_menu', 'id_menu');
    }

    public function transaksiMenuCustom()
    {
        return $this->hasMany(TransaksiMenuCustom::class, 'id_menu', 'id_menu');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_menu', 'id_menu');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori_menu', $kategori);
    }
}