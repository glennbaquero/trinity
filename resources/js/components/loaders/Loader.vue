<template>
    <transition name="fade">
        <div v-if="loading" class="loader-container" :class="absolute ? 'absolute' : 'fixed'">
            <div class="loader loader-element" :class="absolute ? 'absolute' : 'fixed'"></div>
        </div>
    </transition>
</template>

<script type="text/javascript">
/**
* ==================================================================================
* Loader for VUE components
*
* Required PROPS:
* - loading variable
* 
* ==================================================================================
**/    

export default {
    props: {
        loading: {
            default: false,
            type: Boolean,
        },

        absolute: {
            default: false,
            type: Boolean,
        },
    },
}
</script>

<style type="text/css" scoped>
/*
|-----------------------------------------------
| @Preloaders
|-----------------------------------------------
*/

/*
| @General
|-----------------------------------------------
*/
.fixed {
    position: fixed;    
}

.absolute {
    position: absolute;
}

.loader-container, .loader {
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: auto;
    z-index: 50;
}

.loader-container {
    height: 100%;
    width: 100%;
}

.loader{
    width: 50px;
    height: 50px;

}

.loader-element:before, .loader-element:after{
    content: "";
    position: absolute;
    top: -10px;
    left: -10px;
    width: 100%;
    height: 100%;
    border-radius: 100%;
    border: 5px solid transparent;
    border-top-color: #3498db;
}

.loader-element:before{
    z-index: 100;
    animation: spin 1s infinite;
}

.loader-element:after{
    border: 5px solid #ccc;
}

@keyframes spin{
    0%{
        -webkit-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100%{
        -webkit-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
</style>