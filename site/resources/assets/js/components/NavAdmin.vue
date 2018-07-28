<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark justify-content-between">
        <a class="navbar-brand" href="/">
            Rust ReCON
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#app-navbar-collapse" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <router-link class="nav-link"
                                 class-active="active"
                                 to="/dashboard">Dashboard</router-link>
                </li>
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
                <li class="nav-item">
                    <router-link class="nav-link"
                                 class-active="active"
                                 to="/servers">Servers</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link"
                                 class-active="active"
                                 to="/players">Players</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link"
                                 class-active="active"
                                 to="/chat">Chat</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link"
                                 class-active="active"
                                 to="/serverEvents">Events List</router-link>
                </li>
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav justify-content-end">
                <!-- Authentication Links -->
                <server-select></server-select>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                       aria-expanded="false" aria-haspopup="true">
                        {{ authUser.name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <router-link class="nav-link dropdown-item"
                                         class-active="active"
                                         :to="'/u/' + authUser.slug">Profile</router-link>
                        </li>
                        <li>
                            <router-link class="nav-link dropdown-item"
                                         class-active="active"
                                         to="/admin">Admin</router-link>
                        </li>
                        <li>
                            <a href="/logout" class="nav-link dropdown-item"
                               onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="logout" method="POST" style="display: none;">

                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

</template>
<script>
    import ServerSelect from './ServerSelect';

    export default {
        props: [
            'authedUserData'
        ],
        components: {
            ServerSelect
        },
        data: function() {
            return {
                authUser: {}
            }
        },
        created: function() {
            console.log('this: ', this);

            this.authUser = _.clone(this.authedUserData);
        },
        methods: {
        },
        computed: {
        }
    }
</script>
