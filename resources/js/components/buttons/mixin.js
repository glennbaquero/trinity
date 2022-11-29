import ButtonLoader from '../loaders/ButtonLoader.vue';

export default {
	computed: {
		button_disabled() {
			return this.loading;
		},
	},
	
	components: {
		'button-loader': ButtonLoader,
	},
}