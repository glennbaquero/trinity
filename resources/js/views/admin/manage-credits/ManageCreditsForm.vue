<template>
<div>
	<form-request 
	:params="params"
	class="mt-3" :submit-url="submitUrl" @load="load" @success="reload" sync-on-success>
	    <card>
	        <template v-slot:header>Manage Credits Information</template>               

	        <div class="row">

	            <selector
	            class="col-sm-12 col-md-12"
	            :items="actions"
	            v-model="params.type"
	            name="type"
	            label="Action type"
	            item-value="type"
	            item-text="name"
	            required
	            empty-text="Please select action type"
	            ></selector>

	            <div class="form-group col-sm-12 col-md-12">
	                <label>Value</label>
	                <input 
	                @change="test()"
	                v-model="params.value" 
	                placeholder="ex: 100" 
	                name="value" type="text" class="form-control input-sm"
	                required>
	                <template v-if="!canUpdate">
		                <div class="ml-t">
		                	<label class="text-danger">When deducting credits value most not be greater than {{ currentCredits }}</label>
		                </div>
	                </template>
	            </div>

	            <div class="form-group col-sm-12 col-md-12">
	                <label>Message</label>
	                <input 
	                v-model="params.message" 
	                name="message" type="text" class="form-control input-sm"
	                required>
	            </div>

	        </div>
	        
	        <template v-slot:footer>
	            <action-button type="submit" :disabled="loading || !canUpdate" class="btn-primary">Save Changes</action-button>
	            
	        </template>
	    </card>

	    <loader :loading="loading"></loader>

	</form-request>	
</div>
</template>

<script>

import CrudMixin from 'Mixins/crud.js';
import ResponseHandler from 'Mixins/response.js';

import ActionButton from 'Components/buttons/ActionButton.vue';
import Select from 'Components/inputs/Select.vue';

export default {

	props: {
		currentCredits: {
			type: [Number],
			default: 0,
		},
	},

    components: {
        'action-button': ActionButton,
        'selector': Select,
    },

    computed: {
    	canUpdate() {
    		if(this.params.type == 2 && this.params.value > this.currentCredits) {
    			return false;
    		} 
    		return true;
    	},
    },

    data() {
        return {
            actions: [
            	{'type': 1, 'name': 'Add'},
            	{'type': 2, 'name': 'Deduct'},            	
            ],

            params: {
            	type: 1,
            	value: null,
            	message: null,
            },
        }
    },


    mixins: [ CrudMixin, ResponseHandler ],

    methods: {
    	reload() {
    		setTimeout(() => {
    			window.location.reload();
    		}, 1000);
    	},
    },
}
</script>