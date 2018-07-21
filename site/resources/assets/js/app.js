require('./bootstrap');

// Fucking Laravel ending-slash hating bullshit.
if(location.pathname.slice(-1) != '/')
    history.pushState(null, null, location.pathname + '/');

import Vue from 'vue'
import VueRouter from 'vue-router'
import VueMaterial from 'vue-material';
import 'vue-material/dist/vue-material.min.css';

Vue.use(VueMaterial);

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

if (document.getElementById('app') !== null) {

    const Dashboard = Vue.component('dashboard', require('./components/Dashboard.vue'));
    const AccountRecord = Vue.component('account-record', require('./components/AccountRecord.vue'));
    const UserRecord = Vue.component('user-record', require('./components/UserRecord.vue'));
    const HomeCarousel = Vue.component('home-carousel', require('./components/HomeCarousel.vue'));
    const AdminIndex = Vue.component('admin-index', require('./components/AdminIndex.vue'));
    const AdminUsers = Vue.component('admin-users', require('./components/AdminUsers.vue'));
    const AdminServers = Vue.component('admin-servers', require('./components/AdminServers.vue'));
    const AdminPlayers = Vue.component('admin-players', require('./components/AdminPlayers.vue'));
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
                'path': '/admin/servers',
                'component': AdminServers
            }, {
                'path': '/admin/players',
                'component': AdminPlayers
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
            if(this.$route.path === '/dashboard') {
                let url = HTTP.buildUrl('user/me');
                HTTP.get(url)
                    .then(response => {
                        sessionStorage.setItem('accounts', JSON.stringify(response.data.accounts));
                    })
                    .catch(e => {
                        console.error('[API Error] ', url, e);
                        this.errors.push(e)
                    });
            }
        }
    });
}
