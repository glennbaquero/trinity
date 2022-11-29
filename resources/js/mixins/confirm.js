export default {
	methods: {
		showConfirm(event) {
			if (!this.confirmDialog) {
				this.onDialogSuccess(event);
				return;
			}

			let message = {
				title: this.dialog_title,
				title: this.dialog_message,
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

	props: {
		/**
		 * Confirm Dialog
		 */
		
		confirmDialog: {
			default: false,
			type: Boolean,
		},

		title: {
			default: 'Confirm Action',
			type: String,
		},

		message: {
			default: 'Are you sure you want to proceed with this action?',
			type: String,
		},

		dialogType: {
			default: 'basic', // "basic", "soft" & "hard"
			type: String,
		},

		okText: {
			default: 'Continue',
			type: String,
		},

		cancelText: {
			default: 'Cancel',
			type: String,
		},

		verification: {
			default: 'continue',
			type: String,
		},

		verificationHelp: {
			default: 'Type "[+:verification]" below to confirm',
			type: String,
		},
	}
}