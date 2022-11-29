<template>
    <div>
        <filter-box>
            <template v-slot:left>
                <refresh-button @click="fetch"></refresh-button>
                    
                <date-range
                @change="filter($event)"
                ></date-range>

                <selector 
                class="mt-2" 
                v-if="filterTypes"
                :items="filterTypes"
                @change="filter($event, 'subject_type')"
                placeholder="Filter by type"
                no-search
                ></selector>
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

        :fetch-url="fetchUrl"
        :actionable="actionable"
        :selectable="false"

        @loaded="init"
        @loading="load"
        >

            <template v-slot:body>
                <tr v-for="item in items">
                    <td>{{ item.id }}</td>
                    <td v-if="showSubject">
                        <span class="badge badge-secondary">{{ item.subject_type }}</span>
                        <p v-if="item.subject_name">{{ item.subject_name }}</p>
                    </td>
                    <td>{{ item.name }}</td>
                    <td v-if="!hideCauser">
                        <a :href="item.show_causer" class="btn btn-link" :class="!item.show_causer ? 'disabled' : ''" target="_blank">
                            {{ item.caused_by }}
                        </a>
                    </td>
                    <td>{{ item.created_at }}</td>
                    <td v-if="actionable">
                        <view-button :href="item.showUrl"></view-button>
                    </td>
                </tr>
            </template>

        </datatable>

        <loader :loading="loading"></loader>
    </div>
</template>

<script type="text/javascript">
import ListMixin from 'Mixins/list.js';

import SearchBox from 'Components/datatables/SearchBox.vue';
import ViewButton from 'Components/buttons/ViewButton.vue';
import DateRange from 'Components/datepickers/DateRange.vue';
import Select from 'Components/inputs/Select.vue';

export default {
    computed: {
        headers() {
            let array = ['#'];

            if (this.showSubject) {
                array.push('Subject');
            }

            array.push('Description');

            if (!this.hideCauser) {
                array.push('Caused By');
            }

            array.push('Created Date');

            return array;
        },

        columns() {
            let array = ['id'];

            if (this.showSubject) {
                array.push('subject_type');
            }

            array.push('description');

            if (!this.hideCauser) {
                array.push('causer_type');
            }

            array.push('created_at');

            return array;
        },
    },

    props: {
        filterTypes: {},
        
        actionable: {
            default: false,
            type: Boolean,
        },

        hideCauser: {
            default: false,
            type: Boolean,
        },

        showSubject: {
            default: false,
            type: Boolean,
        },
    },

    mixins: [ ListMixin ],

    components: {
        'search-box': SearchBox,
        'view-button': ViewButton,
        'date-range': DateRange,
        'selector': Select,
    },
}
</script>