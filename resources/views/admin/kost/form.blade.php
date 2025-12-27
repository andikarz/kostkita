{{-- resources/views/admin/kost/form.blade.php --}}
{{-- FORM KOST (digunakan pada create & edit) --}}

{{-- NAMA --}}
<div class="mb-3">
  <label class="form-label">Nama Kost</label>
  <input type="text" name="nama" class="form-control"
         value="{{ old('nama', $kost->nama ?? '') }}" required>
  @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
</div>

{{-- JENIS --}}
<div class="mb-3">
  <label class="form-label">Jenis Kost</label>
  <select name="jenis" class="form-select" required>
    @foreach (['putra','putri','campur'] as $j)
      <option value="{{ $j }}" @selected(old('jenis', $kost->jenis ?? '') == $j)>
        {{ ucfirst($j) }}
      </option>
    @endforeach
  </select>
  @error('jenis') <small class="text-danger">{{ $message }}</small> @enderror
</div>

{{-- SPESIFIKASI KAMAR --}}
<div class="mb-3">
  <label class="form-label fw-semibold">Spesifikasi Kamar</label>

  <select name="ukuran_kamar" class="form-control" required>
      <option value="">Pilih ukuran kamar</option>
      <option value="2x3" {{ old('ukuran_kamar', $kost->ukuran_kamar ?? '') == '2x3' ? 'selected' : '' }}>2 x 3 meter</option>
      <option value="3x3" {{ old('ukuran_kamar', $kost->ukuran_kamar ?? '') == '3x3' ? 'selected' : '' }}>3 x 3 meter</option>
      <option value="3x4" {{ old('ukuran_kamar', $kost->ukuran_kamar ?? '') == '3x4' ? 'selected' : '' }}>3 x 4 meter</option>
      <option value="3x5" {{ old('ukuran_kamar', $kost->ukuran_kamar ?? '') == '3x5' ? 'selected' : '' }}>3 x 5 meter</option>
  </select>
  @error('ukuran_kamar')
    <small class="text-danger d-block mt-1">{{ $message }}</small>
  @enderror

  <div class="mt-3">
    <label class="form-label">Status Listrik</label>
    <div class="d-flex gap-4 align-items-center" style="margin-top: 4px;">
      <div class="form-check me-3">
        <input class="form-check-input" type="radio" name="listrik_status" id="listrik_termasuk"
               value="termasuk" {{ old('listrik_status', $kost->listrik_status ?? '') == 'termasuk' ? 'checked' : '' }}>
        <label class="form-check-label" for="listrik_termasuk">
          Termasuk
        </label>
      </div>

      <div class="form-check">
        <input class="form-check-input" type="radio" name="listrik_status" id="listrik_tidak"
               value="tidak" {{ old('listrik_status', $kost->listrik_status ?? '') == 'tidak' ? 'checked' : '' }}>
        <label class="form-check-label" for="listrik_tidak">
          Tidak Termasuk
        </label>
      </div>
    </div>
  </div>

  @error('listrik_status')
    <small class="text-danger d-block mt-1">{{ $message }}</small>
  @enderror
</div>

{{-- FASILITAS KAMAR --}}
<div class="mb-3">
  <label class="form-label fw-semibold">Fasilitas Kamar</label>

  @php
      // Ambil data fasilitas terpilih (dari old() atau dari database)
      $selectedFasilitas = old('fasilitas', $kost->fasilitas ?? []);

      // Kalau masih bentuk string "AC, Kipas, Meja", ubah ke array
      if (!is_array($selectedFasilitas)) {
          $selectedFasilitas = array_map('trim', explode(',', $selectedFasilitas));
      }

      $fasilitasList = [
          ['value' => 'AC',               'label' => 'AC'],
          ['value' => 'Kipas',            'label' => 'Kipas'],
          ['value' => 'Meja',             'label' => 'Meja'],
          ['value' => 'Kursi',            'label' => 'Kursi'],
          ['value' => 'Kasur Single',     'label' => 'Kasur 1 Bed'],
          ['value' => 'Kasur Double',     'label' => 'Kasur Double Bed'],
          ['value' => 'Lemari',           'label' => 'Lemari'],
          ['value' => 'Jendela',          'label' => 'Jendela'],
      ];
  @endphp

  <div class="row g-2">
      @foreach ($fasilitasList as $item)
          <div class="col-6 col-md-4">
              <div class="form-check">
                  <input 
                      class="form-check-input" 
                      type="checkbox" 
                      name="fasilitas[]" 
                      id="fasilitas_{{ Str::slug($item['value'], '_') }}"
                      value="{{ $item['value'] }}"
                      {{ in_array($item['value'], $selectedFasilitas) ? 'checked' : '' }}
                  >
                  <label class="form-check-label d-flex align-items-center gap-1" 
                         for="fasilitas_{{ Str::slug($item['value'], '_') }}">
                      <span>{{ $item['label'] }}</span>
                  </label>
              </div>
          </div>
      @endforeach
  </div>

  <small class="text-muted d-block mt-1">
      Pemilik bisa memilih lebih dari satu fasilitas.
  </small>

  @error('fasilitas')
      <small class="text-danger d-block mt-1">{{ $message }}</small>
  @enderror
