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
						required 
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
				</div>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Select Label background color</label>
						<swatches v-model="item.bg_color">
							<input slot="trigger" :value="item.bg_color" name="bg_color" class="form-control form__input__element" readonly>
						</swatches>
					</div>
				</div>
				
				<div class="row">
					<selector class="col-sm-12"
					v-model="item.action_type"
					name="action_type"
					label="Action type"
					:items="actionTypes"
					item-value="value"
					item-text="label"
					empty-text="None"
					placeholder="Please select an action type"
					></selector>
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
import Select from 'Components/inputs/Select.vue';

import Swatches from 'vue-swatches'

export default {
	props: {
		actionTypes: {
			type: Array,
			default: [],
		},
	},

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
		'selector': Select,
		Swatches,
	},

	mixins: [ CrudMixin ],
}
</script>