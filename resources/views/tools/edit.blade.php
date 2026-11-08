@extends('layouts.app')

@section('title', 'Edit Alat')

@section('content')

    <div class="mb-4">
        <a href="{{ route('tools.index') }}" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Data Alat
        </a>

        <h4 class="fw-bold mt-3 mb-1">Edit Data Alat</h4>
        <p class="text-secondary mb-0">
            Perbarui data alat yang dipilih.
        </p>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">

            <form action="{{ route('tools.update', $tool) }}" method="POST">
                @csrf
                @method('PUT')

                @include('tools._form')

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4 rounded-3">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('tools.index') }}" class="btn btn-light px-4 rounded-3">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>

@endsection
