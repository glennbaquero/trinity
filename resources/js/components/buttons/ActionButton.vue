<template>
	<button @click="showConfirm" type="button" class="btn" :class="[ buttonColor, buttonSize ]" :title="title" :disabled="buttonDisabled">
		<template v-if="buttonLoading">
			<div class="spinner-border spinner-border-sm" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		</template>
		<template v-else>
			<i v-if="iconVisibility" :class="[ buttonIcon ]"></i>
			<slot>{{ buttonLabel }}</slot>
		</template>
	</button>
</template>

<script type="text/javascript">
// Read Vue Dialog for usage and options https://www.npmjs.com/package/vuejs-dialog
import ResponseMixin from '../../mixins/response.js';
import ConfirmProps from '../../mixins/confirm/props.js';
import ConfirmMethods from '../../mixins/confirm/methods.js';

export default {
	computed: {
		buttonDisabled() {
			return this.disabled;
		},

		buttonLoading() {
			return this.loading;
		},

		buttonColor() {
			return this.showAlt ? this.altColor : this.color;
		},

		buttonSize() {
			if (this.small) {
				return 'btn-sm';
			}
		},

		buttonLabel() {
			return this.showAlt ? this.altLabel : this.label;
		},

		buttonIcon() {
			return this.showAlt ? this.altIcon : this.icon;
		},

		iconVisibility() {
			return this.icon || this.altIcon;
		},

		dialog_title() {
			return this.showAlt ? this.altTitle : this.title;
		},

		dialog_message() {
			return this.showAlt ? this.altMessage : this.message;
		},
	},

	methods: {
		onDialogSuccess(event, dialog) {
			this.submit(event, dialog);
		},

		submit(event, dialog = null) {
			if (this.isLoading) { 
				dialog.loading(false); 
				dialog.close(); 
				return; 
			}

			let url = this.showAlt ? this.altActionUrl : this.actionUrl;
			
			/* If not action url proceed to success immediately */
			if (!url) { 
				dialog.loading(false); 
				dialog.close(); 
				this.onSuccess(); 
				return; 
			}

			this.load(true);

			axios.post(url, this.request)
			.then(response => {
				const data = response.data;

				if (!this.hideResponse) {
					this.parseSuccess(data.message);
				}

				this.onSuccess(response);
				
			}).catch(error => {
				if (!this.hideResponse) {
					this.parseError(error);
				}

				this.onError(error);
			}).then(() => {

				this.load(false);
				
				if (dialog) {
					dialog.loading(false);
					dialog.close();
				}
			});
		},

		onSuccess(response = null) {
			if (this.href) {
				if (this.target) {
					window.open(this.href, this.target);
				} else {
					window.location.href = this.href;
				}
			}

			this.$emit('success', response);
		},

		onError(error = null) {
			this.$emit('error', error);
		},

		load(value) {
			this.isLoading = value;
			this.$emit('load', value);
		},
	},

	data() {
		return {
			isLoading: false,
		}
	},

	props: {
		/**
		 * States
		 */
		request: {
			type: Object,
			default() {
				return {};
			}
		},
		
		disabled: {
			default: false,
		},

		loading: {
			default: false,
		},

		showAlt: {
			default: false,
		},

		/**
		 * Button Label & Styling
		 */
		
		icon: {
			default: null,
		},

		altIcon: {
			default: null,
		},

		label: {
			default: null,
		},

		altLabel: {
			default: null
		},

        color: {
            default: null,
            type: String,
        },

        altColor: {
            default: null,
            type: String,
        },

        /**
         * Link
         */
        href: String,
        target: String,

        /**
         * Buttton Sizes
         */

		small: {
			default: false,
			type: Boolean,
		},

		/**
		 * Actions
		 */

 		actionUrl: String,
		altActionUrl: String,

		hideResponse: {
			default: false,
			type: Boolean,
		},

		/**
		 * Confirm Dialog
		 */
		altTitle: {
			default: 'Confirm Action',
			type: String,
		},

		altMessage: {
			default: 'Are you sure you want to proceed with this action?',
			type: String,
		},
	},

	model: {
		props: 'showAlt',
		event: 'change',
	},

	mixins: [ ResponseMixin, ConfirmProps, ConfirmMethods ],
}
</script>