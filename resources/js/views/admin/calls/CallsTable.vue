<template>
	<div>
        <form-request :submit-url="exportUrl" @load="load" normal-submit-on-success method="POST" :action="exportUrl">
            <filter-box>
                <template v-slot:left>
                    <label v-if="items.findIndex(item => item.status.id === 0) !== -1" for="check-all" class="mr-2" style="cursor: pointer">
                        <input type="checkbox" id="check-all" style="display: none;" @change="selectAll(items)">
                        <i :class="checkedItems.length ? 'fas fa-check-square' : 'far fa-square'" style="font-size: 2rem"></i>
                    </label>

                    <div v-if="checkedItems.length" class="mr-3">
                        <action-button
                        small 
                        color="btn-success"
                        label="Approve"
                        action-url="admin/calls/approve"
                        confirm-dialog
                        :disabled="loading"
                        :message="'Are you sure you want to approve all selected items?'"
                        :request="{ items: checkedItems }"
                        @load="load"
                        @success="clear"
                        ></action-button>

                        <action-button
                        small
                        mt-2
                        color="btn-danger"
                        label="Reject"
                        action-url="admin/calls/reject"
                        confirm-dialog
                        :disabled="loading"
                        :message="'Are you sure you want to deny all selected items?'"
                        :request="{ items: checkedItems }"
                        @load="load"
                        @success="clear"
                        ></action-button>
                    </div>

                    <action-button v-if="exportUrl" type="submit" :disabled="loading" class="btn-warning mr-3" icon="fa fa-download">Export</action-button>
                    <refresh-button @click="fetch"></refresh-button>

                    <date-range
                    @change="filter($event)"
                    ></date-range>

                    <selector
                    class="mt-2"
                    v-if="filterStatus"
                    :items="filterStatus"
                    item-value="id"
                    item-text="name"
                    @change="filter($event, 'status')"
                    placeholder="Filter by status"
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
        :headers="['#', 'Medical Representative','Doctor', 'Status', 'Scheduled Date', 'Created Date']"
        :columns="['id', null, null, null, null, 'created_at']"
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
                    <td>
                        <label v-if="item.status.id === 0" :for="`cb-${item.id}`" style="cursor: pointer">
                            <input type="checkbox" :id="`cb-${item.id}`" style="display: none;" :value="item.id" v-model="checkedItems">
                            <i :class="checkedItems.includes(item.id) ? 'fas fa-check-square' : 'far fa-square'"></i>
                        </label>
                        <span :class="item.status.id === 0 ? 'ml-2' : ''">{{ item.id }}</span>
                    </td>
                    <td>{{ item.medRep }}</td>
                    <td>{{ item.doctor }}</td>
                    <td>
                        <label
                        :class="`badge badge-${item.status.id === 0 ? 'secondary' : (item.status.id === 1 ? 'success' : 'danger')}`"
                        style="color: #fff"
                        >
                            {{ item.status.value }}
                        </label>
                    </td>
                    <td>{{ item.scheduled_date }}</td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">

                        <div class="mb-2">
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
                            :message="'Are you sure you want to approve Call #' + item.id + '?'"
                            :request="{ items: [item.id] }"
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
                            :message="'Are you sure you want to reject Call #' + item.id + '?'"
                            :request="{ items: [item.id] }"
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
                            title="Archive"
                            alt-title="Restore Item"
                            :message="'Are you sure you want to archive Call #' + item.id + '?'"
                            :alt-message="'Are you sure you want to restore Call #' + item.id + '?'"
                            @load="load"
                            @success="sync"
                            ></action-button>
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
import DateRange from 'Components/datepickers/DateRange.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';
import ViewButton from 'Components/buttons/ViewButton.vue';
import Select from 'Components/inputs/Select.vue';
import FormRequest from 'Components/forms/FormRequest.vue';

export default {
    data: () => ({
        checkAll: false,
        checkedItems: [],
    }),

    methods: {
        update() {
            EventBus.$emit('update-sample-item-count');
            this.fetch();
        },
        selectAll(items) {
            if (this.checkAll) {
                this.checkAll = false;
                this.checkedItems = [];
            }
            else {
                const ids = items.reduce((list, item, index, array) => {
                    if (item.status.id === 0) {
                        list.push(item.id);
                    }

                    return list;
                }, []);

                this.checkAll = true;
                this.checkedItems = ids;
            }
        },
        clear() {
            this.sync();
            this.checkedItems = [];
        }
    },

    props: {
        filterStatus: {
            type: Array,
            default: null
        },
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
        'form-request': FormRequest
    },
}
</script>