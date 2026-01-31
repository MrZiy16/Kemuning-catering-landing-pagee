@extends('layouts.app')

@section('content')
<style>
    .user-management {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-warning {
        background: #ffc107;
        color: #000;
    }

    .btn-warning:hover {
        background: #e0a800;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    .alert {
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    /* Desktop Table View */
    .table-wrapper {
        overflow-x: auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .user-table thead {
        background: #343a40;
        color: white;
    }

    .user-table th,
    .user-table td {
        padding: 14px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    .user-table th {
        font-weight: 600;
        font-size: 14px;
    }

    .user-table tbody tr:hover {
        background: #f8f9fa;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .badge-success {
        background: #28a745;
        color: white;
    }

    .badge-secondary {
        background: #6c757d;
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-buttons form {
        display: inline-block;
        margin: 0;
    }

    /* Mobile Card View */
    .user-cards {
        display: none;
    }

    .user-card {
        background: white;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .user-card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #dee2e6;
    }

    .user-card-header h3 {
        margin: 0;
        font-size: 18px;
        color: #333;
    }

    .user-card-body {
        margin-bottom: 16px;
    }

    .user-info-row {
        display: flex;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .user-info-label {
        font-weight: 600;
        min-width: 80px;
        color: #666;
    }

    .user-info-value {
        color: #333;
        flex: 1;
    }

    .user-card-footer {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .empty-state svg {
        width: 80px;
        height: 80px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    /* Responsive Breakpoints */
    @media (max-width: 768px) {
        .user-management {
            padding: 16px;
        }

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .page-header h1 {
            font-size: 24px;
        }

        .btn-primary {
            width: 100%;
            text-align: center;
        }

        .table-wrapper {
            display: none;
        }

        .user-cards {
            display: block;
        }
    }

    @media (max-width: 480px) {
        .page-header h1 {
            font-size: 20px;
        }

        .user-card {
            padding: 12px;
        }

        .user-card-header h3 {
            font-size: 16px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
    }
</style>

<div class="user-management">
    <div class="page-header">
        <h1>Kelola User</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Tambah User</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($users->isEmpty())
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <p>Belum ada user terdaftar</p>
        </div>
    @else
        <!-- Desktop Table View -->
        <div class="table-wrapper">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                    <td>{{ $loop->iteration }}</td> 
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->no_hp }}</td>
                        <td>{{ $user->alamat }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            @if($user->status == '1')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user ini?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="user-cards">
            @foreach($users as $user)
            <div class="user-card">
                <div class="user-card-header">
                    <h3>{{ $user->nama }}</h3>
                    @if($user->status == '1')
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-secondary">Inactive</span>
                    @endif
                </div>
                <div class="user-card-body">
                    <div class="user-info-row">
                        <span class="user-info-label">ID:</span>
                        <span class="user-info-value">{{ $user->id }}</span>
                    </div>
                    <div class="user-info-row">
                        <span class="user-info-label">Email:</span>
                        <span class="user-info-value">{{ $user->email }}</span>
                    </div>
                    <div class="user-info-row">
                        <span class="user-info-label">No HP:</span>
                        <span class="user-info-value">{{ $user->no_hp }}</span>
                    </div>
                    <div class="user-info-row">
                        <span class="user-info-label">Alamat:</span>
                        <span class="user-info-value">{{ $user->alamat }}</span>
                    </div>
                    <div class="user-info-row">
                        <span class="user-info-label">Role:</span>
                        <span class="user-info-value">{{ ucfirst($user->role) }}</span>
                    </div>
                </div>
                <div class="user-card-footer">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user ini?')">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection