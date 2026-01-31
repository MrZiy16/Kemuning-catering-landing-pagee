<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\StatusLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

use App\Models\Customer;
use App\Models\DetailTransaksi;
use App\Models\MasterTransaksi;
use App\Models\Payment;
use App\Models\Produk;
use App\Models\MasterMenu;
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

    // ========== PEMBEDAAN ROLE ==========
    // Jika ADMIN, filter hanya transaksi dengan status semua kecuali delivered
    if (auth()->user()->role === 'admin') {
        $query->where('status', '!=', 'completed');
    }
    // Jika role lain (misalnya super_admin), lihat SEMUA

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

    // Status counts for filter buttons, disesuaikan dengan role
    $statusQuery = MasterTransaksi::query();
    if (auth()->user()->role === 'admin') {
        $statusQuery->where('status', '!=', 'delivered');
    }
    $statusCounts = [
        'all' => $statusQuery->clone()->count(),
        'pending' => $statusQuery->clone()->where('status', 'pending')->count(),
        'confirmed' => $statusQuery->clone()->where('status', 'confirmed')->count(),
        'preparing' => $statusQuery->clone()->where('status', 'preparing')->count(),
        'ready' => $statusQuery->clone()->where('status', 'ready')->count(),
        'delivered' => $statusQuery->clone()->where('status', 'delivered')->count(),
        'completed' => $statusQuery->clone()->where('status', 'completed')->count(),
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
    // 🔥 TAMBAH: Get shipping zones
    $shippingZones = \App\Models\ShippingZone::select('id', 'nama_zona', 'ongkir', 'keterangan')
                                            ->orderBy('ongkir')
                                            ->get();
    return view('admin.transaksi.index', compact('transaksis', 'statusCounts', 'customers', 'produk', 'menus', 'shippingZones'));
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
public function checkEmail(Request $request)
{
    try {
        $email = $request->query('email');
        
        if (!$email) {
            return response()->json(['exists' => false]);
        }

        $customer = Customer::where('email', $email)
                           ->select('id_customer', 'nama', 'no_hp')
                           ->first();
        
        if ($customer) {
            return response()->json([
                'exists' => true,
                'id_customer' => $customer->id_customer,
                'nama' => $customer->nama,
                'no_hp' => $customer->no_hp
            ]);
        }

        return response()->json(['exists' => false]);
    } catch (\Exception $e) {
        return response()->json(['exists' => false], 500);
    }
}
public function store(Request $request)
{
    // Validasi input - TAMBAH shipping_zone_id
    $validated = $request->validate([
        'customer_nama' => 'required|string|max:100',
        'customer_no_hp' => 'required|string|max:15',
        'customer_email' => 'nullable|email|max:70',
        'customer_alamat' => 'nullable|string',
        'tanggal_acara' => 'required|date|after_or_equal:today',
        'waktu_acara' => 'required',
        'alamat_pengiriman' => 'required|string',
        'catatan_customer' => 'nullable|string',
        'shipping_zone_id' => 'required|exists:shipping_zones,id', // 🔥 TAMBAH
        'items' => 'required|array|min:1',
        'items.*.type' => 'required|in:produk,menu',
        'items.*.id' => 'required|integer',
        'items.*.qty' => 'required|integer|min:1',
        'items.*.harga' => 'required|numeric|min:0',
        'payment_type' => 'required|in:full,dp',
        'payment_amount' => 'required|numeric',
    ]);

    try {
        DB::beginTransaction();

        // LOGIC: Cek berdasarkan EMAIL
        $customer = null;

        if ($request->filled('customer_email')) {
            $customer = Customer::where('email', $request->customer_email)->first();

            if ($customer) {
                $customer->update([
                    'nama' => $request->customer_nama,
                    'no_hp' => $request->customer_no_hp,
                    'alamat' => $request->customer_alamat,
                ]);
            } else {
                $customer = Customer::create([
                    'user_id' => null,
                    'nama' => $request->customer_nama,
                    'email' => $request->customer_email,
                    'no_hp' => $request->customer_no_hp,
                    'alamat' => $request->customer_alamat,
                    'source' => 'offline',
                ]);
            }
        } else {
            $customer = Customer::create([
                'user_id' => null,
                'nama' => $request->customer_nama,
                'email' => null,
                'no_hp' => $request->customer_no_hp,
                'alamat' => $request->customer_alamat,
                'source' => 'offline',
            ]);
        }

        // 🔥 TAMBAH: Ambil ongkir dari ShippingZone
        $shippingZone = \App\Models\ShippingZone::findOrFail($request->shipping_zone_id);
        $ongkir = $shippingZone->ongkir;

        // Hitung subtotal dari items
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['qty'] * $item['harga'];
        }

        // 🔥 TAMBAH: Total = subtotal + ongkir
        $total = $subtotal + $ongkir;

        // Validasi payment amount
        $paymentAmount = $request->payment_amount;
        $paymentType = $request->payment_type;
        $paymentStatus = ($paymentType === 'full') ? 'paid' : 'remaining';

        if ($paymentType === 'full' && $paymentAmount != $total) {
            throw new \Exception('Untuk full payment, jumlah harus sama dengan total transaksi');
        } elseif ($paymentType === 'dp' && $paymentAmount >= $total) {
            throw new \Exception('Untuk DP, jumlah harus kurang dari total transaksi');
        }

        // Generate ID transaksi unik
        $today = Carbon::now()->format('Ymd');
        $countToday = MasterTransaksi::where('id_transaksi', 'like', "TRX-$today%")->count() + 1;
        $id_transaksi = "TRX-$today" . str_pad($countToday, 3, '0', STR_PAD_LEFT);

        // 🔥 TAMBAH: shipping_zone_id & ongkir ke master_transaksi
        $transaksi = MasterTransaksi::create([
            'id_transaksi' => $id_transaksi,
            'id_customer' => $customer->id_customer,
            'tanggal_transaksi' => Carbon::now()->format('Y-m-d'),
            'tanggal_acara' => $request->tanggal_acara,
            'waktu_acara' => $request->waktu_acara,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'total' => $total, // Total sudah + ongkir
            'status' => 'pending',
            'catatan_customer' => $request->catatan_customer,
            'catatan_admin' => null,
            'shipping_zone_id' => $request->shipping_zone_id, // 🔥 TAMBAH
            'ongkir' => $ongkir, // 🔥 TAMBAH
        ]);

        // Insert detail transaksi
        foreach ($request->items as $item) {
            $id_produk = $item['type'] === 'produk' ? $item['id'] : null;
            $id_menu = $item['type'] === 'menu' ? $item['id'] : null;

            // Verifikasi harga dari database untuk keamanan
            $harga = $item['type'] === 'produk' 
                ? Produk::findOrFail($item['id'])->harga 
                : MasterMenu::findOrFail($item['id'])->harga_satuan;

            if ($harga != $item['harga']) {
                throw new \Exception('Harga item tidak sesuai');
            }

            DetailTransaksi::create([
                'id_transaksi' => $id_transaksi,
                'id_produk' => $id_produk,
                'id_menu' => $id_menu,
                'qty' => $item['qty'],
                'harga' => $harga,
                'subtotal' => $item['qty'] * $harga,
            ]);
        }

        // Insert pembayaran offline
        Payment::create([
            'master_transaction_id' => $id_transaksi,
            'method' => 'offline',
            'type' => $paymentType,
            'amount' => $paymentAmount,
            'payment_status' => $paymentStatus,
            'paid_at' => now(),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat!',
            'data' => $transaksi
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 422);
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
      
        'catatan_customer' => 'nullable|string',
        'catatan_admin' => 'nullable|string',
    ]);

    $transaksi->update([
        'tanggal_acara' => $request->tanggal_acara,
        'waktu_acara' => $request->waktu_acara,
     
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


// Di TransaksiController.php - Update method download()

public function download($id)
{
    // Ambil transaksi dengan relationship - PENTING: gunakan Model, bukan raw query!
    $transaksi = MasterTransaksi::with(['customer', 'shippingZone'])->findOrFail($id);

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

    // Hitung total yang sudah dibayar
    $total_paid = DB::table('payments')
        ->where('master_transaction_id', $id)
        ->whereIn('payment_status', ['paid', 'remaining'])
        ->sum('amount');

    // Tentukan status pembayaran
    $payment_status = 'unpaid';
    if ($total_paid > 0 && $total_paid >= $transaksi->total) {
        $payment_status = 'fully_paid';
    } elseif ($total_paid > 0) {
        $payment_status = 'partially_paid';
    }

    // Generate PDF dengan Eloquent Model (bukan stdClass)
    $pdf = Pdf::loadView('admin.transaksi.invoice', [
        'transaksi' => $transaksi,  // Ini sudah Eloquent Model
        'items' => $items,
        'total_paid' => $total_paid,
        'payment_status' => $payment_status
    ])->setPaper('a4', 'portrait');

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
