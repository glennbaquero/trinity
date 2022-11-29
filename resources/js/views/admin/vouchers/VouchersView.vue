<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<div class="row">
					<selector class="col-sm-6"
					v-model="item.voucher_type"
					name="voucher_type"
					label="Voucher type"
					:items="voucher_types"
                	item-value="value"
                    item-text="name"
					placeholder="Please select a voucher type"
					></selector>

					<div class="form-group col-sm-6">
						<label>Name</label>
						<input v-model="item.name" name="name" type="text" class="form-control">
					</div>
				</div>

				<div class="row">
					<selector class="col-sm-6"
					v-model="item.type"
					name="type"
					label="Type"
					:items="types"
                	item-value="value"
                    item-text="name"
					placeholder="Please select a type"
					></selector>

					<div class="form-group col-sm-6">
						<label>Discount <small class="text-muted">{{ discount_text }}</small></label>
						<input v-model="item.discount" name="discount" type="number" class="form-control">
					</div>	
				</div>

				<div class="row">

					<selector class="col-sm-6"
					ref="type"
					v-model="item.user_type"
					name="user_type"
					label="User Type"
					:items="user_types"
                	item-value="value"
                    item-text="name"
                    label-note="(user who can use the voucher)"
                    label-note-class="text-muted"
					placeholder="Please select user type"
					></selector>

					<div class="form-group col-sm-6">
						<label>Code</label>
						<input v-model="item.code" name="code" type="text" class="form-control">
					</div>
				</div>
				
				<div class="row">
					<div class="form-group col-sm-6">
						<label>Days of validity</label>
						<input v-model="item.valid_days" @keypress="isNumber($event)" type="number" name="valid_days" class="form-control" >
					</div>

					<div class="form-group col-sm-6">
						<label>Max Usage</label>
						<input v-model="item.max_usage" name="max_usage" type="number" class="form-control">
					</div>
				</div>

				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                
	                <action-button
	                v-if="item.archiveUrl && item.restoreUrl"
	                color="btn-danger"
	                alt-color="btn-warning"
	                :action-url="item.archiveUrl"
	                :alt-action-url="item.restoreUrl"
	                label="Archive"
	                alt-label="Restore"
	                :show-alt="item.deleted_at"
	                confirm-dialog
	                title="Archive Item"
	                alt-title="Restore Item"
	                :message="'Are you sure you want to archive voucher #' + item.id + '?'"
	                :alt-message="'Are you sure you want to restore voucher #' + item.id + '?'"
	                :disabled="loading"
	                @load="load"
	                @success="fetch"
	                @error="fetch"
	                ></action-button>
				</template>
			</card>

			<loader :loading="loading"></loader>
			
		</form-request>
	</div>
</template>

<script type="text/javascript">
import { EventBus }from '../../../EventBus.js';
import CrudMixin from '../../../mixins/crud.js';

import ActionButton from '../../../components/buttons/ActionButton.vue';
import Select from '../../../components/inputs/Select.vue';
import ImagePicker from '../../../components/inputs/ImagePicker.vue';
import TextEditor from '../../../components/inputs/TextEditor.vue';
import Datepicker from '../../../components/datepickers/Datepicker.vue';


export default {
	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.types = data.types ?  data.types : this.types;
			this.voucher_types = data.voucher_types ?  data.voucher_types : this.voucher_types;
			this.user_types = data.user_types ?  data.user_types : this.user_types;
		},

		update() {
			this.fetch();
		},

	    isNumber: function(evt) {
	      evt = (evt) ? evt : window.event;
	      var charCode = (evt.which) ? evt.which : evt.keyCode;
	      if ((charCode > 31 && (charCode < 48 || charCode > 57)) || charCode === 46) {
	        evt.preventDefault();;
	      } else {
	        return true;
	      }
	    }

	},

	watch : {

		// max percentage discount limit to 100
     	'item.discount': function (newVal, oldVal){
     		var amount = 0;
     		var percentage = 1;
     		
        	if(this.item.type == percentage ) {
        		 if(this.item.discount >= 100) {
        		 	this.item.discount = 100;
        		 }
        	}
     	},

     	'item.type': function (newVal, oldVal){
	   		var amount = 0;
     		var percentage = 1;
     		
        	if(this.item.type == percentage ) {
        		this.discount_text = "(discount on percentage %)";
    		 	if(this.item.discount >= 100) {
        		 	this.item.discount = 100;
        		 }
        	}

    		if(this.item.type == amount ) {
        		this.discount_text = "(discount on ₱)"
        	}

     	},

	},

	data() {
		return {
			// Containers
			item: [],
			types : [],
			user_types : [],
			voucher_types: [],

			//Text Display
			discount_text : "",
		}
	},

	components: {
		'action-button': ActionButton,
		'selector': Select,
		'image-picker': ImagePicker,
		'text-editor': TextEditor,
		'date-picker': Datepicker,
	},

	mixins: [ CrudMixin ],
}
</script>