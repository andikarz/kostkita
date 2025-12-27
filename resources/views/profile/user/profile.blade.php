@extends('profile.layout')

@section('title', 'Profil Saya')

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header text-white" style="background-color: #145391;">
            <h5 class="mb-0">Profil Saya</h5>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ auth()->user()->name }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nomor HP --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor HP *</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        value="{{ auth()->user()->phone ?? '' }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email *</label>
                    <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                </div>

                {{-- Foto Diri --}}
                <div class="mb-2 fw-semibold">Foto Diri</div>
                <small class="text-muted d-block mb-2">Maksimal 3MB</small>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror mb-3">
                @error('image')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror

                {{-- Jenis Kelamin --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Kelamin *</label>
                    <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                        <option value="">Pilih...</option>
                        <option value="Laki-laki" {{ (auth()->user()->gender == 'Laki-laki') ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="Perempuan" {{ (auth()->user()->gender == 'Perempuan') ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- NIK --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">NIK *</label>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik"
                        value="{{ auth()->user()->nik ?? '' }}" placeholder="">
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Foto Identitas --}}
                <div class="mb-2 fw-semibold">Foto KTP</div>
                <small class="text-muted d-block mb-2">Maksimal 3MB</small>
                <input type="file" name="image_id" class="form-control @error('image_id') is-invalid @enderror mb-4">
                @error('image_id')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>

            </form>
        </div>
    </div>

@endsection