<!DOCTYPE html>
<html class="wide wow-animation" lang="en">
@php
    $settings = DB::table('site_settings')->first(); // Or Settings model
@endphp

<head>
    <!-- Site Title-->
    {{-- resources/views/layouts/app.blade.php head section --}} <title>{{ $settings->meta_title ?? ($settings->site_name ?? 'HV Capitals') }}</title>
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
    <meta name="author" content="{{ $settings->site_name ?? 'HV Capitals' }}">

    {{-- Open Graph / Social Media --}}
    <meta property="og:title" content="{{ $settings->meta_title ?? ($settings->site_name ?? 'HV Capitals') }}">
    <meta property="og:description" content="{{ $settings->meta_description ?? '' }}">
    <meta property="og:image" content="{{ asset($settings->og_image_path ?? $settings->logo_path) }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $settings->site_name ?? 'HV Capitals' }}">


    <!-- Stylesheets-->
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto+Mono:300,400,500,700">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Remove old local FA CSS link, add this in <head> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Before </body> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .ie-panel {
            display: none;
            background: #212121;
            padding: 10px 0;
            box-shadow: 3px 3px 5px 0 rgba(0, 0, 0, .3);
            clear: both;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        html.ie-10 .ie-panel,
        html.lt-ie-10 .ie-panel {
            display: block;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="ie-panel"><a href="http://windows.microsoft.com/en-US/internet-explorer/"><img
                src="images/ie8-panel/warning_bar_0000_us.jpg" height="42" width="820"
                alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."></a>
    </div>
    <!-- Page Loader-->
    <div class="preloader" id="preloader">
        <div class="page-loader-body">
            <div id="loadingProgressG">
                <div class="loadingProgressG" id="loadingProgressG_1"></div>
            </div>
        </div>
    </div>
    <!-- Page-->
    <div class="page">
        <!-- Page Header -->
        @include('partials.header')

        @yield('content')
        <!-- Page Footer-->
        @include('partials.footer')

    </div>
    <!-- Global Mailform Output -->
    <div class="snackbars" id="form-output-global"></div>

    <!-- Javascript-->
    <script src="{{ asset('js/core.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    @stack('scripts')

    <script>
        (function() {
            var $carousel = $('#servicesCardSlider');
            var $inner = $carousel.find('.carousel-inner');

            // Clone next items so we always show 3 cards on desktop smoothly
            // (Like a multi-item carousel, but still Bootstrap 4 compatible)
            $carousel.find('.carousel-item').each(function() {
                var $item = $(this);
                var $next = $item.next();
                if (!$next.length) $next = $item.siblings(':first');

                // clone first card from next slide
                $next.children(':first-child').clone().appendTo($item.find('.row'));

                // clone second card
                var $nextNext = $next.next();
                if (!$nextNext.length) $nextNext = $item.siblings(':first');
                $nextNext.children(':first-child').clone().appendTo($item.find('.row'));
            });
        })();
    </script>


</body>

</html>
