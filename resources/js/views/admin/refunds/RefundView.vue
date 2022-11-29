<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Consultation Details</template>
				<div class="form-group col-sm-12">
					<label>User</label>
					<p>{{ item.user_name }}</p>

					<label>Doctor</label>
					<p>{{ item.doctor_name }}</p>	

					<label>Consultation fee</label>
					<p>{{ item.consultation_fee }}</p>	

					<label>Scheduled Date</label>
					<p>{{ item.scheduled_date }}</p>	
				</div>
			</card>
			<card>
				<template v-slot:header>Conversation Details</template>
				
				<div class="row">
					<template v-if="!chats.length">
						<div class="col-md-12 text-center">
							<p>No conversation found.</p>
						</div>
					</template>
					<div class="form-group col-sm-12" v-for="chat in chats">
						<h5>{{ chat.sender_name }}</h5>
						<p class="text-muted">{{ chat.readable_date }}</p>
						<p v-html="chat.message"></p>
						<hr>
					</div>
				</div>	

				<template v-slot:footer>
					    <template v-if="!item.canArchive">
                            <action-button
                            icon="fas fa-check"
                            color="btn-primary"
                            :action-url="item.approveUrl"
                            confirm-dialog
                            :disabled="loading"
                            title="Approve Refund Request"
                            :message="'Are you sure you want to approve refund request #' + item.id + '?'"
                            @load="load"
                            @success="sync"
                            >Approve</action-button>
                            
                            <a
                            class="btn btn-danger"
                            :href="item.disapprovalFormUrl"
                            >
                            <i class="fas fa-times" />
                            Disapprove
                            </a>
                        </template>
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

export default {

	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.chats = data.chats ? data.chats : this.chats;
		},
	},

	data() {
		return {
			items: [],
			chats: [],
		}
	},

	components: {
		'action-button': ActionButton
	},

	mixins: [ CrudMixin ],
}
</script>