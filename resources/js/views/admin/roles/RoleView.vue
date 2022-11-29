<template>
	<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
		<card>
	        <template v-slot:header>Basic Information</template>

			<div class="row">
				<div class="form-group col-sm-12">
					<label>Name</label>
					<input v-model="item.name" name="name" type="text" class="form-control input-sm">
				</div>
			</div>

			<div class="row">
				<div class="form-group col-sm-12">
					<label>Description</label>
					<textarea name="description" type="text" class="form-control input-sm">{{ item.description }}</textarea>
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
                :message="'Are you sure you want to archive Role #' + item.id + '?'"
                :alt-message="'Are you sure you want to restore Role #' + item.id + '?'"
                :disabled="loading"
                @load="load"
                @success="fetch"
                ></action-button>
			</template>
		</card>
		
		<loader :loading="loading"></loader>

	</form-request>
</template>

<script type="text/javascript">
import CrudMixin from 'Mixins/crud.js';
import ActionButton from 'Components/buttons/ActionButton.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
		}
	},

	components: {
		'action-button': ActionButton,
	},

	mixins: [ CrudMixin ],
}
</script>