<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pemesanan - #{{ $booking->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
        }

        .content {
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 50px;
        }

        .badge-success {
            color: green;
            font-weight: bold;
            border: 1px solid green;
            padding: 2px 5px;
            border-radius: 4px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Booking</button>
    </div>

    <div class="header">
        <div class="title">KOSTKITA</div>
        <div class="subtitle">Bukti Pemesanan Kost</div>
    </div>

    <div class="content">
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="border: none; width: 50%;">
                    <strong>Penyewa:</strong><br>
                    {{ $booking->user->name }}<br>
                    {{ $booking->user->email }}<br>
                    {{ $booking->user->phone ?? '-' }}
                </td>
                <td style="border: none; width: 50%; text-align: right;">
                    <strong>No. Booking:</strong> #{{ $booking->id }}<br>
                    <strong>Tanggal:</strong> {{ $booking->created_at->format('d F Y') }}<br>
                    <strong>Status:</strong> <span class="badge-success">LUNAS</span>
                </td>
            </tr>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nama Kost</td>
                    <td>{{ $booking->kost->nama }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>{{ $booking->kost->alamat }}</td>
                </tr>
                <tr>
                    <td>Durasi Sewa</td>
                    <td>{{ $booking->lama_sewa }} Bulan ({{ $booking->tanggal_mulai }} s/d
                        {{ \Carbon\Carbon::parse($booking->tanggal_mulai)->addMonths($booking->lama_sewa)->subDay()->format('Y-m-d') }})
                    </td>
                </tr>
                <tr>
                    <td>Harga per Bulan</td>
                    <td>Rp
                        {{ number_format($booking->kost->harga_per_bulan ?? ($booking->total / $booking->lama_sewa), 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td>Total Pembayaran</td>
                    <td>Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <p>Terima kasih telah menggunakan layanan KostKita.</p>
        <p>Simpan bukti ini sebagai referensi pemesanan Anda.</p>
    </div>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>

</html>