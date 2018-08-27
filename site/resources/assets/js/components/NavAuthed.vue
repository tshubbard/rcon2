<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark justify-content-between">
        <a class="navbar-brand" href="/">
            <img src="images/rr_wm.png" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#app-navbar-collapse" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <router-link class="nav-link"
                                 class-active="active"
                                 to="/dashboard">Dashboard</router-link>
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
            <server-select></server-select>

            <!-- Right Side Of Navbar -->
            <ul class="nav justify-content-end">
                <!-- Authentication Links -->
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
                        <li v-if="authUser.role_id === 4">
                            <router-link class="nav-link dropdown-item"
                                         class-active="active"
                                         to="/admin">Admin</router-link>
                        </li>
                        <li>
                            <md-divider></md-divider>
                        </li>
                        <li>
                            <a class="nav-link dropdown-item" class-active="active"
                               href="javascript:void(0)"
                               @click="this.onLogoutClicked">
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

</template>
<script>
    import {HTTP} from '../app';
    import ServerSelect from './ServerSelect';

    export default {
        props: [
            'authedUserData',
            'appNameData'
        ],
        components: {
            ServerSelect
        },
        data: function() {
            return {
                appName: '',
                authUser: {},
                errors: []
            }
        },
        created: function() {
            console.log('[NavAuthed] this NavAuthed: ', this);

            this.authUser = _.clone(this.authedUserData);
            this.appName = _.clone(this.appNameData);

            console.log('NavAuthed this.authUser ', this.authUser);
        },
        methods: {
            onLogoutClicked: function() {
                sessionStorage.clear();
                HTTP.get('/logout')
                    .then(response => {
                        console.log('logout data: ', response);
                        window.location.replace('/');
                    })
                    .catch(e => {
                        HTTP.logError('/logout', e);

                        this.errors.push(e)
                    });
            }
        },
        computed: {

        }
    }
</script>
