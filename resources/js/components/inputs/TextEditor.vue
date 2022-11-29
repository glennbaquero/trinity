<template>
    <div class="form-group">
        <label v-if="label">{{ label }}</label>
        <textarea ref="elem" :name="name" :placeholder="placeholder" type="text" class="form-control input-sm">{{ value }}</textarea>
    </div>
</template>

<script>
require('./build/ckeditor.js');

export default {
    mounted() {
        this.setup();
    },

    watch: {
        value(newValue, oldValue) {
            if (this.editor && !this.has_set) {
                this.editor.setData(newValue);
                this.has_set = true;
            }
        },
    },

    methods: {
        setup() {
            ClassicEditor
            .create(this.$refs.elem, {
                
            })
            .then(editor => {
                this.editor = editor;

                this.editor.model.document.on('change:data', (event, name) => {
                    this.has_set = true;
                    this.$emit('change', this.editor.getData());
                });
            }).catch(error => {

            });
        },
    },

    props: {
        name: {
            type: String,
        },

        label: {
            type: String,
        },

        placeholder: {
            type: String,
        },

        value: {},
    },

    model: {
        prop: 'value',
        event: 'change',
    },

    data() {
        return {
            editor: null,
            text: null,
            has_set: false,
        }
    }
}
</script>

<style type="text/css">
.ck.ck-content.ck-editor__editable {
    min-height: 175px;
}
</style>