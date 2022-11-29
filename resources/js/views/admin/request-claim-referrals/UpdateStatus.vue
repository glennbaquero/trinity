<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<template v-if="action == 'reject'">
					<div class="row">
						<div class="form-group col-sm-12">
							<label>Reason</label>
							<input v-model="item.reason" name="reason" type="text" class="form-control">
						</div>
					</div>
				</template>

				<template v-if="action == 'approve'">
					<div class="row">
						<selector class="col-sm-12"
						ref="type"
						v-model="item.voucher_id"
						name="voucher_id"
						label="Voucher"
						:items="vouchers"
		            	item-value="id"
		                item-text="name"
						placeholder="Please select voucher"
						></selector>	
					</div>
				</template>			

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</template>
			</card>

			<loader :loading="loading"></loader>
			
		</form-request>
	</div>
</template>

<script type="text/javascript">

import CrudMixin from '../../../mixins/crud.js';

import ActionButton from '../../../components/buttons/ActionButton.vue';
import Select from '../../../components/inputs/Select.vue';

export default {

	props: {
		action: {
			type: String,
			default: null
		},
	},

	components: {
		'action-button': ActionButton,
		'selector': Select,
	},

	data() {
		return {
			vouchers: [],
		}
	},

	mixins: [ CrudMixin ],

	methods: {
		
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.vouchers = data.vouchers.length ?  data.vouchers : this.vouchers;
		},

	}		
}
</script>