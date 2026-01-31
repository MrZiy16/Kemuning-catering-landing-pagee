{{-- resources/views/payment/failed.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-times-circle"></i> Pembayaran Gagal
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Oops!</strong> Pembayaran Anda tidak dapat diproses. 
                        Silakan coba lagi atau hubungi customer service kami.
                    </div>

                    <!-- Detail Payment -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Detail Pembayaran</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>ID Pembayaran:</strong></td>
                                        <td>#{{ $payment->id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>ID Transaksi:</strong></td>
                                        <td>{{ $payment->master_transaction_id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jenis Pembayaran:</strong></td>
                                        <td>{{ $payment->type_label }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Metode:</strong></td>
                                        <td>{{ $payment->method_label }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jumlah:</strong></td>
                                        <td class="text-danger"><strong>{{ $payment->formatted_amount }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td><span class="badge {{ $payment->status_badge }}">{{ $payment->status_text }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal:</strong></td>
                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Kemungkinan Penyebab -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Kemungkinan Penyebab</h5>
                            <ul>
                                <li>Saldo kartu kredit/debit tidak mencukupi</li>
                                <li>Transaksi ditolak oleh bank penerbit</li>
                                <li>Koneksi internet terputus saat proses pembayaran</li>
                                <li>Session pembayaran sudah kedaluwarsa</li>
                                <li>Data kartu tidak valid atau salah</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Solusi -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Solusi</h5>
                            <div class="alert alert-info">
                                <h6><i class="fas fa-lightbulb"></i> Yang Dapat Anda Lakukan:</h6>
                                <ol>
                                    <li><strong>Coba Lagi:</strong> Ulangi proses pembayaran dengan metode yang sama</li>
                                    <li><strong>Ganti Metode:</strong> Gunakan kartu lain atau e-wallet</li>
                                    <li><strong>Cek Saldo:</strong> Pastikan saldo mencukupi</li>
                                    <li><strong>Hubungi Bank:</strong> Konfirmasi dengan bank penerbit kartu</li>
                                    <li><strong>Pembayaran Offline:</strong> Pilih transfer manual sebagai alternatif</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center">
                        <a href="{{ route('payment.select', $payment->master_transaction_id) }}" class="btn btn-primary">
                            <i class="fas fa-redo"></i> Coba Lagi
                        </a>
                        <a href="{{ route('payment.select', $payment->master_transaction_id) }}" class="btn btn-warning">
                            <i class="fas fa-money-bill"></i> Bayar Offline
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-home"></i> Kembali ke Dashboard
                        </a>
                    </div>

                    <!-- Customer Service Info -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-light">
                                <h6><i class="fas fa-headset"></i> Butuh Bantuan?</h6>
                                <p class="mb-2">Jika masalah terus berlanjut, silakan hubungi customer service kami:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="https://api.whatsapp.com/send?phone=6281234567890&text=Halo%2C%20saya%20mengalami%20masalah%20pembayaran%20untuk%20ID%3A%20{{ $payment->master_transaction_id }}" 
                                           class="btn btn-success btn-sm" target="_blank">
                                            <i class="fab fa-whatsapp"></i> WhatsApp: +62 812-3456-7890
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="mailto:cs@catering.com?subject=Masalah Pembayaran {{ $payment->master_transaction_id }}" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-envelope"></i> Email: cs@catering.com
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection