<template>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
            <card>
                <template v-slot:header>
                    Basic Information
                </template>
                    
                <div class="row">

                    <selector class="col-sm-12"
                    v-model="item.specialization_id"
                    name="specialization_id"
                    label="Specialization"
                    :items="specializations"
                    item-value="id"
                    item-text="name"
                    empty-text="None"
                    placeholder="Please select a specialization"
                    ></selector>                    

                    <div class="form-group col-sm-12 col-md-12">
                        <label>Name</label>
                        <input v-model="item.name" name="name" type="text" class="form-control input-sm">
                    </div>
    
                    <text-editor
                    v-model="item.description"
                    class="col-sm-12"
                    label="Description"
                    name="description"
                    row="5"
                    ></text-editor>
    
                    <image-picker
                    :value="item.image_path"
                    class="form-group col-sm-12 col-md-12 mt-2"
                    label="Image"
                    name="image_path"
                    placeholder="Choose a File"
                    :required="item.image_path ? false: true"
                    ></image-picker>                    
    
                </div>

                <template v-slot:footer>
                    <action-button type="submit" :disabled="loading" class="btn-primary">Save Changes</action-button>
                
                    <action-button
                    v-if="item.archiveUrl && item.restoreUrl"
                    color="btn-danger"
                    alt-color="btn-warning"
                    :action-url="item.archiveUrl"
                    :alt-action-url="item.restoreUrl"
                    label="Archive Product Parent"
                    alt-label="Restore Product Parent"
                    :show-alt="item.deleted_at"
                    confirm-dialog
                    title="Archive Item"
                    alt-title="Restore Item"
                    :message="'Are you sure you want to archive Product Parent #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore Product Parent #' + item.id + '?'"
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
            this.specializations = data.specializations ? data.specializations : this.specializations
        }
    },

    components: {
        'action-button': ActionButton,
        'selector': Select,
        'text-editor': TextEditor,
        'image-picker': ImagePicker,
        'date-picker': Datepicker,
    },

    data() {
        return {
            specializations: []
        }
    },

    mixins: [ CrudMixin ],
}
</script>