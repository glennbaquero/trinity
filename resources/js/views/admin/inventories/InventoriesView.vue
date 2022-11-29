<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<div class="row">
					<div class="form-group col-sm-6">
						<label>Product Name: <strong>{{ product.name }}</strong></label>
					</div>
					<div class="form-group col-sm-6">
						<label>Product SKU: <strong>{{ product.sku }}</strong></label>
					</div>
					<div class="form-group col-sm-6">
						<label>Remaining Stocks: </label> <label class="badge badge-danger">{{ item.remaining_stocks }}</label>
					</div>
				</div>


				<div class="row">
					<div class="form-group col-sm-12">
						<label>Stocks to add </label> <span class="text-muted">(stocks to be add on remaining stocks)</span>
						<input 
						v-model="stocks"
						name="stocks"
						type="text" 
						class="form-control"
						placeholder="Stocks" 
						required 
						>
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
import { EventBus }from '../../../EventBus.js';
import CrudMixin from 'Mixins/crud.js';

import ActionButton from 'Components/buttons/ActionButton.vue';
import TextEditor from 'Components/inputs/TextEditor.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.product = data.product ? data.product : this.product;
			this.stocks = null;			
		},

		update() {
			this.fetch();
		},
	},

	data() {
		return {
			product: {},
			stocks: null,
		}
	},

	components: {
		'action-button': ActionButton,
		'text-editor': TextEditor,
	},

	mixins: [ CrudMixin ],
}
</script>