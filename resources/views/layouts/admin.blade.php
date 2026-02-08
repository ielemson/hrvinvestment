<!DOCTYPE html>
<html lang="en">
@php
    $settings = DB::table('site_settings')->first(); // Or Settings model
@endphp

<head>

    <title>Dashboard:
        {{ $settings->meta_title ?? ($settings->site_name ?? 'HV UK RF1 INVESTMENTS LTD & HV Royalty Acquisition II Trust ') }}
    </title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">

    {{-- Favicon & Logo --}}
    <link rel="icon" href="{{ asset($settings->favicon_path) }}" type="image/x-icon">
    @if ($settings->logo_mini_path)
        <link rel="apple-touch-icon" href="{{ asset($settings->logo_mini_path) }}">
    @endif

    {{-- Core SEO Meta --}}
    <meta name="description" content="{{ $settings->meta_description ?? '' }}">
    <meta name="keywords" content="{{ $settings->meta_keywords ?? '' }}">
    <meta name="author"
        content="{{ $settings->site_name ?? 'HV UK RF1 INVESTMENTS LTD & HV Royalty Acquisition II Trust ' }}">

    {{-- Open Graph / Social Media --}}
    <meta property="og:title"
        content="{{ $settings->meta_title ?? ($settings->site_name ?? 'HV UK RF1 INVESTMENTS LTD & HV Royalty Acquisition II Trust ') }}">
    <meta property="og:description" content="{{ $settings->meta_description ?? '' }}">
    <meta property="og:image" content="{{ asset($settings->og_image_path ?? $settings->logo_path) }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name"
        content="{{ $settings->site_name ?? 'HV UK RF1 INVESTMENTS LTD & HV Royalty Acquisition II Trust ' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- plugins:css -->
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('admin/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->

    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('admin/css/horizontal-layout/style.css') }}">
    <!-- endinject -->

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset($settings->favicon_path) }}" />
    @stack('styles')

</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_horizontal-navbar.html -->


        @auth
            @role('admin')
                @include('admin.partials.navbar')
                @elserole('user')
                @include('layouts.partials.nav-user')
            @else
                @include('layouts.partials.nav-user') {{-- fallback --}}
            @endrole
        @endauth

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                @include('admin.partials.footer')
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="{{ asset('admin/vendors/chart.js/chart.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/jquery-bar-rating/jquery.barrating.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/morris.js/morris.min.js') }}"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
    <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('admin/js/template.js') }}"></script>
    <script src="{{ asset('admin/js/settings.js') }}"></script>
    <script src="{{ asset('admin/js/todolist.js') }}"></script>
    <script src="{{ asset('admin/js/file-upload.js') }}"></script>

    <!-- endinject -->

    <!-- Custom js for this page -->
    <script src="{{ asset('admin/js/dashboard.js') }}"></script>
    <!-- End custom -->
    @stack('scripts')

</body>

</html>
