<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notifikasi Order Top Up</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f5; margin: 0; padding: 20px; }
        .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #3b82f6; padding: 20px; text-align: center; color: #ffffff; }
        .content { padding: 30px; color: #374151; line-height: 1.6; }
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 4px; font-weight: bold; font-size: 14px; margin-bottom: 20px; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-success { background-color: #d1fae5; color: #065f46; }
        .status-failed { background-color: #fee2e2; color: #991b1b; }
        .status-expired { background-color: #f3f4f6; color: #374151; }
        .details-table { w-full; border-collapse: collapse; margin-top: 20px; }
        .details-table th, .details-table td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .details-table th { color: #6b7280; font-weight: normal; width: 40%; }
        .details-table td { font-weight: bold; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
        .btn { display: inline-block; background-color: #3b82f6; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">Informasi Transaksi Top Up</h1>
        </div>
        <div class="content">
            <p>Halo, pelanggan Yth.</p>
            <p>Berikut adalah informasi mengenai pesanan Top Up Anda dengan nomor invoice <strong>{{ $order->invoice_number }}</strong>.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                @php
                    $statusClass = match($order->status) {
                        'pending' => 'status-pending',
                        'success' => 'status-success',
                        'failed'  => 'status-failed',
                        'expired' => 'status-expired',
                        default   => 'status-pending'
                    };
                    $statusLabel = match($order->status) {
                        'pending' => 'Menunggu Pembayaran',
                        'success' => 'Transaksi Berhasil',
                        'failed'  => 'Transaksi Gagal',
                        'expired' => 'Kadaluarsa',
                        default   => strtoupper($order->status)
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
                    <th>Produk</th>
                    <td>{{ $order->product->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>ID Tujuan</th>
                    <td>{{ $order->user_game_id }} {{ $order->server_id ? '(' . $order->server_id . ')' : '' }}</td>
                </tr>
                <tr>
                    <th>Nickname</th>
                    <td>{{ $order->username_result ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Total Pembayaran</th>
                    <td>Rp {{ number_format($order->final_price, 0, ',', '.') }}</td>
                </tr>
            </table>

            @if($order->status == 'pending')
                <div style="text-align: center;">
                    <a href="{{ $order->snap_token ? 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $order->snap_token : '#' }}" class="btn">Lanjutkan Pembayaran</a>
                </div>
            @endif

            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">Jika Anda memiliki pertanyaan, silakan balas email ini atau hubungi Customer Service kami melalui WhatsApp.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Maullstore. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
