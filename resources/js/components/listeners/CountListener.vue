<template>
	<span v-show="count && !loading" class="badge">{{ count }}</span>
</template>

<script type="text/javascript">
import { EventBus } from '../../EventBus.js';
import FetchMixin from '../../mixins/fetch.js';

export default {
	mounted() {
		this.listen();
	},

	methods: {
		listen() {
			if (this.event && !this.disabled) {
				EventBus.$on(this.event, () => {
					this.fetch();
				});
			}
		},

		fetchSuccess(data) {
			this.count = data.count;
		},
	},

	data() {
		return {
			count: 0,
		}
	},

	props: {
		event: String,

		fetchUrl: String,

		disabled: {
			default: false,
			type: Boolean,
		}
	},

	mixins: [ FetchMixin ],
}
</script>