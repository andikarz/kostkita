@extends('profile.layout')

@section('title', 'Edit Kost')

@section('content')
    <div class="card shadow-sm p-4">
        <h4 class="mb-3">Edit Kost: {{ $kost->nama }}</h4>

        <form action="{{ route('kost.update', $kost->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Gunakan form partial yang sama dengan admin/create --}}
            @include('admin.kost.form')

            <button type="submit" class="btn btn-primary mt-3">
                <i class="bi bi-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
@endsection