@extends('profile.layout')

@section('title', 'Edit Kost')

@section('content')
    <div class="card shadow-sm p-4">
        <h4 class="mb-3">Edit Kost: {{ $kost->nama }}</h4>

        <form action="{{ route('kost.update', $kost->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama Kost --}}
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kost</label>
                <input
                    type="text"
                    name="nama"
                    id="nama"
                    class="form-control @error('nama') is-invalid @enderror"
                    value="{{ old('nama', $kost->nama) }}"
                    required
                >
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tambah field lain: alamat, harga, fasilitas, dsb sesuai tabel kost kamu --}}
            {{-- contoh:
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control">{{ old('alamat', $kost->alamat) }}</textarea>
            </div>
            --}}

            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection
