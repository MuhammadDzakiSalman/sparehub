@extends('layouts.app')

@section('title', 'Dashboard - Sparehub')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endpush

@section('content')

    <div class="dashboard-page">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-0" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-0" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-0" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <span class="fw-semibold">Mohon periksa kesalahan pengisian form:</span>
                <ul class="mb-0 mt-1 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="fw-bold mb-1">Selamat Datang di Gudang Alat</h4>
                <p class="text-secondary small mb-0">
                    Pantau kondisi alat dan kelola aktivitas peminjaman dari halaman ini.
                </p>
            </div>

            <div class="text-secondary small bg-white px-3 py-2 rounded-3 border">
                <i class="bi bi-calendar3 me-1 text-primary"></i>
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="row g-2 g-xl-3">
            <div class="col-6 col-xl-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total Unit Alat</div>
                        <div class="stat-number text-dark">
                            {{ $totalTools }}<span class="stat-unit">unit</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-label">Siap Dipinjam</div>
                        <div class="stat-number text-success">
                            {{ $availableTools }}<span class="stat-unit">unit</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div>
                        <div class="stat-label">Sedang Dipinjam</div>
                        <div class="stat-number text-warning">
                            {{ $borrowedTools }}<span class="stat-unit">unit</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="stat-card shadow-sm">
                    <div class="stat-icon bg-danger-subtle text-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="stat-label">Perlu Diperiksa</div>
                        <div class="stat-number text-danger">
                            {{ $inspectionTools }}<span class="stat-unit">unit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-2 px-3">
                <ul class="nav nav-pills card-header-pills" id="operationalTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold" id="tab-pinjam-btn" data-bs-toggle="pill"
                            data-bs-target="#tab-pinjam" type="button" role="tab" aria-controls="tab-pinjam"
                            aria-selected="true">
                            Form Peminjaman Alat
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold" id="tab-kembali-btn" data-bs-toggle="pill"
                            data-bs-target="#tab-kembali" type="button" role="tab" aria-controls="tab-kembali"
                            aria-selected="false">
                            Form Pengembalian Alat
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4 tab-content" id="operationalTabsContent">
                <div class="tab-pane fade show active" id="tab-pinjam" role="tabpanel" aria-labelledby="tab-pinjam-btn">
                    <form action="{{ route('borrowings.store') }}" method="POST" id="formBorrowingDashboard">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4">
                                <label for="dashboard_tool_search" class="form-label fw-semibold small">
                                    Alat yang Dipinjam <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="tool_name" id="dashboard_tool_search"
                                    list="dashboardToolDatalist"
                                    class="form-control rounded-3 @error('tool_id') is-invalid @enderror"
                                    placeholder="Ketik nama alat..." value="{{ old('tool_name') }}" autocomplete="off"
                                    required>
                                <input type="hidden" name="tool_id" id="dashboard_tool_id"
                                    value="{{ old('tool_id') }}">

                                <datalist id="dashboardToolDatalist">
                                    @foreach ($availableToolsList as $tool)
                                        <option value="{{ $tool->name }}" data-id="{{ $tool->id }}"
                                            data-rack="{{ $tool->rack_location }}"
                                            data-available="{{ $tool->available_quantity }}">
                                            {{ $tool->name }}
                                            {{ $tool->rack_location ? '[' . $tool->rack_location . ']' : '' }} (Tersedia:
                                            {{ $tool->available_quantity }} unit)
                                        </option>
                                    @endforeach
                                </datalist>

                                <div id="dashboard_tool_rack_info" class="small text-secondary mt-1"
                                    style="display: none;">
                                    <span id="dashboard_tool_rack_wrapper">
                                        Lokasi: <span id="dashboard_tool_rack_text" class="fw-semibold text-dark"></span>
                                    </span>
                                    <span class="mx-1 text-muted" id="dashboard_tool_info_separator">·</span>
                                    <span>
                                        Bisa dipinjam: <span id="dashboard_tool_available_text"
                                            class="fw-semibold text-dark">0</span> unit
                                    </span>
                                </div>

                                @error('tool_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-lg-2">
                                <label for="dashboard_borrowing_quantity" class="form-label fw-semibold small">
                                    Jumlah Pinjam <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" name="quantity" id="dashboard_borrowing_quantity"
                                        class="form-control rounded-start-3 @error('quantity') is-invalid @enderror"
                                        value="{{ old('quantity', 1) }}" min="1" required>
                                    <span class="input-group-text bg-light text-secondary rounded-end-3 small">Unit</span>
                                </div>
                                @error('quantity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <label for="dashboard_technician_name" class="form-label fw-semibold small">
                                    Nama Teknisi <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="technician_name" id="dashboard_technician_name"
                                    list="dashboardTechDatalist"
                                    class="form-control rounded-3 @error('technician_name') is-invalid @enderror"
                                    placeholder="Ketik nama teknisi..." value="{{ old('technician_name') }}"
                                    autocomplete="off" required>
                                <datalist id="dashboardTechDatalist">
                                    @foreach ($savedTechnicians as $tech)
                                        <option value="{{ $tech->name }}" data-dept="{{ $tech->department }}"
                                            data-phone="{{ $tech->phone }}">{{ $tech->name }}
                                            ({{ $tech->department }})
                                        </option>
                                    @endforeach
                                </datalist>
                                @error('technician_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <label for="dashboard_technician_department" class="form-label fw-semibold small">
                                    Departemen / Bagian <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="technician_department" id="dashboard_technician_department"
                                    list="dashboardDeptDatalist"
                                    class="form-control rounded-3 @error('technician_department') is-invalid @enderror"
                                    placeholder="Contoh: Mekanikal" value="{{ old('technician_department') }}" required>
                                <datalist id="dashboardDeptDatalist">
                                    <option value="Mechanical Engineering">Mechanical Engineering</option>
                                    <option value="Electrical Maintenance">Electrical Maintenance</option>
                                    <option value="Plant Maintenance">Plant Maintenance</option>
                                    <option value="Quality Inspection">Quality Inspection</option>
                                    <option value="Hydraulics & Pneumatics">Hydraulics & Pneumatics</option>
                                    <option value="Welding & Fabrication">Welding & Fabrication</option>
                                </datalist>
                                @error('technician_department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 col-lg-3">
                                <label for="dashboard_technician_phone" class="form-label fw-semibold small">
                                    No. HP (Opsional)
                                </label>
                                <input type="text" name="technician_phone" id="dashboard_technician_phone"
                                    class="form-control rounded-3 @error('technician_phone') is-invalid @enderror"
                                    placeholder="0812xxxx" value="{{ old('technician_phone') }}">
                                @error('technician_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8 col-lg-6">
                                <label for="dashboard_borrowing_notes" class="form-label fw-semibold small">
                                    Catatan Keperluan / Lokasi Kerja (Opsional)
                                </label>
                                <input type="text" name="borrowing_notes" id="dashboard_borrowing_notes"
                                    class="form-control rounded-3 @error('borrowing_notes') is-invalid @enderror"
                                    placeholder="Misal: Perbaikan mesin diesel unit B / pemotongan pipa"
                                    value="{{ old('borrowing_notes') }}">
                                @error('borrowing_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-lg-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold">
                                    Simpan Peminjaman<i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="tab-kembali" role="tabpanel" aria-labelledby="tab-kembali-btn">
                    @if ($activeBorrowings->isEmpty())
                        <div class="alert alert-light border mb-0 text-center py-4 rounded-3">
                            <i class="bi bi-check-circle text-success fs-2 d-block mb-2"></i>
                            <h6 class="fw-bold mb-1 text-dark">Semua Alat Berada di Gudang</h6>
                            <p class="text-secondary small mb-0">
                                Saat ini tidak ada alat yang sedang dipinjam oleh teknisi.
                            </p>
                        </div>
                    @else
                        <form action="{{ route('borrowings.return-direct') }}" method="POST" id="formReturnDirect">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label for="selectReturnBorrowing" class="form-label fw-semibold small">
                                        Pilih Peminjaman yang Akan Dikembalikan <span class="text-danger">*</span>
                                    </label>
                                    <select name="borrowing_id" id="selectReturnBorrowing"
                                        class="form-select rounded-3 @error('borrowing_id') is-invalid @enderror" required>
                                        <option value="">Pilih Alat / Nama Peminjam</option>
                                        @foreach ($activeBorrowings as $b)
                                            <option value="{{ $b->id }}"
                                                {{ old('borrowing_id') == $b->id ? 'selected' : '' }}
                                                data-tool="{{ $b->tool->name ?? 'Alat' }}"
                                                data-quantity="{{ $b->quantity ?? 1 }}"
                                                data-rack="{{ $b->tool->rack_location ?? '-' }}"
                                                data-tech="{{ $b->technician->name ?? 'Teknisi' }}"
                                                data-dept="{{ $b->technician->department ?? '-' }}"
                                                data-time="{{ $b->borrowed_at->format('d/m/Y H:i') }}">
                                                {{ $b->tool->name ?? 'Alat' }} ({{ $b->quantity ?? 1 }} unit) - Peminjam:
                                                {{ $b->technician->name ?? 'Teknisi' }}
                                                ({{ $b->technician->department ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('borrowing_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror

                                    <div id="returnDirectSummary" class="p-3 mt-2 bg-light rounded-3 border"
                                        style="display: none;">
                                        <div class="row g-2 small">
                                            <div class="col-sm-6">
                                                <span class="text-secondary d-block">Alat:</span>
                                                <div class="d-flex align-items-center gap-1">
                                                    <strong class="text-dark" id="rdSummaryTool">-</strong>
                                                    <span class="badge text-bg-light border text-dark" id="rdSummaryQty">1
                                                        unit</span>
                                                </div>
                                                <small class="text-secondary d-block" id="rdSummaryRack">-</small>
                                            </div>
                                            <div class="col-sm-6">
                                                <span class="text-secondary d-block">Peminjam:</span>
                                                <strong class="text-dark" id="rdSummaryTech">-</strong>
                                                <small class="text-muted d-block">Dipinjam: <span
                                                        id="rdSummaryTime">-</span></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <label for="return_direct_notes"
                                            class="form-label fw-semibold small text-secondary">
                                            Catatan Pengembalian (Opsional)
                                        </label>
                                        <input type="text" name="return_notes" id="return_direct_notes"
                                            class="form-control form-control-sm rounded-3"
                                            placeholder="Contoh: Kabel lecet, baut penutup hilang, dsb.">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label fw-semibold small text-secondary d-block">
                                        Kondisi Fisik Saat Kembali <span class="text-danger">*</span>
                                    </label>

                                    <div class="d-flex flex-column gap-2 mb-3">
                                        <label
                                            class="p-2 border rounded-2 d-flex align-items-center gap-2 cursor-pointer bg-white">
                                            <input type="radio" name="return_condition" value="good"
                                                class="form-check-input mt-0" checked>
                                            <div>
                                                <strong class="text-success small d-block">Baik</strong>
                                            </div>
                                        </label>

                                        <label
                                            class="p-2 border rounded-2 d-flex align-items-center gap-2 cursor-pointer bg-white">
                                            <input type="radio" name="return_condition" value="needs_inspection"
                                                class="form-check-input mt-0">
                                            <div>
                                                <strong class="text-warning-emphasis small d-block">Perlu Diperiksa
                                            </div>
                                        </label>

                                        <label
                                            class="p-2 border rounded-2 d-flex align-items-center gap-2 cursor-pointer bg-white">
                                            <input type="radio" name="return_condition" value="damaged"
                                                class="form-check-input mt-0">
                                            <div>
                                                <strong class="text-danger small d-block">Rusak / Tidak Berfungsi</strong>
                                            </div>
                                        </label>
                                    </div>

                                    <button type="submit" id="btnSubmitReturnDirect"
                                        class="btn btn-success w-100 rounded-3 py-2 fw-semibold">
                                        Simpan Pengembalian
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div
                class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">
                        Alat yang Sedang Dipinjam
                    </h6>
                    <small class="text-secondary">
                        Daftar alat aktif yang sedang dibawa teknisi dan belum dikembalikan.
                    </small>
                </div>

                <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                    Lihat Riwayat Lengkap <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th class="ps-4">Alat & Lokasi Rak</th>
                                <th>Teknisi Peminjam</th>
                                <th>Departemen</th>
                                <th>Alasan / Keperluan</th>
                                <th>Waktu Pinjam</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($activeBorrowings as $borrowing)
                                @php
                                    $hours = max(0, (int) $borrowing->borrowed_at->diffInHours(now()));
                                @endphp

                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark">
                                            {{ $borrowing->tool->name }}
                                            @if (($borrowing->quantity ?? 1) > 1)
                                                <span
                                                    class="badge text-bg-light border text-dark ms-1">{{ $borrowing->quantity }}
                                                    unit</span>
                                            @endif
                                        </div>
                                        @if ($borrowing->tool->rack_location)
                                            <small class="text-secondary d-block">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $borrowing->tool->rack_location }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="fw-medium text-dark">
                                            {{ $borrowing->technician->name }}
                                        </div>
                                        @if ($borrowing->technician->phone)
                                            <small class="text-secondary">
                                                <i class="bi bi-telephone me-1"></i>{{ $borrowing->technician->phone }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $borrowing->technician->department ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($borrowing->borrowing_notes)
                                            <span class="small text-dark">{{ $borrowing->borrowing_notes }}</span>
                                        @else
                                            <span class="text-secondary small">-</span>
                                        @endif
                                    </td>

                                    <td class="text-nowrap small">
                                        <div class="fw-semibold">{{ $borrowing->borrowed_at->format('d/m/Y') }}</div>
                                        <div class="text-secondary">{{ $borrowing->borrowed_at->format('H:i') }} WIB</div>
                                    </td>

                                    <td class="text-nowrap">
                                        @if ($hours >= 24)
                                            <span
                                                class="badge text-bg-warning-subtle text-warning-emphasis border border-warning-subtle"
                                                title="Alat sudah dipinjam lebih dari 1 hari">
                                                <i class="bi bi-clock-history me-1"></i>{{ (int) ($hours / 24) }} hari
                                                {{ $hours % 24 }}j
                                            </span>
                                        @else
                                            <span class="badge text-bg-light border text-dark">
                                                <i class="bi bi-stopwatch me-1"></i>{{ $hours }} jam
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge text-bg-warning">
                                            <i class="bi bi-arrow-repeat me-1"></i> Dipinjam
                                        </span>
                                    </td>

                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-success rounded-3 btn-return-trigger"
                                            data-bs-toggle="modal" data-bs-target="#modalReturn"
                                            data-action="{{ route('borrowings.return', $borrowing) }}"
                                            data-tool="{{ $borrowing->tool->name ?? 'Alat' }}"
                                            data-quantity="{{ $borrowing->quantity ?? 1 }}"
                                            data-tech="{{ $borrowing->technician->name ?? 'Teknisi' }}"
                                            data-borrowed="{{ $borrowing->borrowed_at->format('d/m/Y H:i') }}">
                                            Kembalikan
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="mb-2">
                                            <i class="bi bi-check-circle text-success fs-1"></i>
                                        </div>
                                        <div class="fw-semibold text-dark">Semua alat berada di gudang</div>
                                        <div class="text-secondary small">
                                            Saat ini belum ada peminjaman alat yang sedang aktif.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @include('borrowings._return_modal')

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toolInput = document.getElementById('dashboard_tool_search');
            const toolIdInput = document.getElementById('dashboard_tool_id');
            const toolDatalist = document.getElementById('dashboardToolDatalist');
            const qtyInput = document.getElementById('dashboard_borrowing_quantity');
            const toolRackInfo = document.getElementById('dashboard_tool_rack_info');
            const toolRackWrapper = document.getElementById('dashboard_tool_rack_wrapper');
            const toolRackText = document.getElementById('dashboard_tool_rack_text');
            const toolInfoSep = document.getElementById('dashboard_tool_info_separator');
            const toolAvailableText = document.getElementById('dashboard_tool_available_text');

            if (toolInput && toolDatalist) {
                function syncTool() {
                    const val = toolInput.value.trim();
                    const option = Array.from(toolDatalist.options).find(opt => opt.value === val);
                    if (option) {
                        if (toolIdInput) toolIdInput.value = option.dataset.id;
                        if (qtyInput && option.dataset.available) {
                            qtyInput.max = option.dataset.available;
                        }
                        if (toolRackInfo) {
                            const hasRack = Boolean(option.dataset.rack);
                            if (toolRackWrapper) {
                                toolRackWrapper.style.display = hasRack ? 'inline' : 'none';
                                if (hasRack && toolRackText) toolRackText.textContent = option.dataset.rack;
                            }
                            if (toolInfoSep) {
                                toolInfoSep.style.display = hasRack ? 'inline' : 'none';
                            }
                            if (toolAvailableText) {
                                toolAvailableText.textContent = option.dataset.available ?? '0';
                            }
                            toolRackInfo.style.display = 'block';
                        }
                    } else {
                        if (toolIdInput) toolIdInput.value = '';
                        if (toolRackInfo) toolRackInfo.style.display = 'none';
                        if (qtyInput) qtyInput.removeAttribute('max');
                    }
                }

                toolInput.addEventListener('input', syncTool);
                toolInput.addEventListener('change', syncTool);
                if (toolInput.value) syncTool();
            }

            const techInput = document.getElementById('dashboard_technician_name');
            const deptInput = document.getElementById('dashboard_technician_department');
            const phoneInput = document.getElementById('dashboard_technician_phone');
            const techDatalist = document.getElementById('dashboardTechDatalist');

            if (techInput && techDatalist) {
                techInput.addEventListener('input', function() {
                    const val = this.value.trim();
                    const option = Array.from(techDatalist.options).find(opt => opt.value === val);
                    if (option) {
                        if (option.dataset.dept) deptInput.value = option.dataset.dept;
                        if (option.dataset.phone) phoneInput.value = option.dataset.phone;
                    }
                });
            }

            const returnSelect = document.getElementById('selectReturnBorrowing');
            const formReturnDirect = document.getElementById('formReturnDirect');
            const summaryBox = document.getElementById('returnDirectSummary');
            const summaryTool = document.getElementById('rdSummaryTool');
            const summaryQty = document.getElementById('rdSummaryQty');
            const summaryRack = document.getElementById('rdSummaryRack');
            const summaryTech = document.getElementById('rdSummaryTech');
            const summaryTime = document.getElementById('rdSummaryTime');

            if (returnSelect && formReturnDirect) {
                function updateReturnSummary() {
                    const selected = returnSelect.options[returnSelect.selectedIndex];
                    if (returnSelect.value && selected) {
                        returnSelect.classList.remove('is-invalid');
                        if (summaryTool) summaryTool.textContent = selected.dataset.tool || '-';
                        if (summaryQty) summaryQty.textContent = (selected.dataset.quantity || '1') + ' unit';
                        if (summaryRack) summaryRack.textContent = selected.dataset.rack ? ('Lokasi: ' + selected
                            .dataset.rack) : 'Lokasi Rak: -';
                        if (summaryTech) summaryTech.textContent = (selected.dataset.tech || '-') + ' (' + (selected
                            .dataset.dept || '-') + ')';
                        if (summaryTime) summaryTime.textContent = selected.dataset.time || '-';
                        if (summaryBox) summaryBox.style.display = 'block';
                    } else {
                        if (summaryBox) summaryBox.style.display = 'none';
                    }
                }

                returnSelect.addEventListener('change', updateReturnSummary);
                if (returnSelect.value) updateReturnSummary();

                formReturnDirect.addEventListener('submit', function(e) {
                    if (!returnSelect.value) {
                        e.preventDefault();
                        returnSelect.classList.add('is-invalid');
                        returnSelect.focus();
                    }
                });
            }
        });
    </script>
@endpush
