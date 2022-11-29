<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<div class="row">

					<div class="form-group col-sm-4">					
						<div class="custom-control custom-switch">
							<input
							v-model="item.best_seller"
							name="best_seller" :checked="item.best_seller" type="checkbox" class="custom-control-input" id="best_seller">
							<label class="custom-control-label" for="best_seller">Best Seller</label>
						</div>
					</div>
					
					<div class="form-group col-sm-4">					
						<div class="custom-control custom-switch">
							<input
							v-model="item.new_arrival"
							name="new_arrival" :checked="item.new_arrival" type="checkbox" class="custom-control-input" id="new_arrival">
							<label class="custom-control-label" for="new_arrival">New Arrival</label>
						</div>
					</div>

					<div class="form-group col-sm-4">					
						<div class="custom-control custom-switch">
							<input
							v-model="item.promo"
							name="promo" :checked="item.promo" type="checkbox" class="custom-control-input" id="promo">
							<label class="custom-control-label" for="promo">Promo</label>
						</div>
					</div>

					<selector class="col-sm-6"
					v-model="item.type"
					name="type"
					label="Product Type"
					:items="types"
					item-value="value"
					item-text="name"
					empty-text="None"
					placeholder="Please select a product type"
					></selector>

					<selector class="col-sm-6"
					v-model="item.specializations"
					name="specialization_ids[]"
					label="Specializations"
					:items="specializations"
					item-value="id"
					item-text="name"
					empty-text="None"
					multiple
					placeholder="Please select a specializations"
					></selector>

					<div class="form-group col-sm-6">
						<label>Generic Name</label>
						<input 
						v-model="item.generic_name" 
						name="generic_name" 
						type="text" 
						class="form-control" 
						placeholder="Generic Name" 
						required>
					</div>
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
						<label>Brand</label>
						<input 
						v-model="item.brand_name" 
						name="brand_name" 
						type="text" 
						class="form-control" 
						placeholder="Brand" 
						required>
					</div>

					<div class="form-group col-sm-6">
						<label>SKU</label>
						<input 
						v-model="item.sku" 
						name="sku" 
						type="text" 
						class="form-control" 
						placeholder="SKU" 
						required>
					</div>					

					<div class="form-group col-sm-6">
						<label>Product Size</label>
						<input 
						v-model="item.product_size" 
						name="product_size" 
						type="text" 
						class="form-control" 
						placeholder="Product Size" 
						required>
					</div>

					<div class="form-group col-sm-6">
						<label>Price</label>
						<input 
						v-model="item.price" 
						name="price" 
						type="number" 
						class="form-control" 
						placeholder="Price" 
						required>
					</div>

					<template v-if="!item.created_at">
						<div class="form-group col-sm-6">
							<label>Initial Stocks</label>
							<input 
							v-model="item.initial_stocks" 
							name="initial_stocks" 
							type="number" 
							class="form-control" 
							placeholder="Initial Stocks" 
							required>
						</div>
					</template>					

					<div class="form-group col-sm-4">
						<label>Client Points</label>
						<input 
						v-model="item.client_points" 
						name="client_points" 
						type="number" 
						class="form-control" 
						placeholder="Client Points">
					</div>

					<template v-if="item.type == 1">
						<div class="form-group col-sm-4">
							<label>Doctor Points</label>
							<input 
							v-model="item.doctor_points" 
							name="doctor_points" 
							type="number" 
							class="form-control" 
							placeholder="Doctor Points" 
							required>
						</div>


						<div class="form-group col-sm-4">
							<label>Secretary Points</label>
							<input 
							v-model="item.secretary_points" 
							name="secretary_points" 
							type="number" 
							class="form-control" 
							placeholder="Secretary Points" 
							required>
						</div>
					</template>
				</div>

				<div class="row">
					<text-editor
					v-model="item.ingredients"
					class="col-sm-12"
					label="Ingredients"
					name="ingredients"
					row="5"
					></text-editor>
				</div>

				<div class="row">
					<text-editor
					v-model="item.nutritional_facts"
					class="col-sm-12"
					label="Nutritional Facts"
					name="nutritional_facts"
					row="5"
					></text-editor>
				</div>


				<div class="row">
					<text-editor
					v-model="item.directions"
					class="col-sm-12"
					label="Directions"
					name="directions"
					row="5"
					></text-editor>
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

				<div class="form-group">
					<div class="custom-control custom-switch">
						<input
						v-model="item.is_free_product"
						name="is_free_product" :checked="item.is_free_product" type="checkbox" class="custom-control-input" id="is_free_product">
						<label class="custom-control-label" for="is_free_product">Free Product?</label>
					</div>

					<div class="custom-control custom-switch">
						<input
						v-model="item.prescriptionable"
						name="prescriptionable" :checked="item.prescriptionable" type="checkbox" class="custom-control-input" id="prescriptionable">
						<label class="custom-control-label" for="prescriptionable">Has prescription?</label>
					</div>

					<div class="custom-control custom-switch">
						<input
						v-model="item.is_other_brand"
						name="is_other_brand" :checked="item.is_other_brand" type="checkbox" class="custom-control-input" id="is_other_brand">
						<label class="custom-control-label" for="is_other_brand">Other brands?</label>
					</div>

				</div>

				<image-picker
				:value="item.image_path"
				class="form-group col-sm-12 col-md-12 mt-2"
	            label="Image"
	            name="image_path"
	            placeholder="Choose a File"
	            :required="item.image_path ? false: true"
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

	props: {
		variant: {
			type: String,
			default: '0',
		},
	},

	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.specializations = data.specializations ? data.specializations: this.specializations;
			this.parents = data.parents ? data.parents: this.parents;			
		},

		update() {
			this.fetch();
		},
		
		copy() {
			this.item = this.parent;
		}
	},

	data() {
		return {
			items: [],
			specializations: [],
			parents: [],
			types: [
				{value: 1, name: 'Non-pharmaceutical product'},
				{value: 2, name: 'Pharmaceutical product'},				
			],
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