<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    @include('layouts.head-css')
    
</head> 

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- Topbar Header -->
        @include('layouts.topbar') 
        <!---- App Menu ---->
        <!-- Sidebar Menu -->
        @include('layouts.sidebar')
        <!-- Left Sidebar End -->

        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                
                    @yield('content')
               
                <!-- container -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->



@include('layouts.body-tools')
@include('layouts.customizer')

<!-- Notification Modal (at the end of Body for better rendering) -->
@include('layouts.notification-modal')
@include('layouts.scripts')

   


</body>

</html>