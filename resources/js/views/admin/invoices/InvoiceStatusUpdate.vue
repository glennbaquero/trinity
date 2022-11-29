<template>
	<div class="col-md-12">
        <template v-if="statuses">
            <select v-model="invoice.status_id" 
                class="form-control"
                @change="changeStatus(invoice, invoice.status_id)"
                :disabled="invoice.completed ? true: false"
            >
                <template v-for="status in statuses">
                    <option :value="status.id">{{ status.name }}</option>
                </template>
            </select>
        </template>
        <template v-else>
            <label>No action required</label>   
        </template>
    </div>
</template>
<script>

import ListMixin from 'Mixins/list.js';
import ResponseHandler from 'Mixins/response.js';
import ActionButton from 'Components/buttons/ActionButton.vue';

export default {

	props: {

		submitUrl: {
			type: String,
			default: null
		},

		statuses: {
			type: Array,
			default: [],
		},

		invoice: {
			type: Object,
			default: {}
		},
	},

    components: {
        ActionButton
    },

    mixins: [ 
        ListMixin,  
        ResponseHandler
    ],


    methods: {

        /**
         * Confirmation for changing status of specified invoice
         * 
         * @param  object invoice
         * @param  int status
         */
        changeStatus: function(invoice, status) {
            
            let message = {
                title: 'Update invoice status',
                title: 'Are you sure you want to update the status of invoice #' + invoice.invoice_number +'?',
            }

            let options = {
                loader: true,
                okText: 'Confirm',
                cancelText: 'Cancel',
                animation: 'fade',
                type: 'basic',
            };

            let post = {
                'id': invoice.id,
                'status': status
            };

            this.$dialog.confirm(message, options)
            .then((dialog) => {
                dialog.loading(true);
                this.submit(post, dialog);
            }).catch(() => {
                dialog.loading(false);
                dialog.close();
            });
        },

        /**
         * Submitting of post request
         * 
         * @param  object post
         * @param dialog
         */
        submit: function(post, dialog) {
            axios.post(`${this.submitUrl}/${post.id}`, post)
                .then((response) => {
                    dialog.loading(false);
                    dialog.close();
                    if(response.status === 200) {

                        if(response.data.redirectUrl) {
                            window.location.href = response.data.redirectUrl;
                        } else {
                            this.parseSuccess(response.data.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000)
                        }

                    }

                }).catch((error) => {
                    dialog.loading(false);
                    dialog.close();
                    this.parseError(error);                    
                });
        },

    },
}

</script>