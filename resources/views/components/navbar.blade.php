<header class="app-topbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-light btn-sm border sidebar-toggle-btn" id="sidebarToggle" type="button"
            aria-label="Toggle Sidebar" title="Buka/Tutup Sidebar">
            <i class="bi bi-list fs-5"></i>
        </button>
    </div>

    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-person-circle text-secondary"></i>
        <span class="fw-medium text-dark small">{{ auth()->user()->name ?? 'Operator' }}</span>
    </div>
</header>
