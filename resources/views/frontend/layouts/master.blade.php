<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Default Title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc." />
    <meta name="author" content="Zoyothemes" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('frontend/assets/images/EC logo.webp') }}">

    <!-- App css -->
    <link href="{{ asset('frontend/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons -->
    <link href="{{ asset('frontend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('frontend/assets/js/head.js') }}"></script>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<!-- body start -->

<body data-menu-color="light" data-sidebar="default">

    <!-- Begin page -->
    <div id="app-layout">

        <!-- Topbar Start -->
        @include('frontend.layouts.topbar')
        <!-- end Topbar -->

        <!-- Left Sidebar Start -->
        @include('frontend.layouts.sidebar')
        <!-- Left Sidebar End -->

        <!-- content Start -->
        <div> @yield('content') </div>
        <!-- content End -->

        <!-- Footer Start -->
        @include('frontend.layouts.footer')
        <!-- end Footer -->

    </div>
    <!-- End Page content -->

    <!-- /div -->
    <!-- END wrapper -->

    <!-- Vendor -->
    <script src="{{ asset('frontend/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/libs/feather-icons/feather.min.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('frontend/assets/js/app.js') }}"></script>

    <!-- SweetAlert2 Notifications -->
    @include('sweetalert')

    <!-- Yield additional scripts -->
    @yield('scripts')

</body>

</html>