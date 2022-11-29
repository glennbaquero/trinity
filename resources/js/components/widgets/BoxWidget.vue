<template>
	<div>
	    <div class="info-box elevation-1">
	        <span :class="bgIcon" class="info-box-icon elevation-1"><i :class="`${icon} text-white`"></i></span>
	        <div :class="bgColor" class="info-box-content">
	            <span class="info-box-text">{{ label }}</span>
	            <span class="info-box-number">
	                {{ count }}
	            </span>
	        </div>
	    </div>
	</div>
</template>

<script type="text/javascript">
export default {
	props: {
		icon: String,
		label: String,
		count: {},
		bgIcon: String,
		bgColor: String,
	}
}
</script>