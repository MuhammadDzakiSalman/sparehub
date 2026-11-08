@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    <div class="card auth-card">
        <div class="card-body p-4 p-md-5">

            <div class="text-center mb-4">
                <div class="auth-logo">
                    <i class="bi bi-tools"></i>
                </div>

                <h4 class="fw-bold mb-1">Sparehub</h4>

                <p class="text-secondary mb-0">
                    Sistem Peminjaman dan Pengembalian Alat
                </p>
            </div>

            @if (session('success'))
                <div class="alert alert-success rounded-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">
                        Email
                    </label>

                    <input type="email" name="email" id="email"
                        class="form-control form-control-lg @error('email') is-invalid @enderror rounded-3"
                        value="{{ old('email', 'admin@sparehub.my.id') }}" placeholder="Masukkan email"
                        autocomplete="username" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">
                        Password
                    </label>

                    <input type="password" name="password" id="password"
                        class="form-control form-control-lg @error('password') is-invalid @enderror rounded-3"
                        placeholder="Masukkan password" autocomplete="current-password" value="password" required>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">

                    <label for="remember" class="form-check-label">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Masuk
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-secondary">
                    Sparehub &copy; {{ date('Y') }}
                </small>
            </div>

        </div>
    </div>

@endsection
