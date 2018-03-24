require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'

if(document.getElementById('vueapp') !== null)
{
	// Fucking Laravel ending-slash hating bullshit.
	if(location.pathname.slice(-1) != '/')
		history.pushState(null, null, location.pathname + '/');

	Vue.component('example-component', require('./components/ExampleComponent.vue'));
	Vue.component('dashboard', require('./components/Dashboard.vue'));

	var router = new VueRouter({
		routes:[
			{'path': '', 'component': Vue.component('dashboard')}
		]
	});

	Vue.use(VueRouter);

	var app = new Vue({
		'el': '#vueapp',
		'router': router,
		'data': {
			'server_selected': null
		}
	});
}
