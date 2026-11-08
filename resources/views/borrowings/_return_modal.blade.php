<div class="modal fade" id="modalReturn" tabindex="-1" aria-labelledby="modalReturnLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom">
                <h6 class="modal-title fw-bold" id="modalReturnLabel">Konfirmasi Pengembalian Alat</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <form action="" method="POST" id="formReturnModal">
                @csrf
                @method('PUT')

                <div class="modal-body p-4">
                    <div class="p-3 mb-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-secondary small">Alat:</span>
                            <div class="d-flex align-items-center gap-1">
                                <strong class="text-dark" id="modalReturnTool">-</strong>
                                <span class="badge text-bg-light border text-dark" id="modalReturnQty">1 unit</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-secondary small">Peminjam:</span>
                            <span class="fw-semibold text-dark" id="modalReturnTech">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary small">Dipinjam Sejak:</span>
                            <span class="small text-muted" id="modalReturnTime">-</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary d-block">
                            Kondisi Fisik Saat Kembali <span class="text-danger">*</span>
                        </label>

                        <div class="d-flex flex-column gap-2">
                            <label class="p-2 border rounded-2 d-flex align-items-center gap-2 cursor-pointer bg-white">
                                <input type="radio" name="return_condition" value="good"
                                    class="form-check-input mt-0" checked>
                                <div>
                                    <strong class="text-success small d-block">Baik</strong>
                                </div>
                            </label>

                            <label class="p-2 border rounded-2 d-flex align-items-center gap-2 cursor-pointer bg-white">
                                <input type="radio" name="return_condition" value="needs_inspection"
                                    class="form-check-input mt-0">
                                <div>
                                    <strong class="text-warning-emphasis small d-block">Perlu Diperiksa</strong>
                                </div>
                            </label>

                            <label class="p-2 border rounded-2 d-flex align-items-center gap-2 cursor-pointer bg-white">
                                <input type="radio" name="return_condition" value="damaged"
                                    class="form-check-input mt-0">
                                <div>
                                    <strong class="text-danger small d-block">Rusak / Butuh Perbaikan</strong>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="modal_return_notes" class="form-label fw-semibold small text-secondary">
                            Catatan Pengembalian (Opsional)
                        </label>
                        <textarea name="return_notes" id="modal_return_notes" rows="2" class="form-control form-control-sm"
                            placeholder="Contoh: Kabel lecet, baut penutup hilang, dsb."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top py-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success btn-sm px-3 fw-semibold rounded-3">
                        Simpan Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalForm = document.getElementById('formReturnModal');
            const modalTool = document.getElementById('modalReturnTool');
            const modalQty = document.getElementById('modalReturnQty');
            const modalTech = document.getElementById('modalReturnTech');
            const modalTime = document.getElementById('modalReturnTime');

            document.querySelectorAll('.btn-return-trigger').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (modalForm) modalForm.action = this.dataset.action || '';
                    if (modalTool) modalTool.textContent = this.dataset.tool || '-';
                    if (modalQty) modalQty.textContent = (this.dataset.quantity || '1') + ' unit';
                    if (modalTech) modalTech.textContent = this.dataset.tech || '-';
                    if (modalTime) modalTime.textContent = this.dataset.borrowed || '-';
                });
            });
        });
    </script>
@endpush
