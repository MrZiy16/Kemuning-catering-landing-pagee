<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
   public function __invoke(EmailVerificationRequest $request): RedirectResponse
{
    // Jika sudah diverifikasi
    if ($request->user()->hasVerifiedEmail()) {
        return redirect('/');
    }

    // Tandai email sebagai terverifikasi
    if ($request->user()->markEmailAsVerified()) {
        event(new Verified($request->user()));
        return redirect('/');
    }

    // Kalau belum terverifikasi, kembalikan ke halaman verifikasi email
    return redirect()->route('verification.notice');
}

}
