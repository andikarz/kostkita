@extends('layouts.main')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8">

        {{-- Foto-foto kost --}}
        <div id="kostGallery" class="carousel slide mb-3" data-bs-ride="carousel">
          <div class="carousel-inner rounded-3 overflow-hidden">

            {{-- Slide cover utama --}}
            <div class="carousel-item active">
              <img
                src="{{ $kost->cover ? asset('storage/' . $kost->cover) : 'https://via.placeholder.com/960x540?text=Tidak+ada+foto' }}"
                class="d-block w-100" style="object-fit: cover; height: 400px;" alt="Cover {{ $kost->nama }}">
            </div>

            {{-- Slide foto tambahan dari relasi --}}
            @foreach($kost->photos as $photo)
              <div class="carousel-item">
                <img
                  src="{{ $photo->path ? asset('storage/' . $photo->path) : 'https://via.placeholder.com/960x540?text=Tidak+ada+foto' }}"
                  class="d-block w-100" style="object-fit: cover; height: 400px;" alt="Foto {{ $kost->nama }}">
              </div>
            @endforeach
          </div>

          <button class="carousel-control-prev" type="button" data-bs-target="#kostGallery" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Sebelumnya</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#kostGallery" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Berikutnya</span>
          </button>
        </div>

        {{-- Judul + badge --}}
        <h3 class="mb-1">
          {{ $kost->nama }}
          @if($kost->is_recommended ?? false)
            <span class="badge bg-warning text-dark ms-1">Rekomendasi</span>
          @endif
        </h3>

        <p class="mb-1">
          <i class="bi bi-geo-alt"></i>
          {{ $kost->alamat }}, {{ $kost->kecamatan }}, {{ $kost->kota }}
        </p>
        <p class="mb-3">
          Jenis:
          <span class="badge bg-primary text-capitalize">{{ $kost->jenis }}</span>
        </p>

        {{-- Spesifikasi & Fasilitas --}}
        @php
          // Mapping ukuran & listrik
          $ukuranMap = [
            '2x3' => '2 x 3 meter',
            '3x3' => '3 x 3 meter',
            '3x4' => '3 x 4 meter',
            '3x5' => '3 x 5 meter',
          ];
          $ukuranText = $ukuranMap[$kost->ukuran_kamar ?? ''] ?? ($kost->ukuran_kamar ?? '-');

          $listrikText = match ($kost->listrik_status ?? null) {
            'termasuk' => 'Termasuk listrik',
            'tidak' => 'Tidak termasuk listrik',
            default => '-',
          };

          // Array fasilitas
          $fasilitasKamar = $kost->fasilitas ?? [];
          $fasilitasKM = $kost->fasilitas_kmandi ?? [];
          $fasilitasUmum = $kost->fasilitas_umum ?? [];

          // Icon untuk fasilitas kamar (pakai Bootstrap Icons)
          $iconFasilitasKamar = [
            'ac' => 'bi-wind',
            'kipas' => 'bi-fan',
            'meja' => 'bi-table',
            'kursi' => 'bi-chair',
            'kasur single' => 'bi-box',
            'kasur double' => 'bi-box2',
            'lemari' => 'bi-door-closed',
            'jendela' => 'bi-window',
          ];

          // Icon untuk fasilitas kamar mandi
          $iconFasilitasKM = [
            'k. mandi luar' => 'bi-door-open',
            'k. mandi dalam' => 'bi-house-door',
            'shower' => 'bi-shower',
            'kloset jongkok' => 'bi-droplet',
            'kloset duduk' => 'bi-droplet-half',
          ];

          // Icon fasilitas umum
          $iconFasilitasUmum = [
            'ruang tamu' => 'bi-people',
            'wifi' => 'bi-wifi',
            'jemuran' => 'bi-cloud-sun',
            'cctv' => 'bi-camera-video',
            'kulkas' => 'bi-box-seam',
            'penjaga kost' => 'bi-person-badge',
            'dapur bersama' => 'bi-egg-fried',
            'balkon' => 'bi-building',
          ];

          function iconFor($name, $map)
          {
            $key = strtolower(trim($name));
            return $map[$key] ?? 'bi-dot';
          }
        @endphp

        {{-- Spesifikasi tipe kamar --}}
        <div class="mt-3 pb-3 border-bottom">
          <h5 class="fw-semibold mb-3">Spesifikasi tipe kamar</h5>

          <div class="d-flex flex-column gap-3 small">
            <div class="d-flex align-items-center gap-3">
              <div class="fs-3 text-muted">
                <i class="bi bi-grid-3x3-gap"></i>
              </div>
              <div>
                <div class="fw-semibold">Ukuran kamar</div>
                <div>{{ $ukuranText }}</div>
              </div>
            </div>

            <div class="d-flex align-items-center gap-3">
              <div class="fs-3 text-muted">
                <i class="bi bi-lightning-charge"></i>
              </div>
              <div>
                <div class="fw-semibold">Listrik</div>
                <div>{{ $listrikText }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Fasilitas kamar --}}
        <div class="mt-4">
          <h5 class="fw-semibold mb-3">Fasilitas kamar</h5>

          @if(!empty($fasilitasKamar))
            <div class="row row-cols-1 row-cols-md-2 g-2">
              @foreach($fasilitasKamar as $f)
                <div class="col">
                  <div class="d-flex align-items-center gap-3 small">
                    <span class="fs-4 text-muted">
                      <i class="bi {{ iconFor($f, $iconFasilitasKamar) }}"></i>
                    </span>
                    <span>{{ $f }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-muted small mb-0">Tidak ada data fasilitas kamar.</p>
          @endif
        </div>

        {{-- Fasilitas kamar mandi --}}
        <div class="mt-4">
          <h5 class="fw-semibold mb-3">Fasilitas kamar mandi</h5>

          @if(!empty($fasilitasKM))
            <div class="row row-cols-1 row-cols-md-2 g-2">
              @foreach($fasilitasKM as $f)
                <div class="col">
                  <div class="d-flex align-items-center gap-3 small">
                    <span class="fs-4 text-muted">
                      <i class="bi {{ iconFor($f, $iconFasilitasKM) }}"></i>
                    </span>
                    <span>{{ $f }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-muted small mb-0">Tidak ada data fasilitas kamar mandi.</p>
          @endif
        </div>

        {{-- Fasilitas umum --}}
        <div class="mt-4 mb-3">
          <h5 class="fw-semibold mb-3">Fasilitas umum</h5>

          @if(!empty($fasilitasUmum))
            <div class="row row-cols-1 row-cols-md-2 g-2">
              @foreach($fasilitasUmum as $f)
                <div class="col">
                  <div class="d-flex align-items-center gap-3 small">
                    <span class="fs-4 text-muted">
                      <i class="bi {{ iconFor($f, $iconFasilitasUmum) }}"></i>
                    </span>
                    <span>{{ $f }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-muted small mb-0">Tidak ada data fasilitas umum.</p>
          @endif
        </div>

        {{-- Deskripsi --}}
        <h5 class="fw-semibold mt-4">Deskripsi Kost</h5>
        <p>{{ $kost->deskripsi ?? '-' }}</p>

        {{-- Ulasan & Rating --}}
        <div class="mt-5 border-top pt-4">
          <h5 class="fw-semibold mb-3">Ulasan & Rating</h5>

          @php
            $avgRating = $kost->ratings->avg('rating');
            $totalRating = $kost->ratings->count();
          @endphp

          <div class="d-flex align-items-center mb-4">
            <div class="display-4 fw-bold me-3 text-warning">
              @if($avgRating)
                {{ number_format($avgRating, 1) }}
              @else
                0.0
              @endif
            </div>
            <div>
              <div class="text-warning small">
                @for($i = 1; $i <= 5; $i++)
                  @if($i <= round($avgRating))
                    <i class="bi bi-star-fill"></i>
                  @else
                    <i class="bi bi-star"></i>
                  @endif
                @endfor
              </div>
              <div class="text-muted small">
                Berdasarkan {{ $totalRating }} ulasan
              </div>
            </div>
          </div>

          @if($totalRating > 0)
            <div class="d-flex flex-column gap-3">
              @foreach($kost->ratings as $rating)
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div class="fw-semibold text-truncate">
                        {{ $rating->user->name ?? 'Pengguna' }}
                      </div>
                      <div class="text-muted small">
                        {{ $rating->created_at->diffForHumans() }}
                      </div>
                    </div>
                    <div class="mb-2 text-warning small">
                      @for($i = 1; $i <= 5; $i++)
                        @if($i <= $rating->rating)
                          <i class="bi bi-star-fill"></i>
                        @else
                          <i class="bi bi-star"></i>
                        @endif
                      @endfor
                    </div>
                    <p class="mb-0 small text-secondary">
                      {{ $rating->comment ?? '-' }}
                    </p>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="alert alert-info">
              Belum ada ulasan untuk kost ini.
            </div>
          @endif
        </div>


        {{-- Kost lain di kecamatan yang sama --}}
        <h5 class="mt-4">Kost lain di {{ $kost->kecamatan }}</h5>
        <div class="row">
          @foreach($related as $r)
            <div class="col-md-6 mb-2">
              <a href="{{ route('kost.show.id', $r->id) }}"
                class="d-flex border rounded overflow-hidden text-decoration-none text-dark">
                <img
                  src="{{ $r->cover ? asset('storage/' . $r->cover) : 'https://via.placeholder.com/160x100?text=Tidak+ada+foto' }}"
                  alt="Cover {{ $r->nama }}" style="width: 160px; height: 100px; object-fit: cover;">
                <div class="p-2">
                  <strong class="d-block">{{ $r->nama }}</strong>
                  <p class="mb-1 small">Rp{{ number_format($r->harga_bulanan) }}/bln</p>
                  <span class="badge bg-light text-dark text-capitalize">{{ $r->jenis }}</span>
                </div>
              </a>
            </div>
          @endforeach
        </div>

      </div>

      {{-- Sidebar harga / booking --}}
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4>Rp{{ number_format($kost->harga_bulanan) }} <small class="text-muted">/bulan</small></h4>
            <p class="mb-1">Sisa kamar: {{ $kost->stok_kamar }}</p>
            <p class="mb-3">Pemilik: {{ $kost->owner?->name }}</p>

            <div class="d-grid gap-2">
              <a href="https://wa.me/{{ $kost->owner?->phone ?? '6281234567890' }}?text=Halo%20saya%20tertarik%20kost%20{{ urlencode($kost->nama) }}"
                class="btn btn-outline-success" target="_blank">Hubungi Pemilik</a>

              @auth
                <a href="{{ route('booking.create', $kost->id) }}" class="btn btn-primary">Sewa</a>
              @else
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk untuk sewa</a>
              @endauth
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection