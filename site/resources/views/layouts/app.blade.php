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

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons|Titillium+Web" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/vue-material@beta/dist/theme/default.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>
<body>
    <div id="app" class="site-wrapper" v-cloak>
        <header>
            <div class="container-fluid">

                @guest
                    @include('partials.nav')
                @else
                    <nav-bar></nav-bar>
                @endguest
            </div>
        </header>

        <div class="content">
            @guest
                @yield('content')
            @else
                <router-view></router-view>
            @endguest
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
