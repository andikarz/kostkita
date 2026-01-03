@extends('profile.layout')

@section('title', 'Dashboard Pemilik')

@section('content')
    <div class="siko-wrapper">
        {{-- MAIN --}}
        <main class="siko-main">


           
        {{-- Tabel Kelola Stok Kost --}}
        <div class="siko-panel mt-4">
            <div class="siko-panel-header mb-3">
                <div class="siko-panel-title">Kelola Stok Kost</div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kost</th>
                            <th>Lokasi</th>
                            <th>Harga</th>
                            <th style="width: 250px;">Stok Kamar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kosts as $kost)
                            <tr>
                                <td class="fw-bold">{{ $kost->nama }}</td>
                                <td>{{ $kost->kota }}</td>
                                <td>Rp{{ number_format($kost->harga_bulanan, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('kost.updateStock', $kost->id) }}" method="POST"
                                        class="d-flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="stok_kamar" class="form-control"
                                            value="{{ $kost->stok_kamar }}" min="0" required style="width: 80px;">
                                        <button type="submit" class="btn btn-primary btn-sm px-3">
                                            Update
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('kost.edit', $kost->id) }}" class="btn btn-sm btn-warning" title="Edit Kost">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Belum ada kost. Silakan tambah kost terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

            {{-- Tabel Daftar Pesanan --}}
            <div class="siko-panel mt-4">
                <div class="siko-panel-header mb-3">
                    <div class="siko-panel-title">Daftar Pesanan Masuk</div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Penyewa</th>
                                <th>Kost</th>
                                <th>Tgl Booking</th>
                                <th>Durasi</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ownerBookings as $booking)
                                <tr>
                                    <td class="fw-bold">#{{ $booking->id }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $booking->user->name }}</div>
                                        <div class="text-muted small">{{ $booking->user->email }}</div>
                                    </td>
                                    <td>{{ $booking->kost->nama }}</td>
                                    <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $booking->lama_sewa }} Bulan</td>
                                    <td>Rp{{ number_format($booking->total, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $status = $booking->payment->status ?? 'pending';
                                            $badgeClass = match ($status) {
                                                'success', 'paid' => 'bg-success',
                                                'pending' => 'bg-warning text-dark',
                                                'failed', 'cancelled', 'expired' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Belum ada pesanan masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

@endsection