<template>

    <div class="">
        <div class="table-responsive">

            <table class="table table-hover table-striped table-bordered text-center">

                <!-- /*---------------------------------------------------
                | <thead> Used for sorting and mass selection
                |---------------------------------------------------*/ -->
                <thead>
                    <tr>
                        <th v-if="selectable">
                            <input type="checkbox" v-model="selectAll">
                        </th>
                        <th v-for="(header,index) in headers">
                            <span v-if="sortable && columns[index]"
                            class="sorter" @click="setSort(columns[index])">
                                {{ header }} &nbsp; 
                                <i :class="renderSort(columns[index])"></i>
                            </span>
                            <span v-else>
                                {{ header }}
                            </span>
                        </th>
                        <th v-show="actionable">Actions</th>
                        <th v-if="selectable">
                            <input type="checkbox" v-model="selectAll">
                        </th>
                    </tr>
                </thead>
                
                <!-- /*---------------------------------------------------
                | <slot> body
                |---------------------------------------------------------
                |
                | Emit fetched database data to parent component where
                | they can use this slot to render that data
                |
                */ -->
                <tbody>
                    <slot name="body"></slot>
                </tbody>

                <tbody v-if="empty && !customEmpty">
                    <tr>
                        <td :colspan="colspan">No Items Found</td>
                    </tr>
                </tbody>

                <!-- /*---------------------------------------------------
                | <tfoot> Used for sorting and mass selection
                |---------------------------------------------------*/ -->
                <!-- <tfoot>
                    <tr>
                        <th v-if="selectable"><input type="checkbox" v-model="selectAll"></th>
                        <th v-for="(header, index) in headers">
                            <span class="sorter" @click="setSort(columns[index])" v-if="sortable">
                                {{ header }} &nbsp; <i :class="renderSort(columns[index])"></i>
                            </span>
                            <span v-else>
                                {{ header }}
                            </span>
                        </th>
                        <th v-show="actionable">Actions</th>
                    </tr>
                </tfoot> -->

            </table>
        </div>

        <pagination ref="page"
        :fetch-url="url"
        :autofetch="autofetch"
        :visible="paginated"
        :per_page="per_page"
        
        @total="init"
        @paginate="fetch"
        ></pagination>

    </div>

</template>
<script>
import DataMixin from './mixin.js';

export default {
    methods: {

        /**
         * ==================================================================================
         * @Render
         * ==================================================================================
         **/

        /**
         * Returns appropriate sort icon.
         *
         * @param string col
         */
        renderSort: function(col) {

            if(this.sort == col) {
                return this.asc ? 'fa fa-sort-up active' : 'fa fa-sort-down active';
            }

            return 'fa fa-sort';
        },
    },


    watch: {
        selectAll: function(val) {
            
            if(this.selectEmit) {
                this.$emit('select', val);
            }

            this.selectEmit = true;
        }
    },

    computed: {
        colspan: function() {
            return this.headers.length + (this.actionable ? 1 : 0) + (this.selectable ? 1 : 0);
        }
    },

    props: {
        /**
         * Adds a "Action" column at the end of the table
         */
        actionable: {
            type: Boolean,
            default: true
        },

        /**
         * Adds a checkbox column at the start of the table
         */
        selectable: {
            type: Boolean,
            default: false
        },

        /**
         * Table header columns
         */
        headers: Array,
        
        columns: Array,

        /**
         * Enables sorting on the table header
         */
        sortable: {
            type: Boolean,
            default: true
        },
    },

    mixins: [ DataMixin ],
}
</script>