export default {
	methods: {
		showConfirm(event) {
			if (!this.confirmDialog) {
				this.onDialogSuccess(event);
				return;
			}

			let message = {
				title: this.dialog_title,
				body: this.dialog_message,
			}

			let options = {
				loader: true,
				okText: this.okText,
				cancelText: this.cancelText,
				animation: 'fade',
				type: this.dialogType,
				verification: this.verification,
				verificationHelp: this.verificationHelp,
			};

			this.$dialog.confirm(message, options)
			.then((dialog) => {
				this.onDialogSuccess(event, dialog);
			}).catch(() => {
				this.onDialogCancel(event);
			});
		},

		onDialogSuccess(event, dialog) {

		},

		onDialogCancel(event) {

		},
	},

	computed: {
		dialog_title() {
			return this.title;
		},

		dialog_message() {
			return this.message;
		},
	},
}