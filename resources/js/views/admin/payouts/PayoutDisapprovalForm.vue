<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Disapproval</template>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Doctor</label>
						<input 
						v-model="item.doctor_id" 
						name="doctor" 
						type="text" 
						class="form-control" 
						required
						disabled
						/>

						<label>Credits Requested</label>
						<input 
						v-model="item.value" 
						name="value" 
						type="text" 
						class="form-control" 
						required
						disabled
						/>

						<label>Reason</label>
						<input 
						v-model="item.reason" 
						name="reason" 
						type="text" 
						class="form-control" 
						placeholder="Reason" 
						required>
					</div>
				</div>	

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-danger">Disapprove</action-button>
				</template>
			</card>

			<loader :loading="loading"></loader>
			
		</form-request>
	</div>
</template>

<script type="text/javascript">
import { EventBus }from '../../../EventBus.js';
import CrudMixin from 'Mixins/crud.js';


export default {

	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
		},
	},

	data() {
		return {
			items: []
		}
	},

	components: {
	},

	mixins: [ CrudMixin ],
}
</script>