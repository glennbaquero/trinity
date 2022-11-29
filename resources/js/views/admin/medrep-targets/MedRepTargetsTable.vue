<template>
	<div>
        <filter-box>
            <template v-slot:right>
                <search-box
                @search="filter($event, 'search')">
                </search-box>
            </template>
        </filter-box>

        <!-- DATATABLE -->
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['#', 'Type', 'Month', 'Year', 'Target', 'Created Date']"
        :columns="['id', null, null, null, null, 'created_at']"
        :filters="filters"
        
        :defaultsort="'year'"
        :defaultorder="false"

        :fetch-url="fetchUrl"
        :actionable="true"
        :selectable="false"

        @loaded="init"
        @loading="load">

            <template v-slot:body>
                <tr v-for="item in items">
                    <td>{{ item.id }}</td>
                    <td>{{ item.display_type }}</td>
                    <td>{{ item.display_month }}</td>                    
                    <td>{{ item.display_year }}</td>
                    <td>{{ item.display_value }}</td>
                    <td>{{ item.created_at }}</td>                    
                    <td v-if="actionable">

                        <div class="mb-2">
                            <button v-if="!item.deleted_at" type="button" @click="select(item.id)" :class="selected.id == item.id ? 'btn-danger' : 'btn-success'" class="btn btn-sm">
                                <i :class="selected.id == item.id ? 'fa fa-redo-alt' : 'fas fa-edit'"></i>
                            </button>
                            
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
                            title="Archive Medical Representative Target"
                            alt-title="Restore Medical Representative Target"
                            :message="'Are you sure you want to archive #' + item.id + '?'"
                            :alt-message="'Are you sure you want to restore #' + item.id + '?'"
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
import ActionButton from 'Components/buttons/ActionButton.vue';
import ViewButton from 'Components/buttons/ViewButton.vue';
import Select from 'Components/inputs/Select.vue';
import FormRequest from 'Components/forms/FormRequest.vue';


export default {

    props: {
        
    },

    components: {
        'search-box': SearchBox,
        'view-button': ViewButton,
        'action-button': ActionButton,
        'selector': Select,
        'form-request': FormRequest,
    },


    data: function() {
        return {
            selected: {},
        }
    },

    mounted: function() {

        this.fetchEvent();
    },      

    mixins: [ ListMixin ],

    methods: {

        fetchEvent: function() {
            EventBus.$on('success', () => {
                this.fetch();
                this.clear();
            });
        },

        select: function(id) {
            let item = this.items.filter(obj => { return obj.id === id })[0];

            if (item) {
                if (item.id != this.selected.id) {
                    this.clear();
                    this.$nextTick(() => {
                        this.selected = item;
                        this.$emit('itemSelect', this.selected);
                    });
                } else {
                    this.clear();
                }
            }
        },

        clear() {
            this.selected = {};
            this.$emit('itemSelect', this.selected);
        },

    },

}
</script>