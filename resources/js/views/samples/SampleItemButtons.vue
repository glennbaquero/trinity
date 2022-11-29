<template>
    <div>
        <action-button
        v-if="item.canApprove"
        :small="small"
        color="btn-success"
        :action-url="item.approveUrl"
        icon="fa fa-check"
        confirm-dialog
        :disabled="loading"
        title="Approve"
        :message="'Are you sure you want to approve Sample Item #' + item.id + '?'"
        @load="load"
        @success="fetch"
        @error="fetch">
            Approve
        </action-button>
        
        <form-modal 
        v-if="item.canDeny" 
        :small="small"
        :submit-url="item.denyUrl" 
        @success="fetch" 
        label="Deny" 
        icon="fa fa-times" 
        color="btn-danger" 
        ok-text="Deny"
        ok-color="btn-danger" 
        cancel-text="Cancel" 
        cancel-color="btn-light"
        confirm-dialog
        title="Deny"
        :message="'Are you sure you want to deny Sample Item #' + item.id"
        persistent 
        sync-on-success>
            <template v-slot:title>
                Deny Sample Item #{{ item.id }}?
            </template>
            <div class="form-group">
                <textarea name="reason" placeholder="Please enter your reason" class="form-control"></textarea>
            </div>
        </form-modal>
    </div>
</template>

<script type="text/javascript">
import LoaderMixin from '../../components/loaders/mixin.js';
import ActionButton from '../../components/buttons/ActionButton.vue';
import FormModal from '../../components/forms/FormModal.vue';

export default {
    methods: {
        fetch() {
            this.$emit('success');
        },
    },

    props: {
        item: {},
        small: {
            default: false,
            type: Boolean,
        },
    },

    components: {
        'action-button': ActionButton,
        'form-modal': FormModal,
    },

    mixins: [ LoaderMixin ],
}
</script>