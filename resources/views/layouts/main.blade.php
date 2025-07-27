<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.components.head')

    <!-- ✅ Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Optional: SweetAlert2 CSS (optional, if needed for styling consistency) -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body class="g-sidenav-show bg-gray-100">

    <!-- ✅ Sidebar/Menu -->
    @include('layouts.components.menu')

    <!-- ✅ Main Content -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <!-- ✅ Navbar -->
        @include('layouts.components.navbar')

        <!-- ✅ Page Content -->
        <section class="content">
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </section>

        <!-- ✅ Footer -->
        @include('layouts.components.footer')

    </main>
    <!-- ✅ TEMPATKAN MODAL DI SINI -->
@stack('modals')

    <!-- ✅ Bootstrap JS via CDN (includes Popper.js) -->
    
    <!-- Bootstrap JS bundle (termasuk Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- ✅ SweetAlert2 for alerts & popups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ✅ Custom Sidebar Toggle Script -->
    <script>
        const toggleBtn = document.getElementById('iconNavbarSidenav');
        const sidebar = document.getElementById('sidenav-main');
        const closeBtn = document.getElementById('iconSidenav');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });
        }

        if (closeBtn && sidebar) {
            closeBtn.addEventListener('click', () => {
                sidebar.classList.add('collapsed');
            });
        }
    </script>

    <!-- ✅ Yield additional scripts -->
    @stack('scripts')

</body>
</html>
