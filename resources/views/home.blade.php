@extends('layouts.main')

@section('content')
@php
  use Illuminate\Support\Str;

  function coverSrc($cover) {
      if (empty($cover)) {
          // fallback placeholder
          return 'https://via.placeholder.com/800x450?text=Foto+belum+ada';
      }
      // kalau sudah URL penuh, pakai langsung
      if (Str::startsWith($cover, ['http://','https://'])) {
          return $cover;
      }
      // selain itu anggap path di storage/app/public
      return asset('storage/'.$cover);
  }
@endphp

<div class="container mt-0 pt-0">

  {{-- HERO BANNER (3 SLIDE) --}}
  <div id="heroCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner rounded-3 overflow-hidden">
      @foreach($heroSlides as $i => $slide)
        <div class="carousel-item {{ $i==0 ? 'active' : '' }}">
          <img src="{{ $slide }}" class="d-block w-100 hero-slide" alt="slide" loading="lazy" style="max-height:420px;object-fit:cover;">
          <div class="carousel-caption d-none d-md-block text-start">
            <h3>Selamat datang di Kost Kita</h3>
            <p>Hunian praktis untuk mahasiswa dan pekerja.</p>
          </div>
        </div>
      @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  {{-- MENU PENCARIAN --}}
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <form class="row g-2" action="{{ route('kost.search') }}">
        <div class="col-md-4">
          <input type="text" name="q" class="form-control" placeholder="Nama kost / alamat" value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
          <select name="area" class="form-select">
            <option value="">Semua Area</option>
            @foreach (['Purwokerto Selatan','Purwokerto Timur','Purwokerto Barat','Purwokerto Utara','Banyumas'] as $area)
              <option value="{{ $area }}" @selected(request('area')===$area)>{{ $area }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <input type="number" name="harga_max" class="form-control" placeholder="Max harga (Rp)" value="{{ request('harga_max') }}">
        </div>
        <div class="col-md-2">
          <button class="btn w-100" style="background-color:#145391;color:#fff;">Cari</button>
        </div>
      </form>
    </div>
  </div>

  {{-- SECTION KOST TERBARU --}}
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h4>Kost Terbaru</h4>
    <a href="{{ route('kost.search') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a>
  </div>
  <div class="row mb-4">
    @forelse($kostTerbaru as $kost)
    @php
        $rating = $kost->ratings_avg_rating ?? 0;
        $ratingCount = $kost->ratings_count ?? 0;
        $rounded = round($rating * 2) / 2; // bulatkan ke 0.5
    @endphp
      <div class="col-md-4 mb-3">
        <a href="{{ route('kost.show.id', $kost->id) }}" class="text-decoration-none text-dark">
          <div class="card kost-card h-100">
            <img
              src="{{ coverSrc($kost->cover) }}"
              class="card-img-top"
              alt="Foto {{ $kost->nama }}"
              loading="lazy"
              style="height:200px;object-fit:cover;">
            <div class="card-body">
              <span class="badge bg-primary text-capitalize">{{ $kost->jenis }}</span>
              <span class="badge bg-warning text-dark">Sisa {{ $kost->stok_kamar }} kamar</span>
              <div class="mt-2 mb-1">
              @if($rating > 0)
                <div>
                  @for($i = 1; $i <= 5; $i++)
                    @if($rounded >= $i)
                      <i class="fa-solid fa-star" style="color:#f7b500;"></i>
                    @elseif($rounded + 0.5 == $i)
                      <i class="fa-regular fa-star-half-stroke" style="color:#f7b500;"></i>
                    @else
                      <i class="fa-regular fa-star" style="color:#f7b500;"></i>
                    @endif
                  @endfor
                </div>
                <small class="text-muted">
                  {{ number_format($rating,1) }} / 5
                  @if($ratingCount)
                    ({{ $ratingCount }} ulasan)
                  @endif
                </small>
              @else
                <small class="text-muted">Belum ada rating</small>
              @endif
            </div>
              <h5 class="card-title mt-2">{{ $kost->nama }}</h5>
              <p class="card-text mb-1">{{ $kost->kecamatan }}, {{ $kost->kota }}</p>
              <p class="fw-bold mb-2">Rp{{ number_format($kost->harga_bulanan,0,',','.') }}/bulan</p>
            </div>
          </div>
        </a>
      </div>
    @empty
      <p>Tidak ada data.</p>
    @endforelse
  </div>

  {{-- SECTION REKOMENDASI KOS --}}
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h4>Rekomendasi Kos</h4>
  </div>
  <div class="row mb-4">
    @foreach($rekomendasi as $kost)
      <div class="col-md-3 mb-3">
        <a href="{{ route('kost.show.id', $kost->id) }}" class="text-decoration-none text-dark">
          <div class="card kost-card h-100">
            <img
              src="{{ coverSrc($kost->cover) }}"
              class="card-img-top"
              alt="Foto {{ $kost->nama }}"
              loading="lazy"
              style="height:180px;object-fit:cover;">
            <div class="card-body">
              <span class="badge bg-warning text-dark">Sisa {{ $kost->stok_kamar }} kamar</span>
              <h6 class="mt-2">{{ $kost->nama }}</h6>
              <p class="mb-1 small">{{ $kost->kecamatan }}</p>
              <p class="fw-bold">Rp{{ number_format($kost->harga_bulanan,0,',','.') }}/bulan</p>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  {{-- SECTION AREA KOST PURWOKERTO --}}
<div class="mb-2 d-flex justify-content-between">
  <h4>Area Kost Purwokerto</h4>
</div>

<div class="row">
  @foreach($areaPurwokerto as $kec => $list)
    <div class="col-md-3 mb-3">
      <a href="{{ route('kost.search', ['area' => $kec]) }}"
         class="text-decoration-none text-dark">

        <div class="card border-0 shadow-sm h-100 kost-area-card"
             style="cursor:pointer; transition:all .2s;">
          <div class="card-body text-center">
            <h5 class="card-title">{{ $kec ?? 'Lainnya' }}</h5>
            <p class="card-text">{{ $list->count() }} kost tersedia</p>
          </div>
        </div>

      </a>
    </div>
  @endforeach
</div>


</div>
@endsection
