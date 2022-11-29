export default {
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