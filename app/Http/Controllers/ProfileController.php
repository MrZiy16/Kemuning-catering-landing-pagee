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
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }
    public function update(Request $request)
    {
        // Validasi input (hanya nama, telepon, dan kata_sandi)
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'kata_sandi' => 'nullable|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        // Update field basic
        $user->nama = $validated['nama'];
        $user->no_hp = $validated['no_hp'] ?? null;

        // Update password kalau diisi
        if (!empty($validated['kata_sandi'])) {
            $user->password = Hash::make($validated['kata_sandi']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
