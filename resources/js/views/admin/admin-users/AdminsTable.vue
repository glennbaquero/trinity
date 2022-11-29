<template>
    <div>
        <filter-box>
            <template v-slot:left>

                <refresh-button @click="fetch"></refresh-button>
                    
                <date-range
                @change="filter($event)"
                ></date-range>

                <selector 
                class="mt-2"
                :items="filterRoles"
                item-value="id"
                item-text="name"
                @change="filter($event, 'role_id')"
                placeholder="Filter by role"
                no-search
                ></selector>

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
        :headers="['#', 'Name', 'Email', 'Roles', 'Created Date']"
        :columns="['id', 'last_name', 'email', null, 'created_at']"
        :filters="filters"
        
        :defaultsort="'created_at'"
        :defaultorder="false"

        :fetch-url="fetchUrl"
        :actionable="true"
        :selectable="false"

        @loaded="init"
        @loading="load"
        >

            <template v-slot:body>
                <tr v-for="item in items">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.email }}</td>
                    <td>{{ item.roles }}</td>
                    <td>{{ item.created_at }}</td>
                    <td>
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
                        :message="'Are you sure you want to archive Admin #' + item.id + '?'"
                        :alt-message="'Are you sure you want to restore Admin #' + item.id + '?'"
                        @load="load"
                        @success="sync"
                        ></action-button>
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

import SearchBox from 'Components/datatables/SearchBox.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';
import ViewButton from 'Components/buttons/ViewButton.vue';
import Select from 'Components/inputs/Select.vue';
import DateRange from 'Components/datepickers/DateRange.vue';

export default {
    props: {
        filterRoles: {},
    },

    mixins: [ ListMixin ],

    components: {
        'search-box': SearchBox,
        'view-button': ViewButton,
        'action-button': ActionButton,
        'selector': Select,
        'date-range': DateRange,
    },
}
</script>