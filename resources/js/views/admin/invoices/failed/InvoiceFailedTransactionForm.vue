<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Failed Transaction Details</template>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Name</label>
						<input 
						v-model="status.name" 
						name="type" 
						type="text" 
						class="form-control"
						placeholder="Type" 
						required 
						>
					</div>
				</div>				

				<div class="row">
					<div class="col-md-12">
						<label>Reason</label>
						<textarea 
						v-model="item.reason"
						rows="5" 
						name="reason"
						class="form-control"
						required 
						placeholder="Reason" 
						></textarea>
					</div>
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
import { EventBus }from '../../../../EventBus.js';
import CrudMixin from 'Mixins/crud.js';

import ActionButton from 'Components/buttons/ActionButton.vue';
import TextEditor from 'Components/inputs/TextEditor.vue';

export default {
	props: {

		status: {
			type: Object,
			default: null,
		},

	},

	mounted: function() {
	},

	methods: {
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
	},

	mixins: [ CrudMixin ],
}
</script>