@extends('profile.layout')

@section('title', 'Riwayat Pemesanan')

@section('content')
    <div class="container my-4">
        <h4 class="fw-bold mb-3">Riwayat Pemesanan</h4>

        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama Kost</th>
                    <th>Tanggal Sewa</th>
                    <th>Mulai Sewa</th>
                    <th>Lama Sewa</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $index => $booking)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $booking->kost->nama }}</td>
                        <td>{{ $booking->created_at->format('d-m-Y') }}</td>
                        <td>{{ $booking->tanggal_mulai ?? '-' }}</td>
                        <td class="text-center">{{ $booking->lama_sewa ?? '-' }} Bulan</td>
                        <td>Rp{{ number_format($booking->total ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            Belum ada pemesanan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection