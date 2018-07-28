require('./bootstrap');

import Vue from 'vue'
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
const ServerEventsList = Vue.component('server-events-list', require('./components/ServerEventsList.vue'));
const ServerRecord = Vue.component('server-record', require('./components/ServerRecord.vue'));

var router = new VueRouter({
    //mode: 'history',
    routes: [
        {
            'path': '/',
            'component': HomeCarousel
        }, {
            'path': '/a/:accountName',
            'component': AccountRecord
        }, {
            'path': '/admin',
            'component': AdminIndex
        }, {
            'path': '/admin/users',
            'component': AdminUsers
        }, {
            'path': '/servers',
            'component': ServersList
        }, {
            'path': '/players',
            'component': PlayersList
        }, {
            'path': '/dashboard',
            'component': Dashboard
        }, {
            'path': '/dashboard/:serverId',
            'component': Dashboard
        }, {
            'path': '/s/:serverName',
            'component': ServerRecord
        }, {
            'path': '/u/:userName',
            'component': UserRecord
        }, {
            'path': '/serverEvents',
            'component': ServerEventsList
        }
    ]
});

Vue.use(VueRouter);

var app = new Vue({
    el: '#app',
    router: router,
    data: {
        errors: [],
        server_selected: null
    },
    created: function() {
        if (this.$route.path === '/dashboard') {
            let url = HTTP.buildUrl('user/me');
            HTTP.get(url)
                .then(response => {
                    sessionStorage.setItem('accounts', JSON.stringify(response.data.accounts));
                })
                .catch(e => {
                    HTTP.logError(url, e);
                    this.errors.push(e)
                });
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
