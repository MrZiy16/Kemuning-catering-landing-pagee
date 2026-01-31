<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $timestamps = false;

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'kategori_produk',
        'jumlah_orang',
        'status',
        'gambar',
        'slug'
    ];

    protected $casts = [
        'harga' => 'decimal:2'
    ];

    // Auto-generate slug when creating/updating
    protected static function booted()
    {
        static::creating(function ($produk) {
            $produk->slug = Str::slug($produk->nama_produk);
        });

        static::updating(function ($produk) {
            $produk->slug = Str::slug($produk->nama_produk);
        });
    }

    // Tell Laravel to use 'slug' for route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Resolve model by slug
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->first() ?? abort(404);
    }

    // Relationships
    public function paketBoxDetails()
    {
        return $this->hasMany(PaketBoxDetail::class, 'id_produk', 'id_produk');
    }

    public function prasmananDetails()
    {
        return $this->hasMany(PrasmananDetail::class, 'id_produk', 'id_produk');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_produk', 'id_produk');
    }

    // Get menu items based on product category
    public function menuItems()
    {
        if ($this->kategori_produk === 'paket_box') {
            return $this->belongsToMany(MasterMenu::class, 'paket_box_detail', 'id_produk', 'id_menu')
                        ->withPivot('qty');
        } else {
            return $this->belongsToMany(MasterMenu::class, 'prasmanan_detail', 'id_produk', 'id_menu')
                        ->withPivot('qty');
        }
    }

    // Get menu items with qty for display
    public function getMenuItemsAttribute()
    {
        return $this->menuItems()->get();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePaketBox($query)
    {
        return $query->where('kategori_produk', 'paket_box');
    }

    public function scopePrasmanan($query)
    {
        return $query->where('kategori_produk', 'prasmanan');
    }
}