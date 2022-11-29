<template>
    <form-request :submit-url="submitUrl" @load="load" reset-on-success sync-on-success>
        <card>
            
            <template v-slot:header>Change Password</template>

            <div class="row">
                <password
                class="form-group col-sm-12"
                label="Current Password"
                name="current_password"
                placeholder="Current Password"
                ></password>
            </div>

            <div class="row">
                <password
                class="form-group col-sm-12 col-md-6"
                label="New Password"
                name="new_password"
                placeholder="New Password"
                ></password>

                <password
                class="form-group col-sm-12 col-md-6"
                label="Confirm New Password"
                name="new_password_confirmation"
                placeholder="Confirm New Password"
                ></password>
            </div>
            
            <template v-slot:footer>
                <action-button type="submit" :loading="loading" :disabled="loading" class="btn-primary">Change Password</action-button>
            </template>
        </card>

    </form-request>
</template>

<script type="text/javascript">
import CrudMixin from '../../mixins/crud.js';
import Password from '../inputs/Password.vue';

export default {
	components: {
		'password': Password,
	},

    mixins: [ CrudMixin ],
}
</script>