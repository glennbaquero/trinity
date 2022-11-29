<template>
        <form-request :submit-url="submitUrl" @load="load" @success="fetch" sync-on-success>
            <card>
                <template v-slot:header>
                    Set Shipping Details
                </template>
                    
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="checkbox">
                                <input 
                                v-model="item.free"
                                type="checkbox" name="free">
                                <label for="free">Free Shipping</label>
                            </div>
                        </div>
                    </div>

                    <selector class="col-sm-12"
                    v-model="item.area_id"
                    name="area_id"
                    label="Area"
                    item-value="id"
                    item-text="name"
                    :items="areas"
                    placeholder="Please select an area"
                    ></selector>

                    <template v-if="item.free">
                        
                        <div class="form-group col-sm-12 col-md-12">                            
                            <div class="checkbox">
                                <input 
                                v-model="item.quantity"
                                type="checkbox" name="quantity">
                                <label for="quantity">Quantity</label>
                            </div>
                            <input v-model="item.quantity_minimum" name="quantity_minimum" type="number" class="form-control input-sm"
                            :required="item.quantity"
                            :disabled="!item.quantity"
                            placeholder="Minimum quantity required"
                            >
                        </div>

                        <div class="form-group col-sm-12 col-md-12">
                            <div class="checkbox">
                                <input 
                                v-model="item.price"
                                type="checkbox" name="price">
                                <label for="price">Price</label>
                            </div>
                            <input v-model="item.price_minimum" name="price_minimum" type="number" class="form-control input-sm"
                            :required="item.price"
                            :disabled="!item.price"
                            placeholder="Minimum price required" 
                            >
                        </div>

                    </template>

                    <div class="form-group col-sm-12 col-md-12">
                        <label>Shipping Fee (<small> This amount will be applied if the price and quantity requirements are not met.</small>)</label>
                        <input v-model="item.fee" name="fee" type="number" class="form-control input-sm" required

                        >
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
                    label="Archive Area"
                    alt-label="Restore Area"
                    :show-alt="item.deleted_at"
                    confirm-dialog
                    title="Archive Item"
                    alt-title="Restore Item"
                    :message="'Are you sure you want to archive Area #' + item.id + '?'"
                    :alt-message="'Are you sure you want to restore Area #' + item.id + '?'"
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

    data() {
        return {
            areas: [],
        }
    },


    methods: {
        fetchSuccess(data) {
            this.item = data.item ? data.item : this.item;
            this.areas = data.areas ? data.areas : [];
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