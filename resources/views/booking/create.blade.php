@extends('layouts.main')

@section('content')
  @php
    $hargaBulanan = $kost->harga_bulanan;
    // aturan biaya layanan
    $biayaLayanan = $hargaBulanan < 1000000 ? 15000 : 25000;
    $lamaDefault = 1;
    $subtotalAwal = $hargaBulanan * $lamaDefault; // Preview hanya subtotal
    $totalAwal = $subtotalAwal + $biayaLayanan; // Total lengkap tabel bawah
  @endphp

  <div class="container">
    <div class="row">
      <div class="col-md-8">

        <h4>Pemesanan Kamar Kost</h4>
        <p>Pastikan Anda mengisi semua informasi di halaman ini dengan benar sebelum melanjutkan ke pembayaran.</p>

        <form method="POST" action="{{ route('booking.store', $kost->id) }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Tanggal Mulai Sewa</label>
            <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
              value="{{ old('tanggal_mulai') }}" required>
            @error('tanggal_mulai')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Sisa Kamar</label>
            <input type="text" value="{{ $kost->stok_kamar }} Kamar Tersedia" class="form-control" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Lama Sewa</label>
            <select name="lama_sewa" id="lamaSewa" class="form-select @error('lama_sewa') is-invalid @enderror">
              <option value="1" @selected(old('lama_sewa') == 1)>1 bulan</option>
              <option value="3" @selected(old('lama_sewa') == 3)>3 bulan</option>
              <option value="6" @selected(old('lama_sewa') == 6)>6 bulan</option>
              <option value="12" @selected(old('lama_sewa') == 12)>12 bulan</option>
            </select>
            @error('lama_sewa')
               <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- PREVIEW TOTAL HANYA SUBTOTAL --}}
          <div class="mb-3">
            <label class="form-label">Total Harga (Preview)</label>
            <input type="text" id="totalHargaInput" value="Rp {{ number_format($subtotalAwal, 0, ',', '.') }}"
              class="form-control" disabled>
          </div>

          {{-- Rincian Total Asli --}}
          <div class="mb-4 border rounded p-3 bg-light">
            <h5>Rincian Harga</h5>
            <div class="d-flex justify-content-between">
              <span id="labelHargaKamar">Harga kamar (1 bulan)</span>
              <span id="nilaiHargaKamar">Rp {{ number_format($subtotalAwal, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between">
              <span>Biaya Layanan</span>
              <span id="biayaLayananSpan">Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
              <span>Total Harga</span>
              <span id="totalHargaSpan">Rp {{ number_format($totalAwal, 0, ',', '.') }}</span>
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ url()->previous() }}" class="btn btn-light">Kembali</a>
            <button class="btn btn-primary">Lanjutkan Pembayaran</button>
          </div>
        </form>

      </div>

      <script>
        document.addEventListener('DOMContentLoaded', function () {
          const hargaBulanan = {{ $hargaBulanan }};
          const biayaLayanan = {{ $biayaLayanan }};

          const selectLamaSewa = document.getElementById('lamaSewa');
          const labelHargaKamar = document.getElementById('labelHargaKamar');
          const nilaiHargaKamar = document.getElementById('nilaiHargaKamar');
          const biayaLayananSpan = document.getElementById('biayaLayananSpan');
          const totalHargaSpan = document.getElementById('totalHargaSpan');
          const totalHargaInput = document.getElementById('totalHargaInput');

          function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
          }

          function updateHarga() {
            const lama = parseInt(selectLamaSewa.value);
            const subtotal = hargaBulanan * lama;
            const total = subtotal + biayaLayanan;

            labelHargaKamar.textContent = `Harga kamar (${lama} bulan)`;
            nilaiHargaKamar.textContent = 'Rp ' + formatRupiah(subtotal);
            biayaLayananSpan.textContent = 'Rp ' + formatRupiah(biayaLayanan);
            totalHargaSpan.textContent = 'Rp ' + formatRupiah(total);

            // Preview hanya subtotal tanpa biaya layanan
            totalHargaInput.value = 'Rp ' + formatRupiah(subtotal);
          }

          selectLamaSewa.addEventListener('change', updateHarga);
          updateHarga();
        });
      </script>
@endsection