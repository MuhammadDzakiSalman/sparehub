@extends('layouts.app')

@section('title', 'Data Alat - SpareHub')

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Daftar Alat</h4>
            <p class="text-secondary mb-0">
                Kelola inventaris alat dan pantau ketersediaannya.
            </p>
        </div>

        <a href="{{ route('tools.create') }}" class="btn btn-primary rounded-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Alat Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body p-3">
            <form action="{{ route('tools.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-9 col-lg-10">
                        <div class="input-group">
                            <input type="text" name="search" id="search" class="form-control rounded-3"
                                value="{{ request('search') }}" placeholder="Cari nama alat atau lokasi rak...">
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 rounded-3">
                            Cari
                        </button>

                        @if (request('search'))
                            <a href="{{ route('tools.index') }}" class="btn btn-light border rounded-3"
                                title="Reset pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="fw-bold mb-0">Inventaris Alat</h6>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th class="ps-4" width="5%">No.</th>
                            <th>Nama Alat & Lokasi Rak</th>
                            <th class="text-center">Total Unit</th>
                            <th class="text-center">Siap Dipinjam</th>
                            <th class="text-center">Diperbaiki</th>
                            <th>Peminjam Saat Ini</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tools as $tool)
                            @php
                                $available = $tool->available_quantity;
                                $borrowedCount = $tool->active_borrowings_count;
                                $maintenance = $tool->maintenance_quantity ?? 0;

                                if ($tool->status === 'unavailable') {
                                    $statusLabel = 'Nonaktif';
                                    $statusClass = 'text-bg-secondary';
                                } elseif ($available === 0 && $maintenance > 0 && $borrowedCount === 0) {
                                    $statusLabel = 'Dalam Perbaikan';
                                    $statusClass = 'text-bg-danger';
                                } elseif ($available === 0) {
                                    $statusLabel = 'Semua Dipinjam';
                                    $statusClass = 'text-bg-warning';
                                } elseif ($maintenance > 0 && $borrowedCount > 0) {
                                    $statusLabel = 'Dipinjam & Diservis';
                                    $statusClass = 'text-bg-warning';
                                } elseif ($maintenance > 0) {
                                    $statusLabel = 'Ada Rusak/Servis';
                                    $statusClass = 'text-bg-warning';
                                } elseif ($borrowedCount > 0) {
                                    $statusLabel = 'Sebagian Dipinjam';
                                    $statusClass = 'text-bg-info text-dark';
                                } else {
                                    $statusLabel = 'Tersedia Lengkap';
                                    $statusClass = 'text-bg-success';
                                }
                            @endphp

                            <tr>
                                <td class="ps-4 text-secondary">
                                    {{ $tools->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div class="fw-semibold text-dark fs-6">
                                        {{ $tool->name }}
                                    </div>

                                    @if ($tool->rack_location)
                                        <small class="text-secondary d-block">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $tool->rack_location }}
                                        </small>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="fw-bold fs-6">{{ $tool->total_quantity }}</span>
                                    <span class="text-secondary small">unit</span>
                                </td>

                                <td class="text-center">
                                    @if ($tool->status === 'unavailable')
                                        <span class="badge text-bg-secondary fs-6">0 unit</span>
                                    @elseif ($available > 0)
                                        <span class="badge text-bg-success fs-6">{{ $available }} unit</span>
                                    @else
                                        <span class="badge text-bg-warning fs-6">0 unit (Habis)</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($maintenance > 0)
                                        <span class="badge text-bg-danger fs-6">{{ $maintenance }} unit</span>
                                    @else
                                        <span class="text-secondary small">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($tool->activeBorrowings->isNotEmpty())
                                        <div class="d-flex flex-column gap-1">
                                            @foreach ($tool->activeBorrowings as $borrowing)
                                                <div class="small">
                                                    <span
                                                        class="fw-medium text-dark">{{ $borrowing->technician->name }}</span>
                                                    <span
                                                        class="text-secondary">({{ $borrowing->technician->department }})</span>
                                                    @if (($borrowing->quantity ?? 1) > 1)
                                                        <span
                                                            class="badge text-bg-light border text-dark ms-1">{{ $borrowing->quantity }}
                                                            unit</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-secondary small">-</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('tools.edit', $tool) }}"
                                            class="btn btn-sm btn-outline-secondary rounded-2"
                                            title="Edit data dan atur unit perbaikan">
                                            Edit
                                        </a>

                                        <form action="{{ route('tools.destroy', $tool) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus alat ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-2">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox fs-2 text-secondary"></i>
                                    <p class="text-secondary mt-2 mb-0">
                                        Belum ada data alat yang terdaftar.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($tools->hasPages())
            <div class="card-footer bg-white border-0 py-3 px-4">
                {{ $tools->links() }}
            </div>
        @endif
    </div>

@endsection
