<template>
	<div>
        <form-request :submit-url="exportUrl" @load="load" normal-submit-on-success method="POST" :action="exportUrl">
            <filter-box>
                <template v-slot:left>
                    <action-button v-if="exportUrl" type="submit" :disabled="loading" class="btn-warning mr-3" icon="fa fa-download">Export</action-button>
                    <refresh-button @click="fetch"></refresh-button>

                    <date-range
                    @change="filter($event)"
                    ></date-range>

                    <selector
                    class="mt-2"
                    :items="statuses"
                    item-value="id"
                    item-text="name"
                    placeholder="Filter by status"
                    @change="filter($event, 'status')"
                    ></selector>
                    
                    <selector
                    class="mt-2"
                    :items="medreps"
                    item-value="id"
                    item-text="fullname"
                    placeholder="Filter by Medical Representativ"
                    @change="filter($event, 'medrep')"
                    ></selector>
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
        :headers="['#', 'QR Code', 'QR ID', 'Name', 'Ratings' ,'Medical Representative', 'Specialization', 'Points', 'Credits', 'Status', 'Created Date']"
        :columns="['id', null, 'last_name', null, null, null, null, null, null, 'created_at']"
        :filters="filters"
        
        :defaultsort="'created_at'"
        :defaultorder="false"

        :fetch-url="fetchUrl"
        :actionable="true"
        :selectable="false"

        @loaded="init"
        @loading="load">

            <template v-slot:body>
                <tr v-for="item in items">
                    <td>{{ item.id }}</td>
                    <td><img :src="item.qr_path" width="100px" class="rounded img-fluid img-thumbnail"></td>
                    <td>{{ item.qr_id }}</td>
                    <td>{{ item.fullname }}</td>
                    <td>{{ item.ratings }} / 5</td>
                    <td>{{ item.medRep }}</td>
                    <td>{{ item.specialization }}</td>
                    <td>{{ item.points }}</td>
                    <td>{{ item.credits }}</td>
                    <td>
                        <label
                        :class="`badge badge-${!item.status.id ? 'secondary' : (item.status.id === 1 ? 'success' : 'danger')}`"
                        style="color: #fff"
                        >
                            {{ item.status.value }}
                        </label>
                    </td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">

                        <div class="mb-2">

                            <a 
                            :href="item.manageCreditsUrl"
                            class="btn btn-warning btn-sm text-white"><i class="fas fa-wallet"></i> Manage Credits</a>                            

                            <view-button :href="item.showUrl"></view-button>

                            <action-button
                            v-if="item.status.id === 0"
                            small 
                            color="btn-success"
                            icon="fas fa-check"
                            :action-url="item.approveUrl"
                            confirm-dialog
                            :disabled="loading"
                            title="Approve"
                            :message="'Are you sure you want to approve Doctor #' + item.id + '?'"
                            @load="load"
                            @success="sync"
                            ></action-button>

                            <action-button
                            v-if="item.status.id === 0"
                            small 
                            color="btn-warning"
                            icon="fas fa-times"
                            :action-url="item.rejectUrl"
                            confirm-dialog
                            :disabled="loading"
                            title="Reject"
                            :message="'Are you sure you want to reject Doctor #' + item.id + '?'"
                            @load="load"
                            @success="sync"
                            ></action-button>
                            
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
                            title="Archive Item"
                            alt-title="Restore Item"
                            :message="'Are you sure you want to archive Doctor #' + item.id + '?'"
                            :alt-message="'Are you sure you want to restore Doctor #' + item.id + '?'"
                            @load="load"
                            @success="sync"
                            ></action-button>
                        </div>

                        <doctors-buttons v-if="!item.deleted_at" @load="load" @success="update" :item="item" small></doctors-buttons>
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
import DateRange from 'Components/datepickers/DateRange.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';
import ViewButton from 'Components/buttons/ViewButton.vue';
import Select from 'Components/inputs/Select.vue';
import FormRequest from 'Components/forms/FormRequest.vue';
import DoctorsButtons from './DoctorsButtons.vue';

export default {
    methods: {
        update() {
            EventBus.$emit('update-sample-item-count');
            this.fetch();
        },
    },

    computed: {
        statuses() {
            return [
                { id: 0, name: 'Pending' },
                { id: 1, name: 'Approved' },
                { id: 2, name: 'Rejected' }
            ];
        }
    },

    props: {
        exportUrl: String,
        medreps: Array
    },

    mixins: [ ListMixin ],

    components: {
        'search-box': SearchBox,
        'view-button': ViewButton,
        'action-button': ActionButton,
        'date-range': DateRange,
        'selector': Select,
        'form-request': FormRequest,
        'doctors-buttons': DoctorsButtons,
    },
}
</script>