import ResponseMixin from '../../mixins/response.js';
import LoaderMixin from '../loaders/mixin.js';
import ConfirmProps from '../../mixins/confirm/props.js';
import ConfirmMethods from '../../mixins/confirm/methods.js';

export default {
	methods: {
		onDialogSuccess(event, dialog) {
			this.submit(event, dialog);
		},

		submit(event, dialog = null) {
			this.load(true);
	
			let params = this.params;

			if (!this.params) {
				let form = event.target;
				params = new FormData(form);
			}

			console.log(params);

			axios.post(this.submitUrl, params)
			.then(response => {
				this.$emit('success');

				const data = response.data;
				
				if (data.message) {
					this.parseSuccess(data.message);
				}

				this.success(event, data, response);
			}).catch(error => {
				this.$emit('error');
				this.parseError(error);
				this.error(event, error);
			}).then(() => {
				this.load(false);

				if (dialog) {
					dialog.loading(false);
					dialog.close();
				}
			});
		},

		success(event, data, response) {
			console.log(event, data, response);
		},

		error(event, error) {

		},
	},

	data() {
		return {
			item: {},
			token: null,
		}
	},

	props: {
		submitUrl: String,

		params: {},
	},

	mixins: [ ResponseMixin, LoaderMixin, ConfirmProps, ConfirmMethods ],
}