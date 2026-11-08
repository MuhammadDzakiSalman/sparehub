@extends('layouts.app')

@section('title', 'Riwayat Peminjaman - SpareHub')

@section('content')
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Riwayat Peminjaman Alat</h4>
        <p class="text-secondary mb-0">
            Pantau sirkulasi alat dan seluruh riwayat peminjaman serta pengembalian.
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <span class="fw-semibold">Mohon periksa kesalahan pengisian:</span>
            <ul class="mb-0 mt-1 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div
            class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h6 class="fw-bold mb-0 text-dark">Data Riwayat Peminjaman</h6>
                <small class="text-secondary">Pantau siapa yang sedang memegang alat dan riwayat pengembalian</small>
            </div>

            <form method="GET" action="{{ route('borrowings.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
                <input type="text" name="search" class="form-control form-control-sm rounded-3"
                    placeholder="Cari alat / teknisi..." value="{{ request('search') }}" style="width: 180px;">

                <select name="status" class="form-select form-select-sm rounded-3" style="width: 150px;">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Sudah Kembali
                    </option>
                </select>

                <button type="submit" class="btn btn-sm btn-outline-primary rounded-3">
                    <i class="bi bi-funnel"></i>Cari
                </button>

                @if (request()->hasAny(['search', 'status']))
                    <a href="{{ route('borrowings.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th class="ps-4">Alat</th>
                            <th class="text-center">Jumlah</th>
                            <th>Teknisi Peminjam</th>
                            <th>Departemen</th>
                            <th>Waktu Pinjam</th>
                            <th>Status / Kondisi</th>
                            <th>Alasan & Catatan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $b)
                            @php
                                $isActive = is_null($b->returned_at);
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $b->tool->name ?? '-' }}</div>
                                    @if ($b->tool && $b->tool->rack_location)
                                        <small class="text-secondary d-block">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $b->tool->rack_location }}
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge text-bg-light border text-dark fw-semibold fs-6">
                                        {{ $b->quantity ?? 1 }} unit
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $b->technician->name ?? '-' }}</div>
                                    @if ($b->technician->phone)
                                        <small class="text-secondary"><i
                                                class="bi bi-telephone me-1"></i>{{ $b->technician->phone }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $b->technician->department ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small fw-semibold">{{ $b->borrowed_at->format('d/m/Y H:i') }}</div>
                                    @if ($isActive)
                                        <small class="text-warning-emphasis fw-medium">
                                            <i class="bi bi-clock me-1"></i>{{ $b->borrowed_at->diffForHumans() }}
                                        </small>
                                    @else
                                        <small class="text-secondary">Kembali:
                                            {{ $b->returned_at->format('d/m/Y H:i') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($isActive)
                                        <span class="badge text-bg-warning">
                                            <i class="bi bi-arrow-repeat me-1"></i> Sedang Dipinjam
                                        </span>
                                    @else
                                        @if ($b->return_condition === 'good')
                                            <span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i>
                                                Baik</span>
                                        @elseif($b->return_condition === 'needs_inspection')
                                            <span class="badge text-bg-warning"><i class="bi bi-tools me-1"></i> Perlu
                                                Cek</span>
                                        @elseif($b->return_condition === 'damaged')
                                            <span class="badge text-bg-danger"><i class="bi bi-x-circle me-1"></i>
                                                Rusak</span>
                                        @else
                                            <span class="badge text-bg-secondary">Selesai</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($b->borrowing_notes)
                                        <div class="small">
                                            <span class="text-secondary fw-semibold">Keperluan:</span>
                                            <span class="text-dark">{{ $b->borrowing_notes }}</span>
                                        </div>
                                    @endif

                                    @if ($b->return_notes)
                                        <div class="small {{ $b->borrowing_notes ? 'mt-1' : '' }}">
                                            <span class="text-secondary fw-semibold">Kembali:</span>
                                            <span class="text-dark">{{ $b->return_notes }}</span>
                                        </div>
                                    @endif

                                    @if (!$b->borrowing_notes && !$b->return_notes)
                                        <span class="text-secondary small">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if ($isActive)
                                        <button type="button" class="btn btn-sm btn-success btn-return-trigger rounded-3"
                                            data-bs-toggle="modal" data-bs-target="#modalReturn"
                                            data-action="{{ route('borrowings.return', $b) }}"
                                            data-tool="{{ $b->tool->name ?? 'Alat' }}"
                                            data-quantity="{{ $b->quantity ?? 1 }}"
                                            data-tech="{{ $b->technician->name ?? 'Teknisi' }}"
                                            data-borrowed="{{ $b->borrowed_at->format('d/m/Y H:i') }}">
                                            Kembalikan
                                        </button>
                                    @else
                                        <span class="text-secondary small">
                                            <i class="bi bi-check2 text-success me-1"></i> Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox fs-2 text-secondary"></i>
                                    <p class="text-secondary mt-2 mb-0">
                                        Tidak ada data peminjaman yang cocok.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($borrowings->hasPages())
            <div class="card-footer bg-white border-0 py-3 px-4">
                {{ $borrowings->links() }}
            </div>
        @endif
    </div>

    @include('borrowings._return_modal')
@endsection
