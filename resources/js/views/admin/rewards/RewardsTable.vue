<template>
	<div>
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['#', 'Name', 'Points', 'Created Date']"
        :columns="['id', 'name', 'points', 'created_at']"
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
                    <td>{{ item.name }}</td>
                    <!-- <td>{{ item.category_name }}</td> -->
                    <td>{{ item.points }}</td>
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
                            title="Archive Reward"
                            alt-title="Restore Reward"
                            :message="'Are you sure you want to archive Reward #' + item.id + '?'"
                            :alt-message="'Are you sure you want to restore Reward #' + item.id + '?'"
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
        'selector': Select,
        'form-request': FormRequest,
    },
}
</script>