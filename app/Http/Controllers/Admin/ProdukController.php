<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\MasterMenu;
use App\Models\PaketBoxDetail;
use App\Models\PrasmananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['menuItems']);

        // Filter
        if ($request->filled('kategori')) {
            $query->where('kategori_produk', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        $produks = $query->paginate(12);

        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        $menus = MasterMenu::active()->get()->groupBy('kategori_menu');
        return view('admin.produk.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'kategori_produk' => 'required|in:paket_box,prasmanan,pondokan,tumpeng',
            'jumlah_orang' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
          
        ]);

        DB::transaction(function () use ($request) {
            $data = $request->except(['gambar', 'menu_items']);

            if ($request->hasFile('gambar')) {
                $filename = $request->file('gambar')->store('produk', 'public');
                $data['gambar'] = $filename;
            }

            $produk = Produk::create($data);
if (!empty($request->menu_items) && is_iterable($request->menu_items)) {
    foreach ($request->menu_items as $item) {
        if ($request->kategori_produk === 'paket_box') {
            PaketBoxDetail::create([
                'id_produk' => $produk->id_produk,
                'id_menu' => $item['id_menu'],
                'qty' => $item['qty']
            ]);
        } else {
            PrasmananDetail::create([
                'id_produk' => $produk->id_produk,
                'id_menu' => $item['id_menu'],
                'qty' => $item['qty']
            ]);
        }
    }
}

        });

        return redirect()->route('admin.produk.index')
                        ->with('success', 'Produk berhasil ditambahkan!');
    }

    // Change parameter name from $produk to $slug to match route parameter
    public function show($slug)
    {
        $produk = Produk::where('slug', $slug)->firstOrFail();
        $produk->load(['menuItems']);
        return view('admin.produk.show', compact('produk'));
    }

    // Change parameter name from $produk to $slug to match route parameter
    public function edit($slug)
    {
        $produk = Produk::where('slug', $slug)->firstOrFail();
        $produk->load(['menuItems']);
        $menus = MasterMenu::active()->get()->groupBy('kategori_menu');
        
        return view('admin.produk.edit', compact('produk', 'menus'));
    }

    // Change parameter name from $produk to $slug to match route parameter
    public function update(Request $request, $slug)
    {
        $produk = Produk::where('slug', $slug)->firstOrFail();
        
        $request->validate([
            'nama_produk' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'kategori_produk' => 'required|in:paket_box,prasmanan,pondokan,tumpeng',
            'jumlah_orang' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::transaction(function () use ($request, $produk) {
            $data = $request->except(['gambar', 'menu_items']);

            if ($request->hasFile('gambar')) {
                // Delete old image
                if ($produk->gambar) {
                    Storage::disk('public')->delete($produk->gambar);
                }

                $filename = $request->file('gambar')->store('produk', 'public');
                $data['gambar'] = $filename;
            }

            $produk->update($data);

            // Delete existing menu items
            if ($produk->kategori_produk === 'paket_box') {
                PaketBoxDetail::where('id_produk', $produk->id_produk)->delete();
            } else {
                PrasmananDetail::where('id_produk', $produk->id_produk)->delete();
            }

            // Add new menu items
            
         if (!empty($request->menu_items) && is_iterable($request->menu_items)) {
    foreach ($request->menu_items as $item) {
        if ($request->kategori_produk === 'paket_box') {
            PaketBoxDetail::create([
                'id_produk' => $produk->id_produk,
                'id_menu' => $item['id_menu'],
                'qty' => $item['qty']
            ]);
        } else {
            PrasmananDetail::create([
                'id_produk' => $produk->id_produk,
                'id_menu' => $item['id_menu'],
                'qty' => $item['qty']
            ]);
        }
    }
}

        });

        return redirect()->route('admin.produk.index')
                        ->with('success', 'Produk berhasil diupdate!');
    }

    // Change parameter name from $produk to $slug to match route parameter
    public function destroy($slug)
    {
        $produk = Produk::where('slug', $slug)->firstOrFail();
        
        // Check if used in transactions
        if ($produk->detailTransaksi()->count() > 0) {
            return back()->with('error', 'Produk tidak dapat dihapus karena sudah digunakan dalam transaksi!');
        }

        // Delete image
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')
                        ->with('success', 'Produk berhasil dihapus!');
    }
}