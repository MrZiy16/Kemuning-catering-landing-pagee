<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran - {{ $transaction->id_transaksi }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 5rem auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header {
            background: linear-gradient(135deg, #0bb32cff, #0bb32cff);
            padding: 32px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 2px,
                rgba(255, 255, 255, 0.03) 2px,
                rgba(255, 255, 255, 0.03) 4px
            );
            animation: shimmer 20s linear infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-50%) translateY(-50%) rotate(0deg); }
            100% { transform: translateX(-50%) translateY(-50%) rotate(360deg); }
        }

        .header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .transaction-card {
            margin: 32px;
            padding: 28px;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 20px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            position: relative;
            overflow: hidden;
        }

        .transaction-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #0bb32cff;
            background-size: 200% 100%;
            animation: gradientMove 3s ease infinite;
        }

        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .transaction-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 12px;
        }

        .transaction-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .transaction-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
        }

        .transaction-label {
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .transaction-value {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .payment-section {
            padding: 32px;
        }

        .tabs {
            display: flex;
            background: #f1f5f9;
            border-radius: 16px;
            padding: 4px;
            margin-bottom: 32px;
            position: relative;
        }

        .tab {
            flex: 1;
            text-align: center;
            padding: 16px 24px;
            cursor: pointer;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            color: #64748b;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
        }

        .tab.active {
            color: white;
            background: #0bb32cff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
            transform: translateY(-1px);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.4s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input, select {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
            background: white;
            transition: all 0.3s ease;
            color: #1f2937;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            transform: translateY(-1px);
        }

        input[readonly] {
            background: #f9fafb;
            color: #6b7280;
            cursor: not-allowed;
        }

        .btn {
            width: 100%;
            padding: 18px 24px;
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
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-wa {
            background: #0bb32cff;
            color: white;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
        }

        .btn-wa:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.5);
        }

        .btn-online {
            background: #0bb32cff;
            color: white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }

        .btn-online:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.5);
        }

        .note {
            margin-top: 16px;
            padding: 16px;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            color: #1e40af;
            font-size: 14px;
            line-height: 1.5;
            position: relative;
        }

        .note::before {
            content: '💡';
            font-size: 18px;
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 16px;
            }

            .header {
                padding: 24px 20px;
            }

            .header h2 {
                font-size: 24px;
            }

            .transaction-card {
                margin: 20px;
                padding: 20px;
            }

            .payment-section {
                padding: 20px;
            }

            .tabs {
                flex-direction: column;
                gap: 4px;
            }

            .transaction-grid {
                grid-template-columns: 1fr;
            }

            .tab {
                padding: 12px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<x-navbar></x-navbar>

<div class="container">
    <div class="header">
        <h2>Metode Pembayaran</h2>
        <p>Pilih cara pembayaran yang sesuai dengan kebutuhan Anda</p>
    </div>

    <div class="transaction-card">
        <div class="transaction-grid">
            <div class="transaction-item">
                <span class="transaction-label">ID Transaksi</span>
                <span class="transaction-value">{{ $transaction->id_transaksi }}</span>
            </div>
            <div class="transaction-item">
                <span class="transaction-label">Total Transaksi</span>
                <span class="transaction-value">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
            </div>
            <div class="transaction-item">
                <span class="transaction-label">Total Dibayar</span>
                <span class="transaction-value">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
            </div>
            <div class="transaction-item">
                <span class="transaction-label">Sisa</span>
                <span class="transaction-value" style="color: #dc2626;">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span>
            </div>
            <div class="transaction-item" id="total-section" style="grid-column: 1 / -1;">
                <span class="transaction-label">Total yang Harus Dibayar</span>
                <span id="total-value" class="transaction-value" style="font-size: 24px; color: #0bb32cff;">
                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                </span>
                <small id="fee-note" style="display:block; color:#64748b; font-size:13px; margin-top:8px;">
                    (Harga dapat berubah sesuai metode pembayaran)
                </small>
            </div>
        </div>
    </div>

    <div class="payment-section">
        <!-- Tab header -->
        <div class="tabs">
            <div class="tab active" onclick="openTab('offline', event)">
                <span>🏪 Offline</span>
            </div>
            <div class="tab" onclick="openTab('online', event)">
                <span>💳 Online</span>
            </div>
        </div>

        <!-- Tab content: Offline -->
        <div id="offline" class="tab-content active">
            <form action="{{ route('payment.offline') }}" method="POST">
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $transaction->id_transaksi }}">

                <div class="form-group">
                    <label for="payment_type_offline">Jenis Pembayaran</label>
                    <select name="payment_type" id="payment_type_offline" required>
                        @foreach($availableTypes as $type)
                            <option value="{{ $type }}">
                                {{ $type == 'dp' ? 'Down Payment (35%)' : 'Full Payment' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount_offline">Nominal</label>
                    <input type="number" id="amount_offline" name="amount" value="{{ $defaultAmount }}" readonly required>
                </div>

                <button type="submit" class="btn btn-wa">
                    Lanjutkan via WhatsApp
                </button>
            </form>

            <div class="note">
                Anda akan diarahkan ke WhatsApp untuk konfirmasi pembayaran ke admin kami. Proses verifikasi akan dilakukan dalam 1x24 jam.
            </div>
        </div>

        <!-- Tab content: Online -->
        <div id="online" class="tab-content">
            <form id="onlinePaymentForm">
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $transaction->id_transaksi }}">

                <div class="form-group">
                    <label for="payment_type_online">Jenis Pembayaran</label>
                    <select id="payment_type_online" name="payment_type" required>
                        @foreach($availableTypes as $type)
                            <option value="{{ $type }}">
                                {{ $type == 'dp' ? 'Down Payment (35%)' : 'Full Payment' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount_online">Nominal</label>
                    <input type="number" id="amount_online" name="amount" value="{{ $defaultAmount }}" readonly required>
                </div>

                <button type="button" class="btn btn-online" onclick="submitOnlinePayment()">
                    Bayar Sekarang
                </button>
            </form>

            <div class="note">
                @if($paymentMode === 'new')
                    DP otomatis dihitung 35% dari total transaksi. Pembayaran online menggunakan sistem Midtrans yang aman dan terpercaya.
                @else
                    Nominal otomatis sesuai sisa pembayaran. Pembayaran online menggunakan sistem Midtrans yang aman dan terpercaya.
                @endif
            </div>
            <div class="note">
                Harga sudah termasuk biaya administrasi pembayaran sebesar Rp4.440
            </div>
        </div>
    </div>
</div>

<script>
    // ===== Global Values (dari controller) =====
    const total = {{ $transaction->total }};
    const remainingAmount = {{ $remainingAmount }};
    const minDp = {{ $minDpAmount }};
    const paymentMode = "{{ $paymentMode }}"; // 'new' atau 'remaining'
    const adminFee = 4440; // Biaya admin untuk online payment

    // ===== Update Total Berdasarkan Tab =====
    const totalValueEl = document.getElementById('total-value');
    const feeNote = document.getElementById('fee-note');

    function updateTotalDisplay(mode) {
        let totalDisplay = total;
        
        if (mode === 'online') {
            totalDisplay += adminFee; // Tambah biaya admin
            feeNote.textContent = '(Termasuk biaya admin Rp ' + adminFee.toLocaleString('id-ID') + ')';
        } else {
            feeNote.textContent = '(Harga dapat berubah sesuai metode pembayaran)';
        }
        
        totalValueEl.textContent = 'Rp ' + totalDisplay.toLocaleString('id-ID');
        
        // Update amount field di tab online jika sedang di tab online
        if (mode === 'online') {
            updateAmountOnlineDisplay();
        }
    }

    // ===== Handle Tabs =====
    function openTab(tabName, event) {
        let tabs = document.querySelectorAll('.tab');
        let contents = document.querySelectorAll('.tab-content');

        tabs.forEach(t => t.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));

        document.getElementById(tabName).classList.add('active');
        event.currentTarget.classList.add('active');

        // Update total berdasarkan tab
        updateTotalDisplay(tabName);
    }

    // Set default display saat halaman pertama kali dimuat (tab offline aktif)
    updateTotalDisplay('offline');

    // ===== Offline Payment Logic =====
    const typeFieldOffline = document.getElementById('payment_type_offline');
    const amountFieldOffline = document.getElementById('amount_offline');

    function updateAmountOffline() {
        if (paymentMode === 'remaining') {
            amountFieldOffline.value = remainingAmount;
        } else {
            if (typeFieldOffline.value === 'dp') {
                // DP offline = 35% dari total (TANPA biaya admin)
                amountFieldOffline.value = Math.ceil(total * 0.35);
            } else {
                // Full offline = total (TANPA biaya admin)
                amountFieldOffline.value = total;
            }
        }
        amountFieldOffline.readOnly = true;
    }
    updateAmountOffline();
    typeFieldOffline.addEventListener('change', updateAmountOffline);

    // ===== Online Payment Logic =====
    const typeFieldOnline = document.getElementById('payment_type_online');
    const amountFieldOnline = document.getElementById('amount_online');

    function updateAmountOnlineDisplay() {
        let finalAmount = 0;
        
        if (paymentMode === 'remaining') {
            // Pelunasan = sisa + biaya admin
            finalAmount = remainingAmount + adminFee;
        } else {
            if (typeFieldOnline.value === 'dp') {
                // DP online = 35% dari (total + biaya admin)
                const totalWithFee = total + adminFee;
                finalAmount = Math.ceil(totalWithFee * 0.35);
            } else {
                // Full online = total + biaya admin
                finalAmount = total + adminFee;
            }
        }
        
        amountFieldOnline.value = finalAmount;
        amountFieldOnline.readOnly = true;
    }

    updateAmountOnlineDisplay();
    typeFieldOnline.addEventListener('change', updateAmountOnlineDisplay);

    // ===== Submit Online Payment =====
    function submitOnlinePayment() {
        const form = document.getElementById('onlinePaymentForm');
        const amount = parseInt(amountFieldOnline.value);
        
        fetch("{{ route('payment.online') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                transaction_id: form.querySelector('input[name="transaction_id"]').value,
                payment_type: typeFieldOnline.value,
                amount: amount
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.snap.pay(data.snap_token);
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(err => {
            alert("Terjadi error: " + err);
        });
    }
</script>
<script src="https://app.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}">
</script>
</body>
</html>
