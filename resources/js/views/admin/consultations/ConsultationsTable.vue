<template>
    <div>
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['#', 'Consultation No.', 'Doctor', 'Patient', 'Consultation Fee', 'Schedule', 'status','Created Date']"
        :columns="['id', 'consultation_number', 'doctor_id', 'user_id', 'fee', null, 'status', 'created_at']"
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
                    <td>{{ item.consultation_number }}</td>
                    <td>{{ item.doctor }}</td>
                    <td>{{ item.user }}</td>
                    <td>Php {{ item.fee }}</td>
                    <td>{{ item.schedule_date }}</td>
                    <td>{{ item.status }}</td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">
                        <div class="mb-2">
                            <view-button :href="item.showUrl"></view-button>
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