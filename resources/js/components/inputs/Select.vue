<template>
    <div class="form-group">
        <label>{{ label }} <small v-if="labelNote" :class="labelNoteClass">{{ labelNote }}</small></label>
        <selectize v-model="selected" @change="change" :multiple="multiple" :name="name" :settings="settings" :required="required">
            <option  v-if="placeholder && !multiple" value="" hidden>{{ placeholder }}</option>
            <option v-for="item in items" :value="item[itemValue]">{{ item[itemText] }}</option>
        </selectize>
    </div>
</template>

<script type="text/javascript">
import Selectize from 'vue2-selectize';
import selectizecss from 'selectize/dist/css/selectize.css';

export default {
    mounted() {
        this.selected = this.value;
    },
    
    methods: {
        change() {
            this.$emit('change', this.selected);
        },

        clear() {
            this.selected = this.multiple ? [] : null;
        },
    },

    computed: {
        settings() {
            return {
                plugins: ['remove_button'],
                placeholder: this.placeholder,
            }
        },
    },

    watch: {
        value(value) {
            this.selected = value;
        },

        selected(value) {
            this.change();
        },
    },

    data() {
        return {
            selected: [],
        }
    },

    props: {
        items: {},

        value: {},

        name: {
            default: null,
            type: String,
        },

        multiple: {
            default: false,
            type: Boolean,
        },

        itemText: {
            default: 'label',
            type: String,
        },

        itemValue: {
            default: 'value',
            type: String,
        },

        label: String,
        labelNote: String,
        labelNoteClass: {
            default: 'text-danger',
            type: String,
        },

        placeholder: String,
        emptyText: String,

        disabled: {
            default: false,
            type: Boolean,
        },
        required: {
            type: Boolean,            
            default: false,
        },
    },

    model: {
        prop: 'value',
        event: 'change', 
    },

    components: {
        'selectize': Selectize,
    }
}
</script>

<style type="text/css">
.selectize-control {
    min-width: 130px;    
}

.selectize-input {
    padding: 7px 7px;
}

.selectize-control.multi .selectize-input.has-items {
    padding: 4.5px;
}

.selectize-control.multi .selectize-input > div {
    margin-bottom: 0px;
}
</style>