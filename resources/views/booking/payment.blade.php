@extends('layouts.main')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <h4 class="mb-3">Pembayaran</h4>

      {{-- Alert bootstrap biasa (opsional) --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="card">
        <div class="card-body">
          <h5>Ringkasan Pembayaran</h5>
          <p class="mb-1">Kost: {{ $booking->kost->nama }}</p>
          <p class="mb-1">Lama sewa: {{ $booking->lama_sewa }} bulan</p>

          <div class="mb-3 border rounded p-3 bg-light mt-3">
            <h6 class="mb-3">Rincian Harga</h6>
            <div class="d-flex justify-content-between mb-1">
              <span>Harga/bulan</span>
              <span>Rp {{ number_format($booking->harga_per_bulan,0,',','.') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span>Subtotal ({{ $booking->lama_sewa }} bulan)</span>
              <span>
                Rp {{ number_format($booking->harga_per_bulan * $booking->lama_sewa,0,',','.') }}
              </span>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span>Biaya Layanan</span>
              <span>Rp {{ number_format($booking->pajak,0,',','.') }}</span>
            </div>
            <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
              <span>Total</span>
              <span>Rp {{ number_format($booking->total,0,',','.') }}</span>
            </div>
          </div>

          <hr>

          <p>Silakan pilih metode pembayaran dan upload bukti pembayaran.</p>

          {{-- POST ke route payment.store --}}
          <form method="POST"
                action="{{ route('booking.payment.store', $booking) }}"
                enctype="multipart/form-data">
            @csrf

            {{-- Metode Pembayaran --}}
            <div class="mb-3">
              <label class="form-label d-block">Metode Pembayaran</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="metode" value="transfer" id="tf"
                       {{ old('metode', 'transfer') === 'transfer' ? 'checked' : '' }}>
                <label class="form-check-label" for="tf">
                  Transfer Bank
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="metode" value="qris" id="qr"
                       {{ old('metode') === 'qris' ? 'checked' : '' }}>
                <label class="form-check-label" for="qr">
                  Barcode / QRIS
                </label>
              </div>
              @error('metode') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Info rekening / QRIS --}}
            <div class="mb-3">
              {{-- Transfer --}}
              <div id="transferSection">
                <h6>Transfer ke:</h6>
                <p class="mb-0">BCA 123456789 a/n Kost Kita</p>
                <p class="mb-0">BRI 987654321 a/n Kost Kita</p>
              </div>

              {{-- QRIS --}}
              <div id="qrisSection" style="display: none;">
                <h6>QRIS</h6>
                <p>Scan QR berikut jika memilih metode QRIS:</p>
                <img src="/img/qris.png" alt="QRIS" class="img-fluid border rounded">
              </div>
            </div>

            {{-- Upload Bukti Pembayaran --}}
            <div class="mb-3">
              <label class="form-label">Upload Bukti Pembayaran</label>
              <input type="file" name="bukti" class="form-control" required>
              @error('bukti') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button class="btn btn-success">Konfirmasi Pembayaran</button>
          </form>

        </div>
      </div>

      {{-- FORM RATING (kalau sudah paid) --}}
      @if($booking->status === 'paid')
        <div class="card mt-3">
          <div class="card-body">
            <h5 class="card-title mb-3">Beri Rating Kost Ini</h5>

            <form action="{{ route('booking.rating.store', $booking) }}" method="POST">
              @csrf
              <div class="mb-2">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-select" required>
                  <option value="">Pilih rating</option>
                  @for($i=5; $i>=1; $i--)
                    <option value="{{ $i }}" @selected(old('rating', optional($booking->rating)->rating) == $i)>
                      {{ $i }} ★
                    </option>
                  @endfor
                </select>
                @error('rating') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="mb-2">
                <label class="form-label">Ulasan (opsional)</label>
                <textarea name="comment" class="form-control" rows="3">{{ old('comment', optional($booking->rating)->comment) }}</textarea>
                @error('comment') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <button class="btn btn-primary mt-2">Kirim Rating</button>
            </form>
          </div>
        </div>
      @endif

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // SweetAlert sukses
    @if(session('success'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                confirmButtonColor: '#145391'
            });
        }
    @endif

    // Toggle tampilan Transfer / QRIS
    const radios   = document.querySelectorAll('input[name="metode"]');
    const trf      = document.getElementById('transferSection');
    const qris     = document.getElementById('qrisSection');

    function syncMetode() {
        const checked = document.querySelector('input[name="metode"]:checked');
        if (!checked) return;
        if (checked.value === 'transfer') {
            trf.style.display  = 'block';
            qris.style.display = 'none';
        } else {
            trf.style.display  = 'none';
            qris.style.display = 'block';
        }
    }

    radios.forEach(r => r.addEventListener('change', syncMetode));
    syncMetode();
});
</script>
@endpush
