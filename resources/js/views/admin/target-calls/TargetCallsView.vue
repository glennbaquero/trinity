<template>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
            <card>
                <template v-if="!noTitle" v-slot:header>
                    Basic Information
                </template>

                <div class="row">
                    <selector class="col-sm-12"
                        v-model="item.medical_representative_id"
                        name="medical_representative_id"
                        label="Medical Representative"
                        :items="medreps"
                        item-value="id"
                        item-text="fullname"
                        placeholder="Please select a medical representative"
                    ></selector>
                </div>
                
                <div class="row">
                    <selector class="col-sm-4"
                    v-model="item.month"
                    name="month"
                    label="Month"
                    :items="months"
                    item-value="value"
                    item-text="name"
                    empty-text="None"
                    placeholder="Please select a month"
                    ></selector>

                    <div class="form-group col-sm-4 col-md-4">
                        <label>Year</label>
                        <input v-model="item.year" name="year" type="text" class="form-control input-sm" readonly>
                    </div>

                    <div class="form-group col-sm-4 col-md-4">
                        <label>Target</label>
                        <input v-model="item.target" name="target" type="text" class="form-control input-sm">
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
                    :message="'Are you sure you want to archive Brand #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore Brand #' + item.id + '?'"
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
    mounted: function() {
        this.item.year = this.year; 
    },
    methods: {
        fetchSuccess(data) {
            this.item = data.item ? data.item : this.item;
        },
    },

    props: {
        pageItem: {},
        noTitle: {
            default: false,
        },

        medreps: Array,

        year: {
            type: String,
            default: null,
        },

        months: {
            type: Array,
            default: [],
        },        

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