<template>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
            <card>
                <template v-if="!noTitle" v-slot:header>
                    Basic Information
                </template>
                
                <div class="row">
                    <selector class="col-sm-12"
                        v-model="item.app"
                        name="app"
                        label="App"
                        :items="apps"
                        item-value="value"
                        item-text="label"
                        placeholder="Please select app where to show"
                    ></selector>
                </div>
                    
                <div class="row">
                    <selector class="col-sm-12"
                        v-model="item.faq_category_id"
                        name="faq_category_id"
                        label="Category/Section"
                        :items="categories"
                        item-value="id"
                        item-text="name"
                        placeholder="Please select category/section"
                    ></selector>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12">
                        <label>Question</label>
                        <input v-model="item.question" name="question" type="text" class="form-control input-sm">
                    </div>
                </div>

                <div class="row">
                    <text-editor
                    v-model="item.answer"
                    class="col-sm-12"
                    label="Answer"
                    name="answer"
                    row="5"
                    ></text-editor>
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
                    :message="'Are you sure you want to archive FAQ #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore FAQ #' + item.id + '?'"
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
            this.apps = data.apps ? data.apps : this.apps;
            this.categories = data.categories ? data.categories : this.categories;
        }
    },

    props: {
        pageItem: {},
        noTitle: {
            default: false,
        },
    },

    data() {
        return {
            apps: [],
            categories: []
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