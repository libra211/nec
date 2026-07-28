<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'NEC South Sudan - National Elections Commission' }}</title>
    <meta name="description" content="{{ $meta_description ?? 'National Elections Commission of South Sudan - Ensuring free, fair, and credible elections for the people of South Sudan.' }}">
    <meta name="keywords" content="NEC, South Sudan, elections, voting, voters registration, political parties">
    <meta name="author" content="National Elections Commission - South Sudan">
    <meta property="og:title" content="{{ $title ?? 'NEC South Sudan' }}">
    <meta property="og:description" content="National Elections Commission of South Sudan">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logos/neclogo.jpeg') }}">
    <link rel="icon" type="image/jpeg" sizes="32x32" href="{{ asset('assets/images/logos/neclogo.jpeg') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- Leaflet CSS -->
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

    <!-- NEC Custom CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dark-mode.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animations.css') }}" rel="stylesheet">

    @yield('extra_head')
    @stack('styles')

    <!-- Anti-inspection: disable right-click, F12, Ctrl+U, Ctrl+Shift+I, etc. -->
    <script>
    document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && ['I','C','J'].includes(e.key.toUpperCase())) || (e.ctrlKey && ['U','S'].includes(e.key.toUpperCase()))) {
            e.preventDefault(); e.stopPropagation();
        }
    });
    </script>
</head>

<body>

    @include('layouts.partials.header')

    <!-- HERO SECTION -->
    @yield('hero')

    <!-- MAIN CONTENT -->
    <main class="main-content">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @yield('extra_scripts')
    @stack('scripts')
</body>
</html>
