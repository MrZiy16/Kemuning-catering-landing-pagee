@extends('layouts.app')

@section('content')
<style>
    * {
        box-sizing: border-box;
    }
    
    .modern-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 24px;
    }
    
    .edit-header {
        margin-bottom: 32px;
        color: #1f2937;
        animation: fadeInDown 0.8s ease-out;
    }
    
    .edit-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .edit-subtitle {
        font-size: 16px;
        opacity: 0.9;
        font-weight: 400;
    }
    
    @keyframes fadeInDown {
        from { 
            opacity: 0; 
            transform: translateY(-30px);
        }
        to { 
            opacity: 1; 
            transform: translateY(0);
        }
    }
    
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: fadeInUp 0.8s ease-out;
        position: relative;
    }
    
    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #4f46e5, #7c3aed, #06b6d4, #10b981);
        background-size: 300% 100%;
        animation: gradientShift 4s ease infinite;
    }
    
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    @keyframes fadeInUp {
        from { 
            opacity: 0; 
            transform: translateY(30px);
        }
        to { 
            opacity: 1; 
            transform: translateY(0);
        }
    }
    
    .modern-card-body {
        padding: 40px;
    }
    
    .modern-alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 500;
        border: none;
        animation: slideIn 0.5s ease-out;
        position: relative;
        overflow: hidden;
    }
    
    .modern-alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .modern-alert:hover::before {
        left: 100%;
    }
    
    @keyframes slideIn {
        from { 
            opacity: 0; 
            transform: translateX(-20px);
        }
        to { 
            opacity: 1; 
            transform: translateX(0);
        }
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        color: #dc2626;
        border-left: 4px solid #ef4444;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        color: #16a34a;
        border-left: 4px solid #22c55e;
    }
    
    .form-section {
        margin-bottom: 32px;
        animation: fadeInLeft 0.6s ease-out;
        animation-fill-mode: both;
    }
    
    .form-section:nth-child(1) { animation-delay: 0.1s; }
    .form-section:nth-child(2) { animation-delay: 0.2s; }
    .form-section:nth-child(3) { animation-delay: 0.3s; }
    .form-section:nth-child(4) { animation-delay: 0.4s; }
    
    @keyframes fadeInLeft {
        from { 
            opacity: 0; 
            transform: translateX(-20px);
        }
        to { 
            opacity: 1; 
            transform: translateX(0);
        }
    }
    
    .modern-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
    }
    
    .modern-label::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 20px;
        height: 2px;
        background: linear-gradient(90deg, #4f46e5, #7c3aed);
        border-radius: 1px;
    }
    
    .modern-input {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 500;
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #1f2937;
        position: relative;
    }
    
    .modern-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        transform: translateY(-1px);
        background: #fefefe;
    }
    
    .modern-input:hover {
        border-color: #d1d5db;
        transform: translateY(-1px);
    }
    
    .modern-textarea {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 500;
        background: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #1f2937;
        resize: vertical;
        min-height: 120px;
        font-family: inherit;
    }
    
    .modern-textarea:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        transform: translateY(-1px);
        background: #fefefe;
    }
    
    .modern-textarea:hover {
        border-color: #d1d5db;
        transform: translateY(-1px);
    }
    
    .is-invalid {
        border-color: #ef4444 !important;
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .invalid-feedback {
        display: block;
        color: #dc2626;
        font-size: 14px;
        margin-top: 6px;
        font-weight: 500;
        animation: fadeInUp 0.3s ease-out;
    }
    
    .invalid-feedback::before {
        content: '⚠️';
        margin-right: 6px;
    }
    
    .button-group {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-top: 40px;
        animation: fadeInUp 0.8s ease-out;
        animation-delay: 0.5s;
        animation-fill-mode: both;
    }
    
    .modern-btn {
        padding: 16px 32px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 150px;
    }
    
    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .modern-btn:hover::before {
        left: 100%;
    }
    
    .btn-cancel {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.4);
    }
    
    .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(107, 114, 128, 0.5);
        color: white;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(79, 70, 229, 0.5);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    
    @media (max-width: 768px) {
        .modern-container {
            padding: 16px;
        }
        
        .modern-card-body {
            padding: 24px;
        }
        
        .edit-title {
            font-size: 24px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .button-group {
            flex-direction: column;
            gap: 12px;
        }
        
        .modern-btn {
            width: 100%;
            min-width: auto;
        }
    }
    
    .input-icon {
        position: relative;
    }
    
    .input-icon::before {
        content: attr(data-icon);
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
        color: #6b7280;
        z-index: 1;
        pointer-events: none;
    }
    
    .input-with-icon {
        padding-left: 50px;
    }
</style>

<div class="modern-container">
    <div class="edit-header">
        <h1 class="edit-title">Edit Transaksi</h1>
        <p class="edit-subtitle">#{{ $transaksi->id_transaksi }}</p>
    </div>

    {{-- Alert --}}
    @if(session('error'))
        <div class="modern-alert alert-danger">
            <strong>❌ Error!</strong> {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="modern-alert alert-success">
            <strong>✅ Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    <div class="modern-card">
        <div class="modern-card-body">
            <form action="{{ route('admin.transaksi.update', $transaksi->id_transaksi) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-section">
                        <label class="modern-label">Tanggal Acara</label>
                        <div class="input-icon" data-icon="📅">
                            <input type="date" 
                                   name="tanggal_acara" 
                                   class="modern-input input-with-icon @error('tanggal_acara') is-invalid @enderror"
                                   value="{{ old('tanggal_acara', $transaksi->tanggal_acara->format('Y-m-d')) }}" 
                                   required>
                        </div>
                        @error('tanggal_acara')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-section">
                        <label class="modern-label">Waktu Acara</label>
                        <div class="input-icon" data-icon="🕒">
                            <input type="time" 
                                   name="waktu_acara" 
                                   class="modern-input input-with-icon @error('waktu_acara') is-invalid @enderror"
                                   value="{{ old('waktu_acara', $transaksi->waktu_acara) }}" 
                                   required>
                        </div>
                        @error('waktu_acara')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section">
                    <label class="modern-label">Catatan Customer</label>
                    <div class="input-icon" data-icon="💬">
                        <textarea name="catatan_customer" 
                                  class="modern-textarea input-with-icon @error('catatan_customer') is-invalid @enderror" 
                                  rows="2" 
                                  placeholder="Catatan dari customer...">{{ old('catatan_customer', $transaksi->catatan_customer) }}</textarea>
                    </div>
                    @error('catatan_customer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-section">
                    <label class="modern-label">Catatan Admin</label>
                    <div class="input-icon" data-icon="📝">
                        <textarea name="catatan_admin" 
                                  class="modern-textarea input-with-icon @error('catatan_admin') is-invalid @enderror" 
                                  rows="2" 
                                  placeholder="Catatan internal admin...">{{ old('catatan_admin', $transaksi->catatan_admin) }}</textarea>
                    </div>
                    @error('catatan_admin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <a href="{{ route('admin.transaksi.show', $transaksi->id_transaksi) }}" 
                       class="modern-btn btn-cancel">
                        ← Batal
                    </a>
                    <button type="submit" class="modern-btn btn-save">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection