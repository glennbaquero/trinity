<template>
	<div>
        <filter-box>
            <template v-slot:right>
                <search-box
                placeholder="Search by product's name"
                @search="filter($event, 'search')">
                </search-box>
            </template>
        </filter-box>

        <!-- DATATABLE -->
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['#', 'Product SKU', 'Product Name', 'Stocks', 'Stocks Status', 'Updated At']"
        :columns="['id', null, null, 'stocks', null, 'updated_at']"
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
                    <td>{{ item.sku }}</td>
                    <td>{{ item.product_name }}</td>
                    <td>{{ item.stocks }}</td>
                    <td><label :class="item.stocks_status['class']">{{ item.stocks_status['label'] }}</label></td>
                    <td>{{ item.updated_at }}</td>
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
import ViewButton from 'Components/buttons/ViewButton.vue';
import Select from 'Components/inputs/Select.vue';
import FormRequest from 'Components/forms/FormRequest.vue';


export default {
    methods: {
        update() {
            this.fetch();
        },
    },

    props: {

    },

    mixins: [ ListMixin ],

    components: {
        'search-box': SearchBox,
        'view-button': ViewButton,
        'selector': Select,
        'form-request': FormRequest,
    },
}
</script>