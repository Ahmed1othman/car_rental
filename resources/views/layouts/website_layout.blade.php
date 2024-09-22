<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="An impressive and flawless site template that includes various UI elements and countless features, attractive ready-made blocks and rich pages, basically everything you need to create a unique and professional website.">
    <meta name="keywords" content="bootstrap 5, business, corporate, creative, gulp, marketing, minimal, modern, multipurpose, one page, responsive, saas, sass, seo, startup, html5 template, site template">
    <meta name="author" content="elemis">
    <title>@yield('title', 'مكتب محاماه')</title>
    <link rel="shortcut icon" href="{{ asset('website/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('website/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/rtl_style.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/colors/purple.css') }}">
{{--    <link rel="preload" href="{{ asset('website/css/fonts/urbanist.css') }}" as="style" onload="this.rel='stylesheet'">--}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Almarai;
        }

        .video-wrapper.bg-overlay.bg-overlay-gradient:after {
            opacity: .5;
            background: linear-gradient(120deg, #542461 50%, #7d8017 100%);
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="page-frame bg-pale-primary">
        @include('includes.website.header')
        <div>
            @yield('content')
        </div>
    </div>
@include('includes.website.footer')

<script src="{{ asset('website/js/plugins.js') }}"></script>
<script src="{{ asset('website/js/theme.js') }}"></script>
@stack('scripts')
</body>

</html>
