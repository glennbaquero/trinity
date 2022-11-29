<template>
	<form @submit.prevent="showConfirm" method="GET" action="javascript:void(0)">
		<input v-if="token" type="hidden" name="_token" v-model="token">

		<slot></slot>
	</form>
</template>

<script type="text/javascript">
import FormMixin from './mixin.js';
import { EventBus } from '../../EventBus.js';

export default {
	mounted() {
		this.setup();
	},

	methods: {
		setup() {
			if (this.normalSubmitOnSuccess) {
				this.token = document.head.querySelector('meta[name="csrf-token"]').content;
			}
		},

		success(event, data, response) {
			if (this.normalSubmitOnSuccess) {
				setTimeout(() => {
					event.target.submit();
				}, 500);
				return;
			}

			if (data.redirect) {
				window.location.href = data.redirect;
				return;
			}

			if (this.resetOnSuccess) {
				event.target.reset();
			}

			if (this.syncOnSuccess) {
				EventBus.$emit('sync-tables');
			}
		},
	},

	props: {
		resetOnSuccess: {
			default: false,
			type: Boolean,
		},

		syncOnSuccess: {
			default: false,
			type: Boolean,
		},

		normalSubmitOnSuccess: {
			default: false,
			type: Boolean,
		}
	},

	mixins: [ FormMixin ],
}
</script>