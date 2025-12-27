@extends('layouts.main')

@section('content')
<div class="container">
  <h3 class="mb-4">Hasil Pencarian Kost</h3>

  <div class="row">
    @forelse($kosts as $kost)
      <div class="col-md-4 mb-3">
        <a href="{{ route('kost.show.id', $kost->id) }}" class="text-decoration-none text-dark">
          <div class="card kost-card h-100">
            <img
              src="{{ function_exists('coverSrc') ? coverSrc($kost->cover) : ($kost->cover ? asset('storage/'.$kost->cover) : 'https://via.placeholder.com/400x200?text=Kost') }}"
              class="card-img-top"
              alt="Foto {{ $kost->nama }}"
              loading="lazy"
              style="height:200px;object-fit:cover;">
            <div class="card-body">
              <span class="badge bg-primary text-capitalize">{{ $kost->jenis }}</span>
              <span class="badge bg-warning text-dark">Sisa {{ $kost->stok_kamar }} kamar</span>

              <h5 class="card-title mt-2">{{ $kost->nama }}</h5>
              <p class="card-text mb-1">{{ $kost->kecamatan }}, {{ $kost->kota }}</p>
              <p class="fw-bold mb-2">
                Rp{{ number_format($kost->harga_bulanan, 0, ',', '.') }}/bulan
              </p>
            </div>
          </div>
        </a>
      </div>
    @empty
      <p class="text-center">Tidak ada kost ditemukan.</p>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $kosts->links() }}
  </div>
</div>
@endsection
