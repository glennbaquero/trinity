<template>
	<div>
        <filter-box>
            <template v-slot:left>
                <selector
                class="mt-2 mr-2"
                v-if="specializations"
                name="status"
                :items="specializations"
                item-value="id"
                item-text="name"
                @change="filter($event, 'specialization')"
                placeholder="Filter by specialization"
                ></selector>
                <selector
                class="mt-2"
                name="type"
                :items="types"
                item-value="id"
                item-text="name"
                @change="filter($event, 'type')"
                placeholder="Filter by type"
                ></selector>
            </template>
            <template v-slot:right>
                <search-box
                placeholder="Search by name"
                @search="filter($event, 'search')">
                </search-box>
            </template>
        </filter-box>

        <!-- DATATABLE -->
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['#', 'Image', 'Product Type', 'Name', 'Brand Name', 'Generice Name', 'SKU', 'Product Size', 'Has Prescription', 'Price', 'Client Points', 'Doctors Points', 'Created Date']"
        :columns="['id', null, 'type', 'name', 'brand_name', 'generic_name', 'sku', 'product_size', null, 'price', 'client_points', 'doctor_points', 'created_at']"
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
                    <td>
                        <img class="rounded img-fluid img-thumbnail" :src="item.image_path" :alt="item.name" style="width:100px;">
                    </td>
                    <td>{{ item.type }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.brand_name }}</td>
                    <td>{{ item.generic_name }}</td>                    
                    <td>{{ item.sku }}</td>
                    <td>{{ item.product_size }}</td>
                    <td>{{ item.prescriptionable_text }}</td>
                    <td>{{ item.price }}</td>
                    <td>{{ item.client_points }}</td>
                    <td>{{ item.doctor_points }}</td>                    
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
                            title="Archive Product"
                            alt-title="Restore Product"
                            :message="'Are you sure you want to archive Product #' + item.id + '?'"
                            :alt-message="'Are you sure you want to restore Product #' + item.id + '?'"
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
        specializations: {
            type: Array,
            default: null,
        }
    },

    data() {
        return {
            types: [
                {
                    id: 1,
                    name: 'Non-pharmaceutical product'
                },
                {
                    id: 2,
                    name: 'Pharmaceutical product'
                },
            ]
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