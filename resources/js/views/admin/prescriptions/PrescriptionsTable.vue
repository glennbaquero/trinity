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
                    :items="statuses"
                    item-value="value"
                    item-text="label"
                    @change="filter($event, 'approved')"
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
        :headers="['#', 'User', 'Product', 'Image', 'Status', 'Created Date']"
        :columns="['id', null, null, null, null, 'created_at']"
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
                    <td>{{ item.user }}</td>
                    <td>{{ item.product }}</td>
                    <td><a :href="item.image_path" target="_blank">View image</a></td>
                    <td>
                        <span :class="`badge badge-${item.approved === null ? 'secondary' : (item.approved ? 'success' : 'danger')}`">
                            {{ item.approved === null ? 'Pending' : (item.approved ? 'Approved' : 'Rejected') }}
                        </span>
                    </td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">
                        <div class="mb-2">

                            <template v-if="item.product != 'N/A'">
                                <button v-if="item.approved === null" class="btn btn-sm btn-success" @click="changeStatus(item.id, 1)">
                                    <i class="fas fa-check"></i>
                                </button>

                                <button v-if="item.approved === null" class="btn btn-sm btn-warning" @click="changeStatus(item.id, 0)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </template>
                     
                            <action-button
                            small 
                            color="btn-danger"
                            alt-color="btn-warning"
                            :show-alt="item.deleted_at"
                            :action-url="item.archiveUrl"
                            alt-icon="fas fa-trash-restore"
                            icon="fas fa-trash"
                            confirm-dialog
                            :disabled="loading"
                            title="Archive Item"
                            :message="'Are you sure you want to archive Prescription #' + item.id + '?'"
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

import ResponseHandler from 'Mixins/response.js';

export default {
    methods: {
        update() {
            EventBus.$emit('update-sample-item-count');
            this.fetch();
        },

        changeStatus(id, status) {
            
            let message = {
                title: 'Update prescription status',
                title: `Are you sure you want to update the status of prescription #${id}`,
            }

            let options = {
                loader: true,
                okText: 'Confirm',
                cancelText: 'Cancel',
                animation: 'fade',
                type: 'basic',
            };

            this.$dialog.confirm(message, options)
                .then(dialog => {
                    dialog.loading(true);
                    this.updateStatus({ id, status }, dialog);
                })
                .catch(() => {
                    dialog.loading(false);
                    dialog.close();
                });
        },

        updateStatus(post, dialog) {
            axios.post(`${this.submitUrl}/${post.id}`, post)
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
    },

    props: {
        filterStatus: {},
        exportUrl: String,
        submitUrl: String
    },

    computed: {
        statuses() {
            return [
                { value: 2, label: 'Pending' },
                { value: 1, label: 'Approved' },
                { value: 0, label: 'Rejected' }
            ];
        }
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