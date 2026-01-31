@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit User</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama',$user->nama) }}">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}">
        </div>
        <div class="mb-3">
            <label>Password <small>(kosongkan jika tidak diganti)</small></label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp',$user->no_hp) }}">
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ old('alamat',$user->alamat) }}</textarea>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
    <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
    <option value="pelanggan" @if($user->role == 'pelanggan') selected @endif>Pelanggan</option>

    @if(Auth::user()->role == 'super_admin')
        <option value="super_admin" @if($user->role == 'super_admin') selected @endif>Super Admin</option>
    @endif
</select>

        </div>  
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="1" @if($user->status=='1') selected @endif>Active</option>
                <option value="0" @if($user->status=='0') selected @endif>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
