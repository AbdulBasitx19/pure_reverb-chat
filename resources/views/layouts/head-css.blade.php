<!-- App favicon -->
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- jsvectormap css -->
<link href="{{ asset('assets/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />

<!--Swiper slider css-->
<link href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Layout config Js -->
<script src="{{ asset('assets/js/layout.js') }}"></script>

<!-- Bootstrap Css -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Icons Css -->
<link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

<!-- App Css-->
<link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<!-- custom Css-->
<link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 Bootstrap 5 Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />


{{-- if custom css needed for any page --}}
    @yield('page-css')