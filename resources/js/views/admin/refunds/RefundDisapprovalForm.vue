<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Disapproval</template>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>User</label>
						<p>{{ item.user_name }}</p>

						<label>Doctor</label>
						<p>{{ item.doctor_name }}</p>	

						<label>Consultation fee</label>
						<p>{{ item.consultation_fee }}</p>	

						<label>Scheduled Date</label>
						<p>{{ item.scheduled_date }}</p>	

						<label>Reason for refund request</label>
						<p>{{ item.reason }}</p>

						<label>Reason for refund disapproval</label>
						<input 
						v-model="item.disapproved_reason" 
						name="disapproved_reason" 
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