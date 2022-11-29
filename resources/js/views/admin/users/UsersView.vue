<template>
    <div>
        <div>
            <!--<button v-if="item.status === null || item.status === 0" class="btn btn-sm btn-success" @click="change('approve')">
                Approve
            </button>

            <button v-if="item.status === null" class="btn btn-sm btn-danger" @click="change('deny')">
                Reject
            </button> -->
            <template v-if="item.id">
                <span :class="`badge badge-${item.email_verified_at === null ? 'secondary' : (item.email_verified_at ? 'success' : 'danger')}`">
                     {{ item.email_verified_at ? 'Verified' : 'Not verified' }}
                </span>
            </template>
        </div>

        <form-request class="mt-3" :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
            <card>
                <template v-slot:header>Basic Information</template>               
        
                <div class="row">

                    <selector
                    class="col-sm-12 col-md-12"
                    :items="types"
                    v-model="item.type"
                    name="type"
                    label="User type"
                    item-value="value"
                    item-text="label"
                    required
                    empty-text="Please select a type"
                    ></selector>                     

                    <div class="form-group col-sm-12 col-md-6">
                        <label>First Name</label>
                        <input v-model="item.first_name" name="first_name" type="text" class="form-control input-sm">
                    </div>

                    <div class="form-group col-sm-12 col-md-6">
                        <label>Last Name</label>
                        <input v-model="item.last_name" name="last_name" type="text" class="form-control input-sm">
                    </div>

                    <div class="form-group col-sm-12 col-md-6">
                        <label>Email Address</label>
                        <input v-model="item.email" name="email" type="text" class="form-control input-sm">
                    </div>

                    <template v-if="!item.id">
                        <div class="form-group col-sm-12 col-md-6">
                            <label>Password</label>
                            <div class="input-group">
                                <input v-model="password" name="password" type="text" class="form-control input-sm">
                                <div 
                                @click="generatePassword"
                                class="input-group-append">
                                    <span class="input-group-text btn-primary text-white" id="basic-addon2">Generate Password</span>
                                </div>                                
                            </div>
                        </div>
                    </template>

                    <selector
                    class="col-sm-12 col-md-12"
                    :items="doctors"
                    v-model="item.doctor_ids"
                    name="doctors[]"
                    label="Doctors"
                    multiple
                    item-value="id"
                    item-text="fullname"
                    empty-text="Please select doctors"                    
                    ></selector>

                    <image-picker
                    class="form-group col-sm-12 col-md-12 mt-2"
                    :value="item.renderImage"
                    label="Avatar"
                    name="image_path"
                    placeholder="Choose a File"
                    ></image-picker>

                    <div v-if="item.verificationImage" class="form-group col-sm-12 col-md-4">
                        <a class="btn btn-info" :href="item.verificationImage" target="_blank">View verification image</a>
                    </div>
                </div>
                
                <template v-slot:footer>
                    <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                    
                    <action-button
                    v-if="item.archiveUrl && item.restoreUrl"
                    color="btn-danger"
                    alt-color="btn-warning"
                    :action-url="item.archiveUrl"
                    :alt-action-url="item.restoreUrl"
                    label="Archive"
                    alt-label="Restore"
                    :show-alt="item.deleted_at"
                    confirm-dialog
                    title="Archive Item"
                    alt-title="Restore Item"
                    :message="'Are you sure you want to archive User #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore User #' + item.id + '?'"
                    :disabled="loading"
                    @load="load"
                    @success="fetch"
                    ></action-button>

                </template>
            </card>

            <loader :loading="loading"></loader>

        </form-request>
    </div>
</template>

<script type="text/javascript">
import CrudMixin from 'Mixins/crud.js';
import ResponseHandler from 'Mixins/response.js';

import ActionButton from 'Components/buttons/ActionButton.vue';
import Select from 'Components/inputs/Select.vue';
import ImagePicker from 'Components/inputs/ImagePicker.vue'

export default {

    methods: {
        fetchSuccess(data) {
            this.item = data.item ? data.item : this.item;
            this.doctors = data.doctors ? data.doctors : this.doctors;
            this.types = data.types ? data.types : [];
        },
        change(name) {
            
            let message = {
                title: 'Update user status',
                title: `Are you sure you want to update the status of user #${this.item.id}`,
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
                    this.updateStatus(name, this.item.id, dialog);
                })
                .catch(() => {
                    dialog.loading(false);
                    dialog.close();
                });
        },

        updateStatus(name, id, dialog) {
            axios.post(`admin/users/${id}/${name}`)
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
        },

        generatePassword() {
            this.password = Math.random().toString(36).substring(2, 5) + Math.random().toString(36).substring(2, 5);
        },
    },

    data() {
        return {
            doctors: [],
            types: [],

            password: null,
        }
    },

    components: {
        'action-button': ActionButton,
        'selector': Select,
        'image-picker': ImagePicker,
    },

    mixins: [ CrudMixin, ResponseHandler ],
}
</script>