<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MasterTransaksi;
use App\Models\DetailTransaksi;
use App\Models\TransaksiMenuCustom;
use App\Models\MasterMenu;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function create()
    {
        $menus = MasterMenu::active()->get()->groupBy('kategori_menu');
        $produks = Produk::active()->with('menuItems')->get();
        
        return view('customer.pesanan.create', compact('menus', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer.nama' => 'required|string|max:100',
            'customer.no_hp' => 'required|string|max:15',
            'customer.alamat' => 'required|string',
            'tanggal_acara' => 'required|date|after:today',
            'waktu_acara' => 'nullable|date_format:H:i',
            'alamat_pengiriman' => 'required|string',
            'catatan_customer' => 'nullable|string',
            'items' => 'required|array|min:1',
            'total' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request) {
            // Create or find customer
            $customer = User::firstOrCreate(
                ['no_hp' => $request->customer['no_hp']],
                [
                    'nama' => $request->customer['nama'],
                    'alamat' => $request->customer['alamat']
                ]
            );

            // Generate transaction ID
            $transactionId = MasterTransaksi::generateTransactionId('CTR');

            // Create transaction
            $transaksi = MasterTransaksi::create([
                'id_transaksi' => $transactionId,
                'id_customer' => $customer->id_customer,
                'tanggal_transaksi' => now()->toDateString(),
                'tanggal_acara' => $request->tanggal_acara,
                'waktu_acara' => $request->waktu_acara,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'total' => $request->total,
                'status' => 'pending',
                'catatan_customer' => $request->catatan_customer
            ]);

            // Add items
            foreach ($request->items as $item) {
                if ($item['type'] === 'produk') {
                    DetailTransaksi::create([
                        'id_transaksi' => $transactionId,
                        'id_produk' => $item['id'],
                        'qty' => $item['qty'],
                        'harga' => $item['harga'],
                        'subtotal' => $item['subtotal']
                    ]);
                } elseif ($item['type'] === 'menu') {
                    TransaksiMenuCustom::create([
                        'id_transaksi' => $transactionId,
                        'id_menu' => $item['id'],
                        'qty' => $item['qty'],
                        'harga' => $item['harga'],
                        'subtotal' => $item['subtotal'],
                        'catatan' => $item['catatan'] ?? null
                    ]);
                }
            }

            session(['last_order_id' => $transactionId]);
        });

        return redirect()->route('customer.pesanan.success');
    }

    public function success()
    {
        $orderId = session('last_order_id');
        if (!$orderId) {
            return redirect()->route('customer.home');
        }

        $transaksi = MasterTransaksi::with('customer')->find($orderId);
        
        return view('customer.pesanan.success', compact('transaksi'));
    }
}

