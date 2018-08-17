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
            <div class="container-fluid bg-dark">
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
                <span>© 2018 Archonoclast, Inc. · <a href="#">Privacy</a> · <a href="#">Terms</a></span>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')

    <script src="//widget-cdn.bugplug.omatum.com/bp.js"></script>
    <script>
        //Initialize the Widget ---
        var _bpopts ={
            "API_KEY" : "60e0783589465e359dadc092",
            "screen_anchor" : "left"
        };
        if (_bugplug) {
            var user = JSON.parse(sessionStorage.getItem('me'));
            _bugplug.init(_bpopts);
            if (user && user.id) {
                _bugplug.addVar({"logged_in": true});
                _bugplug.addVar({"user_name": user.name});
                _bugplug.addVar({"user_id": user.id});
                _bugplug.addVar({"user_email": user.email});
            } else {
                _bugplug.addVar({"logged_in": false});

            }
            _bugplug.addVar({"browser": navigator.userAgent});
        }
    </script>
</body>
</html>
