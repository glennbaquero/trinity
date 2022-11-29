<template>
	<div class="form-group">
		<label>{{ label }} <small v-if="note" class="text-danger">({{ note }})</small></label>
		<template v-if="mode == 'single'">
			<input ref="elem" :name="name" :placeholder="placeholder" type="text" class="form-control" readonly>
		</template>
		<template v-if="mode == 'multiple'">
			<input ref="elem" :placeholder="placeholder" type="text" class="form-control" readonly>
			<input v-for="date in value" :name="name" :value="date" type="hidden">
		</template>
	</div>
</template>

<script type="text/javascript">
/* Flatpickr Documentation: https://flatpickr.js.org/options/ */
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

import ArrayHelpers from '../../mixins/array.js';

export default {
	watch: {
		value(value, oldValue) {
			if (!oldValue) {
				this.elem.setDate(value);
			}
		},
	},

	mounted() {
		this.setup();
	},

	methods: {
		setup() {
			let options = this.getOptions();
			
			if (this.defaultDate) {
				options.defaultDate = this.defaultDate;
			}

			this.elem = $(this.$refs.elem).flatpickr(options);
		},

		getOptions() {
			let $this = this;

			let options = {
				enableTime: this.enableTime,
				dateFormat: this.dateFormat,
				altInput : this.altInput,
				altFormat : this.altFormat,
				mode: this.mode,
				onChange(selectedDates, dateStr, instance) {
					let date = dateStr;

					switch ($this.mode) {
						case 'multiple':
								date = date.split(', ');
							break;
					}

					$this.$emit('change', date);
				},
				"disable": [
			        (date) => {
			        	if (this.disabledDates) {
			        		if (this.inArray(date.getDay(), this.disabledDates)) {
				        		return true;
				        	}	
			        	}

			        	if (this.disabledDays) {
				        	if (this.inArray(date.getDay(), this.disabledDays)) {
				        		return true;
				        	}
			        	}

			        	return false;
			        }
			    ],
			};

			if (this.minDate) {
				options.minDate = this.minDate;
			}

			return options;
		},
	},

	props: {
		label: String,

		placeholder: String,

		name: String,

		note: String,
		altInput: {
			default: false,
			type: Boolean,
		},
		altFormat: {
		 	default: "F j, Y",
			type: String,
		},
		placeholder: {
			default: null,
		},

		/* 'single', 'multiple,', 'range' */
		mode: {
			default: 'single',
			type: String,
		},

		enableTime: {
			default: true,
		},

		dateFormat: {
			default: 'Y-m-d H:i:S',
			type: String,
		},

		value: {},

		defaultDate: {},

		minDate: {},

		disabledDays: {},
		disabledDates: {},
	},

	data() {
		return {
			//
		}
	},

	model: {
        prop: 'value',
        event: 'change', 
    },

    mixins: [ ArrayHelpers ],
}
</script>

<style scoped>
.form-control[readonly] {
	background-color: #fff;
}
</style>