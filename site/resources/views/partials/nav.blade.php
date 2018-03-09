<nav class="navbar navbar-expand-md navbar-dark bg-dark justify-content-between">
    <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name', 'Laravel') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#app-navbar-collapse" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
        <ul class="navbar-nav mr-auto">
            @auth
                <li class="nav-item active">
                    <a class="nav-link" href="/dashboard">Dashboard <span class="sr-only">(current)</span></a>
                </li>
            @endauth
        </ul>
        <!-- Right Side Of Navbar -->
        <ul class="nav justify-content-end">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('login') }}">
                        Login
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('register') }}">
                        Register
                    </a>
                </li>
            @else
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                       aria-expanded="false" aria-haspopup="true">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        @if(Auth::user()->isAdmin())
                            <li>
                                <a class="dropdown-item" href="/admin">Admin</a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('logout') }}" class="dropdown-item"
                               onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            @endguest
        </ul>
    </div>
</nav>
