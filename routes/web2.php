Route::prefix('payment')->group(function () {
    Route::get('/select/{transaction}', [PaymentController::class, 'selectPayment'])->name('payment.select');
    Route::post('/offline', [PaymentController::class, 'processOfflinePayment'])->name('payment.offline');
    Route::post('/online', [PaymentController::class, 'processOnlinePayment'])->name('payment.online');

    Route::get('/{payment}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/{payment}/failed', [PaymentController::class, 'failed'])->name('payment.failed');
    
    Route::get('/my-orders', [PaymentController::class, 'pending'])->name('payment.pending');
    Route::get('/{transaction}/remaining', [PaymentController::class, 'payRemaining'])->name('payment.remaining');
    Route::get('/{transaction}/continue', [PaymentController::class, 'continuePayment'])->name('payment.continue');
});

// Midtrans callback (tanpa CSRF)
Route::post('/midtrans/callback', [PaymentController::class, 'midtransCallback'])
    ->name('midtrans.callback')
    ->withoutMiddleware(['web']);


    */
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});
Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
  
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});