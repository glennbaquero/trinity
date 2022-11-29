<template>
	<div>
        <template v-if="call.status">
	        <div v-if="call.status.id === 0">
	        	<button class="btn btn-sm btn-success" @click="change('approve')">
	                Approve
	            </button>

	            <button class="btn btn-sm btn-danger" @click="change('reject')">
	                Reject
	            </button>
	        </div>
        </template>

		<form-request class="mt-3" :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<div class="row">
					<selector class="col-sm-6"
					v-model="call.medical_representative_id"
					name="medical_representative_id"
					label="Medical Representative"
					:items="medReps"
					item-value="id"
					item-text="fullname"
					placeholder="Please select a medical representative"
					></selector>

					<selector class="col-sm-6"
					v-model="call.doctor_id"
					name="doctor_id"
					label="Doctor"
					:items="doctors"
					item-value="id"
					item-text="fullname"
					placeholder="Please select a doctor"
					></selector>

				</div>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Clinic</label>
						<input v-model="call.clinic" name="clinic" type="text" class="form-control">
					</div>
				</div>

				<div class="row">
					<date-picker
					v-model="call.scheduled_date"
					class="form-group col-sm-6 col-md-6 mt-2"
					label="Scheduled Date"
					name="scheduled_date"
					placeholder="Choose a date"
					></date-picker>

					<!-- <div class="form-group col-sm-6">
						<label>Scheduled Time</label>
						<input v-model="call.scheduled_time" name="scheduled_time" type="time" class="form-control">
					</div> -->
				</div>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Agenda</label>
						<input v-model="call.agenda" name="agenda" type="text" class="form-control">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-6">
						<label>Arrived at</label>
						<input v-model="call.arrived_at" name="arrived_at" type="time" class="form-control">
					</div>

					<div class="form-group col-sm-6">
						<label>Left at</label>
						<input v-model="call.left_at" name="left_at" type="time" class="form-control">
					</div>
				</div>

				<div class="row">
					<text-editor
					v-model="call.notes"
					class="col-sm-12"
					label="Notes"
					name="notes"
					row="5"
					></text-editor>
				</div>

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                
	                <action-button
	                v-if="call.archiveUrl && call.restoreUrl"
	                color="btn-danger"
	                alt-color="btn-warning"
	                :action-url="call.archiveUrl"
	                :alt-action-url="call.restoreUrl"
	                label="Archive"
	                alt-label="Restore"
	                :show-alt="call.deleted_at"
	                confirm-dialog
	                title="Archive Item"
	                alt-title="Restore Item"
	                :message="'Are you sure you want to archive call #' + call.id + '?'"
	                :alt-message="'Are you sure you want to restore call #' + call.id + '?'"
	                :disabled="loading"
	                @load="load"
	                @success="fetch"
	                @error="fetch"
	                ></action-button>
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
import Datepicker from 'Components/datepickers/Datepicker.vue';
import Select from 'Components/inputs/Select.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.call = data.call ? data.call : this.call;
			this.medReps = data.medReps ? data.medReps : this.medReps;
			this.doctors = data.doctors ? data.doctors : this.doctors;
		},

		update() {
			this.fetch();
			EventBus.$emit('update-sample-item-count');
		},

		change(name) {
            
            let message = {
                title: 'Update call status',
                title: `Are you sure you want to update the status of call #${this.call.id}`,
            }

            let options = {
                loader: true,
                okText: 'Confirm',
                cancelText: 'Cancel',
                animation: 'fade',
                type: 'basic',
            };

            this.$dialog.confirm(message, options)
                .then(dialog => {
                    dialog.loading(true);
                    this.updateStatus(name, this.call.id, dialog);
                })
                .catch(() => {
                    dialog.loading(false);
                    dialog.close();
                });
        },

        updateStatus(name, id, dialog) {
            axios.post(`admin/calls/${name}`, { items: [id] })
                .then(response => {
                    dialog.loading(false);
                    dialog.close();
                    
                    if (response.data.redirectUrl) {
                        window.location.href = response.data.redirectUrl;
                    }
                    else {
                        this.parseSuccess(response.data.message);
                        this.fetch();
                    }
                })
                .catch(error => {
                    dialog.loading(false);
                    dialog.close();
                    this.parseError(error);
                });
        }
	},

	data() {
		return {
			call: {},
			medReps: [],
			doctors: []
		}
	},

	components: {
		'action-button': ActionButton,
		'text-editor': TextEditor,
		'date-picker': Datepicker,
		'selector': Select
	},

	mixins: [ CrudMixin ],
}
</script>