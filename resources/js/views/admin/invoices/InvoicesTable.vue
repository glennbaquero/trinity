<template>
	<div>
        <form-request :submit-url="exportUrl" @load="load" normal-submit-on-success method="POST" :action="exportUrl">
            <filter-box>
                <template v-slot:left>
                    <div class="row col-md-12">
                        <selector
                        class="col-md-12 ml-0"
                        v-if="doctors"
                        name="doctor"
                        :items="doctors"
                        item-value="id"
                        item-text="fullname"
                        @change="filter($event, 'doctor')"
                        placeholder="Filter by doctor"
                        ></selector>
                    </div>         

                    <action-button v-if="exportUrl" type="submit" :disabled="loading" class="btn-warning mr-3" icon="fa fa-download">Export</action-button>
                    <refresh-button @click="fetch"></refresh-button>

                    <date-range
                    @change="filter($event)"
                    ></date-range>

                    <selector
                    class="mt-2"
                    v-if="statuses"
                    name="status"
                    :items="statuses"
                    item-value="id"
                    item-text="name"
                    @change="filter($event, 'status')"
                    placeholder="Filter by status"
                    ></selector>

                   <!--   <selector
                    class="mt-2"
                    v-if="filterClaim"
                    name="claimed_at"
                    :items="filterClaim"
                    @change="filter($event, 'claimed_at')"
                    placeholder="Filter by claim"
                    ></selector> -->

                </template>
                <template v-slot:right>
                    <search-box
                    @search="filter($event, 'search')">
                    </search-box>
                </template>
            </filter-box>
        </form-request>

        <!-- DATATABLE -->
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['#', 'Invoice Number', 'User', 'Code', 'Grand Total', 'Status', 'Payment Method', 'Status Actions', 'Created Date']"
        :columns="['id', 'invoice_number', null, null, 'grand_total', null, null, null, 'created_at']"
        :filters="filters"
        
        :defaultsort="'id'"
        :defaultorder="false"

        :fetch-url="fetchUrl"
        :actionable="true"
        :selectable="false"

        @loaded="init"
        @loading="load">

            <template v-slot:body>
                <tr v-for="item in items">
                    <td>{{ item.id }}</td>
                    <td>{{ item.invoice_number }}</td>
                    <td>{{ item.user }}</td>
                    <td>{{ item.code }}</td>
                    <td>{{ item.grand_total }}</td>
                    <td>
                        <label
                        class="badge"
                        :style="{
                            'color': '#fff',
                            'background-color': item.status.bg_color
                        }"
                        >
                            {{ item.status.name }}
                        </label>
                    </td>
                    <td>
                        <label
                        class="badge"
                        style="color: #fff"
                        :style="{'background-color': item.payment_method_class}"
                        >
                            {{ item.payment_method }}
                        </label>
                        <a class="btn btn-primary btn-sm" target="_blank" :href="item.renderDepositSlipPath" v-if="item.deposit_slip"><i class="fa fa-eye"></i> View Deposit Slip</a>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-12">
                                <template v-if="statuses">
                                    <select v-model="item.status_id" 
                                        class="form-control"
                                        @change="changeStatus(item, item.status_id)"
                                        :disabled="item.completed ? true: false"
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
                        </div>

                    </td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">

                        <div class="mb-2">
                            <view-button :href="item.showUrl"></view-button>
                                
                            <template v-if="item.status_complete">
                                <action-button
                                small 
                                color="btn-danger"
                                alt-color="btn-warning"
                                :show-alt="item.deleted_at"
                                :action-url="item.archiveUrl"
                                :alt-action-url="item.restoreUrl"
                                icon="fas fa-trash"
                                alt-icon="fas fa-trash-restore-alt"
                                confirm-dialog
                                :disabled="loading"
                                title="Archive Specialization"
                                alt-title="Restore Specialization"
                                :message="'Are you sure you want to archive Specialization #' + item.id + '?'"
                                :alt-message="'Are you sure you want to restore Specialization #' + item.id + '?'"
                                @load="load"
                                @success="sync"
                                ></action-button>
                            </template>
                        </div>
                    </td>
                </tr>
            </template>

        </datatable>

        <loader 
        :loading="loading">
        </loader>
	</div>
</template>

<script type="text/javascript">
import ListMixin from 'Mixins/list.js';
import { EventBus }from '../../../EventBus.js';

import SearchBox from 'Components/datatables/SearchBox.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';
import ViewButton from 'Components/buttons/ViewButton.vue';
import Datepicker from 'Components/datepickers/Datepicker.vue';
import Select from 'Components/inputs/Select.vue';
import FormRequest from 'Components/forms/FormRequest.vue';
import DateRange from 'Components/datepickers/DateRange.vue';


import ResponseHandler from 'Mixins/response.js';

export default {
    methods: {
        update() {
            this.fetch();
        },

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
                            this.fetch();
                        }

                    }

                }).catch((error) => {
                    dialog.loading(false);
                    dialog.close();
                    this.parseError(error);                    
                });
        },

    },

    props: {
        statuses: {
            type: Array,
            default: null,
        },

        doctors: {
            type: Array,
            default: null,
        },

        submitUrl: String,
        exportUrl: String
    },

    mixins: [ 
        ListMixin,  
        ResponseHandler
    ],

    components: {
        'search-box': SearchBox,
        'view-button': ViewButton,
        'action-button': ActionButton,
        'selector': Select,
        'form-request': FormRequest,
        'date-range' : DateRange
    },
}
</script>