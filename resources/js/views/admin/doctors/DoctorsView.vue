<template>
	<div>
		<div class="row mb-3">
            <div class="col-sm-6">
                <p v-if="item.status_label">
                    <span>Status: </span><span class="badge" :class="item.status_class">{{ item.status_label }}</span>
                </p>
            </div>
            <div class="col-sm-6 text-sm-right">
                <doctors-buttons @load="load" @success="update" :item="doctor"></doctors-buttons>
            </div>
        </div>

        <template v-if="doctor"> 
	        <div v-if="doctor.status === 0">
	        	<button class="btn btn-sm btn-success" @click="change('approve')">
	                Approve
	            </button>

	            <button class="btn btn-sm btn-danger" @click="change('reject')">
	                Reject
	            </button>
	        </div>
	        <div v-else>
				<button class="btn btn-sm btn-success" @click="sendPasswordReset()"
				:disabled="resetCounter === 0 ? false: true">
	            	Send Password Reset
	            </button>
	            <template v-if="resetCounter != 0">
		            <label>Send reset email again in: </label>
		            <label class="badge badge-danger">{{ resetCounter }}</label>
	            </template>
	        </div>
        </template>


		<form-request class="mt-3" :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<template v-if="doctor">
					<div class="row">
						<div class="form-group col-md-12">
							<a :href="doctor.qr_path" target="_blank">
								<img :src="doctor.qr_path" width="100px" class="rounded img-fluid img-thumbnail">
							</a>
							<div class="col-md-12">
								<a :href="doctor.downloadQRUrl">Download</a>
							</div>
						</div>

						<div class="form-group col-sm-4">
							<label>First Name</label>
							<input v-model="doctor.first_name" name="first_name" type="text" class="form-control">
						</div>
						<div class="form-group col-sm-4">
							<label>Last Name</label>
							<input v-model="doctor.last_name" name="last_name" type="text" class="form-control">
						</div>
						<div class="form-group col-sm-4">
							<label>Consultation Fee</label>
							<input v-model="doctor.consultation_fee" name="consultation_fee" type="number" min="1" step="1.00" class="form-control">
						</div>
					</div>
				</template>

				<div class="row">
					<selector class="col-sm-4"
					v-model="doctor.specialization_id"
					name="specialization_id"
					label="Specialization"
					:items="specializations"
					item-value="id"
					item-text="name"
					placeholder="Please select a specialization"
					></selector>

					<selector class="col-sm-4"
					v-model="doctor.medical_representative_id"
					name="medical_representative_id"
					label="Medical Representative"
					:items="medReps"
					item-value="id"
					item-text="fullname"
					placeholder="Please select a medical representative"
					></selector>

					<selector class="col-sm-4"
					v-model="doctor.class"
					name="class"
					label="Class"
					:items="[{ id: 1, type: 'A' }, { id: 2, type: 'B'}]"
					item-value="id"
					item-text="type"
					placeholder="Please select a classs"
					></selector>
				</div>

				<div class="row">
					<div class="form-group col-sm-6">
						<label>Clinic Address</label>
						<input v-model="doctor.clinic_address" name="clinic_address" type="text" class="form-control">
					</div>

					<div class="form-group col-sm-6">
						<label>Clinic Hours</label>
						<input v-model="doctor.clinic_hours" name="clinic_hours" type="text" class="form-control">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Mobile Number</label>
						<input v-model="doctor.mobile_number" name="mobile_number" type="text" class="form-control">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Email</label>
						<input v-model="doctor.email" name="email" type="text" class="form-control" :disabled="!Number(createMode)">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Alma Mater</label>
						<input v-model="doctor.alma_mater" name="alma_mater" type="text" class="form-control">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>Place of practice</label>
						<input v-model="doctor.place_of_practice" name="place_of_practice" type="text" class="form-control">
					</div>
				</div>

				<div class="row">
					<div class="form-group col-sm-12">
						<label>License Number</label>
						<input v-model="doctor.license_number" name="license_number" type="text" class="form-control">
					</div>
				</div>								

				<div class="row">
					<text-editor
					v-model="item.brand_adaption_notes"
					class="col-sm-12"
					label="Brand Adaption Notes"
					name="brand_adaption_notes"
					row="5"
					></text-editor>
				</div>

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                
	                <action-button
	                v-if="doctor.archiveUrl && doctor.restoreUrl"
	                color="btn-danger"
	                alt-color="btn-warning"
	                :action-url="doctor.archiveUrl"
	                :alt-action-url="doctor.restoreUrl"
	                label="Archive"
	                alt-label="Restore"
	                :show-alt="doctor.deleted_at"
	                confirm-dialog
	                title="Archive Item"
	                alt-title="Restore Item"
	                :message="'Are you sure you want to archive Doctor #' + doctor.id + '?'"
	                :alt-message="'Are you sure you want to restore Doctor #' + doctor.id + '?'"
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
import ResponseHandler from 'Mixins/response.js';

import ActionButton from 'Components/buttons/ActionButton.vue';
import TextEditor from 'Components/inputs/TextEditor.vue';
import Select from 'Components/inputs/Select.vue';
import DoctorsButtons from './DoctorsButtons.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.doctor = data.doctor ? data.doctor : this.doctor;
			this.doctors = data.doctors ? data.doctors : this.doctors;
			this.medReps = data.medReps ? data.medReps : this.medReps;
			this.specializations = data.specializations ? data.specializations : this.specializations;
		},

		update() {
			this.fetch();
			EventBus.$emit('update-sample-item-count');
		},

		change(name) {
            
            let message = {
                title: 'Update doctor status',
                title: `Are you sure you want to update the status of doctor #${this.doctor.id}`,
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
                    this.updateStatus(name, this.doctor.id, dialog);
                })
                .catch(() => {
                    dialog.loading(false);
                    dialog.close();
                });
        },

        updateStatus(name, id, dialog) {
		this.loading = true;        	
            axios.post(`admin/doctors/${id}/${name}`)
                .then(response => {
					this.loading = false;
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
                	this.loading = false;
                    dialog.loading(false);
                    dialog.close();
                    this.parseError(error);
                });
        },

        sendPasswordReset: function () {
        	this.loading = true;
        	axios.post(this.doctor.resetPasswordUrl)
        		.then((response) => {
        			this.loading = false;
        			if(response.status === 200) {
	                    this.parseSuccess(response.data.message);
	                    this.runResetCounter();
        			}
        		})
        		.catch((error) => {
        			this.loading = false;        			
                    this.parseError(error);
        		});
        },

        runResetCounter: function() {
        	this.resetCounter = 30;
        	setInterval(() => {
        		if(this.resetCounter != 0) {
	        		this.resetCounter--;        			
        		}
        	}, 1000);
        },

        load: function(e) {
        	this.loading = e;
        },
	},

	data() {
		return {
			doctor: {},
			doctors: [],
			medReps: [],
			specializations: [],

			resetCounter: 0,
		}
	},

	props: {
		createMode: {
			default: false
		}
	},

	components: {
		'action-button': ActionButton,
		'text-editor': TextEditor,
		'selector': Select,
		'doctors-buttons': DoctorsButtons
	},

	mixins: [ CrudMixin, ResponseHandler ],
}
</script>