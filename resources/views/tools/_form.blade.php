<div class="mb-3">
    <label for="name" class="form-label fw-semibold">
        Nama Alat <span class="text-danger">*</span>
    </label>

    <input type="text" name="name" id="name"
        class="form-control rounded-3 form-control-lg @error('name') is-invalid @enderror"
        value="{{ old('name', $tool->name) }}" placeholder="Contoh: Bor Listrik Bosch / Multimeter Fluke" required>

    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="total_quantity" class="form-label fw-semibold">
            Total Unit Alat <span class="text-danger">*</span>
        </label>

        <input type="number" name="total_quantity" id="total_quantity" min="1"
            class="form-control rounded-3 @error('total_quantity') is-invalid @enderror"
            value="{{ old('total_quantity', $tool->total_quantity ?? 1) }}" required>
        <div class="form-text text-muted small">Seluruh unit fisik yang dimiliki.</div>

        @error('total_quantity')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="maintenance_quantity" class="form-label fw-semibold">
            Sedang Diperbaiki (Rusak)
        </label>

        <input type="number" name="maintenance_quantity" id="maintenance_quantity" min="0"
            class="form-control rounded-3 @error('maintenance_quantity') is-invalid @enderror"
            value="{{ old('maintenance_quantity', $tool->maintenance_quantity ?? 0) }}">
        <div class="form-text text-muted small">Unit rusak yang tidak bisa dipinjam.</div>

        @error('maintenance_quantity')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="rack_location" class="form-label fw-semibold">
            Lokasi Rak
        </label>

        <input type="text" name="rack_location" id="rack_location"
            class="form-control rounded-3 @error('rack_location') is-invalid @enderror"
            value="{{ old('rack_location', $tool->rack_location) }}" placeholder="Contoh: Rak A-01">
        <div class="form-text text-muted small">Posisi penyimpanan di gudang.</div>

        @error('rack_location')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
