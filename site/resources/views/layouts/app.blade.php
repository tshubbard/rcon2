<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <base href="/" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    @yield('styles')
</head>
<body>
    <div id="app" class="site-wrapper">
        <header>
            <div class="container-fluid">
                @include('partials.nav')
            </div>
        </header>

        <div class="content">
            <router-view></router-view>
        </div>

        <div class="footer">
            <div class="container">
                <span class="float-right"><a href="#">Back to top</a></span>
                <span>© 2017 Amilix, Inc. · <a href="#">Privacy</a> · <a href="#">Terms</a></span>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
