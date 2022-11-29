<template>
	<div class="form-group">
    	<button type="button" class="btn btn-light border" @click="click"><i class="fas fa-sync-alt"></i></button>
	</div>
</template>

<script type="text/javascript">
export default {
	methods: {
		click() {
			this.$emit('click');
		},
	}
}
</script>