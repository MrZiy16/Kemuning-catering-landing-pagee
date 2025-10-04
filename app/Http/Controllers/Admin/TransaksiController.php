<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterTransaksi;
use App\Models\StatusLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterTransaksi::with(['customer', 'latestStatus']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_acara', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_acara', '<=', $request->tanggal_akhir);
        }

        // Search customer
        if ($request->filled('search')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        $transaksis = $query->orderBy('created_at', 'desc')->paginate(10);

        // Status counts for filter buttons
        $statusCounts = [
            'all' => MasterTransaksi::count(),
            'pending' => MasterTransaksi::where('status', 'pending')->count(),
            'confirmed' => MasterTransaksi::where('status', 'confirmed')->count(),
            'preparing' => MasterTransaksi::where('status', 'preparing')->count(),
            'ready' => MasterTransaksi::where('status', 'ready')->count(),
            'delivered' => MasterTransaksi::where('status', 'delivered')->count(),
            'completed' => MasterTransaksi::where('status', 'completed')->count(),
        ];

        // Get customers for the modal
        $customers = User::where('role', 'pelanggan')
                        ->where('status', 1)
                        ->select('id', 'nama', 'no_hp', 'email')
                        ->orderBy('nama')
                        ->get();

        // Get products for the modal
        $produk = \App\Models\Produk::where('status', 'active')
                                   ->select('id_produk', 'nama_produk', 'harga', 'kategori_produk')
                                   ->orderBy('nama_produk')
                                   ->get();

        // Get menus for the modal
        $menus = \App\Models\MasterMenu::where('status', 'active')
                                      ->select('id_menu', 'nama_menu', 'harga_satuan', 'kategori_menu')
                                      ->orderBy('nama_menu')
                                      ->get();

        return view('admin.transaksi.index', compact('transaksis', 'statusCounts', 'customers', 'produk', 'menus'));
    }

    public function getCustomers()
{
    $customers = User::whereIn('role', ['admin', 'pelanggan'])
                    ->where('status', 1)
                    ->select('id', 'nama', 'no_hp')
                    ->orderBy('nama')
                    ->get();
    
    return response()->json(['customers' => $customers]);
}
public function store(Request $request)
{
    try {
        // Validasi input
        $request->validate([
            'id_customer' => 'required|exists:users,id',
            'tanggal_acara' => 'required|date|after_or_equal:today',
            'waktu_acara' => 'required',
            'alamat_pengiriman' => 'required|string|max:500',
            'catatan_customer' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|in:produk,menu',
            'items.*.id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        // Generate ID transaksi menggunakan fungsi database
        $idResult = DB::select("SELECT generate_transaction_id('TRX') as id");
        $transactionId = $idResult[0]->id;

        // Hitung total dari items
        $total = 0;
        foreach ($request->items as $item) {
            $total += $item['qty'] * $item['harga'];
        }

        // Buat transaksi baru
        $transaksi = MasterTransaksi::create([
            'id_transaksi' => $transactionId,
            'id_customer' => $request->id_customer,
            'tanggal_transaksi' => now()->toDateString(),
            'tanggal_acara' => $request->tanggal_acara,
            'waktu_acara' => $request->waktu_acara,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'total' => $total,
            'status' => 'pending', // Default status
            'catatan_customer' => $request->catatan_customer
        ]);

        // Simpan detail transaksi
        foreach ($request->items as $item) {
            \App\Models\DetailTransaksi::create([
                'id_transaksi' => $transactionId,
                'id_produk' => $item['type'] === 'produk' ? $item['id'] : null,
                'id_menu' => $item['type'] === 'menu' ? $item['id'] : null,
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga']
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil ditambahkan',
            'id_transaksi' => $transaksi->id_transaksi
        ]);

    } catch (ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Data tidak valid',
            'errors' => $e->errors()
        ], 422);

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Error creating transaction: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menyimpan transaksi'
        ], 500);
    }
}
    public function show(MasterTransaksi $transaksi)
    {
        $transaksi->load([
            'customer',
            'detailTransaksi.produk',
            'detailTransaksi.menu',
            'transaksiMenuCustom.menu',
            'statusLog'
        ]);

        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function edit(MasterTransaksi $transaksi)
{
    // Hanya boleh edit jika status draft, pending, confirmed
    if (!in_array($transaksi->status, ['draft', 'pending', 'confirmed'])) {
        return redirect()->route('admin.transaksi.index')
            ->with('error', 'Transaksi dengan status ini tidak dapat diedit!');
    }

    $transaksi->load(['customer', 'detailTransaksi.produk', 'detailTransaksi.menu', 'transaksiMenuCustom.menu']);

    return view('admin.transaksi.edit', compact('transaksi'));
}

public function update(Request $request, MasterTransaksi $transaksi)
{
    // Cek status dulu
    if (!in_array($transaksi->status, ['draft', 'pending', 'confirmed'])) {
        return redirect()->route('admin.transaksi.index')
            ->with('error', 'Transaksi dengan status ini tidak dapat diubah!');
    }

    $request->validate([
        'tanggal_acara' => 'required|date',
        'waktu_acara' => 'required',
        'alamat_pengiriman' => 'required|string',
        'catatan_customer' => 'nullable|string',
        'catatan_admin' => 'nullable|string',
    ]);

    $transaksi->update([
        'tanggal_acara' => $request->tanggal_acara,
        'waktu_acara' => $request->waktu_acara,
        'alamat_pengiriman' => $request->alamat_pengiriman,
        'catatan_customer' => $request->catatan_customer,
        'catatan_admin' => $request->catatan_admin,
    ]);

    return redirect()->route('admin.transaksi.show', $transaksi->id_transaksi)
        ->with('success', 'Transaksi berhasil diperbarui!');
}


  public function updateStatus(Request $request, MasterTransaksi $transaksi)
{
    $request->validate([
        'status' => 'required|in:pending,confirmed,preparing,ready,delivered,completed,cancelled',
        'keterangan' => 'nullable|string',
        'catatan_admin' => 'nullable|string'
    ]);

    if (!$transaksi->canUpdateStatus()) {
        return response()->json(['error' => 'Status transaksi tidak dapat diubah!'], 400);
    }

    $oldStatus = $transaksi->status;

    $transaksi->update([
        'status' => $request->status,
        'catatan_admin' => $request->catatan_admin
    ]);

    if ($request->filled('keterangan')) {
        StatusLog::create([
            'id_transaksi' => $transaksi->id_transaksi,
            'status_from' => $oldStatus,
            'status_to' => $request->status,
            'keterangan' => $request->keterangan,
            'created_by' => Auth::user()?->name 
        ]);
    }

    // 🔥 return JSON, bukan redirect
    return response()->json([
        'success' => true,
        'status' => $transaksi->status
    ]);
}


  public function download($id)
    {
        // Ambil data transaksi + customer
        $transaksi = DB::table('master_transaksi as t')
            ->join('users as u', 'u.id', '=', 't.id_customer')
            ->select('t.*', 'u.nama', 'u.no_hp', 'u.alamat as alamat_customer')
            ->where('t.id_transaksi', $id)
            ->first();

        // Ambil detail pesanan
        $items = DB::table('detail_transaksi as d')
            ->leftJoin('produk as p', 'p.id_produk', '=', 'd.id_produk')
            ->leftJoin('master_menu as m', 'm.id_menu', '=', 'd.id_menu')
            ->select(
                'd.qty',
                'd.harga',
                'd.subtotal',
                DB::raw("COALESCE(p.nama_produk, m.nama_menu) as nama_item")
            )
            ->where('d.id_transaksi', $id)
            ->get();

        // Ambil pembayaran terakhir
        $payment = DB::table('payments')
            ->where('master_transaction_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        $pdf = Pdf::loadView('admin.transaksi.invoice', compact('transaksi', 'items', 'payment'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("invoice-{$id}.pdf");
    }

    // Quick status updates for dashboard
    public function quickStatus(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'required|exists:master_transaksi,id_transaksi',
            'status' => 'required|in:confirmed,preparing,ready,delivered,completed'
        ]);

        $transaksi = MasterTransaksi::find($request->id_transaksi);
        
        if (!$transaksi->canUpdateStatus()) {
            return response()->json(['error' => 'Status tidak dapat diubah'], 400);
        }

        $transaksi->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $transaksi->status_text]);
    }

    public function destroy(MasterTransaksi $transaksi)
    {
        try {
            // Cek status dulu
            if (!in_array($transaksi->status, ['draft', 'pending', 'cancelled'])) {
                return redirect()->route('admin.transaksi.index')
                    ->with('error', 'Transaksi dengan status ini tidak dapat dihapus!');
            }

            DB::beginTransaction();

            // Cek apakah ada payment yang terkait
            $payments = $transaksi->payments;
            
            if ($payments->count() > 0) {
                Log::info("Deleting {$payments->count()} payments for transaction {$transaksi->id_transaksi}");
                
                // Hapus semua payment yang terkait dengan transaksi ini
                foreach ($payments as $payment) {
                    Log::info("Deleting payment ID: {$payment->id}, Status: {$payment->payment_status}");
                    $payment->delete();
                }
            }

            // Hapus detail transaksi
            $transaksi->detailTransaksi()->delete();
            
            // Hapus transaksi menu custom jika ada
            $transaksi->transaksiMenuCustom()->delete();
            
            // Hapus status log jika ada
            $transaksi->statusLog()->delete();

            // Hapus transaksi utama
            $transaksi->delete();

            DB::commit();

            Log::info("Transaction {$transaksi->id_transaksi} and all related data deleted successfully");

            return redirect()->route('admin.transaksi.index')
                ->with('success', 'Transaksi dan semua data terkait berhasil dihapus!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting transaction: ' . $e->getMessage());
            
            return redirect()->route('admin.transaksi.index')
                ->with('error', 'Terjadi kesalahan saat menghapus transaksi!');
        }
    }
}