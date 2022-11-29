<template>
	<div>
        <form-request :submit-url="exportUrl" @load="load" normal-submit-on-success method="POST" :action="exportUrl">
            <filter-box>
                <template v-slot:left>
                    <refresh-button @click="fetch"></refresh-button>

                    <date-range
                    @change="filter($event)"
                    ></date-range>

                    <selector
                    class="mt-2"
                    v-if="filterStatus"
                    :items="filterStatus"
                    @change="filter($event, 'status')"
                    placeholder="Filter by status"
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
        :headers="['#', 'Voucher Type', 'Name', 'Type', 'Discount', 'User Type','Created Date']"
        :columns="['id', 'voucher_type', 'name', 'type', 'discount', 'User Type','created_at']"
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
                    <td><span class="badge" :class="'badge-'+item.voucher_type.class">
                            {{ item.voucher_type.name }}
                        </span>
                    </td>
                    <td>{{ item.name }}</td>
                    <td><span class="badge" :class="'badge-'+item.type.class">
                            {{ item.type.name }}
                        </span>
                    </td>
                    <td>
                        {{ formatDiscount(item.type.value,item.discount) }}
                    </td>
                    <td><span class="badge" :class="'badge-'+item.user_type.class">
                            {{ item.user_type.name }}
                        </span>
                    </td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">

                        <div class="mb-2">
                            <view-button :href="item.showUrl"></view-button>
                            
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
                            :message="'Are you sure you want to archive voucher ' + item.name + '?'"
                            :alt-message="'Are you sure you want to restore voucher #' + item.name + '?'"
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
import ListMixin from '../../../mixins/list.js';
import { EventBus }from '../../../EventBus.js';

import SearchBox from '../../../components/datatables/SearchBox.vue';
import DateRange from '../../../components/datepickers/DateRange.vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import ViewButton from '../../../components/buttons/ViewButton.vue';
import Select from '../../../components/inputs/Select.vue';
import FormRequest from '../../../components/forms/FormRequest.vue';

export default {
    methods: {
        update() {
            this.fetch();
        },
        formatDiscount(type,value) {
            let discount = value;
            if(type == 0) {
               return "‎₱ " + Number(discount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }else{
                return Math.trunc(discount) + " %";
            }
        }
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
        'selector': Select,
        'form-request': FormRequest,
    },
}
</script>