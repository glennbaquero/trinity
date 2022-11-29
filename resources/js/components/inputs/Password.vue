<template>
	<div class="form-group">
		<label>{{ label }}</label>
		<div class="input-group">
			<input :placeholder="placeholder" :name="name" :type="hidden ? 'password' : 'text'" class="form-control">
			<div class="input-group-append">
                <span @click="hidden = !hidden" class="btn btn-default">
					<i v-if="hidden" class="fa fa-eye-slash"></i>
					<i v-if="!hidden" class="fa fa-eye"></i>
				</span>
            </div>
		</div>
	</div>
</template>

<script type="text/javascript">
export default {
	data() {
		return {
			hidden: true,
		};
	},

	props: {
		name: String,
		label: String,
		placeholder: String,
	}
}
</script>