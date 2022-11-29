<template>
	<div>
        <filter-box>
            <template v-slot:left>
                <refresh-button @click="fetch"></refresh-button>

                <date-range
                @change="filter($event)"
                ></date-range>

            </template>
            <template v-slot:right>
                <search-box
                @search="filter($event, 'search')">
                </search-box>
            </template>
        </filter-box>

        <!-- DATATABLE -->
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['#', 'Request By', 'Status', 'Approved By', 'Claimed At', 'Rejected By', 'Reason', 'Created Date']"
        :columns="['id', null, null, 'distribute_by', 'claimed_at', 'disapproved_by', 'reason', 'created_at']"
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
                    <td>{{ item.name }}</td>
                    <td>{{ item.status }}</td>
                    <td>{{ item.distribute_by }}</td>
                    <td>{{ item.claimed_at }}</td>
                    <td>{{ item.disapproved_by }}</td>
                    <td>{{ item.reason }}</td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">

                        <div class="mb-2">
                            <template v-if="hasAction">
                                <a
                                :href="item.approveUrl"
                                class="btn btn-success btn-sm text-white">
                                    <i class="fa fa-thumbs-up"></i>
                                </a>
                                <a 
                                :href="item.rejectUrl"
                                class="btn btn-danger btn-sm text-white">
                                    <i class="fa fa-thumbs-down"></i>
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
                                :message="'Are you sure you want to archive the request of ' + item.name + '?'"
                                :alt-message="'Are you sure you want to restore the request of ' + item.name + '?'"
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
import ListMixin from '../../../mixins/list.js';

import SearchBox from '../../../components/datatables/SearchBox.vue';
import DateRange from '../../../components/datepickers/DateRange.vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';

export default {

    props: {
        hasAction: {
            type: Boolean,
            default: false,
        }
    },

    components: {
        'search-box': SearchBox,
        'action-button': ActionButton,
        'date-range': DateRange,
    },
    mixins: [ ListMixin ],
}
</script>