/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import bootstrap from './bootstrap.js';
import script from './script.js';

import VuejsDialog from 'vuejs-dialog';
Vue.use(VuejsDialog);

/** Vue-swatches css */
import "vue-swatches/dist/vue-swatches.min.css"

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('count-listener', require('./components/listeners/CountListener.vue').default);
Vue.component('page-pagination', require('./components/datatables/PagePagination.vue').default);

Vue.component('change-password-form', require('./components/forms/ChangePasswordForm.vue').default);

Vue.component('activity-logs-table', require('./views/activity-logs/ActivityLogsTable.vue').default);
Vue.component('notifications-table', require('./views/notifications/NotificationsTable.vue').default);

Vue.component('sample-items-table', require('./views/samples/SampleItemsTable.vue').default);
Vue.component('sample-items-view', require('./views/samples/SampleItemsView.vue').default);


import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'
 
Vue.use(VueGoogleMaps, {
  load: {
    key: 'AIzaSyBdZmifNZogZcfRQ-wZy0B7yVVTd0cvPm4',
    libraries: 'places', // This is required if you use the Autocomplete plugin
    // OR: libraries: 'places,drawing'
    // OR: libraries: 'places,drawing,visualization'
    // (as you require)
 
    //// If you want to set the version, you can do so:
    // v: '3.26',
  },
})


import developer from './developer.js';
import admin from './admin.js';
import web from './web.js';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = {
	init() {
		this.setup();
	},

	setup() {
		new Vue({
			el: '#app',
			
			methods: {
				initList(component) {
					let element = this.$refs[component];

					if(element) {
						if(!element.has_fetched) {
							element.fetch();
						}
					}
				}
			},
		});
	}
}

app.init();
