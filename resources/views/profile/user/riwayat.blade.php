@extends('profile.layout')

@section('title', 'Riwayat Pemesanan')

@section('content')
    <div class="container my-4">
        <h4 class="fw-bold mb-3">Riwayat Pemesanan</h4>

        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr class="text-center">
                    <th>No.</th>
                    <th>No. Booking</th>
                    <th>Nama Kost</th>
                    <th>Tanggal Sewa</th>
                    <th>Mulai Sewa</th>
                    <th>Lama Sewa</th>
                    <th>Harga</th>
                    <th>Status Pembayaran</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $index => $booking)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold">#{{ $booking->id }}</td>
                        <td>{{ $booking->kost->nama }}</td>
                        <td>{{ $booking->created_at->format('d-m-Y') }}</td>
                        <td>{{ $booking->tanggal_mulai ?? '-' }}</td>
                        <td class="text-center">{{ $booking->lama_sewa ?? '-' }} Bulan</td>
                        <td>Rp{{ number_format($booking->total ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @php
                                $status = $booking->payment->status ?? 'pending';
                                $badgeClass = match ($status) {
                                    'success', 'paid' => 'bg-success',
                                    'pending' => 'bg-warning text-dark',
                                    'failed', 'cancelled', 'expired' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                $statusLabel = match ($status) {
                                    'success', 'paid' => 'Berhasil',
                                    'pending' => 'Menunggu',
                                    'failed' => 'Gagal',
                                    'cancelled' => 'Dibatalkan',
                                    'expired' => 'Kadaluarsa',
                                    default => 'Belum Bayar'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="text-center">
                            @if ($status == 'success' || $status == 'paid')
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('booking.print', $booking->id) }}" target="_blank"
                                        class="btn btn-sm btn-info text-white" title="Cetak Bukti">
                                        <i class="bi bi-printer"></i> Print
                                    </a>

                                    @php
                                        $hasRating = $booking->rating;
                                        $btnClass = $hasRating ? 'btn-success' : 'btn-warning';
                                        $btnText = $hasRating ? 'Edit Rating' : 'Beri Rating';
                                        $btnIcon = $hasRating ? 'bi-pencil-square' : 'bi-star';
                                        $currentRating = $hasRating ? $booking->rating->rating : 5;
                                        $currentComment = $hasRating ? $booking->rating->comment : '';
                                    @endphp

                                    <button type="button" class="btn btn-sm {{ $btnClass }}" data-bs-toggle="modal"
                                        data-bs-target="#ratingModal-{{ $booking->id }}" title="{{ $btnText }}">
                                        <i class="bi {{ $btnIcon }}"></i> {{ $btnText }}
                                    </button>

                                    <!-- Modal Rating -->
                                    <div class="modal fade" id="ratingModal-{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $hasRating ? 'Edit Ulasan' : 'Beri Ulasan' }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('booking.rating.store', $booking->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body text-start">
                                                        <div class="mb-3">
                                                            <label class="form-label">Rating</label>
                                                            <select name="rating" class="form-select" required>
                                                                <option value="5" {{ $currentRating == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
                                                                <option value="4" {{ $currentRating == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (Bagus)</option>
                                                                <option value="3" {{ $currentRating == 3 ? 'selected' : '' }}>⭐⭐⭐ (Cukup)</option>
                                                                <option value="2" {{ $currentRating == 2 ? 'selected' : '' }}>⭐⭐ (Kurang)</option>
                                                                <option value="1" {{ $currentRating == 1 ? 'selected' : '' }}>⭐ (Sangat Kurang)</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Ulasan</label>
                                                            <textarea name="comment" class="form-control" rows="3"
                                                                placeholder="Tulis pengalamanmu...">{{ $currentComment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-3">
                            Belum ada pemesanan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection