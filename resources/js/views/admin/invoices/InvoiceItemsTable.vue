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
        :headers="['#', 'Product Image', 'Name', 'Doctor', 'Prescription', 'Consultation', 'Size', 'Quantity', 'Price']"
        :columns="['id', null, null, 'doctor_id', null, 'consultation_id', null, 'quantity', 'price']"
        :filters="filters"
        
        :defaultsort="'id'"
        :defaultorder="false"

        :fetch-url="fetchUrl"
        :actionable="false"
        :selectable="false"

        @loaded="init"
        @loading="load">

            <template v-slot:body>
                <tr v-for="item in items">
                    <td>{{ item.id }}</td>
                    <td>
                        <img class="rounded img-fluid img-thumbnail" :src="item.product_image" :alt="item.product_number" style="width:100px;">
                    </td>
                    <td>{{ item.name }}</td>
                    <td>
                        <template v-if="item.doctor_id">
                            <a :href="item.doctor.showUrl">{{ item.doctor.name }}</a>
                        </template>
                        <template v-else>N/A</template>
                    </td>
                    <td>
                        <template v-if="item.prescription_path">
                            <a :href="item.prescription_path" target="_blank"><img class="rounded img-fluid img-thumbnail" :src="item.prescription_path" style="width:100px;"></a>
                        </template>
                        <template v-else>N/A</template>                        
                    </td>
                    <td>
                        <template v-if="item.consultation_id">
                            <a :href="item.consultation_show_url" target="_blank">{{ item.consultation }}</a>
                        </template>
                        <template v-else>N/A</template>                        
                    </td>
                    <td>{{ item.size }}</td>                    
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.price }}</td>
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
import ActionButton from '../../../components/buttons/ActionButton.vue';
import ViewButton from '../../../components/buttons/ViewButton.vue';
import Select from '../../../components/inputs/Select.vue';
import FormRequest from '../../../components/forms/FormRequest.vue';


export default {
    methods: {
        update() {
            EventBus.$emit('product-count');
            this.fetch();
        },
    },

    props: {

    },

    mixins: [ ListMixin ],

    components: {
        'search-box': SearchBox,
        'view-button': ViewButton,
        'action-button': ActionButton,
        'selector': Select,
        'form-request': FormRequest,
    },
}
</script>