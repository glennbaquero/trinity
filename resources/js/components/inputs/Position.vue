<template>
	<div class="col-sm-12">
		<action-button
		class="mb-2"
        v-if="fetchUrl"
        :small="small"
        color="btn-warning"
        icon="fas fa-map-pin"
        confirm-dialog
        :disabled="loading"
        title="Fetch Position"
        message="Are you sure you want to fetch position base on your address?"
        @load="load"
        @success="fetch">
        Fetch Position
    	</action-button>

    	<div class="row">
	        <div class="form-group col-sm-12 col-md-6">
	            <label>Latitude</label>
	            <input v-model="item.latitude" @change="change" type="text" name="latitude" class="form-control">
	        </div>

	        <div class="form-group col-sm-12 col-md-6">
	            <label>Longitude</label>
	            <input :value="item.longitude" @change="change" type="text" name="longitude" class="form-control">
	        </div>

	        <div class="col-sm-12">
	        	<small>Click <a href="https://www.wikihow.com/Get-Latitude-and-Longitude-from-Google-Maps" target="_blank">here</a> to find out how to manually get your latitude and longitude</small>
	        </div>
    	</div>
	</div>
</template>

<script type="text/javascript">
import LoaderMixin from '../loaders/mixin.js';
import ResponseMixin from '../../mixins/response.js';

import ActionButton from '../buttons/ActionButton.vue';

export default {
	methods: {
        fetch() {
            if (this.loading) { return; }

            let params = {
                address: this.address
            };

            this.load(true);

            axios.post(this.fetchUrl, params)
            .then(response => {
                const data = response.data;
                this.parseSuccess(data.message);
                this.item.latitude = data.latitude;
                this.item.longitude = data.longitude;

            	this.change();
            }).catch(error => {
                this.parseError(error);
            }).then(() => {
                this.load(false);
            });
        },

        change() {
        	this.$emit('change', {
            	latitude: this.item.latitude,
            	longitude: this.item.longitude,
            });
        },
	},

	watch: {
		address(value) {
			this.item.address = value;
		},

		latitude(value) {
			this.item.latitude = value;
		},

		longitude(value) {
			this.item.longitude = value;
		},
	},

	data() {
		return {
			item: {},
		}
	},

	props: {
		address: String,
		latitude: {},
		longitude: {},
		value: {},
		fetchUrl: String,
		small: {
			default: false,
			type: Boolean,
		}
	},

	model: {
		props: 'value',
		event: 'change',
	},

	components: {
		'action-button': ActionButton,
	},

	mixins: [ LoaderMixin, ResponseMixin ],
}
</script>