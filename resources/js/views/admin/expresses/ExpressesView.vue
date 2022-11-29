<template>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
		        <template v-if="!noTitle" v-slot:header>
		        	Basic Information
		        </template>

				<div class="row">
					<div class="form-group col-sm-6 col-md-6">
						<label>Fee</label>
						<input v-model="item.fee" step="0.01" name="fee" type="number" class="form-control input-sm">
					</div>
				
					<selector class="col col-sm-6"
					v-model="item.city_id"
					name="city_id"
					label="City"
					:items="cities"
					item-value="id"
					item-text="name"
					empty-text="None"
					placeholder="Please select a brand"
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
	                :message="'Are you sure you want to archive shipping fee #' + item.id + '?'"
	                :alt-message="'Are you sure you want to restore shipping fee #' + item.id + '?'"
	                :disabled="loading"
	                @load="load"
	                @success="fetch"
	                ></action-button>
		        </template>
			</card>

			<loader :loading="loading"></loader>

		</form-request>
</template>

<script type="text/javascript">
import CrudMixin from 'Mixins/crud.js';

import ActionButton from 'Components/buttons/ActionButton.vue';
import Select from 'Components/inputs/Select.vue';
import TextEditor from 'Components/inputs/TextEditor.vue';
import ImagePicker from 'Components/inputs/ImagePicker.vue';
import Datepicker from 'Components/datepickers/Datepicker.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
		}
	},

	props: {
		pageItem: {},
		noTitle: {
			default: false,
		},

		cities: Array,
	},

	components: {
		'action-button': ActionButton,
		'selector': Select,
		'text-editor': TextEditor,
        'image-picker': ImagePicker,
		'date-picker': Datepicker,
	},

	mixins: [ CrudMixin ],
}
</script>
