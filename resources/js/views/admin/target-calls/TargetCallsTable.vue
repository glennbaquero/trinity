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
                v-if="months"
                name="month"
                :items="months"
                item-value="value"
                item-text="name"
                @change="filter($event, 'month')"
                placeholder="Filter by month"
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
                    <td>{{ item.medical_representative }}</td>
                    <td>{{ item.month }}</td>
                    <td>{{ item.year }}</td>
                    <td>{{ item.target }}</td>
                    <td>{{ item.created_at }}</td>
                    <td>
                        <view-button :href="item.showUrl"></view-button>
                        
                        <action-button
                        v-if="!hideButtons"
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
                        :message="'Are you sure you want to archive Page Item #' + item.id + '?'"
                        :alt-message="'Are you sure you want to restore Page Item #' + item.id + '?'"
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
import Select from 'Components/inputs/Select.vue';
import FormRequest from 'Components/forms/FormRequest.vue';

export default {
    computed: {
        headers() {
            let array = ['#', 'Full Name', 'Month', 'Year', 'Targets'];

            array = array.concat(['Created Date']);

            return array;
        },

        columns() {
            let array = ['id', 'fullname', 'month', 'year', 'target', 'created_at'];

            array = array.concat(['created_at']);

            return array;
        },
    },

    props: {
        months: {
            type: Array,
            default: null
        },

        hideParent: {
            default: false,
            type: Boolean,
        },

        hideButtons: {
            default: false,
            type: Boolean,
        },
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