</div>


{{-- FASILITAS KAMAR MANDI --}}
<div class="mb-3">
  <label class="form-label fw-semibold">Fasilitas Kamar Mandi</label>

  @php
      $selected = old('fasilitas_kmandi', $kost->fasilitas_kmandi ?? []);

      if (!is_array($selected)) {
          $selected = array_map('trim', explode(',', $selected));
      }

      $fasilitasKM = [
          ['value' => 'K. Mandi Luar', 'label' => 'K. Mandi Luar'],
          ['value' => 'K. Mandi Dalam', 'label' => 'K. Mandi Dalam'],
          ['value' => 'Kloset Jongkok', 'label' => 'Kloset Jongkok'],
          ['value' => 'Kloset Duduk', 'label' => 'Kloset Duduk'],
      ];
  @endphp

  <div class="row g-2">
      @foreach ($fasilitasKM as $item)
          <div class="col-6 col-md-4">
              <div class="form-check">
                  <input 
                      class="form-check-input"
                      type="checkbox" 
                      name="fasilitas_kmandi[]" 
                      id="km_{{ Str::slug($item['value'], '_') }}"
                      value="{{ $item['value'] }}"
                      {{ in_array($item['value'], $selected) ? 'checked' : '' }}
                  >
                  <label class="form-check-label" for="km_{{ Str::slug($item['value'], '_') }}">
                      {{ $item['label'] }}
                  </label>
              </div>
          </div>
      @endforeach
  </div>

  @error('fasilitas_kmandi')
      <small class="text-danger d-block mt-1">{{ $message }}</small>
  @enderror
</div>


{{-- FASILITAS UMUM --}}
<div class="mb-3">
  <label class="form-label fw-semibold">Fasilitas Umum</label>

  @php
      $selectedUmum = old('fasilitas_umum', $kost->fasilitas_umum ?? []);

      if (!is_array($selectedUmum)) {
          $selectedUmum = array_map('trim', explode(',', $selectedUmum));
      }

      $fasilitasUmum = [
          ['value' => 'Ruang Tamu',      'label' => 'Ruang Tamu'],
          ['value' => 'WiFi',            'label' => 'WiFi'],
          ['value' => 'Jemuran',         'label' => 'Jemuran'],
          ['value' => 'CCTV',            'label' => 'CCTV'],
          ['value' => 'Kulkas',          'label' => 'Kulkas'],
          ['value' => 'Penjaga Kost',    'label' => 'Penjaga Kost'],
          ['value' => 'Dapur Bersama',   'label' => 'Dapur Bersama'],
          ['value' => 'Balkon',          'label' => 'Balkon'],
      ];
  @endphp

  <div class="row g-2 mt-1">
      @foreach ($fasilitasUmum as $item)
          <div class="col-6 col-md-4">
              <div class="form-check">
                  <input
                      class="form-check-input"
                      type="checkbox"
                      name="fasilitas_umum[]"
                      id="umum_{{ Str::slug($item['value'], '_') }}"
                      value="{{ $item['value'] }}"
                      {{ in_array($item['value'], $selectedUmum) ? 'checked' : '' }}
                  >
                  <label class="form-check-label" 
                         for="umum_{{ Str::slug($item['value'], '_') }}">
                      {{ $item['label'] }}
                  </label>
              </div>
          </div>
      @endforeach
  </div>

  @error('fasilitas_umum')
      <small class="text-danger d-block mt-1">{{ $message }}</small>
  @enderror
