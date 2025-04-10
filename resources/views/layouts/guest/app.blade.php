<!DOCTYPE html>
<html lang="zxx">

<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="{{ getSetting('site_description') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ getSetting('site_description') }}">
    <meta name="keywords" content="{{ getSetting('site_keywords') }}">
    <!--====== Title ======-->
    <title>@yield('title') - {{ getSetting('site_name') }}</title>
    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="{{ asset('storage/' . getSetting('site_favicon')) }}" type="image/png">
    <link rel="stylesheet" href="{{ route('theme.css') }}">

    <!--====== Google Fonts ======-->
    {{-- <link
        href="https://fonts.googleapis.com/css2?family=Aoboshi+One&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap"
        rel="stylesheet"> --}}
    <link rel="preload" href="{{ asset('assets/guest/fonts/SFProDisplay-Bold.woff2') }}" as="font"
        type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="{{ asset('assets/guest/fonts/SFProDisplay-Regular.woff2') }}" as="font"
        type="font/woff2" crossorigin="anonymous">

    <!--====== Flaticon css ======-->
    <link rel="stylesheet" href="{{ asset('assets/guest/fonts/flaticon/flaticon_pesco.css') }}">
    <!--====== FontAwesome css ======-->
    <link rel="stylesheet" href="{{ asset('assets/guest/fonts/fontawesome/css/all.min.css') }}">
    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="{{ asset('assets/guest/vendor/bootstrap/css/bootstrap.min.css') }}">
    <!--====== Slick-popup css ======-->
    <link rel="stylesheet" href="{{ asset('assets/guest/vendor/slick/slick.css') }}">
    <!--====== Nice Select css ======-->
    <link rel="stylesheet" href="{{ asset('assets/guest/vendor/nice-select/css/nice-select.css') }}">
    <!--====== Magnific-popup css ======-->
    <link rel="stylesheet" href="{{ asset('assets/guest/vendor/magnific-popup/dist/magnific-popup.css') }}">
    <!--====== Jquery UI css ======-->
    <link rel="stylesheet" href="{{ asset('assets/guest/vendor/jquery-ui/jquery-ui.min.css') }}">
    <!--====== Animate css ======-->
    <link rel="stylesheet" href="{{ asset('assets/guest/vendor/aos/aos.css') }}">
    <!--====== Default css ======-->
    <link rel="stylesheet" href="{{ asset('assets/guest/css/default.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--====== Style css ======-->
    <link rel="stylesheet" href="{{ asset('assets/guest/css/style.css') }}">

</head>

<body>
    <!--====== Preloader ======-->

    <!--======  Start Overlay  ======-->
    <div class="offcanvas__overlay"></div>
    @include('layouts.guest.header')
    <!--====== Main Bg  ======-->
    <main class="main-bg">
        @yield('content')
    </main>
    @include('layouts.guest.footer')
    @include('layouts.guest.components.mobile-bottom-menu')
    <!--====== Back To Top  ======-->
    <div class="back-to-top"><i class="far fa-angle-up"></i></div>
    <!--====== Jquery js ======-->
    <script src="{{ asset('assets/guest/vendor/jquery-3.7.1.min.js') }}"></script>
    <!--====== Bootstrap js ======-->
    <script src="{{ asset('assets/guest/vendor/popper/popper.min.js') }}"></script>
    <!--====== Bootstrap js ======-->
    <script src="{{ asset('assets/guest/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <!--====== Slick js ======-->
    <script src="{{ asset('assets/guest/vendor/slick/slick.min.js') }}"></script>
    <!--====== Magnific js ======-->
    <script src="{{ asset('assets/guest/vendor/magnific-popup/dist/jquery.magnific-popup.min.js') }}"></script>
    <!--====== Nice-select js ======-->
    <script src="{{ asset('assets/guest/vendor/nice-select/js/jquery.nice-select.min.js') }}"></script>
    <!--====== Jquery Ui js ======-->
    <script src="{{ asset('assets/guest/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <!--====== SimplyCountdown js ======-->
    <script src="{{ asset('assets/guest/vendor/simplyCountdown.min.js') }}"></script>
    <!--====== Aos js ======-->
    <script src="{{ asset('assets/guest/vendor/aos/aos.js') }}"></script>
    <!--====== Main js ======-->
    <script src="{{ asset('assets/guest/js/theme.js') }}"></script>



</body>

</html>
