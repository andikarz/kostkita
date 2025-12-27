@extends('profile.layout')

@section('title', 'Tambah Kost')

@section('content')
    <div class="card shadow-sm p-4">
        <h4 class="mb-3">Tambah Kost Baru</h4>

        <form action="{{ route('kost.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- INCLUDE FORM --}}
            @include('admin.kost.form')

            <button class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
