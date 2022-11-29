<template>
	<div>
        <filter-box>
            <template v-slot:left>
                <date-range
                @change="filter($event)"
                ></date-range>

                <selector
                class="mt-2"
                :items="doctors"
                item-value="id"
                item-text="fullname"
                placeholder="Filter by Doctor"
                @change="filter($event, 'doctor')"
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
        :headers="['#', 'Consultation No.', 'Reviewer', 'Doctor', 'Ratings','Description', 'Created Date']"
        :columns="['id', null, null, null, 'ratings', null, 'created_at']"
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
                    <td>{{ item.consultation_number }}</td>                    
                    <td>{{ item.reviewer }}</td>
                    <td>{{ item.doctor }}</td>
                    <td>{{ item.ratings }} / 5</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">

                        <div class="mb-2">
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
                            :message="'Are you sure you want to archive this review #' + item.id + '?'"
                            :alt-message="'Are you sure you want to restore this review #' + item.id + '?'"
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
import Select from 'Components/inputs/Select.vue';

export default {

    props: {
        doctors: {
            type: Array,
            default: []
        },
    },

    components: {
        'search-box': SearchBox,
        'action-button': ActionButton,
        'date-range': DateRange,
        'selector': Select
    },

    mixins: [ ListMixin ],

    methods: {
        update() {
            this.fetch();
        },
    },

}
</script>