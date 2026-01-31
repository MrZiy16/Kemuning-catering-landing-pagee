<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\WhatsappHelper;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\CustomerController;
use Barryvdh\DomPDF\Facade\Pdf;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================
// PUBLIC ROUTES
// =========================

// Ganti route ini:
// Route::get('/', fn () => view('customer.home'));

// Dengan route ini:
Route::get('/', [HomeController::class, 'index'])->name('home');

// =========================
// AUTHENTICATION REQUIRED ROUTES
// =========================
Route::middleware('auth')->group(function () {
    
    // Customer Orders Management
    Route::get('/my-orders', [HomeController::class, 'myOrder'])->name('pesanan.index');
    Route::get('/my-orders/{id}', [HomeController::class, 'detailMyorder'])->name('pesanan.detail');
    Route::post('/my-orders/{id}/cancel', [HomeController::class, 'cancel'])->name('order.cancel');
    Route::patch('/pesanan/{id}/confirm-delivery', [HomeController::class, 'confirmDelivery'])
        ->name('pesanan.confirm-delivery');
    
    // Payment Continuation
    // Route::get('/payment/{order}/continue', [PaymentController::class, 'continuePayment'])->name('payment.continue');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =========================
// ADMIN ROUTES
// =========================
Route::prefix('admin')->name('admin.')->middleware(['auth','isAdminOrSuperAdmin'])->group(function () {

    // Dashboard Management
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/update', [DashboardController::class, 'update'])->name('dashboard.update');
    Route::get('/dashboard/chart', [DashboardController::class, 'getChartData'])->name('dashboard.chart');
    Route::get('/dashboard/top-performance', [DashboardController::class, 'getTopPerformance'])->name('dashboard.top-performance');

    // Menu Management
    Route::resource('menu', MenuController::class)->parameters([
        'menu' => 'slug',
    ]);
    Route::post('menu/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])->name('menu.toggle-status');

    // Product Management
    Route::resource('produk', ProdukController::class)->parameters([
        'produk' => 'slug',
    ]);

    // User Management (Superadmin only)
    Route::resource('users', UserController::class)->middleware('isSuperadmin');
    
    // Transaction Management
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('transaksi/customers', [TransaksiController::class, 'getCustomers'])
        ->name('transaksi.customers');
        Route::get('customers/check-email', [TransaksiController::class, 'checkEmail'])->name('customers.check-email');
    Route::post('transaksi', [TransaksiController::class, 'store'])
        ->name('transaksi.store');
    Route::get('transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::post('transaksi/{transaksi}/update-status', [TransaksiController::class, 'updateStatus'])->name('transaksi.updateStatus');
  
    Route::post('transaksi/quick-status', [TransaksiController::class, 'quickStatus'])->name('transaksi.quickStatus');
    Route::get('transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->middleware('isSuperadmin')->name('transaksi.destroy');

    // Customer Management
    Route::resource('customers', CustomerController::class)->parameters([
        'customers' => 'customer',
    ]);

    // Payment Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentsController::class, 'index'])->name('index');
        Route::get('payments/transaction/{id}/details', [PaymentsController::class, 'getTransactionDetails']);
        Route::post('/', [PaymentsController::class, 'store'])->name('store');
        Route::get('/customers/search', [PaymentsController::class, 'searchCustomers'])->name('customers.search');
        Route::get('/transaction/{transactionId}/details', [PaymentsController::class, 'getTransactionDetails'])->name('transaction.details');
        Route::delete('/{payment}', [PaymentsController::class, 'destroy'])->middleware('isSuperadmin')->name('destroy');
        Route::get('/{payment}', [PaymentsController::class, 'show'])->name('show');
        Route::post('/{payment}/confirm', [PaymentsController::class, 'confirm'])->name('confirm');
        Route::post('/{payment}/reject', [PaymentsController::class, 'reject'])->name('reject');
        Route::post('/bulk-confirm', [PaymentsController::class, 'bulkConfirm'])->name('bulk-confirm');
        Route::get('/analytics/dashboard', [PaymentsController::class, 'analytics'])->name('analytics');
        Route::post('/reminder/{transaction}', [PaymentsController::class, 'sendReminder'])->name('send-reminder');
    });

    // Report Management (Superadmin only)
    Route::prefix('laporan')->name('laporan.')->middleware('isSuperadmin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('index');
        Route::get('/penjualan', [\App\Http\Controllers\Admin\LaporanController::class, 'penjualan'])->name('penjualan');
        Route::get('/produk', [\App\Http\Controllers\Admin\LaporanController::class, 'produk'])->name('produk');
        Route::get('/customer', [\App\Http\Controllers\Admin\LaporanController::class, 'customer'])->name('customer');
        Route::get('/pembayaran', [\App\Http\Controllers\Admin\LaporanController::class, 'pembayaran'])->name('pembayaran');
        Route::get('/operasional', [\App\Http\Controllers\Admin\LaporanController::class, 'operasional'])->name('operasional');
    });
});

// =========================
// CUSTOMER ORDER MANAGEMENT
// =========================
Route::get('/pemesanan', [OrderController::class, 'index'])->name('pemesanan.index');
Route::get('/paket-box', [OrderController::class, 'paketBox'])->name('pemesanan.paket-box');
Route::get('/prasmanan', [OrderController::class, 'prasmanan'])->name('pemesanan.prasmanan');
  Route::get('/pondokan', [OrderController::class, 'pondokan'])->name('pemesanan.pondokan');
  Route::get('/tumpeng', [OrderController::class, 'tumpeng'])->name('pemesanan.tumpeng');
Route::middleware('auth')->prefix('pemesanan')->name('pemesanan.')->group(function () {
    
    // Main Order Page
    
    // Order Type Selection

    Route::post('/pondokan/cart/add', [OrderController::class, 'addToCart'])->name('pondokan.cart.add');
    Route::post('/pondokan/cart/update-qty', [OrderController::class, 'updateQty'])->name('pondokan.cart.updateQty');
    Route::post('/pondokan/cart/remove', [OrderController::class, 'removeFromCart'])->name('pondokan.cart.remove');
    Route::post('/pondokan/cart/clear', [OrderController::class, 'clearCart'])->name('pondokan.cart.clear');    
    // Paket Box Orders
    Route::get('/paket-box/{produk}', [OrderController::class, 'paketBoxDetail'])->name('paket-box.detail');
    Route::post('/paket-box/{produk}/checkout', [OrderController::class, 'checkout'])->name('paket-box.checkout');

    // Prasmanan Orders
    Route::get('/prasmanan/{produk}', [OrderController::class, 'prasmananDetail'])->name('prasmanan.detail');
    Route::post('/prasmanan/{produk}/checkout', [OrderController::class, 'checkout'])->name('prasmanan.checkout');
    
       Route::get('/tumpeng/{produk}', [OrderController::class, 'paketBoxDetail'])->name('tumpeng.detail');
    Route::post('/paket-box/{produk}/checkout', [OrderController::class, 'checkout'])->name('tumpeng.checkout');

        // Checkout
    Route::post('/pemesanan/checkout-pondokan', [OrderController::class, 'checkoutPondokan'])
    ->name('checkoutPondokan');


    // Checkout Process & Confirmation
    Route::get('/pengiriman', [OrderController::class, 'formPengiriman'])->name('pengiriman');
    Route::post('/konfirmasi', [OrderController::class, 'konfirmasiPesanan'])->name('konfirmasi');
    Route::post('/cancel/{id}', [OrderController::class, 'cancel'])->name('cancel');
    
    // Order Tracking
    Route::get('/sukses/{id}', [OrderController::class, 'sukses'])->name('sukses');
    Route::get('/tracking/{id}', [OrderController::class, 'tracking'])->name('tracking');
});

// =========================
// PAYMENT MANAGEMENT
// =========================
Route::prefix('payment')->middleware(['auth'])->group(function () {
    
    // Payment Method Selection
    Route::get('/select/{transactionId}', [PaymentController::class, 'selectPayment'])->name('payment.select');
    
    // Payment Processing
    Route::post('/offline', [PaymentController::class, 'processOfflinePayment'])->name('payment.offline');
    Route::post('/online', [PaymentController::class, 'processOnlinePayment'])->name('payment.online');
    Route::post('/payment/manual', [PaymentController::class, 'manualTransfer'])->name('payment.manual');

    // Payment Result Pages
    Route::get('/{payment}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/{payment}/failed', [PaymentController::class, 'failed'])->name('payment.failed');
    
    // User Payment Management
    Route::get('/{payment}/pending', [PaymentController::class, 'pending'])->name('payment.pending');

    // Remaining Payment After Down Payment
    Route::get('/{transactionId}/remaining', [PaymentController::class, 'payRemaining'])->name('payment.remaining');
    
    // Continue Pending Payment
    Route::get('/{transactionId}/continue', [PaymentController::class, 'continuePayment'])->name('payment.continue');
});
  Route::get('/invoice/{id}/download', [TransaksiController::class, 'download'])->name('invoice.download');
// =========================
// EXTERNAL CALLBACKS
// =========================
// 
/*******  7694de92-79b5-45d5-a466-11107824692d  *******/
Route::get('/test-wa', function () {
    $pesan = "🚀 Notifikasi uji coba Fonnte dari Laravel berhasil dikirim!";
    $nomor = env('ADMIN_WA');

    $res = WhatsappHelper::kirimPesan($nomor, $pesan);

    return response()->json($res);
});
// =========================
// AUTHENTICATION ROUTES
// =========================
Route::post('/midtrans/callback', [PaymentController::class, 'midtransCallback'])
    ->name('midtrans.callback')
    ->withoutMiddleware(['web', 'auth', 'verified']);
require __DIR__.'/auth.php';
