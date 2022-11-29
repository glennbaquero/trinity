import { EventBus } from '../EventBus.js';
import LoaderMixin from '../components/loaders/mixin.js';

import Loader from '../components/loaders/Loader.vue';
import DataTable from '../components/datatables/DataTable.vue';
import RefreshButton from '../components/datatables/RefreshButton.vue';
import FilterBox from '../components/datatables/FilterBox.vue';

export default {
	methods: {
        setup() {
            if(this.autofetch && this.has_fetched) {
                this.fetch();
            }
        },

        /**
         * Receives fetched data for rendering.
         */
        init(value) {

            this.has_fetched = true;

            /* Initialize default variables */
            this.items = value;
        },


        /**
         * ==================================================================================
         * @Methods
         * ==================================================================================
         **/

        /**
         * Search keyword.
         */
        filter(event, column = null) {
            if(!this.has_fetched) { return; }
            let data = {};

            if (column) {
                data[column] = event;
            } else {
                data = event;
            }

            this.filters = Object.assign(this.filters, data);
            this.fetch();
        },

        /**
         * Add filter to request and then fetch.
         */
        fetch() {
            this.$nextTick(() => {
                this.$refs.datatable.fetch();
                this.has_fetched = true;
            });
        },

        sync() {
            if (this.syncOnFetch) {
                EventBus.$emit('sync-tables');
                this.fetch();
            }
        },
    },

    mounted() {
        EventBus.$on('sync-tables', () => {
            this.has_fetched = false;
        });

        this.setup();
    },

	data() {
		return {
            items: [],
            filters: {},
            has_fetched: false,
		}
	},

	props: {
        fetchUrl: String,
        
        actionable: {
        	default: true,
        	type: Boolean,
        },

        syncOnFetch: {
            default: true,
            type: Boolean,
        },

        autoFetch: {
            default: true,
            type: Boolean,
        },
    },

    mixins: [ LoaderMixin ],

    components: {
        'loader': Loader,
        'datatable': DataTable,
        'refresh-button': RefreshButton,
        'filter-box': FilterBox,
    },
}