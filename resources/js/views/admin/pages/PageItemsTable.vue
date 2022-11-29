<template>
	<div>
        <filter-box>
            <template v-slot:left>

                <refresh-button @click="fetch"></refresh-button>

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
                    <td>{{ item.name }}</td>
                    <td>{{ item.slug }}</td>
                    <td v-if="!hideParent"><a :href="item.parentUrl" target="_blank" class="btn btn-link btn-sm">{{ item.page }}</a></td>
                    <td><span class="badge" :class="item.type_class">{{ item.type }}</span></td>
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
import ListMixin from '../../../mixins/list.js';

import SearchBox from '../../../components/datatables/SearchBox.vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import ViewButton from '../../../components/buttons/ViewButton.vue';

export default {
    computed: {
        headers() {
            let array = ['#', 'Name', 'Slug'];

            if (!this.hideParent) {
                array.push('Page');
            }

            array = array.concat(['Type', 'Created Date']);

            return array;
        },

        columns() {
            let array = ['id', 'name', 'slug'];

            if (!this.hideParent) {
                array.push('page_id');
            }

            array = array.concat(['type', 'created_at']);

            return array;
        },
    },

    props: {
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
    },
}
</script>