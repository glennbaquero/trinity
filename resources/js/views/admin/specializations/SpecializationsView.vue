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
						>
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

                    <image-picker
                    :value="item.image_path"
                    class="form-group col-sm-12 col-md-12 mt-2"
                    label="Select Icon"
                    name="image_path"
                    placeholder="Choose Icon"
                    ></image-picker>
				</div>	

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
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
import TextEditor from 'Components/inputs/TextEditor.vue';
import ImagePicker from 'Components/inputs/ImagePicker.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
		},

		update() {
			this.fetch();
		},
	},

	data() {
		return {
		}
	},

	components: {
		'action-button': ActionButton,
		'text-editor': TextEditor,
        'image-picker': ImagePicker,
	},

	mixins: [ CrudMixin ],
}
</script>