<aside class="app-sidebar" id="appSidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="sidebar-brand text-decoration-none d-flex align-items-center gap-2">
            <span class="brand-icon">
                <i class="bi bi-tools"></i>
            </span>
            <span class="brand-text">Sparehub</span>
        </a>

        <button type="button" class="btn btn-link text-white-50 d-lg-none p-1 text-decoration-none" id="sidebarClose"
            aria-label="Tutup Sidebar">
            <i class="bi bi-x-lg fs-5"></i>
        </button>
    </div>

    <div class="sidebar-body">
        <div class="px-3 mb-2 text-uppercase small text-secondary fw-semibold sidebar-heading">
            Menu Utama
        </div>

        <nav class="nav flex-column sidebar-nav">
            <a href="{{ route('dashboard') }}"
                class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                <span class="link-text">Menu Utama</span>
            </a>

            @if (Route::has('tools.index'))
                <a href="{{ route('tools.index') }}"
                    class="sidebar-link {{ request()->routeIs('tools.*') ? 'active' : '' }}">
                    <i class="bi bi-wrench-adjustable"></i>
                    <span class="link-text">Data Alat</span>
                </a>
            @endif

            @if (Route::has('borrowings.index'))
                <a href="{{ route('borrowings.index') }}"
                    class="sidebar-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span class="link-text">Riwayat Peminjaman</span>
                </a>
            @endif
        </nav>
    </div>

    @if (Route::has('logout'))
        <div class="sidebar-footer mt-auto p-3 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2 rounded-3">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    @endif
</aside>
