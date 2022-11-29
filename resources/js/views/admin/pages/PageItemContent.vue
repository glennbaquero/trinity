<template>
	<div class="row">
		<div v-if="isText" class="form-group col-sm-12 col-md-12">
			<label>{{ label }}</label>
			<input :value="value" :name="name" type="text" class="form-control input-sm">
		</div>

		<image-picker v-if="isImage"
		:value="value"
		class="form-group col-sm-12 col-md-12 mt-2"
        :label="label"
        :name="name"
        placeholder="Choose an Image"
		></image-picker>

		<text-editor v-if="isHtml"
		v-model="value"
		class="col-sm-12"
		:label="label"
		:name="name"
		row="3"
		></text-editor>
	</div>
</template>

<script type="text/javascript">
import ImagePicker from '../../../components/inputs/ImagePicker.vue';
import TextEditor from '../../../components/inputs/TextEditor.vue';
import StringHelper from '../../../mixins/string.js';

export default {
	computed: {
		isText() {
			return this.type === 'TEXT';
		},

		isHtml() {
			return this.type === 'HTML';
		},

		isImage() {
			return this.type === 'IMAGE';
		},


	},

	props: {
		type: {},
		name: String,
		value: {},
		label: {
			default: '',
		},
	},

	components: {
		'image-picker': ImagePicker,
		'text-editor': TextEditor,
	},

	mixins: [ StringHelper ],
}
</script>