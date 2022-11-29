<template>
		<form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
			<card>
		        <template v-if="!noTitle" v-slot:header>
		        	Basic Information
		        </template>
		        <div class="row">
		        	<selector class="col-sm-6"
		        	v-model="item.for_doctor"
		        	name="for_doctor"
		        	label="App Availability"
		        	:items="apps"
		        	item-value="id"
		        	item-text="name"
		        	empty-text="None"
		        	placeholder="Please select a category"
		        	@change="relatedArticleFetch()"
		        	></selector>

		        	<selector class="col-sm-6"
		        	v-model="item.category_id"
		        	name="category_id"
		        	label="Article Category"
		        	:items="article_categories"
					placeholder="Please select a category"
		        	item-value="id"
		        	item-text="name"
		        	empty-text="None"
		        	></selector>
		        </div>
				<div class="row">
					
					<div class="form-group col-sm-6 col-md-6">
						<label>Title</label>
						<input v-model="item.title" name="title" type="text" class="form-control input-sm">
					</div>
					
					<date-picker
					v-model="item.date"
					class="form-group col-sm-12 col-md-6"
					label="Date"
					name="date"
					placeholder="Choose a date"
					></date-picker>

				</div>

			    <div class="row">


			    	<text-editor
			    	v-model="item.overview"
			    	class="form-group col-sm-12 col-md-12"
			    	label="Overview"
			    	name="overview"
			    	row="3"
			    	></text-editor>

			    	<image-picker
			    	class="form-group col-sm-12 col-md-12 "
			    	:value="item.renderImage"
			    	label="Banner"
			    	name="image_path"
			    	placeholder="Choose a File"
			    	></image-picker>

			    	<div class="form-group col-sm-12 col-md-12 ">
			    		<label for="downloadFile">Downloadable File for Doc</label> 
			    		<a class="btn btn-primary btn-sm" target="_blank" :href="item.downloadableUrl" v-show="show"><i class="fa fa-eye"></i> View Downloadable File</a>
	                    <div class="input-group">
	                      	<div class="custom-file">
	                        	<input type="file" class="custom-file-input" name="downloadable_path" accept=".pdf, .doc, .docx" @change="filesSelected">
	                        	<label class="custom-file-label" for="downloadFile">{{ file ? file[0].name : 'Choose File' }}</label>
	                      	</div>
                      	</div>
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
	                :message="'Are you sure you want to archive Article #' + item.id + '?'"
	                :alt-message="'Are you sure you want to restore Article #' + item.id + '?'"
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
import TextEditor from 'Components/inputs/TextEditor.vue';
import ImagePicker from 'Components/inputs/ImagePicker.vue';
import Datepicker from 'Components/datepickers/Datepicker.vue';

export default {
	methods: {
		fetchSuccess(data) {
			this.item = data.item ? data.item : this.item;
			this.articles = data.articles ? data.articles : this.articles;
			this.article_categories = data.article_categories ? data.article_categories : this.article_categories;
			this.relatedArticleFetch();
		},

		filesSelected(e) {
			this.file = e.target.files
		},

		relatedArticleFetch() {
			this.availableArticles = [];
			_.each(this.articles, (article) => {
				if(article.for_doctor == this.item.for_doctor) {
					this.availableArticles.push(article);
				}
			})
		}
	},

	props: {
		pageItem: {},
		noTitle: {
			default: false,
		},

		variants: Array,
		show: Boolean
	},

	data() {
		return {
			apps: [
				{
					'id' : 1,
					'name' : 'Care'
				},
				{
					'id' : 2,
					'name' : 'Doc'	
				}
			],

			articles: [],
			availableArticles: [],
			article_categories: [],

			file: null
		}
 	},

	components: {
		'action-button': ActionButton,
		'selector': Select,
		'text-editor': TextEditor,
        'image-picker': ImagePicker,
		'date-picker': Datepicker,
	},

	mixins: [ CrudMixin ],
}
</script>