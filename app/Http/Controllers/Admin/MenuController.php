<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterMenu::query();

        if ($request->filled('kategori')) {
            $query->where('kategori_menu', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        $menus = $query->paginate(12);
        $kategoris = ['makanan_utama', 'sayuran', 'lauk', 'minuman', 'dessert'];

        return view('admin.menu.index', compact('menus', 'kategoris'));
    }

    public function create()
    {
        $kategoris = ['makanan_utama', 'sayuran', 'lauk', 'minuman', 'dessert'];
        return view('admin.menu.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_satuan' => 'required|numeric|min:0',
            'kategori_menu' => 'required|in:makanan_utama,sayuran,lauk,minuman,dessert',
            'status' => 'required|in:active,inactive',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->handleImageUpload($request->file('gambar'));
        }

        MasterMenu::create($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    // Change parameter name from $menu to $slug to match route parameter
    public function edit($slug)  
    {
        $menu = MasterMenu::where('slug', $slug)->firstOrFail();
        $kategoris = ['makanan_utama', 'sayuran', 'lauk', 'minuman', 'dessert'];
        return view('admin.menu.edit', compact('menu', 'kategoris'));
    }

    // Change parameter name from $menu to $slug to match route parameter
    public function update(Request $request, $slug)
    {
        $menu = MasterMenu::where('slug', $slug)->firstOrFail();
        
        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_satuan' => 'required|numeric|min:0',
            'kategori_menu' => 'required|in:makanan_utama,sayuran,lauk,minuman,dessert',
            'status' => 'required|in:active,inactive',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except('gambar');

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama kalau ada
            if ($menu->gambar) {
                Storage::delete('public/menu/' . $menu->gambar);
            }
            $data['gambar'] = $this->handleImageUpload($request->file('gambar'));
        }

        $menu->update($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil diupdate!');
    }

    // Change parameter name from $menu to $slug to match route parameter
    public function destroy($slug)
    {
        $menu = MasterMenu::where('slug', $slug)->firstOrFail();
        
        if ($menu->paketBoxDetail()->count() > 0 || $menu->transaksiMenuCustom()->count() > 0) {
            return back()->with('error', 'Menu tidak dapat dihapus karena sudah digunakan dalam transaksi!');
        }

        if ($menu->gambar) {
            Storage::delete('public/menu/' . $menu->gambar);
        }

        $menu->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus!');
    }

    // Change parameter name from $menu to $slug to match route parameter
    public function toggleStatus($slug)
    {
        $menu = MasterMenu::where('slug', $slug)->firstOrFail();
        
        $menu->update([
            'status' => $menu->status === 'active' ? 'inactive' : 'active'
        ]);

        return response()->json(['success' => true, 'status' => $menu->status]);
    }

    /**
     * Upload gambar tanpa Intervention Image
     */
    private function handleImageUpload($image)
    {
        $filename = time() . '_' . $image->getClientOriginalName();

        // Pastikan folder storage ada
        if (!Storage::exists('public/menu')) {
            Storage::makeDirectory('public/menu');
        }

        // Simpan langsung tanpa resize
        $image->storeAs('menu', $filename, 'public');

        return $filename;
    }

    // Change parameter name from $menu to $slug to match route parameter
    public function show($slug)
    {
        $menu = MasterMenu::where('slug', $slug)->firstOrFail();
        return view('admin.menu.show', compact('menu'));
    }
}