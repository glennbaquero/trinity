<template>
	<div>
        <div class="row">
             
        
            <selector
            class="col-sm-4"
            v-model="type"
            name="type"
            label="Report Type"
            :items="types"
            item-value="value"
            item-text="name"
            placeholder="Select report type"
            ></selector>

            <template v-if="hasFilter(type)">
                
                <div class="col-sm-8">
                    <label>Select date range: </label>
                    <date-range
                        @change="filter($event)"
                    ></date-range>
                </div>

            </template>

            <template v-if="type == 3 || type == 4">
                    <selector
                    v-model="doctor_id"
                    class="col-sm-4"
                    name="doctors"
                    label="Doctors"
                    :items="doctors"
                    item-value="id"
                    item-text="fullname"
                    placeholder="Select doctor"
                    ></selector>
            </template>

            <div class="col-sm-12">
                <div class="text-right">
                    <button 
                    @click="generate()"
                    class="btn btn-primary">Generate</button>
                </div>
            </div>

        </div>

        <loader 
            :loading="loading">
        </loader>
	</div>
</template>

<script type="text/javascript">
import { EventBus }from '../../../EventBus.js';

import DateRange from 'Components/datepickers/DateRange.vue';
import Select from 'Components/inputs/Select.vue';
import Loader from 'Components/loaders/Loader.vue';

import ResponseHandler from 'Mixins/response.js';
import FetchMixin from 'Mixins/fetch.js';

export default {

    props: {
        postUrl: String,
    },

    components: {
        'date-range' : DateRange,
        'selector': Select,
        'loader': Loader,
    },

    computed: {
        renderReportType: function() {
            
            if(this.type == 1) {
                return 'sales-report.xlsx';
            }

            if(this.type == 2) {
                return 'inventory-report.xlsx';
            }

            if(this.type == 3) {
                return 'accepted-consultations-report.xlsx';
            }

            if(this.type == 4) {
                return 'declined-consultations-report.xlsx';
            }

            if(this.type == 5) {
                return 'transaction-history-report.xlsx';
            }
            
        },
    },

    data: function() {
        return {
            loading: false,

            types: [
                {value: 1, name: 'Sales Report'},
                {value: 2, name: 'Inventory Report'},
                {value: 3, name: 'Accepted Consultation Report'},
                {value: 4, name: 'Declined Consultation Report'},
                {value: 5, name: 'Transaction History Report'},
            ],

            type: 1,
            date_range: {},
            doctors: [],
            users: [],
            user_id: 1,
            doctor_id: 0.1
        }
    },

    mixins: [ 
        ResponseHandler,
        FetchMixin
    ],

    methods: {
        
        /*
        |--------------------------------------------------------------------------
        | @Initialize
        |--------------------------------------------------------------------------
        */
       

        /*
        |--------------------------------------------------------------------------
        | @Methods
        |--------------------------------------------------------------------------
        */

        fetchSuccess(data) {
            this.item = data.items ? data.items : this.item;
            this.doctors = data.doctors ? data.doctors : this.doctors;
            this.users = data.users ? data.users : this.users;
        },

        hasFilter($type) {
            if($type != 2) {
                return true;
            }
            return false;
        },

        getPost: function() {
            
            let post = {
                'type': this.type,
                'date_range': this.date_range,
                'doctor_id': this.doctor_id,
                'user_id': this.user_id
            };

            return post;
        },

        generate: function() {

            this.setLoading(true);

            axios.post(this.postUrl, this.getPost(), {responseType: 'blob'})
                .then((response) => {

                    this.setLoading(false);
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', this.renderReportType);
                    document.body.appendChild(link);
                    link.click();

                    swal('Report Generated', 'Report has been successfully generated.', 'success');

                })
                .catch((error) => {
                    this.setLoading(false);
                    swal('Something went wrong', 'Something went wrong while generating report', 'error');
                });

        },

        filter: function(e) {
            this.date_range = e;
        },

        setLoading: function(status) {
            this.loading = status;
        },




    },
}
</script>