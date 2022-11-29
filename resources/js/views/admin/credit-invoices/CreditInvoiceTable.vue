<template>
    <div>
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['#', 'Invoice Number', 'Name', 'Package', 'Total', 'Status', 'Created Date']"
        :columns="['id', 'invoice_number', null, null, 'total', 'status', 'created_at']"
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
                    <td>{{ item.name }}</td>                    
                    <td>{{ item.package }}</td>
                    <td>{{ item.total }}</td>
                    <td>{{ item.status }}</td>                    
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">

                        <div class="mb-2">
                            
                            <template v-if="item.canRemove">                            
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
                                title="Archive Invoice"
                                alt-title="Restore Invoice"
                                :message="'Are you sure you want to archive Invoice #' + item.id + '?'"
                                :alt-message="'Are you sure you want to restore Invoice #' + item.id + '?'"
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
import ViewButton from 'Components/buttons/ViewButton.vue';
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
        'view-button': ViewButton,
        'action-button': ActionButton,
        'date-range': DateRange,
        'form-request': FormRequest,
    },
}
</script>