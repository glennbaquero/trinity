<template>
    <div>
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['#', 'Doctor', 'Value', 'Created Date']"
        :columns="['id', 'doctor', 'value', 'created_at']"
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
                    <td>{{ item.doctor }}</td>
                    <td>{{ item.value }}</td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">

                        <div class="mb-2">
                        <template v-if="!item.canArchive">
                            <action-button
                            small 
                            color="btn-primary"
                            :action-url="item.approveUrl"
                            icon="fas fa-check"
                            confirm-dialog
                            :disabled="loading"
                            title="Approve Payout Request"
                            :message="'Are you sure you want to approve payout request #' + item.id + '?'"
                            @load="load"
                            @success="sync"
                            ></action-button>

                            <view-button 
                            :href="item.disapprovalFormUrl"
                            color="btn-warning"
                            ></view-button>
                            
                            <a
                            class="btn btn-sm btn-danger"
                            :href="item.disapprovalFormUrl"
                            ><i class="fas fa-times" />
                            </a>
                        </template>

                        <template v-else>
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
                            :message="'Are you sure you want to archive Contact Information #' + item.id + '?'"
                            :alt-message="'Are you sure you want to restore Contact Information #' + item.id + '?'"
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
import DateRange from 'Components/datepickers/DateRange.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';
import FormRequest from 'Components/forms/FormRequest.vue';


export default {
    methods: {
        update() {
            EventBus.$emit('product-count');
            this.fetch();
        },
    },

    props: {
        filterStatus: {},
        exportUrl: String,
    },

    mixins: [ ListMixin ],

    components: {
        'search-box': SearchBox,
        'action-button': ActionButton,
        'date-range': DateRange,
        'form-request': FormRequest,
    },
}
</script>