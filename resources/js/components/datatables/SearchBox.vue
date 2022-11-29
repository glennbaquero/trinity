<template>
    <div class="form-group">
        <form @submit.prevent="search" method="GET" action="#">
            <div class="input-group" :class="small ? 'input-group-sm' : ''">
                <input v-model="value" type="text" :name="name" :placeholder="placeholder" aria-label="Search here..." class="form-control">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script type="text/javascript">
export default {
    methods: {
        search() {
            this.$emit('search', this.value);
        }
    },

    data() {
        return {
            value: null,
        }
    },

    props: {
        placeholder: {
            default: 'Search here...'
        },
        small: {
            default: false,
            type: Boolean,
        },

        name: String,
    },
}
</script>