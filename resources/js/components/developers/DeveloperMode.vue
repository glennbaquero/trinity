<template>
	<div>
		<div @click="toggle" class="float-right d-none d-sm-block">
			<a href="javascript:void(0)" class="text-info"><b>Version</b> {{ label }}</a>
		</div>
		<form-modal 
		:submit-url="submitUrl"
		v-model="visibility"
		ok-text="Change User"
		ok-color="btn-primary" 
		cancel-text="Cancel" 
		cancel-color="btn-light">
			<template v-slot:title>
				Developer Mode
			</template>
				
			<p>Select a Guard</p>
			<div class="container mb-3">
				<div class="row">
					<div class="form-check col-sm-6">
						<input v-model="guard" class="form-check-input" type="radio" name="guard" id="admin" value="admin" checked>
						<label class="form-check-label" for="admin">Admin</label>
					</div>

					<div class="form-check col-sm-6">
						<input v-model="guard" class="form-check-input" type="radio" name="guard" id="web" value="web">
						<label class="form-check-label" for="web">Web</label>
					</div>
				</div>
			</div>

			<div v-if="guard == 'admin'" class="form-group">
				<selector
				label="Change Account (Admin)"
				:items="admins"
				item-text="email"
				item-value="id"
				placeholder="Select a user to change account"
				name="id"
				></selector>
			</div>

			<div v-if="guard == 'web'" class="form-group">
				<selector
				label="Change Account (User)"
				:items="users"
				item-text="email"
				item-value="id"
				placeholder="Select a user to change account"
				name="id"
				></selector>
			</div>
		</form-modal>
	</div>
</template>

<script type="text/javascript">
import FetchMixin from 'Mixins/fetch.js';

import ActionButton from 'Components/buttons/ActionButton.vue';
import Select from 'Components/inputs/Select.vue';
import FormModal from 'Components/forms/FormModal.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.users = data.users;
			this.admins = data.admins;
		},

		toggle() {
			if (this.clickNumber < 3) {
				this.clickNumber++;
				return;
			}

			this.visibility = !this.visibility;
		},
	},

	data() {
		return {
			users: [],
			admins: [],

			visibility: false,

			guard: 'admin',

			clickNumber: 0,
		}
	},

	props: {
		label: String,
		submitUrl: String,
	},

	components: {
		'action-button': ActionButton,
		'selector': Select,
        'form-modal': FormModal,
	},

	mixins: [ FetchMixin ],
}
</script>