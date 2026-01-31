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
        $customer = auth()->user()->customer;

        if (!$customer) {
            abort(403);
        }

        $orders = MasterTransaksi::with('payments')
            ->where('id_customer', $customer->id_customer)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.pesanan_saya.index', compact('orders'));
    }

    public function detailMyOrder($id_transaksi)
    {
        $customer = auth()->user()->customer;

        if (!$customer) {
            abort(403);
        }

        $order = MasterTransaksi::with([
                'detailTransaksi',
                'detailTransaksi.produk',
                'detailTransaksi.menu',
                'payments',
                'customer'
            ])
            ->where('id_transaksi', $id_transaksi)
            ->where('id_customer', $customer->id_customer) // 🔒 proteksi data
            ->firstOrFail();

        return view('customer.pesanan_saya.detail', compact('order'));
    }

    public function cancel($id_transaksi)
    {
        $customer = auth()->user()->customer;

        if (!$customer) {
            abort(403);
        }

        $order = MasterTransaksi::where('id_transaksi', $id_transaksi)
            ->where('id_customer', $customer->id_customer)
            ->firstOrFail();

        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);

            return redirect()
                ->route('pesanan.index')
                ->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()
            ->route('pesanan.index')
            ->with('error', 'Pesanan tidak bisa dibatalkan.');
    }

    public function confirmDelivery($id_transaksi)
    {
        $customer = auth()->user()->customer;

        if (!$customer) {
            abort(403);
        }

        $order = MasterTransaksi::where('id_transaksi', $id_transaksi)
            ->where('id_customer', $customer->id_customer)
            ->firstOrFail();

        if ($order->status !== 'delivered') {
            return redirect()
                ->back()
                ->with('error', 'Pesanan tidak dalam status dikirim');
        }

        $order->update(['status' => 'completed']);

        return redirect()
            ->back()
            ->with('success', 'Pesanan berhasil dikonfirmasi sebagai selesai');
    }

    public function index()
    {
        $bestSellers = DetailTransaksi::select('id_produk')
            ->selectRaw('COUNT(*) as total_sales')
            ->whereNotNull('id_produk')
            ->groupBy('id_produk')
            ->with(['produk:id_produk,nama_produk,harga,gambar,deskripsi'])
            ->orderByDesc('total_sales')
            ->take(3)
            ->get()
            ->pluck('produk')
            ->filter();

        return view('customer.home', compact('bestSellers'));
    }
}
