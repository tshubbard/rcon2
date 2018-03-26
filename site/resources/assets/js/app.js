require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'

if (document.getElementById('app') !== null) {

    const Dashboard = Vue.component('dashboard', require('./components/Dashboard.vue'));
    const AccountRecord = Vue.component('account-record', require('./components/AccountRecord.vue'));
    const UserRecord = Vue.component('user-record', require('./components/UserRecord.vue'));
    const HomeCarousel = Vue.component('home-carousel', require('./components/HomeCarousel.vue'));
    const AdminIndex = Vue.component('admin-index', require('./components/AdminIndex.vue'));
    const AdminUsers = Vue.component('admin-users', require('./components/AdminUsers.vue'));

    var router = new VueRouter({
        mode: 'history',
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
                'path': '/dashboard',
                'component': Dashboard
            }, {
                'path': '/dashboard/:serverId',
                'component': Dashboard
            }, {
                'path': '/u/:userName',
                'component': UserRecord
            }
        ]
    });

    Vue.use(VueRouter);

    var app = new Vue({
        'el': '#app',
        'router': router,
        'data': {
            'server_selected': null
        }
    });
}
