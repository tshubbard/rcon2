require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
import VueMaterial from 'vue-material';
import 'vue-material/dist/vue-material.min.css';
import VueSocketio from 'vue-socket.io';

Vue.use(VueMaterial);
Vue.use(VueSocketio, 'http://localhost:6969');

import axios from 'axios';

export const HTTP = axios.create({
    //baseURL: '',
    headers: {
        Authorization: 'Bearer {token}'
    }
});
HTTP._apiVersion = 'v1';
HTTP.buildUrl = function(path) {
    if (path[0] === '/') {
        // remove the leading slash
        path = path.substr(1);
    }
    return '/api/' + this._apiVersion + '/' + path;
};
HTTP.logError = function(url, error) {
    console.error('[API ' + this._apiVersion + ' Error] ', url, error);
};

const Dashboard = Vue.component('dashboard', require('./components/Dashboard.vue'));
const AccountRecord = Vue.component('account-record', require('./components/AccountRecord.vue'));
const UserRecord = Vue.component('user-record', require('./components/UserRecord.vue'));
const HomeCarousel = Vue.component('home-carousel', require('./components/HomeCarousel.vue'));
const AdminIndex = Vue.component('admin-index', require('./components/AdminIndex.vue'));
const AdminUsers = Vue.component('admin-users', require('./components/AdminUsers.vue'));
const ServersList = Vue.component('servers-list', require('./components/ServersList.vue'));
const PlayersList = Vue.component('players-list', require('./components/PlayersList.vue'));
const ChatList = Vue.component('chat-list', require('./components/ChatList.vue'));
const ServerEventsList = Vue.component('server-events-list', require('./components/ServerEventsList.vue'));
const ServerRecord = Vue.component('server-record', require('./components/ServerRecord.vue'));
const EventBus = new Vue();

var router = new VueRouter({
    //mode: 'history',
    routes: [
        {
            path: '/',
            component: HomeCarousel,
            name: 'Home',
            meta: { zone: 'home' }
        }, {
            path: '/a/:accountName',
            component: AccountRecord,
            name: 'Account Record',
            meta: { zone: 'authed' }
        }, {
            path: '/admin',
            component: AdminIndex,
            name: 'Admin Dashboard',
            meta: { zone: 'admin' }
        }, {
            path: '/admin/users',
            component: AdminUsers,
            name: 'Admin Users',
            meta: { zone: 'admin' }
        }, {
            path: '/servers',
            component: ServersList,
            name: 'Servers List',
            meta: { zone: 'authed' }
        }, {
            path: '/players',
            component: PlayersList,
            name: 'Players List',
            meta: { zone: 'authed' }
        }, {
            path: '/chat',
            component: ChatList,
            name: 'Chat List',
            meta: { zone: 'authed' }
        }, {
            path: '/dashboard',
            component: Dashboard,
            name: 'Dashboard',
            meta: { zone: 'authed' }
        }, {
            path: '/dashboard/:serverId',
            component: Dashboard,
            name: 'Server Dashboard',
            meta: { zone: 'authed' }
        }, {
            path: '/s/:serverName',
            component: ServerRecord,
            name: 'Servers Record',
            meta: { zone: 'authed' }
        }, {
            path: '/u/:userName',
            component: UserRecord,
            name: 'User Record',
            meta: { zone: 'authed' }
        }, {
            path: '/serverEvents',
            component: ServerEventsList,
            name: 'Servers Events List',
            meta: { zone: 'authed' }
        }
    ]
});

Vue.use(VueRouter);

import NavBar from './components/NavBar';
Vue.use(NavBar);

Object.defineProperties(Vue.prototype, {
    $bus: {
        get: function () {
            return EventBus
        }
    }
});

var app = new Vue({
    el: '#app',
    router: router,
    data: {
        errors: [],
        server_selected: null
    },
    components: {
        NavBar
    },
    created: function() {
        if (this.$route.path === '/dashboard') {
            let userData = sessionStorage.getItem('me');
            console.log('userData ', JSON.parse(userData));

            if (!userData) {
                let url = HTTP.buildUrl('user/me');
                HTTP.get(url)
                    .then(response => {
                        sessionStorage.setItem('accounts', JSON.stringify(response.data.accounts));
                        sessionStorage.setItem('me', JSON.stringify(response.data));

                        this.$bus.$emit('user-authed', response.data);
                    })
                    .catch(e => {
                        HTTP.logError(url, e);
                        window.location.replace('/');
                    });
            }
        }
    },
    sockets: {
        connect: function() {
            console.log('socket connected ', this.$socket);
            // DEV CODE REMOVE YOU FUCK
            window.$socket = this.$socket;
        }
    },
});
