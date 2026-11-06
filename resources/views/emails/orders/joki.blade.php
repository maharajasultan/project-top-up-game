<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notifikasi Order Jasa Joki</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f5; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #8b5cf6; padding: 20px; text-align: center; color: #ffffff; }
        .content { padding: 30px; color: #374151; line-height: 1.6; }
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 4px; font-weight: bold; font-size: 14px; margin-bottom: 20px; }
        .status-pending_payment { background-color: #fef3c7; color: #92400e; }
        .status-waiting_process { background-color: #dbeafe; color: #1e40af; }
        .status-on_process { background-color: #f3e8ff; color: #6b21a8; }
        .status-completed { background-color: #d1fae5; color: #065f46; }
        .status-failed { background-color: #fee2e2; color: #991b1b; }
        .status-expired { background-color: #f3f4f6; color: #374151; }
        .details-table { w-full; border-collapse: collapse; margin-top: 20px; }
        .details-table th, .details-table td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .details-table th { color: #6b7280; font-weight: normal; width: 40%; }
        .details-table td { font-weight: bold; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
        .btn { display: inline-block; background-color: #8b5cf6; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">Informasi Order Jasa Joki</h1>
        </div>
        <div class="content">
            <p>Halo, pelanggan Yth.</p>
            <p>Berikut adalah informasi mengenai pesanan Jasa Joki Anda dengan nomor invoice <strong>{{ $order->invoice_number }}</strong>.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                @php
                    $statusClass = match($order->status) {
                        'pending_payment' => 'status-pending_payment',
                        'waiting_process' => 'status-waiting_process',
                        'on_process'      => 'status-on_process',
                        'completed'       => 'status-completed',
                        'failed'          => 'status-failed',
                        'expired'         => 'status-expired',
                        default           => 'status-pending_payment'
                    };
                    $statusLabel = match($order->status) {
                        'pending_payment' => 'Menunggu Pembayaran',
                        'waiting_process' => 'Menunggu Proses / Antrian',
                        'on_process'      => 'Sedang Dikerjakan',
                        'completed'       => 'Selesai',
                        'failed'          => 'Gagal',
                        'expired'         => 'Kadaluarsa',
                        default           => strtoupper($order->status)
                    };
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    Status: {{ $statusLabel }}
                </span>
            </div>

            <table class="details-table" style="width: 100%;">
                <tr>
                    <th>Invoice</th>
                    <td>{{ $order->invoice_number }}</td>
                </tr>
                <tr>
                    <th>Game</th>
                    <td>{{ $order->game->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Layanan Joki</th>
                    <td>{{ $order->jokiType->name ?? '-' }} ({{ $order->jokiTier->tier_name ?? '-' }})</td>
                </tr>
                <tr>
                    <th>Kuantitas (Match/Bintang)</th>
                    <td>{{ $order->qty }}</td>
                </tr>
                <tr>
                    <th>Email / ID Akun</th>
                    <td>{{ $order->account_email }}</td>
                </tr>
                <tr>
                    <th>Total Pembayaran</th>
                    <td>Rp {{ number_format($order->final_total_price, 0, ',', '.') }}</td>
                </tr>
            </table>

            @if($order->status == 'pending_payment')
                <div style="text-align: center;">
                    <a href="{{ $order->snap_token ? 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $order->snap_token : '#' }}" class="btn">Lanjutkan Pembayaran</a>
                </div>
            @elseif($order->status == 'waiting_process')
                <p style="margin-top: 20px; background-color: #fffbeb; padding: 15px; border-left: 4px solid #f59e0b; border-radius: 4px;">
                    <strong>Pembayaran Berhasil!</strong> Pesanan Anda telah masuk antrian. Admin/Worker kami akan segera memproses akun Anda. Anda bisa memantau status pesanan melalui website.
                </p>
            @elseif($order->status == 'on_process')
                <p style="margin-top: 20px; background-color: #f3e8ff; padding: 15px; border-left: 4px solid #8b5cf6; border-radius: 4px;">
                    <strong>Sedang Dikerjakan!</strong> Worker kami sedang login dan mengerjakan akun Anda. Mohon untuk <strong>TIDAK LOGIN</strong> ke dalam akun hingga status berubah menjadi Selesai.
                </p>
            @endif

            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">Jika Anda memiliki pertanyaan, silakan balas email ini atau hubungi Customer Service kami melalui chat website atau WhatsApp.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Maullstore. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
