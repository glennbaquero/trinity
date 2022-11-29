<template>
	<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
		<card>
	        <template v-if="!noTitle" v-slot:header>
	        	Basic Information
	        </template>

			<div class="row">
				<div class="form-group col-sm-12 col-md-6">
					<label>Name</label>
					<input v-model="item.name" name="name" type="text" class="form-control input-sm">
				</div>

				<div class="form-group col-sm-12 col-md-6">
					<label>Slug <small class="text-danger">(Warning: editing this may cause issues)</small></label>
					<input v-model="item.slug" name="slug" type="text" class="form-control input-sm" :readonly="disabled">
				</div>

				<selector class="col-sm-12 col-md-6"
				v-model="item.page_id"
				name="page_id"
				label="Page"
				label-note="(Warning: editing this may cause issues)"
				:items="pages"
				item-value="id"
				item-text="name"
				empty-text="None"
				placeholder="Please select a page"
				:readonly="disabled"
				></selector>

				<selector class="col-sm-12 col-md-6"
				v-model="item.type"
				name="type"
				label="Type"
				label-note="(Warning: editing this may cause issues)"
				:items="types"
				placeholder="Please select a type"
				:readonly="disabled"
				></selector>
			</div>

			<page-item-content :type="item.type" name="content" label="Content" :value="item.content"></page-item-content>

			<template v-slot:footer>
				<a :href="item.parentUrl" target="_blank" class="btn btn-secondary">View Parent</a>

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
                :message="'Are you sure you want to archive Page Item #' + item.id + '?'"
                :alt-message="'Are you sure you want to restore Page Item #' + item.id + '?'"
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
import Select from 'Components/inputs/Select.vue';
import PageItemContent from './PageItemContent.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.pages = data.pages ? data.pages : this.pages;
			this.types = data.types ? data.types : this.types;
		}
	},

	data() {
		return {
			types: [],
			pages: [],
		}
	},

	props: {
		pageItem: {},
		noTitle: {
			default: false,
		},
		disabled: {
			type: Boolean,
			default: true
		}
	},

	components: {
		'action-button': ActionButton,
		'selector': Select,
		'page-item-content': PageItemContent,
	},

	mixins: [ CrudMixin ],
}
</script>