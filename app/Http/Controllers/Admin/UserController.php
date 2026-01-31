<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed',
            'role'=>'required|in:admin,pelanggan',
                'status'=>'required|in:0,1'
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'email_verified_at' => $request->has('email_verified') ? now() : null,
        ]);

        return redirect()->route('admin.users.index')->with('success','User berhasil dibuat!');
    }

    public function edit(User $user) {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'nama'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'role'=>'required|in:admin,pelanggan',
              'status'=>'required|in:0,1' 
        ]);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'email_verified_at' => $request->has('email_verified') ? now() : null,
        ]);

        if($request->password){
            $user->update(['password'=>Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success','User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->transaksi()->count() > 0) {
            return back()->with('error', 'User tidak dapat dihapus karena sudah digunakan dalam transaksi!');
        }

 

        $user->delete();
        return redirect()->route('admin.users.index')->with('success','User berhasil dihapus!');
    }
}
