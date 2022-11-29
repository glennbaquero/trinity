<template>
	<div>
		<form-request :submit-url="formUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<div class="row">

					<selector class="col-sm-12"
					v-model="item.type"
					name="type"
					label="Type"
					:items="types"
					item-value="value"
					item-text="name"
					empty-text="None"
					placeholder="Please select a type"
					></selector>

					<selector class="col-sm-6"
					v-model="item.month"
					name="month"
					label="Month"
					:items="months"
					item-value="value"
					item-text="name"
					empty-text="None"
					placeholder="Please select a month"
					></selector>

					<div class="form-group col-sm-6">
						<label>Year</label>
						<input 
						v-model="item.year" 
						name="year" 
						type="text" 
						class="form-control"
						placeholder="Target" 
						readonly 
						>
					</div>

					<div class="form-group col-sm-12">
						<label>Target</label>
						<input 
						v-model="item.value" 
						name="value" 
						type="number" 
						class="form-control"
						placeholder="Target" 
						>
					</div>

				</div>

				<template v-slot:footer>
					<button :disabled="!formUrl" :class="has_selected ? 'btn-warning' : 'btn-primary'" class="btn">{{ has_selected ? 'Update' : 'Create' }}</button>
				</template>
			</card>

			<loader :loading="loading"></loader>
			
		</form-request>

		<medrep-targets-table
			:fetch-url="fetchUrl"
			@itemSelect="itemSelect"
		></medrep-targets-table>

	</div>
</template>

<script type="text/javascript">
import { EventBus }from '../../../EventBus.js';
import CrudMixin from 'Mixins/crud.js';

import ActionButton from 'Components/buttons/ActionButton.vue';
import TextEditor from 'Components/inputs/TextEditor.vue';
import Select from 'Components/inputs/Select.vue';

import MedRepTargetsTable from './MedRepTargetsTable';

export default {


	props: {
		months: {
			type: Array,
			default: [],
		},

		types: {
			type: Array,
			default: [],
		},

		year: {
			type: String,
			default: null,
		},

		fetchUrl: {
			type: String,
			default: null,
		}

	},


	components: {
		'action-button': ActionButton,
		'text-editor': TextEditor,
		'selector': Select,
		'medrep-targets-table': MedRepTargetsTable,	
	},

	computed: {
		formUrl() {
			return this.has_selected ? this.item.updateUrl : this.submitUrl;
		},
	},

	watch: {
		item(value) {
			if (value.updateUrl) {
				this.has_selected = true;
			} else {
				this.has_selected = false;
				this.item.year = this.year;
			}
		}
	},

	data() {
		return {
			has_selected: false,		
		}
	},


	mounted: function() {
		this.item.year = this.year;
	},

	mixins: [ CrudMixin ],

	methods: {

		fetch() {
			this.item = {};
			EventBus.$emit('success');
		},

		itemSelect: function(e) {
			this.item = e;
		},
	},

}
</script>