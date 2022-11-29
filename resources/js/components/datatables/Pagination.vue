<template>

    <div class="dataTables_wrapper" v-if="visible">
        <div class="row">
                
            <div v-show="pagination.total > limits[0]" class="col-sm-6">
                <!-- /*-----------------------------------------------------------
                | Item Limit
                |-----------------------------------------------------------*/ -->
                <div class="dataTables_length">
                    <label class="pagination-select-label">
                        Show &nbsp;
                        <select ref="pageLimit" v-model="total" @change="fetch()" class="pagination-select form-control input-sm">

                            <template v-for="(limit, index) in limits">
                                <option :value="limit" v-show="pagination.total > limits[index > 0 ? index - 1 : 0]">{{ limit }}</option>
                            </template>

                        </select>
                        &nbsp; entries
                    </label>
                </div>

            </div>

            <div v-show="pagination.last !== 1" class="col-sm-6">
                
                <!-- /*-----------------------------------------------------------
                | Pagination Bar
                |-----------------------------------------------------------*/ -->
                <ul class="pagination float-right" style="cursor:pointer">

                    <li class="page-item"><a class="page-link" @click.prevent="prev()">&laquo;</a></li>

                    <li class="page-item" :class="pagination.current === 1 ? 'active' : ''"  @click.prevent="page(1)" v-show="pagination.last !== 1">
                        <a class="page-link" href="#">1</a>
                    </li>

                    <template v-for="n in pagination.last" v-if="pagination.last > 1">
                        <template v-if="Math.abs(n-pagination.current) > limit && Math.abs(n-pagination.current) < (limit+2)">
                            <li class="page-item"><a class="page-link" href="javascript:void(0)">...</a></li>
                        </template>

                        <template v-else-if="Math.abs(n-pagination.current) < (limit+2) && (n !== 1 && n !== pagination.last)">
                            <li class="page-item" :class="{ 'active':n == pagination.current }"><a class="page-link" @click.prevent="page(n)" v-text="n"></a></li>
                        </template>
                    </template>

                    <template v-if="pagination.last == 1">
                        <li class="page-item"><span>...</span></li>
                    </template>

                    <li class="page-item" :class="pagination.last === pagination.current ? 'active' : ''" @click.prevent="page(pagination.last)" v-show="pagination.last !== 1">
                        <a class="page-link" href="javascript:void(0)">{{ pagination.last }}</a>
                    </li>

                    <li class="page-item"><a class="page-link" @click.prevent="next()">&raquo;</a></li>

                </ul>
            </div>
        </div>


        <div class="row d-flex align-items-center mt-3">
            
            <div class="col-sm-6">
                 <!-- /*-----------------------------------------------------------
                | Show Total
                |-----------------------------------------------------------*/ -->
                <p class="text-muted hidden-sm hidden-xs">
                    Showing 
                    {{ pagination.from == null ? 0 : pagination.from }} to {{ pagination.to == null ? 0 : pagination.to }} of {{ pagination.total == null ? 0 : pagination.total }}
                </p>
            </div>

            <div v-show="pagination.last !== 1" class="col-sm-6">
                <!-- /*-----------------------------------------------------------
                | Jump to Page
                |-----------------------------------------------------------*/ -->
                <div class="dataTables_length text-right">
                    <label>
                        &emsp; Go to Page: &nbsp;
                        <select ref="pageNumber" v-model="current" @change="page()" class="form-control input-sm">
                            <option v-for="x in pagination.last" :value="x" v-text="x"></option>
                        </select>
                    </label>
                </div>
            </div>

        </div>

    </div>

</template>
<script>

/**
 * ==================================================================================
 * Pagination VUE component
 *
 * Component of:
 * - resources/js/components/DataTable.vue;
 * 
 * ==================================================================================
 **/

import { URLHelper } from './URLHelper.js';

export default {

    props: {
        fetchUrl: String,

        autofetch: {
            type: Boolean,
            default: true
        },

        visible: {
            type: Boolean,
            default: true
        },

        per_page: {
            default: 10,
        },
    },

    mixins: [ URLHelper ],

    data() {
        return {
            total: 10,
            limit: 2,
            current: 1,
            pagination: {},

            limits: [
                10, 15, 20,
            ],
        };
    },

    mounted() {
        /* Initialize component */
        this.init();
        
        /* Run fetch if autofetch is toggled */
        if(this.autofetch) {
            this.fetch();
        }
    },

    watch: {
        pagination(value) {
            this.current = value.current;
            this.limits = value.limits;
        },

        per_page(value) {
            this.total = value;
        },
    },

    methods: {

        init() {
            this.total = this.per_page;
            /* Set default IDs */
            this.setIDs();
        },


        /**
         * ==================================================================================
         * @Methods
         * ==================================================================================
         **/

        /**
         * Set unique ID of select box
         */
        setIDs() {
            
            if(this.visible) {
                this.$refs.pageLimit.id = this._uid;
                this.$refs.pageNumber.id = this._uid;
            }
        },

        /**
         * Initializes fetch of parent data-table component.
         */
        fetch() {
            this.$emit('total', this.total);
            this.$emit('paginate', null);
        },


        /**
         * ==================================================================================
         * @Pagination
         * ==================================================================================
         **/

        /**
         * Go to previous page.
         */
        prev() {

            /* Check if prev page exists */
            if(!this.pagination.prevpage)
                return false;

            this.$emit('paginate', URLHelper.addURLParam('page', (this.pagination.current - 1), this.fetchUrl));
        },

        /**
         * Go to specific page.
         *
         * @param int index
         */
        page(index = null) {

            let page = index || this.current;

            /* Check if on current page */
            if(this.pagination.current == page)
                return false;

            this.$emit('paginate', URLHelper.addURLParam('page', page, this.fetchUrl));
        },

        /**
         * Go to next page.
         */
        next() {

            /* Check if next page exists */
            if(!this.pagination.nextpage)
                return false;

            this.$emit('paginate', URLHelper.addURLParam('page', (this.pagination.current + 1), this.fetchUrl));
        }
    }

}
</script>

<style scoped>
.pagination-select {
    display: inline-block;
    max-width: 80px;
}

.pagination-select-label {
    width: 100%;
}
</style>