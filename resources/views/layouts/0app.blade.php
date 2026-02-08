<!DOCTYPE html>
<html class="wide wow-animation" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title', 'HV Capitals')</title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport"
        content="width=device-width height=device-height initial-scale=1.0 maximum-scale=1.0 user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">

    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    {{-- Fonts --}}
    <link rel="stylesheet" type="text/css"
        href="//fonts.googleapis.com/css?family=Work+Sans:300,700,800%7COswald:300,400,500">

    {{-- Vendor --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-styles-link">

    @stack('styles')
</head>

<body>
    <div class="page">

        {{-- Header/Nav --}}
        @yield('header')

        {{-- Page Content --}}
        <main>
            @yield('banner')
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('partials.footer')

    </div>
    {{-- Preloader --}}
    @include('partials.preloader')

    {{-- Global Mailform Output --}}
    <div class="snackbars" id="form-output-global"></div>
    {{-- JS --}}
    <script src="{{ asset('assets/js/core.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    @stack('scripts')
</body>

</html>
