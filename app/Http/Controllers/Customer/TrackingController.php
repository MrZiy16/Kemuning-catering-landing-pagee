<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MasterTransaksi;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('customer.tracking.index');
    }

    public function track(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'required|string',
            'no_hp' => 'required|string'
        ]);

        $transaksi = MasterTransaksi::with([
            'customer',
            'statusLog',
            'detailTransaksi.produk',
            'detailTransaksi.menu',
            'transaksiMenuCustom.menu'
        ])
        ->where('id_transaksi', $request->id_transaksi)
        ->whereHas('customer', function ($query) use ($request) {
            $query->where('no_hp', $request->no_hp);
        })
        ->first();

        if (!$transaksi) {
            return back()->with('error', 'Pesanan tidak ditemukan atau nomor HP tidak cocok!');
        }

        return view('customer.tracking.detail', compact('transaksi'));
    }

    public function show($id, Request $request)
    {
        $transaksi = MasterTransaksi::with([
            'customer',
            'statusLog',
            'detailTransaksi.produk',
            'detailTransaksi.menu',
            'transaksiMenuCustom.menu'
        ])
        ->where('id_transaksi', $id)
        ->first();

        if (!$transaksi) {
            abort(404);
        }

        return view('customer.tracking.detail', compact('transaksi'));
    }
}
