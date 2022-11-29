<template>
    <div>
        <filter-box>
            <template v-slot:left>

                <refresh-button @click="fetch"></refresh-button>

                <selector
                class="mt-2 ml-2"
                @change="filter($event, 'status')"
                :items="status"
                item-value="value"
                item-text="label"
                placeholder="Filter by Status">
                </selector>

                <selector
                class="mt-2 ml-2"
                @change="filter($event, 'type')"
                :items="types"
                item-value="value"
                item-text="label"
                placeholder="Filter by User types">
                </selector>

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
        :headers="['#', 'Name', 'Email', 'Status', 'User type', 'Linked Doctors', 'Points', 'Wallets', 'Registration Date']"
        :columns="['id', 'last_name', 'email', null, 'type', null, null, null, 'created_at']"
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
                    <td>
                        <span :class="`badge badge-${item.email_verified_at === null ? 'secondary' : (item.email_verified_at ? 'success' : 'danger')}`">
                            {{ item.email_verified_at ? 'Verified' : 'Not verified' }}
                        </span>
                    </td>
                    <td>{{ item.user_type }}</td>
                    <td>
                        <template v-for="doctor in item.doctors">
                            <span class="badge bg-primary mr-1">{{ doctor.fullname }}</span>
                        </template>
                    </td>
                    <td>{{ item.points }}</td>
                    <td>{{ item.wallets }}</td>
                    <td>{{ item.created_at }}</td>
                    <td>
                        <a 
                        :href="item.manageCreditsUrl"
                        class="btn btn-warning btn-sm text-white"><i class="fas fa-wallet"></i> Manage Credits</a>

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
                        :message="'Are you sure you want to archive User #' + item.id + '?'"
                        :alt-message="'Are you sure you want to restore User #' + item.id + '?'"
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
import Selector from 'Components/inputs/Select.vue';
import ActionButton from 'Components/buttons/ActionButton.vue';
import ViewButton from 'Components/buttons/ViewButton.vue';

import ResponseHandler from 'Mixins/response.js';

export default {
    data() {
        return {
            status: [
                { value: 0, label: 'Not Verified' },
                { value: 1, label: 'Verified' },
                { value: 2, label: 'All' },
            ],
            types: [
                { value: 0, label: 'Patient' },
                { value: 1, label: 'Secretary' },
            ],
        }
    },

    props: {
        filterRoles: {},
    },

    mixins: [ ListMixin ],

    components: {
        'selector': Selector,
        'search-box': SearchBox,
        'view-button': ViewButton,
        'action-button': ActionButton,
    },

    methods: {
        confirm(id, title, message, url) {
            let text = {
                title,
                title: message
            }

            let options = {
                loader: true,
                okText: 'Confirm',
                cancelText: 'Cancel',
                animation: 'fade',
                type: 'basic',
            };

            this.$dialog.confirm(text, options)
                .then(dialog => {
                    dialog.loading(true);
                    this.execute(url, id, dialog);
                })
                .catch(() => {
                    dialog.loading(false);
                    dialog.close();
                });
        },
        execute(url, id, dialog) {
            axios.post(url)
                .then(response => {
                    dialog.loading(false);
                    dialog.close();

                    if (response.data.redirectUrl) {
                        window.location.href = response.data.redirectUrl;
                    }
                    else {
                        this.parseSuccess(response.data.message);
                        this.fetch();
                    }
                })
                .catch(error => {
                    dialog.loading(false);
                    dialog.close();
                    this.parseError(error);
                });
        }
    }
}
</script>
