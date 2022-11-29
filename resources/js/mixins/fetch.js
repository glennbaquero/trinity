import LoaderMixin from '../components/loaders/mixin.js';
import Loader from '../components/loaders/Loader.vue';

export default {
	mounted() {
		if (this.fetchUrl && this.autoFetch && !this.has_fetched) {
			this.fetch();
		}
	},

	methods: {
        fetch() {
            this.load(true);

            axios.post(this.fetchUrl)
            .then(response => {
                this.fireEmitters();
                this.fetchSuccess(response.data);
            }).catch(error => {

            }).then(() => {
                this.has_fetched = true;
                this.load(false);
            });
        },

        fireEmitters() {
            // fire events here
        },

        fetchSuccess(data) {
        	console.log(data);
        },
    },

    data() {
        return {
            has_fetched: false,
        }
    },

    props: {
		fetchUrl: String,

        autoFetch: {
            default: true,
            type: Boolean,
        },
	},

	components: {
		'loader': Loader,
	},

	mixins: [ LoaderMixin ],
}