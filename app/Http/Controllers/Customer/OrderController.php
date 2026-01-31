<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\MasterMenu;
use Illuminate\Http\Request;
use App\Models\MasterTransaksi;
use App\Models\DetailTransaksi;
use App\Models\ShippingZone;
use App\Models\StatusLog;
use App\Models\Customer; // TAMBAH INI
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // Halaman pilih jenis pemesanan
    public function index()
    {
        return view('customer.pemesanan.index');
    }

    // Paket Box → ambil produk kategori paket_box
    public function paketBox()
    {
        $produk = Produk::where('kategori_produk', 'paket_box')
                        ->where('status', 'active')
                        ->get();

        return view('customer.pemesanan.paket_box', compact('produk'));
    }

    // Prasmanan → ambil produk kategori prasmanan
    public function prasmanan()
    {
        $produk = Produk::where('kategori_produk', 'prasmanan')
                        ->where('status', 'active')
                        ->get();

        return view('customer.pemesanan.prasmanan', compact('produk'));
    }

    // Custom Menu / Pondokan
    public function pondokan()
    {
        $products = Produk::where('kategori_produk', 'pondokan')
                          ->where('status', 'active')
                          ->orderBy('nama_produk')
                          ->get();
        
        // Ambil cart dari session
        $cart = session()->get('pondokan_cart', []);
        
        // Normalisasi struktur cart
        if (!isset($cart['items'])) {
            $cart = [
                'items' => [],
                'qty_total' => 10,
                'tanggal_acara' => null,
            ];
            session()->put('pondokan_cart', $cart);
        }
        
        // Pastikan qty_total dan tanggal_acara ada
        if (!isset($cart['qty_total'])) {
            $cart['qty_total'] = 10;
        }
        if (!isset($cart['tanggal_acara'])) {
            $cart['tanggal_acara'] = null;
        }
        
        return view('customer.pemesanan.custom', compact('products', 'cart'));
    }

    // Tumpeng
    public function tumpeng()
    {
        $produk = Produk::where('kategori_produk', 'tumpeng')
                          ->where('status', 'active')
                          ->orderBy('nama_produk')
                          ->get();
        
        return view('customer.pemesanan.tumpeng', compact('produk'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'tanggal_acara' => 'required|date|after_or_equal:today',
        ]);

        $produk = Produk::find($request->id_produk);
        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan']);
        }

        $cart = session()->get('pondokan_cart', [
            'items' => [],
            'qty_total' => 10,
            'tanggal_acara' => null,
        ]);

        // Update tanggal acara
        $cart['tanggal_acara'] = $request->tanggal_acara;

        // Tambah item ke cart
        $cart['items'][$request->id_produk] = [
            'id_produk' => $produk->id_produk,
            'nama_produk' => $produk->nama_produk,
            'harga' => $produk->harga,
            'gambar' => $produk->gambar,
        ];

        session()->put('pondokan_cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan',
            'cart_count' => count($cart['items']),
        ]);
    }

    public function updateQty(Request $request)
    {
        $request->validate([
            'qty_total' => 'required|integer|min:10',
        ]);

        $cart = session()->get('pondokan_cart', [
            'items' => [],
            'qty_total' => 10,
            'tanggal_acara' => null,
        ]);
        
        $cart['qty_total'] = $request->qty_total;
        
        session()->put('pondokan_cart', $cart);

        return response()->json(['success' => true]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate(['id_produk' => 'required|exists:produk,id_produk']);

        $cart = session()->get('pondokan_cart', [
            'items' => [],
            'qty_total' => 10,
            'tanggal_acara' => null,
        ]);
        
        if (isset($cart['items'][$request->id_produk])) {
            unset($cart['items'][$request->id_produk]);
            session()->put('pondokan_cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    public function clearCart()
    {
        // Reset cart ke struktur default
        session()->put('pondokan_cart', [
            'items' => [],
            'qty_total' => 10,
            'tanggal_acara' => null,
        ]);
        
        return response()->json(['success' => true]);
    }

    // Detail Paket Box
    public function paketBoxDetail(Produk $produk)
    {
        return view('customer.pemesanan.paket_box_detail', compact('produk'));
    }

    // Detail Prasmanan
    public function prasmananDetail(Produk $produk)
    {
        return view('customer.pemesanan.prasmanan_detail', compact('produk'));
    }

    // Checkout Paket Box / Prasmanan
    public function checkout(Request $request, Produk $produk)
    {
        if ($produk->kategori_produk === 'paket_box') {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:10',
                'tanggal_acara' => 'required|date|after_or_equal:today',
            ]);

            session([
                'checkout' => [
                    'tipe' => 'paket_box',
                    'produk_id' => $produk->id_produk,
                    'produk_nama' => $produk->nama_produk,
                    'quantity' => $validated['quantity'],
                    'tanggal_acara' => $validated['tanggal_acara'],
                ]
            ]);
        } elseif ($produk->kategori_produk === 'tumpeng') {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:10',
                'tanggal_acara' => 'required|date|after_or_equal:today',
            ]);

            session([
                'checkout' => [
                    'tipe' => 'tumpeng',
                    'produk_id' => $produk->id_produk,
                    'produk_nama' => $produk->nama_produk,
                    'quantity' => $validated['quantity'],
                    'tanggal_acara' => $validated['tanggal_acara'],
                ]       
            ]);
        } elseif ($produk->kategori_produk === 'prasmanan') {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:100',
                'tanggal_acara' => 'required|date|after_or_equal:today',
            ]);

            $menus = $produk->menuItems->map(function ($item) {
                return [
                    'id_menu' => $item->id_menu,
                    'nama_menu' => $item->nama_menu,
                    'qty' => $item->pivot->qty,
                    'harga' => $item->harga,
                ];
            })->toArray();

            session([
                'checkout' => [
                    'tipe' => 'prasmanan',
                    'produk_id' => $produk->id_produk,
                    'produk_nama' => $produk->nama_produk,
                    'quantity' => $validated['quantity'],
                    'tanggal_acara' => $validated['tanggal_acara'],
                    'menus' => $menus,
                ]
            ]);
        }

        return redirect()->route('pemesanan.pengiriman');
    }

    // Checkout Custom Menu
    public function checkoutPondokan()
    {
        $cart = session()->get('pondokan_cart', []);

        // Validasi cart kosong
        if (empty($cart['items'])) {
            return redirect()->back()->with('error', 'Keranjang pondokan masih kosong!');
        }

        // Validasi tanggal acara
        if (empty($cart['tanggal_acara'])) {
            return redirect()->back()->with('error', 'Tanggal acara belum dipilih!');
        }

        // Buat daftar nama produk untuk tampil di view / session
        $produk_nama = implode(', ', array_map(fn($item) => $item['nama_produk'], $cart['items']));

        // Simpan ke session checkout
        session([
            'checkout' => [
                'tipe' => 'pondokan',
                'items' => $cart['items'],
                'qty_total' => $cart['qty_total'],
                'tanggal_acara' => $cart['tanggal_acara'],
                'produk_nama' => $produk_nama,
            ]
        ]);

        return redirect()->route('pemesanan.pengiriman');
    }

    // Form pengiriman
  public function formPengiriman()
{
    $checkout = session('checkout');

    if (!$checkout) {
        return back()->with('error', 'Data pesanan tidak ditemukan');
    }

    // Ambil semua shipping zones
    $shippingZones = \App\Models\ShippingZone::all();

    // Hitung subtotal berdasarkan tipe (agar bisa dipass ke view untuk JS data-subtotal)
    $subtotal = 0;
    if ($checkout['tipe'] === 'pondokan') {
        foreach ($checkout['items'] as $item) {
            $subtotal += $item['harga'] * $checkout['qty_total'];
        }
    } else {
        $produk = \App\Models\Produk::findOrFail($checkout['produk_id']);
        $subtotal = $checkout['quantity'] * $produk->harga;
    }

    return view('customer.pemesanan.form_pengiriman', compact('checkout', 'shippingZones', 'subtotal'));
}

    // Konfirmasi pesanan - UPDATED ✅
