<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        return view('profile.edit');
    }
    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'telepon' => 'nullable|string|max:20',
            'kata_sandi' => 'nullable|string|min:6|confirmed', // "confirmed" = butuh field password_confirmation
        ]);

        $user = auth()->user();

        // Update field basic
        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->telepon = $validated['telepon'];

        // Update password kalau diisi
        if (!empty($validated['kata_sandi'])) {
            $user->kata_sandi = Hash::make($validated['kata_sandi']);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
