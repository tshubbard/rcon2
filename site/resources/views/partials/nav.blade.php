<nav class="navbar navbar-expand-md navbar-dark bg-dark justify-content-between">
    <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name', 'rust-rcon') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#app-navbar-collapse" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
        <ul class="navbar-nav mr-auto">
            @auth
                <li class="nav-item">
                    <router-link class="nav-link"
                                 class-active="active"
                                 to="/dashboard">Dashboard</router-link>
                </li>

                @if(Auth::user()->isAdmin())
                    <li class="nav-item">
                        <router-link class="nav-link"
                                     class-active="active"
                                     to="/admin">Admin</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link"
                                     class-active="active"
                                     to="/admin/users">Users</router-link>
                    </li>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->isMod())
                    <li class="nav-item">
                        <router-link class="nav-link"
                                     class-active="active"
                                     to="/admin/servers">Servers</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link"
                                     class-active="active"
                                     to="/admin/players">Players</router-link>
                    </li>
                @endif
                <li class="nav-item">
                    <router-link class="nav-link"
                                 class-active="active"
                                 to="/serverEvents">Events List</router-link>
                </li>
            @endauth
        </ul>
        <!-- Right Side Of Navbar -->
        <ul class="nav justify-content-end">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('login') }}/">
                        Login
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('register') }}/">
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
                        <li>
                            <router-link class="nav-link dropdown-item"
                                         class-active="active"
                                         to="/u/{{Auth::user()->slug}}">Profile</router-link>
                        </li>
                        @if(Auth::user()->isAdmin())
                            <li>
                                <router-link class="nav-link dropdown-item"
                                             class-active="active"
                                             to="/admin">Admin</router-link>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('logout') }}" class="nav-link dropdown-item"
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
