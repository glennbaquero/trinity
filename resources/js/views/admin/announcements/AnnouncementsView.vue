<template>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
            <card>
                <template v-if="!noTitle" v-slot:header>
                    Basic Information
                </template>
                
                <div class="row">
                    <selector class="col-sm-6"
                        v-model="item.announce_to"
                        name="announce_to"
                        label="App"
                        :items="apps"
                        item-value="value"
                        item-text="label"
                        placeholder="Please select app where to show"
                    ></selector>
                    <selector class="col-sm-6"
                        v-model="item.announcement_type_id"
                        name="announcement_type_id"
                        label="Type"
                        :items="types"
                        item-value="id"
                        item-text="name"
                        placeholder="Choose Announcement Type"
                    ></selector>
                </div>
                    
                <div class="row">
                    <date-picker
                    v-model="item.event_date"
                    class="form-group col-sm-12 col-md-12"
                    label="Event date"
                    name="event_date"
                    placeholder="Choose event date"
                    ></date-picker>

                    <div class="form-group col-sm-12 col-md-12">
                        <label>Title</label>
                        <input v-model="item.title" name="title" type="text" class="form-control input-sm">
                    </div>
                </div>

                <div class="row">
                    <text-editor
                    v-model="item.description"
                    class="col-sm-12"
                    label="Description"
                    name="description"
                    row="5"
                    ></text-editor>

                    <image-picker
                    :value="item.path"
                    class="form-group col-sm-12 col-md-12 mt-2"
                    label="Announcement Banner"
                    name="image_path"
                    placeholder="Choose banner"
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
                    label="Archive"
                    alt-label="Restore"
                    :show-alt="item.deleted_at"
                    confirm-dialog
                    title="Archive Item"
                    alt-title="Restore Item"
                    :message="'Are you sure you want to archive Announcement #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore Announcement #' + item.id + '?'"
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
            this.types = data.types ? data.types : this.types;
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
            types: [],
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