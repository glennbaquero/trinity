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

                <action-button
                v-if="readAllUrl"
                small 
                :action-url="readAllUrl"
                color="btn-primary"
                icon="fa fa-envelope-open"
                title="Read Notifications"
                message="Mark all notifications as read?"
                :disabled="!array_count(items)"
                @load="load"
                @success="updateNotifications"
                >
                    Mark all as Read
                </action-button>

            </template>
        </filter-box>

        <!-- DATATABLE -->
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['Title', 'Message', 'Received']"
        :columns="[null, null, 'created_at']"
        :filters="filters"
        
        :defaultsort="'created_at'"
        :defaultorder="false"

        :fetch-url="fetchUrl"
        :actionable="actionable"
        :selectable="false"

        @loaded="init"
        @loading="load"
        >

            <template v-slot:body>
                <tr v-for="item in items">
                    <td>{{ item.title }}</td>
                    <td>{{ item.message }}</td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">
                        <view-button v-if="unreadable" :href="item.showUrl" target="_blank"></view-button>

                        <action-button
                        small 
                        :show-alt="item.read_at"
                        :action-url="item.readUrl"
                        :alt-action-url="item.unreadUrl"
                        color="btn-primary"
                        alt-color="btn-secondary"
                        icon="fa fa-eye"
                        alt-icon="fas fa-envelope"
                        title="Read Notification"
                        alt-title="Unread Notification"
                        message="Mark notification as read?"
                        alt-message="Mark notification as unread?"
                        :href="!unreadable ? item.showUrl : null"
                        target="_blank"
                        :confirm-dialog="unreadable"
                        :hide-response="!unreadable"
                        :disabled="!item.showUrl && !unreadable"
                        @load="load"
                        @success="updateNotifications"
                        @error="updateNotifications"
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
import ArrayHelpers from 'Mixins/array.js';
import { EventBus } from '../../EventBus.js';

import ViewButton from 'Components/buttons/ViewButton.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';
import DateRange from 'Components/datepickers/DateRange.vue';

export default {
    methods: {
        updateNotifications() {
            EventBus.$emit('update-notification-count');
            this.sync();
        },
    },

    props: {
        filterTypes: {},

        unreadable: {
            default: false,
            type: Boolean,
        },

        readAllUrl: String,
    },

    mixins: [ ListMixin, ArrayHelpers ],

    components: {
        'view-button': ViewButton,
        'action-button': ActionButton,
        'date-range': DateRange,
    },
}
</script>