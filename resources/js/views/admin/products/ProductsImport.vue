<template>
	<div>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
				<template v-slot:header>Basic Information</template>

				<div class="row">
	                <div class="col col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
							<label for="">Select Manifest <a href="/files/product-sample-manifest.xls" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-download"></i> Sample Manifest</a></label>
							<input name="file" type="file" class="form-control input-sm" accept=".xlsx, .xls">
						</div>
					</div>

					<div class="col col-xs-12 col-sm-12 col-md-12">
					    <div class="form-group">
					        <label for="">Select Images<small>(Product images should match name in the selected manifest and the image file name should be unique per batch, it is case sensitive, use underscores instead of spaces)</small></label>
					        <input name="images[]" type="file" class="form-control input-sm" multiple accept=".jpeg, .png, .jpg">
					    </div>
					</div>
				</div>
					
				<template v-slot:footer>
					<action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
				</template>
			</card>

			<loader :loading="loading"></loader>
			
		</form-request>
	</div>
</template>

<script type="text/javascript">
import { EventBus }from '../../../EventBus.js';
import CrudMixin from 'Mixins/crud.js';
import ActionButton from 'Components/buttons/ActionButton.vue';

export default {
	data() {
		return {
			item: null
		}
	},

	components: {
		'action-button': ActionButton,
	},

	mixins: [ CrudMixin ],
}
</script>