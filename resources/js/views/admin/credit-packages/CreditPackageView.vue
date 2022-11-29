<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Name</label>
						<input 
						v-model="item.name" 
						name="name" 
						type="text" 
						class="form-control" 
						placeholder="Name" 
						required>
					</div>
					<div class="form-group col-sm-12">
						<label>Credits</label>
						<input 
						v-model="item.credits"
						name="credits" 
						type="number" 
						class="form-control" 
						placeholder="Credits" 
						required>
					</div>
					<div class="form-group col-sm-12">
						<label>Price</label>
						<input 
						v-model="item.price"
						name="price" 
						type="number" 
						class="form-control" 
						placeholder="Price" 
						required>
					</div>

					<div class="form-group col-sm-12">					
						<div class="custom-control custom-switch">
							<input
							v-model="item.status"
							name="status" :checked="item.status" type="checkbox" class="custom-control-input" id="status">
							<label class="custom-control-label" for="status">Enable</label>
						</div>
					</div>

				</div>	

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                
	                <action-button
	                v-if="item.archiveUrl && item.restoreUrl"
	                color="btn-danger"
	                alt-color="btn-warning"
	                :action-url="item.archiveUrl"
	                :alt-action-url="item.restoreUrl"
	                label="Archive"
	                alt-label="Restore"
	                :show-alt="item.deleted_at"
	                confirm-dialog
	                title="Archive Credit Package"
	                alt-title="Restore Credit Package"
	                :message="'Are you sure you want to archive Credit Package #' + item.id + '?'"
	                :alt-message="'Are you sure you want to restore Credit Package #' + item.id + '?'"
	                :disabled="loading"
	                @load="load"
	                @success="fetch"
	                @error="fetch"
	                ></action-button>
				</template>
			</card>

			<loader :loading="loading"></loader>
			
		</form-request>
	</div>
</template>

<script type="text/javascript">
import { EventBus }from '../../../EventBus.js';
import CrudMixin from 'Mixins/crud.js';

import ActionButton from 'Components/buttons/ActionButton.vue';

export default {

	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
		},
	},

	data() {
		return {
			items: []
		}
	},

	components: {
		'action-button': ActionButton
	},

	mixins: [ CrudMixin ],
}
</script>