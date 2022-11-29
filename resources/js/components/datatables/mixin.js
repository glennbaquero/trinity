
/**
 * ==================================================================================
 * Data Table VUE component to be used together w/ Laravel's Pagination response
 *
 * Required VUE:
 * - resources/js/components/Pagination.vue;
 *
 * Required JS:
 * - Axios
 * 
 * ==================================================================================
 **/

import Pagination from './Pagination.vue';
import { URLHelper } from './URLHelper.js';
import ResponseMixin from '../../mixins/response.js';

export default {

    methods: {

        /**
         * Initialize data-table on pagination's mounted hook.
         *
         * @param mixed total
         */
        init: function(total = null) {

            /* Set default variables */
            this.url = this.fetchUrl;
            this.initSettings();
            this.total = total;

            /* Run fetch if autofetch is toggled */
            if(this.autofetch) {
                this.fetch();
            }
        },

        /**
         * Initialize fetch parameters
         */
        initSettings: function() {
            this.sort = this.defaultsort ? this.defaultsort : this.sort; //console.log(this.defaultsort);
            this.asc = this.defaultorder !== null ? this.defaultorder : this.asc; //console.log(this.defaultorder);
            this.total = this.per_page;
        },


        /**
         * ==================================================================================
         * @Methods
         * ==================================================================================
         **/

        /**
         * Returns URL with parameters.
         *
         * @param string url
         */
        getURL: function(url) {

            /* order by */
            url = this.sort ? URLHelper.addURLParam('sort', this.sort, url) : url;

            /* asc, desc */
            url = URLHelper.addURLParam('order', this.asc ? 'asc' : 'desc', url);

            /* paginate(total) */
            url = this.total > 0 ? URLHelper.addURLParam('total', this.total, url) : url;


            /* filters and search query */
            Object.keys(this.filters).forEach(key => {

                var val = this.filters[key];
                url = val ? URLHelper.addURLParam(key, val, url) : url;
            });

            return url;
        },

        /**
         * Update URL string
         * 
         * @param string url
         */
        setURL: function(url) {
            this.url = url;

            /* Add in settings */
            this.initSettings();
            /* Refresh list */
            this.fetch(this.url);
        },

        /**
         * Sorts table according to selected column.
         *
         * @param string col
         */
        setSort: function(col) {
            
            if(this.sort == col) {
                this.asc = !this.asc;

            } else {
                this.sort = col;
                this.asc = true;
            }

            this.fetch();
        },

        /**
         * Enable/Disable loading state
         * 
         * @param boolean loading
         */
        setLoading: function(loading) {
            this.loading = loading;

            /* Dispatch loading state */
            this.$emit('loading', this.loading);
        },

        /**
         * Fetch data from server.
         *
         * @param string link
         */
        fetch: function(link = null) { //console.log(link);

            /* Check loading */
            if(this.loading)
                return false;


            this.setLoading(true);

            if(!this.url) {
                this.setURL(this.fetchUrl);
            }
            

            /*
            |-------------------------------------------------------------
            | @var URL
            |-------------------------------------------------------------
            |
            | The first LINK is received from the pagination component.
            | The second FILTEREDURL is received from parent component
            | that handles filters and searches. The third FETCHURL is
            | the initial route hardcoded in blade.
            |
            | Prioritizes leftmost variable if it has a value.
            |
            */
            var url = link || this.url;

            axios.post(this.getURL(url))
                .then(response => {

                    /* Emit data for parent component to render */
                    var items = response.data.items;
                    /* Dispatch loaded item */
                    this.$emit('loaded', items);

                    /* Update pagination */
                    this.$refs.page.pagination = response.data.pagination;
                    /* Check item length */
                    this.empty = items.length ? false : true;


                }).catch(error => {
                    this.parseError(error);
                }).then(() => {
                    this.setLoading(false);
                });
        },
    },

    props: {
        fetchUrl: String,

        filters: Object,

        /**
         * Default column to sort
         */
        defaultsort: {
            type: String,
            default: null,
        },

        /**
         * Default order for the sorting
         *
         * Ascending = TRUE
         * Descending = FALSE
         */
        defaultorder: {
            type: Boolean,
            default: null,
        }, 

        /**
         * Enable autofetch
         */
        autofetch: {
            type: Boolean,
            default: true,
        },

        /**
         * Enables sorting on the table header
         */
        paginated: {
            type: Boolean,
            default: true
        },

        customEmpty: {
            type: Boolean,
            default: false
        },

        per_page: {
            default: 10,
        },
    },

    data: function() {
        return {
            loading: false,
            empty: false,
            
            url: null,

            total: null,
            sort: 'id',
            asc: true,

            selectAll: false,
            selectEmit: true,
        };
    },

    components: {
        'pagination': Pagination,
    },

    mixins: [ URLHelper, ResponseMixin ],
}