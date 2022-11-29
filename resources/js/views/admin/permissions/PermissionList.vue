<template>				
	<form-request :submit-url="submitUrl" @load="load" sync-on-success>
		<card>
			<template v-for="(category, key) in categories">
				
				<div class="row">
					<div class="col-sm-12">
						<h4 class="font-weight-bold">
							<i :class="category.icon" class="mr-2"></i>
							{{ category.name }}
							<small>({{ category.description }})</small>
						</h4>
					</div>
				</div>

				<div class="row">
					<div v-for="permission in category.permissions" class="checkbox col-sm-12 col-md-4 mt-3">
						<label>
							<input :checked="inArray(permission.id, permission_ids)" 
							type="checkbox" name="permissions[]" :value="permission.id">
							{{ permission.description }}
						</label>
					</div>
				</div>

				<hr v-if="key < (categories.length - 1)">

			</template>

			<template v-slot:footer>
				<action-button type="submit" :disabled="loading" :loading="loading" class="btn-warning">Change Permissions</action-button>
            </template>
		</card>

		<loader :loading="loading"></loader>

	</form-request>
</template>

<script type="text/javascript">
import CrudMixin from 'Mixins/crud.js';
import ArrayHelpers from 'Mixins/array.js';

export default {
	methods: {
		fetchSuccess(data) {
			this.categories = data.categories ? data.categories : this.categories;
			this.permission_ids = data.permission_ids ? data.permission_ids : this.permission_ids;
		}
	},

	data() {
		return {
			categories: {},
			permission_ids: [],
		}
	},

	mixins: [ CrudMixin, ArrayHelpers ],
}
</script>