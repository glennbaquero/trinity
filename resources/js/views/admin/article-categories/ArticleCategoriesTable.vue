 <template>
    <div>

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
                    <td>{{ item.name }}</td>
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
                        :message="'Are you sure you want to archive Type ' + item.name + '?'"
                        :alt-message="'Are you sure you want to restore Type ' + item.name + '?'"
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
            let array = ['#', 'Name'];

            array = array.concat(['Created Date']);

            return array;
        },

        columns() {
            let array = ['id', null, 'created_at'];

            array = array.concat(['created_at']);

            return array;
        },
    },

    props: {
        provinces: {
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