<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<div class="row">
					
					<!-- <selector class="col-sm-12"
					v-model="item.reward_category_id"
					name="reward_category_id"
					label="Category"
					:items="categories"
					item-value="id"
					item-text="name"
					empty-text="None"
					placeholder="Please select a category"
					></selector> -->

					<selector class="col-sm-12"
					v-model="item.sponsorship_id"
					name="sponsorships[]"
					label="Sponsorships"
					:items="sponsorships"
					item-value="id"
					item-text="name"
					multiple
					empty-text="None"
					></selector>

					<div class="form-group col-sm-6">
						<label>Name</label>
						<input 
						v-model="item.name" 
						name="name" 
						type="text" 
						class="form-control" 
						placeholder="Name" 
						required>
					</div>
					<div class="form-group col-sm-6">
						<label>Points</label>
						<input 
						v-model="item.points"
						name="points" 
						type="number" 
						class="form-control" 
						readonly>
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

				<image-picker
				:value="item.image_path"
				class="form-group col-sm-12 col-md-12 mt-2"
	            label="Image"
	            name="image_path"
	            placeholder="Choose a File"
				></image-picker>				


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
	                title="Archive Product"
	                alt-title="Restore Product"
	                :message="'Are you sure you want to archive Product #' + item.id + '?'"
	                :alt-message="'Are you sure you want to restore Product #' + item.id + '?'"
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
import Select from 'Components/inputs/Select.vue';
import ImagePicker from 'Components/inputs/ImagePicker.vue';
import TextEditor from 'Components/inputs/TextEditor.vue';

export default {
	
	watch: {
		item: {
			handler: function() {
				this.points = 0;
				if(this.item.sponsorship_id) {
					this.item.sponsorship_id.forEach((sponsorship) => {
						this.sponsorships.filter((sp) => {
							sp.id == sponsorship ?  this.points += sp.points : ''; 
						});
					});

					this.item.points = this.points;
				}
			}, deep: true
		},
	},

	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.item.sponsorship_id = data.item ? data.item.sponsorship_id: [],
			this.categories = data.categories ? data.categories : this.categories;
			this.sponsorships = data.sponsorships ? data.sponsorships : this.sponsorships;
		},
	},

	data() {
		return {
			items: [],
			categories: [],
			sponsorships: [],
			points: 0,
		}
	},

	components: {
		'action-button': ActionButton,
		'selector': Select,
		'image-picker': ImagePicker,
		'text-editor': TextEditor,
	},

	mixins: [ CrudMixin ],
}
</script>