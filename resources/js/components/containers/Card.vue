<template>
	<div class="card">
        <div v-if="$slots.header" class="card-header">
            <h5 class="font-weight-bold m-0"><slot name="header"></slot></h5>
        </div>

        <div class="card-body">
			<slot></slot>
        </div>
		
		<div class="card-footer">
        	<div class="float-right">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>