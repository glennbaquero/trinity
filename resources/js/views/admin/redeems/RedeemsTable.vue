<template>
	<div>
        <form-request :submit-url="exportUrl" @load="load" normal-submit-on-success method="POST" :action="exportUrl">
            <filter-box>
                <template v-slot:left>
                    <action-button v-if="exportUrl" type="submit" :disabled="loading" class="btn-warning mr-3" icon="fa fa-download">Export</action-button>
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
        :headers="['#', 'Doctor', 'Reward', 'Points', 'Sponsorship','Status', 'Created Date']"
        :columns="['id', 'doctor_id', 'reward_id', 'used_points', 'sponsorships', 'status', 'created_at']"
        :filters="filters"
        
        :defaultsort="'created_at'"
        :defaultorder="false"

        :fetch-url="fetchUrl"
        :actionable="false"
        :selectable="false"

        @loaded="init"
        @loading="load">

            <template v-slot:body>
                <tr v-for="item in items">
                    <td>{{ item.id }}</td>
                    <td><a :href="item.doctorShowUrl">{{ item.doctor }}</a></td>
                    <td><a :href="item.rewardShowUrl">{{ item.reward }}</a></td>
                    <td>{{ item.points }}</td>
                    <td>
                        <template v-for="(sponsorship, key) in item.sponsorships">
                            <a :href="sponsorship.renderShowUrl">
                                {{ sponsorship.name }}
                                <template v-if="key+1 < item.sponsorships.length">,</template>
                            </a>
                        </template>
                    </td>
                    
                    <td v-if="item.status.id == 0">
                        <action-button
                        small 
                        color="btn-success"
                        icon="fas fa-check"
                        :action-url="item.approveUrl"
                        confirm-dialog
                        :disabled="loading"
                        title="Approve"
                        :message="'Are you sure you want to approve Redemption #' + item.id + '?'"
                        @load="load"
                        @success="sync"
                        ></action-button>
                        <action-button
                        small 
                        color="btn-danger"
                        icon="fas fa-times"
                        :action-url="item.rejectUrl"
                        confirm-dialog
                        :disabled="loading"
                        title="Reject"
                        :message="'Are you sure you want to reject Redemption #' + item.id + '?'"
                        @load="load"
                        @success="sync"
                        ></action-button>
                    </td>
                    <td v-else>
                        <label
                        class="badge"
                        style="color: #fff;"
                        :style="item.status.id == 1 ? 'background-color: green;' : 'background-color: red;'"
                        >
                            {{ item.status.value }}
                        </label>
                    </td>
                    <td>{{ item.created_at }}</td>
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
            EventBus.$emit('update-sample-item-count');
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
        'form-request': FormRequest
    },
}
</script>