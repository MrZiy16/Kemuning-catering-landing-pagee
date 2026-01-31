@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah User</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ old('alamat') }}</textarea>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="admin" @if(old('role')=='admin') selected @endif>Admin</option>
                <option value="pelanggan" @if(old('role')=='pelanggan') selected @endif>Pelanggan</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="1" @if(old('status')=='1') selected @endif>Active</option>
                <option value="0" @if(old('status')=='0') selected @endif>Inactive</option>
            </select>
        </div>
<div class="mb-3 form-check">
    <input type="checkbox" name="email_verified" class="form-check-input" id="email_verified"
        @if(old('email_verified', isset($user) && $user->email_verified_at)) checked @endif>
    <label class="form-check-label" for="email_verified">Setujui Verifikasi Email</label>
</div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
