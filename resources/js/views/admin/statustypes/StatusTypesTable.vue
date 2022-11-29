<template>
	<div>


		<loader
			:loading="loading"
		></loader>

		<draggable 
			v-model="types" 
			group="people" 
			@start="drag=true" 
			@end="drag=false"
			:disabled="!canReorder ? true: false"
		>
			<div v-for="(item, index) in types" :key="item.id" 
				class="form-group" 
			>

				<div class="row">
					<div class="col-md-6 text-center">
						<label 
							class="badge" 
							style="color: #fff;" 
							v-bind:style="{'background-color': item.bg_color }"
						>{{ item.name }} ({{ index }})</label>		
					</div>
					<div class="col-md-6 text-center">
                        <view-button :href="item.showUrl"></view-button>	

                        <action-button
                        small 
                        color="btn-danger"
                        alt-color="btn-warning"
                        :show-alt="item.deleted_at"
                        :action-url="item.archiveUrl"
                        :alt-action-url="item.restoreUrl"
                        icon="fas fa-trash"
                        alt-icon="fas fa-trash-restore-alt"
                        confirm-dialog
                        :disabled="loading"
                        title="Archive Status type"
                        alt-title="Restore Status type"
                        :message="'Are you sure you want to archive Status Type #' + item.id + '?'"
                        :alt-message="'Are you sure you want to restore Status Type #' + item.id + '?'"
                        @success="success"
                        ></action-button>

					</div>
				</div>

			</div>

			<template v-if="types.length === 0">
				<div class="form-group text-center">
					<label>No data found</label>
				</div>
			</template>
		
		</draggable>


		<hr />

		<template v-if="canReorder">
			<div class="row">
				<div class="col-md-12 text-right">
					<button 
					@click="saveOrder()"
					:disbled="loading"
					class="btn btn-primary">Save Order</button>
				</div>
			</div>
		</template>

	</div>
</template>	

<script>

import { EventBus }from '../../../EventBus.js';
import draggable from 'vuedraggable'
import ViewButton from 'Components/buttons/ViewButton.vue';
import Loader from 'Components/loaders/Loader.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';

import ResponseHandler from 'Mixins/response.js';

export default {

	props: {
		fetchUrl: String,
		submitUrl: String,
		canReorder: {
			type: Boolean,
			default: true,
		}
	},

	data: function() {
		return {
			types: [],

			loading: false,
		}
	},

	components: {
		draggable,
        'view-button': ViewButton,
        'loader': Loader,	
        'action-button': ActionButton,
    },
		
	mounted: function() {
		this.init();
		this.refreshList();
	},
	

	mixins: [
		ResponseHandler,
	],

	methods: {
		init: function() {

			this.setLoading(true);
			axios.post(this.fetchUrl)
				.then((response) => {
					this.setLoading(false);
					if(response.status === 200) {
						this.types = response.data.items;
					}

				}).catch((error) => {
					this.setLoading(false);					
					this.parseError(error);
				});
		},

		success: function() {
			this.init();
			EventBus.$emit('refresh');
		},

		refreshList: function() {
			EventBus.$on('refresh', () => {
				this.init();
			});			
		},

		saveOrder: function() {
			this.setLoading(true);
			axios.post(this.submitUrl, {'types': this.types})
				.then((response) => {
					this.setLoading(false);
					if(response.status === 200) {
						this.parseSuccess(response.data.message);
					}

				}).catch((error) => {
					this.setLoading(false);					
					this.parseError(error);
				});
		},


		setLoading: function(status) {
			this.loading = status;
		}
	},

}
</script>