</div>

{{-- ALAMAT --}}
<div class="mb-3">
  <label class="form-label">Alamat Lengkap</label>
  <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $kost->alamat ?? '') }}</textarea>
  @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
</div>

{{-- KECAMATAN --}}
<div class="mb-3">
  <label class="form-label">Kecamatan</label>
  <input type="text" name="kecamatan" class="form-control"
         value="{{ old('kecamatan', $kost->kecamatan ?? '') }}" required>
  @error('kecamatan') <small class="text-danger">{{ $message }}</small> @enderror
</div>

{{-- KOTA --}}
<div class="mb-3">
  <label class="form-label">Kota</label>
  <input type="text" name="kota" class="form-control"
         value="{{ old('kota', $kost->kota ?? 'Purwokerto') }}">
  <small class="text-muted">Biarkan "Purwokerto" jika tidak ingin diubah.</small>
  @error('kota') <br><small class="text-danger">{{ $message }}</small> @enderror
</div>

{{-- HARGA & STOK --}}
<div class="row">
  <div class="col-md-6 mb-3">
    <label class="form-label">Harga Bulanan (Rp)</label>
    <input type="number" name="harga_bulanan" class="form-control"
           value="{{ old('harga_bulanan', $kost->harga_bulanan ?? '') }}" required>
    @error('harga_bulanan') <small class="text-danger">{{ $message }}</small> @enderror
  </div>
  <div class="col-md-6 mb-3">
    <label class="form-label">Stok Kamar</label>
    <input type="number" name="stok_kamar" class="form-control"
           value="{{ old('stok_kamar', $kost->stok_kamar ?? '') }}" required>
    @error('stok_kamar') <small class="text-danger">{{ $message }}</small> @enderror
  </div>
</div>

{{-- DESKRIPSI --}}
<div class="mb-3">
  <label class="form-label">Deskripsi Kost</label>
  <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kost->deskripsi ?? '') }}</textarea>
  <small class="text-muted">Opsional: tuliskan kondisi atau aturan kost.</small>
  @error('deskripsi') <br><small class="text-danger">{{ $message }}</small> @enderror
</div>

{{-- REKOMENDASI --}}
<div class="form-check mb-3">
  <input class="form-check-input" type="checkbox" name="is_recommended" value="1"
         @checked(old('is_recommended', $kost->is_recommended ?? false))>
  <label class="form-check-label">Tampilkan sebagai Kost Rekomendasi</label>
</div>

{{-- COVER --}}
<div class="mb-3">
  <label class="form-label">Foto Cover</label>
  <input type="file" name="cover" class="form-control" accept="image/*">
  @error('cover') <small class="text-danger d-block">{{ $message }}</small> @enderror

  @if (!empty($kost->cover))
    <div class="mt-2">
      <img src="{{ asset('storage/'.$kost->cover) }}" width="160" height="120"
           style="object-fit:cover;" class="rounded border">
    </div>
  @endif
</div>

{{-- FOTO TAMBAHAN --}}
<div class="mb-3">
  <label class="form-label">Foto Tambahan</label>
  <input type="file" name="photos[]" class="form-control" accept="image/*" multiple>
  <small class="text-muted">Dapat memilih banyak foto sekaligus</small>
  @error('photos.*') <br><small class="text-danger">{{ $message }}</small> @enderror
</div>

{{-- PREVIEW FOTO LAMA --}}
@if(isset($kost) && $kost->photos->count())
  <div class="mb-3">
    <label class="form-label">Foto Tambahan Yang Sudah Ada</label>
    <div class="row g-2">
      @foreach($kost->photos as $p)
        <div class="col-4">
          <div class="position-relative border rounded overflow-hidden">
            <img src="{{ asset('storage/'.$p->path) }}"
                 style="width:100%; height:120px; object-fit:cover;">
            <form action="{{ route('admin.kost.photo.destroy', $p->id) }}" method="POST" class="position-absolute top-0 end-0 m-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus foto ini?')">×</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endif
