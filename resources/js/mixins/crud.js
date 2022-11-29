import FetchMixin from './fetch.js';
import { EventBus } from '../EventBus.js';
import FormRequest from '../components/forms/FormRequest.vue';
import Card from '../components/containers/Card.vue';
import ActionButton from '../components/buttons/ActionButton.vue';

export default {
	methods: {
		fireEmitters() {
            EventBus.$emit('sync-tables');
        },
	},

	data() {
		return {
			item: {},
		}
	},

	props: {
		submitUrl: String,
		fetchUrl: String,
	},

	components: {
		'form-request': FormRequest,
        'card': Card,
        'action-button': ActionButton,
	},

	mixins: [ FetchMixin ],
}