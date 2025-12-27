@extends('admin.layout')

@section('title', 'Edit Kost')

@section('content')
    <div class="card shadow-sm p-4">
        <h4 class="mb-3">Edit Kost: {{ $kost->nama }}</h4>

        <form action="{{ route('admin.kost.update', $kost->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- <== penting untuk update --}}

            {{-- INCLUDE FORM (pakai form.blade yang sama) --}}
            @include('admin.kost.form')

            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
        </form>
    </div>
@endsection
