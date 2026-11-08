<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Peminjaman Alat')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <script>
        (function() {
            try {
                if (window.innerWidth >= 992 && localStorage.getItem('sparehub_sidebar_collapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>

    @stack('styles')
</head>

<body>
    <script>
        if (document.documentElement.classList.contains('sidebar-collapsed')) {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>

    @include('components.sidebar')

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="app-main">
        @include('components.navbar')

        <main class="app-content">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            const isDesktop = () => window.innerWidth >= 992;

            function toggleSidebar() {
                if (isDesktop()) {
                    const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
                    document.documentElement.classList.toggle('sidebar-collapsed', isCollapsed);
                    localStorage.setItem('sparehub_sidebar_collapsed', isCollapsed ? 'true' : 'false');
                } else {
                    document.body.classList.toggle('sidebar-open');
                }
            }

            function closeMobileSidebar() {
                document.body.classList.remove('sidebar-open');
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeMobileSidebar);
            }

            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', closeMobileSidebar);
            }

            const sidebarLinks = document.querySelectorAll('.app-sidebar .sidebar-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (!isDesktop()) {
                        closeMobileSidebar();
                    }
                });
            });

            window.addEventListener('resize', function() {
                if (isDesktop() && document.body.classList.contains('sidebar-open')) {
                    closeMobileSidebar();
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
