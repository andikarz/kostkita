@extends('admin.layout')

@section('content')
  <div class="container py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <h3 class="mb-0">Daftar Kost</h3>
      <a href="{{ route('admin.kost.create') }}" class="btn btn-primary">+ Tambah Kost</a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger mb-3">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Filter / Pencarian --}}
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <form class="row g-2" method="GET" action="{{ route('admin.kost.index') }}">
          <div class="col-md-4">
            <input type="text" class="form-control" name="q" value="{{ request('q') }}"
              placeholder="Cari nama/alamat/kecamatan">
          </div>
          <div class="col-md-3">
            <select class="form-select" name="area">
              <option value="">Semua Area</option>
              @foreach (['Purwokerto Selatan', 'Purwokerto Timur', 'Purwokerto Barat', 'Purwokerto Utara'] as $area)
                <option value="{{ $area }}" @selected(request('area') === $area)>{{ $area }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <input type="text" class="form-control rupiah-input" id="harga_max" name="harga_max"
              value="{{ request('harga_max') }}" placeholder="Max harga (Rp)">
          </div>
          <div class="col-md-2">
            <button class="btn w-100 btn-outline-primary">Terapkan</button>
          </div>
          @if(request()->hasAny(['q', 'area', 'harga_max']))
            <div class="col-md-1 d-grid">
              <a href="{{ route('admin.kost.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
          @endif
        </form>
      </div>
    </div>

    {{-- Tabel --}}
    <div class="table-responsive">
      <table class="table align-middle table-hover">
        <thead class="table-light">
          <tr>
            <th style="width:88px;">Cover</th>
            <th>Nama</th>
            <th>Lokasi</th>
            <th>Jenis</th>
            <th>Harga</th>
            <th>Rating</th> {{-- ⬅️ kolom baru --}}
            <th>Status</th>
            <th>Stok</th>
            <th style="width:170px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kosts as $kost)
            @php
              // fleksibel: coba baca rating dari beberapa kemungkinan field
              $rating = $kost->rating
                ?? $kost->avg_rating
                ?? $kost->average_rating
                ?? 0;

              $ratingCount = $kost->rating_count
                ?? $kost->reviews_count
                ?? 0;

              $rounded = round($rating * 2) / 2; // bulatkan ke 0.5 terdekat
            @endphp
            <tr>
              <td>
                <div class="ratio ratio-4x3" style="width: 88px;">
                  <img
                    src="{{ $kost->cover ? asset('storage/' . $kost->cover) : 'https://via.placeholder.com/160x120?text=No+Image' }}"
                    class="rounded border" style="object-fit: cover;" alt="Cover">
                </div>
              </td>
              <td>
                <div class="d-flex flex-column">
                  <div class="fw-semibold">
                    {{ $kost->nama }}
                    @if($kost->is_recommended)
                      <span class="badge bg-success ms-1">Rekomendasi</span>
                    @endif
                  </div>
                  <small class="text-muted">ID: {{ $kost->id }}</small>
                </div>
              </td>
              <td>
                <div>{{ $kost->kecamatan }}</div>
                <small class="text-muted">{{ $kost->kota }}</small>
              </td>
              <td class="text-capitalize">
                <span class="badge bg-primary-subtle text-primary">{{ $kost->jenis }}</span>
              </td>
              <td class="fw-semibold">Rp{{ number_format($kost->harga_bulanan, 0, ',', '.') }}</td>

              {{-- RATING --}}
              <td>
                @if($rating > 0)
                  <div class="d-flex flex-column">
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
                      {{ number_format($rating, 1) }} / 5
                      @if($ratingCount)
                        ({{ $ratingCount }} ulasan)
                      @endif
                    </small>
                  </div>
                @else
                  <span class="text-muted">Belum ada rating</span>
                @endif
              </td>

              <td>
                @if($kost->status === 'verified')
                  <span class="badge bg-success">Verified</span>
                @else
                  <span class="badge bg-secondary">Unverified</span>
                @endif
              </td>

              <td>{{ $kost->stok_kamar }}</td>
              <td>
                <div class="d-flex gap-1">
                  <a href="{{ route('admin.kost.edit', $kost) }}" class="btn btn-sm btn-warning">Edit</a>
                  <form action="{{ route('admin.kost.destroy', $kost) }}" method="POST"
                    onsubmit="return confirm('Yakin dihapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                  </form>
                  @if($kost->status !== 'verified')
                    <form action="{{ route('admin.kost.verify', $kost) }}" method="POST"
                      onsubmit="return confirm('Verifikasi kost ini?')">
                      @csrf
                      <button class="btn btn-sm btn-success">Verifikasi</button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">
                Belum ada data kost.
                <a href="{{ route('admin.kost.create') }}">Tambah sekarang</a>.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end">
      {{ $kosts->appends(request()->query())->links() }}
    </div>
  </div>
@endsection