<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterTransaksi;
use App\Models\DetailTransaksi;

class HomeController extends Controller
{
    public function myOrder()
    {
        $orders = MasterTransaksi::with('payments')
            ->where('id_customer', auth()->id()) // filter by user login
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.pesanan_saya.index', compact('orders'));
    }

    public function detailMyOrder($id_transaksi)

    {
        $order = MasterTransaksi::with(['detailTransaksi', 'detailTransaksi.produk', 'detailTransaksi.menu', 'payments', 'customer'])
      
            ->where('id_transaksi', $id_transaksi)
            ->firstOrFail();

        return view('customer.pesanan_saya.detail', compact('order'));
    }

    public function cancel($id_transaksi)
    {
        $order = MasterTransaksi::where('id_transaksi', $id_transaksi)
            ->where('id_customer', auth()->id())
            ->firstOrFail();

        if ($order->status == 'pending') {
            $order->status = 'cancelled';
            $order->save();

            return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->route('pesanan.index')->with('error', 'Pesanan tidak bisa dibatalkan.');
    }

    public function confirmDelivery($id)
{
    $order = MasterTransaksi::where('id_transaksi', $id)
        ->where('id_customer', auth()->id())
        ->firstOrFail();
    
    if ($order->status !== 'delivered') {
        return redirect()->back()->with('error', 'Pesanan tidak dalam status dikirim');
    }
    
    $order->update(['status' => 'completed']);
    
    return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi sebagai selesai');
}

public function index()
{
    // Get top 3 best selling products
    $bestSellers = DetailTransaksi::select('id_produk')
        ->selectRaw('COUNT(*) as total_sales')
        ->whereNotNull('id_produk')
        ->groupBy('id_produk')
        ->with(['produk' => function($query) {
            $query->select('id_produk', 'nama_produk', 'harga', 'gambar', 'deskripsi');
        }])
        ->orderBy('total_sales', 'desc')
        ->take(3)
        ->get()
        ->pluck('produk')
        ->filter(); // Remove null values

    return view('customer.home', compact('bestSellers'));
}
}
