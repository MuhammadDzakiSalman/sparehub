@extends('layouts.app')

@section('title', 'Tambah Alat')

@section('content')

    <div class="mb-4">
        <a href="{{ route('tools.index') }}" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Data Alat
        </a>

        <h4 class="fw-bold mt-3 mb-1">Tambah Alat</h4>
        <p class="text-secondary mb-0">
            Masukkan informasi alat yang akan didaftarkan.
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('tools.store') }}" method="POST">
                @csrf

                @include('tools._form')

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i>
                        Simpan Alat
                    </button>

                    <a href="{{ route('tools.index') }}" class="btn btn-light px-4">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>

@endsection
