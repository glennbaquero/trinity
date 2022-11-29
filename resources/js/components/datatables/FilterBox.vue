<template>
	<div class="row mb-2">
        <!-- FILTERS -->
        <div class="col-sm-8">
            <div class="row">
                <div class="col-sm-12 form-inline">
                    <slot name="left"></slot>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="col-sm-12 justify-content-end form-inline pr-0 pt-2">
            	<slot name="right"></slot>
            </div>
        </div>
    </div>
</template>