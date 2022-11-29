export default {
	methods: {
		load(value) {
			this.loading = value;
			this.$emit('load', value);
		},
	},

	data() {
		return {
			loading: false,
		}
	},
}