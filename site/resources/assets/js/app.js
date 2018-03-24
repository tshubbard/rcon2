require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'

if(document.getElementById('app') !== null)
{

	Vue.component('example-component', require('./components/ExampleComponent.vue'));
    const Dashboard = Vue.component('dashboard', require('./components/Dashboard.vue'));
    const UserRecord = Vue.component('user-record', require('./components/UserRecord.vue'));

	var router = new VueRouter({
        mode: 'history',
        routes:[{
            'path': '/dashboard',
            'component': Dashboard
        }, {
            'path': '/u/:userName',
            'component': UserRecord
        }]
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
