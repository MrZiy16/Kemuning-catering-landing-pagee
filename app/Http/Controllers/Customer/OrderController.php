<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\MasterMenu;
use Illuminate\Http\Request;
use App\Models\MasterTransaksi;
use App\Models\DetailTransaksi;
use App\Models\StatusLog;
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

    // Custom Menu
    public function customMenu()
    {
        $menu = MasterMenu::where('status', 'active')->get();
        return view('customer.pemesanan.custom', compact('menu'));
    }
// Tambah ke keranjang
// Method untuk AJAX - get cart data
public function getCart()
{
    $cart = session()->get('cart', []);
    return response()->json([
        'success' => true,
        'cart' => $cart
    ]);
}

// Method untuk AJAX - add to cart
public function addToCartAjax(Request $request)
{
    $cart = session()->get('cart', []);
    $id_menu = $request->id_menu;

    if (isset($cart[$id_menu])) {
        $cart[$id_menu]['qty'] += $request->qty;
    } else {
        $cart[$id_menu] = [
            'id_menu' => $id_menu,
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'qty' => $request->qty,
        ];
    }

    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'message' => 'Menu berhasil ditambahkan ke keranjang!',
        'cart' => $cart
    ]);
}

public function updateCartAjax(Request $request)
{
    $cart = session()->get('cart', []);
    $id_menu = $request->id_menu;

    if (isset($cart[$id_menu])) {
        $cart[$id_menu]['qty'] = $request->qty;
        session()->put('cart', $cart);
    }

    return response()->json([
        'success' => true,
        'cart' => $cart
    ]);
}

public function removeFromCartAjax(Request $request)
{
    $cart = session()->get('cart', []);
    $id_menu = $request->id_menu;

    if (isset($cart[$id_menu])) {
        unset($cart[$id_menu]);
        session()->put('cart', $cart);
    }

    return response()->json([
        'success' => true,
        'cart' => $cart
    ]);
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

    // Checkout
    public function checkout(Request $request, Produk $produk)
    {
        if ($produk->kategori_produk === 'paket_box') {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:10', // paket box minimal 10
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
        } elseif ($produk->kategori_produk === 'prasmanan') {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:100', // prasmanan minimal 100
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
// Checkout custom menu dengan tanggal acara
public function checkoutCustom(Request $request)
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return response()->json([
            'success' => false,
            'message' => 'Keranjang kosong!'
        ]);
    }

    $request->validate([
        'tanggal_acara' => 'required|date|after_or_equal:today',
    ]);

    session([
        'checkout' => [
            'tipe' => 'custom',
            'menus' => $cart,
            'tanggal_acara' => $request->tanggal_acara,
        ]
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Checkout berhasil!'
    ]);
}
    // Form pengiriman
    public function formPengiriman()
    {
        $checkout = session('checkout');

        if (!$checkout) {
            return redirect()->route('pemesanan.index')->with('error', 'Data pesanan tidak ditemukan');
        }

        return view('customer.pemesanan.form_pengiriman', compact('checkout'));
    }

    // Konfirmasi pesanan
   // Konfirmasi pesanan
public function konfirmasiPesanan(Request $request)
{
    $request->validate([
        'alamat' => 'required|string|max:500',
        'waktu_acara' => 'required',
        'catatan' => 'nullable|string|max:500',
    ]);

    try {
        DB::beginTransaction();

        $checkout = session('checkout');
        if (!$checkout) {
            return redirect()->route('pemesanan.index')
                ->with('error', 'Data checkout tidak ditemukan');
        }

        $transactionId = $this->generateTransactionId();
        $total = 0;

        // Handle berdasarkan tipe pesanan
        if ($checkout['tipe'] === 'custom') {
            // Custom Menu - hitung total dari semua menu
            foreach ($checkout['menus'] as $menu) {
                $total += $menu['harga'] * $menu['qty'];
            }

            // Master transaksi
            $transaction = MasterTransaksi::create([
                'id_transaksi' => $transactionId,
                'id_customer' => Auth::id(),
                'tanggal_transaksi' => now()->toDateString(),
                'tanggal_acara' => $checkout['tanggal_acara'],
                'waktu_acara' => $request->waktu_acara,
                'alamat_pengiriman' => $request->alamat,
                'total' => $total,
                'status' => 'draft',
                'catatan_customer' => $request->catatan,
            ]);

            // Detail transaksi untuk setiap menu custom
            foreach ($checkout['menus'] as $menu) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaction->id_transaksi,
                    'id_produk' => null,
                    'id_menu' => $menu['id_menu'],
                    'qty' => $menu['qty'],
                    'harga' => $menu['harga'],
                    'subtotal' => $menu['harga'] * $menu['qty'],
                ]);
            }

            // Clear cart session juga
            session()->forget('cart');

        } else {
            // Paket Box atau Prasmanan
            $produk = Produk::findOrFail($checkout['produk_id']);
            $total = $checkout['quantity'] * $produk->harga;

            // Master transaksi
            $transaction = MasterTransaksi::create([
                'id_transaksi' => $transactionId,
                'id_customer' => Auth::id(),
                'tanggal_transaksi' => now()->toDateString(),
                'tanggal_acara' => $checkout['tanggal_acara'],
                'waktu_acara' => $request->waktu_acara,
                'alamat_pengiriman' => $request->alamat,
                'total' => $total,
                'status' => 'pending',
                'catatan_customer' => $request->catatan,
            ]);

            if ($checkout['tipe'] === 'prasmanan' && isset($checkout['menus'])) {
                // Prasmanan - simpan detail menu dari produk
                foreach ($checkout['menus'] as $menu) {
                    DetailTransaksi::create([
                        'id_transaksi' => $transaction->id_transaksi,
                        'id_produk' => $checkout['produk_id'],
                        'id_menu' => $menu['id_menu'],
                        'qty' => $menu['qty'] * $checkout['quantity'], // qty menu dikali quantity prasmanan
                          'harga' => $menu['harga'] ?? $produk->harga,
                        'subtotal' => $menu['harga'] * $menu['qty'] * $checkout['quantity'],
                    ]);
                }
            } else {
                // Paket Box - simpan detail produk
                DetailTransaksi::create([
                    'id_transaksi' => $transaction->id_transaksi,
                    'id_produk' => $checkout['produk_id'],
                    'id_menu' => null,
                    'qty' => $checkout['quantity'],
                    'harga' => $produk->harga,
                    'subtotal' => $checkout['quantity'] * $produk->harga,
                ]);
            }
        }

        // Log status (sama untuk semua tipe)
        StatusLog::create([
            'id_transaksi' => $transaction->id_transaksi,
            'status_from' => null,
            'status_to' => 'draft',
            'keterangan' => 'Pesanan dibuat',
            'created_by'   => Auth::id(),
        ]);

        // Clear checkout session
        session()->forget('checkout');
        DB::commit();

        return redirect()->route('payment.select', $transaction->id_transaksi)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage());
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
        return 'TRX-' . $date . $count;
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