public function konfirmasiPesanan(Request $request)
{
    $request->validate([
        'alamat' => 'required|string|max:500',
        'waktu_acara' => 'required',
        'shipping_zone_id' => 'required|exists:shipping_zones,id',
        'catatan' => 'nullable|string|max:500',
    ]);

    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    try {
        DB::beginTransaction();

        // AMBIL CUSTOMER DARI USER YANG LOGIN
        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer) {
            $customer = Customer::create([
                'user_id' => Auth::id(),
                'nama' => Auth::user()->nama,
                'email' => Auth::user()->email,
                'no_hp' => Auth::user()->no_hp,
                'alamat' => Auth::user()->alamat,
                'source' => 'online'
            ]);
        }

        $checkout = session('checkout');
        if (!$checkout) {
            return redirect()->route('pemesanan.index')->with('error', 'Data checkout tidak ditemukan');
        }

        $transactionId = $this->generateTransactionId();
        $subtotal = 0;
        $shippingZoneId = $request->shipping_zone_id;

        // Hitung subtotal berdasarkan tipe
        if ($checkout['tipe'] === 'pondokan') {
            foreach ($checkout['items'] as $item) {
                $subtotal += $item['harga'] * $checkout['qty_total'];
            }
        } else {
            $produk = Produk::findOrFail($checkout['produk_id']);
            $subtotal = $checkout['quantity'] * $produk->harga;
        }

        // Ambil ongkir dari ShippingZone
        $shippingZone = ShippingZone::findOrFail($shippingZoneId);
        $ongkir = $shippingZone->ongkir;

        // Total = subtotal + ongkir
        $total = $subtotal + $ongkir;

        // Buat transaksi
        $transaction = MasterTransaksi::create([
            'id_transaksi' => $transactionId,
            'id_customer' => $customer->id_customer,
            'tanggal_transaksi' => now()->toDateString(),
            'tanggal_acara' => $checkout['tanggal_acara'],
            'waktu_acara' => $request->waktu_acara,
            'alamat_pengiriman' => $request->alamat,
            'total' => $total,
            'status' => 'draft',
            'catatan_customer' => $request->catatan,
            'shipping_zone_id' => $shippingZoneId,
            'ongkir' => $ongkir,
        ]);

        // Buat detail transaksi
        if ($checkout['tipe'] === 'pondokan') {
            foreach ($checkout['items'] as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transactionId,
                    'id_menu' => $item['id_menu'],
                    'qty' => $checkout['qty_total'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['harga'] * $checkout['qty_total'],
                ]);
            }
        } else {
            DetailTransaksi::create([
                'id_transaksi' => $transactionId,
                'id_produk' => $checkout['produk_id'],
                'qty' => $checkout['quantity'],
                'harga' => Produk::find($checkout['produk_id'])->harga,
                'subtotal' => $subtotal,
            ]);
        }

        // Buat status log
        StatusLog::create([
            'id_transaksi' => $transactionId,
            'status_from' => null,
            'status_to' => 'draft',
            'keterangan' => 'Pesanan dibuat',
            'created_by' => Auth::id(),
        ]);

        // Clear session checkout
        session()->forget('checkout');

        DB::commit();

        return redirect()->route('payment.select', $transaction->id_transaksi)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error konfirmasi pesanan: ' . $e->getMessage());

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            ->withInput();
    }
}
    private function generateTransactionId()
    {
        $date = now()->format('Ymd');
        $count = MasterTransaksi::where('id_transaksi', 'like', 'TRX-' . $date . '%')->count() + 1;
        return 'TRX-' . $date . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function sukses($id)
    {
        $transaksi = MasterTransaksi::where('id_transaksi', $id)->firstOrFail();
        return view('customer.pemesanan.sukses', compact('transaksi'));
    }

    public function tracking($id)
    {
        $transaksi = MasterTransaksi::where('id_transaksi', $id)->firstOrFail();
        $logs = StatusLog::where('id_transaksi', $id)->orderBy('created_at')->get();

        return view('customer.pemesanan.tracking', compact('transaksi', 'logs'));
    }

    public function cancel($id)
    {
        $transaksi = MasterTransaksi::where('id_transaksi', $id)->firstOrFail();
        $transaksi->update(['status' => 'cancelled']);
        return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dibatalkan.');
    }
}