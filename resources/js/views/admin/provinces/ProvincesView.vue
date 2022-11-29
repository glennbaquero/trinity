<template>
	<div>
		<div class="row mb-3">
            <div class="col-sm-6">
                <p v-if="item.status_label">
                    <span>Status: </span><span class="badge" :class="item.status_class">{{ item.status_label }}</span>
                </p>
            </div>
            <div class="col-sm-6 text-sm-right">
                <provinces-buttons @load="load" @success="update" :item="item"></provinces-buttons>
            </div>
        </div>

		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<div class="row">
					<div class="form-group col-sm-6">
						<label>Name</label>
						<input v-model="item.name" name="name" type="text" class="form-control">
					</div>

					<selector class="col-sm-6"
					    v-model="item.region_id"
					    name="region_id"
					    label="Region"
					    :items="regions"
					    item-value="id"
					    item-text="name"
					    placeholder="Please select a region"
					></selector>
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
	                title="Archive Item"
	                alt-title="Restore Item"
	                :message="'Are you sure you want to archive Province #' + item.id + '?'"
	                :alt-message="'Are you sure you want to restore Province #' + item.id + '?'"
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
import ProvincesButtons from './ProvincesButtons.vue';
import Select from 'Components/inputs/Select.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.items = data.items ? data.items : this.items;
			this.regions = data.regions ? data.regions : this.regions;
			this.statuses = data.statuses ? data.statuses : this.statuses;
			this.images = data.images ? data.images : this.images;
		},

		update() {
			this.fetch();
			EventBus.$emit('update-sample-item-count');
		},
	},

	data() {
		return {
			items: [],
			statuses: [],
			regions: [],
			images: []
		}
	},

	components: {
		'action-button': ActionButton,
		'provinces-buttons': ProvincesButtons,
        'selector': Select,
	},

	mixins: [ CrudMixin ],
}
</script>