 <template>
    <div>
        <filter-box>
            <template v-slot:left>
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
        :headers="headers"
        :columns="columns"
        :filters="filters"

        :defaultsort="'created_at'"
        :defaultorder="false"

        :fetchUrl="fetchUrl"
        :actionable="true"
        :selectable="false"

        @loaded="init"
        @loading="load">

            <template v-slot:body>
                <tr v-for="item in items">
                    <td>{{ item.id }}</td>
                    <td>{{ item.area }}</td>
                    <td>{{ item.fee }}</td>
                    <td>{{ item.free }}</td>
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
                        title="Archive Shipping Matrix"
                        alt-title="Restore Shipping Matrix"
                        :message="'Are you sure you want to archive Shipping Matrix #' + item.id + '?'"
                        :alt-message="'Are you sure you want to restore Shipping Matrix #' + item.id + '?'"
                        @load="load"
                        @success="sync"
                        ></action-button>
                    </td>
                </tr>
            </template>

        </datatable>

        <loader :loading="loading"></loader>
    </div>
</template>

<script type="text/javascript">
import ListMixin from 'Mixins/list.js';

import SearchBox from 'Components/datatables/SearchBox.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';
import ViewButton from 'Components/buttons/ViewButton.vue';
import DateRange from 'Components/datepickers/DateRange.vue';

export default {
    computed: {
        headers() {
            let array = ['#', 'Area', 'Fee', 'Is Free' ];

            array = array.concat(['Created Date']);

            return array;
        },

        columns() {
            let array = ['id', null, 'fee', null, 'created_at'];

            return array;
        },                
    },

    mixins: [ ListMixin ],

    components: {
        'search-box': SearchBox,
        'view-button': ViewButton,
        'action-button': ActionButton,
        'date-range': DateRange,
    },
}
</script>