<template>
    <div>
        <!-- /*---------------------------------------------------
        | <slot> body
        |---------------------------------------------------------
        |
        | Emit fetched database data to parent component where
        | they can use this slot to render that data
        |
        */ -->
        <div>
            <slot name="body"></slot>
        </div>

        <p class="text-sm-center" v-if="empty && !customEmpty">
            No Items Found
        </p>

        <div>
            <pagination ref="page"
            :fetch-url="url"
            :autofetch="autofetch"
            :visible="paginated"
            :per_page="per_page"
            
            @total="init"
            @paginate="fetch"
            ></pagination>
        </div>
    </div>
</template>
<script>
import DataMixin from './mixin.js';

export default {
    mixins: [ DataMixin ],
}
</script>