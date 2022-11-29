<template>
	<div>
		<div class="row mb-3">
            <div class="col-sm-6">
                <p v-if="item.status_label">
                    <span>Status: </span><span class="badge" :class="item.status_class">{{ item.status_label }}</span>
                </p>
            </div>
            <div class="col-sm-6 text-sm-right">
                <sample-item-buttons @load="load" @success="update" :item="item"></sample-item-buttons>
            </div>
        </div>

		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Name</label>
						<input v-model="item.name" name="name" type="text" class="form-control">
					</div>
				</div>

				<div class="row">
					<text-editor
					v-model="item.description"
					class="col-sm-12"
					label="Description"
					name="description"
					row="5"
					></text-editor>
				</div>
				
				<div class="row">

					<selector class="col-sm-12"
					v-model="item.status"
					name="status"
					label="Status"
					:items="statuses"
					placeholder="Please select a status"
					></selector>

					<div class="form-group col-sm-12" v-if="item.reason">
						<label>Reason</label>
						<textarea name="reason" placeholder="Reason" class="form-control" readonly>{{ item.reason }}</textarea>
					</div>
					
					<selector class="col-sm-6"
					v-model="item.sample_item_id"
					name="sample_item_id"
					label="Sample Item"
					:items="items"
					item-value="id"
					item-text="name"
					empty-text="None"
					placeholder="Please select an item"
					></selector>

					<selector class="col-sm-6"
					v-model="item.data"
					name="data[]"
					label="Data"
					:items="items"
					item-value="id"
					item-text="name"
					multiple
					placeholder="Please select an item"
					></selector>

					<image-picker
					:value="item.path"
					class="form-group col-sm-12 col-md-12 mt-2"
	                label="Image"
	                name="image_path"
	                placeholder="Choose a File"
					></image-picker>

					<image-picker
					:value="images"
					class="form-group col-sm-12 col-md-12 mt-2"
	                label="Images"
	                name="images[]"
	                placeholder="Choose Files"
	                multiple
	                :remove-url="item.removeImageUrl"
	                @remove="fetch"
	                max="3"
	                min="1"
					></image-picker>

					<date-picker
					v-model="item.date"
					class="form-group col-sm-12 col-md-6 mt-2"
					label="Date"
					name="date"
					placeholder="Choose a date"
					></date-picker>

					<date-picker
					v-model="item.dates"
					class="form-group col-sm-12 col-md-6 mt-2"
					label="Dates"
					name="dates[]"
					mode="multiple"
					placeholder="Choose dates"
					></date-picker>
					
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
	                :message="'Are you sure you want to archive Sample Item #' + item.id + '?'"
	                :alt-message="'Are you sure you want to restore Sample Item #' + item.id + '?'"
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
import { EventBus }from '../../EventBus.js';
import CrudMixin from '../../mixins/crud.js';

import ActionButton from '../../components/buttons/ActionButton.vue';
import Select from '../../components/inputs/Select.vue';
import ImagePicker from '../../components/inputs/ImagePicker.vue';
import TextEditor from '../../components/inputs/TextEditor.vue';
import Datepicker from '../../components/datepickers/Datepicker.vue';
import SampleItemButtons from './SampleItemButtons.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.items = data.items ? data.items : this.items;
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
			images: [],
		}
	},

	components: {
		'action-button': ActionButton,
		'selector': Select,
		'image-picker': ImagePicker,
		'text-editor': TextEditor,
		'date-picker': Datepicker,
		'sample-item-buttons': SampleItemButtons,
	},

	mixins: [ CrudMixin ],
}
</script>