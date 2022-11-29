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
                <search-box
                @search="filter($event, 'search')">
                </search-box>
            </template>
        </filter-box>

        <!-- DATATABLE -->
        <datatable ref="datatable"
        :autofetch="autoFetch"
        :headers="['#', 'Name', 'Type', 'Discount', 'Invoice Number', 'Created Date']"
        :columns="['id', 'name', 'type', 'discount', null, 'created_at']"
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
                    <td>{{ item.name }}</td>
                    <td><span class="badge" :class="'badge-'+item.type.class">
                            {{ item.type.name }}
                        </span>
                    </td>
                    <td>
                        {{ item.discount }}
                    </td>
                    <td>
                        <a :href="item.invoice_show_url">{{ item.invoice }}</a>
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
import ListMixin from '../../../mixins/list.js';
import { EventBus }from '../../../EventBus.js';

import SearchBox from '../../../components/datatables/SearchBox.vue';
import DateRange from '../../../components/datepickers/DateRange.vue';
import ActionButton from '../../../components/buttons/ActionButton.vue';
import ViewButton from '../../../components/buttons/ViewButton.vue';
import FormRequest from '../../../components/forms/FormRequest.vue';

export default {

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