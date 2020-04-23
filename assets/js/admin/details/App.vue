<template>
    <Search/>
</template>

<script>
    import Vue from 'vue'
    import VueHighlightJS from 'vue-highlightjs'
    import Search from './Search'

    Vue.use(VueHighlightJS)

    export default {
        components: {
            Search
        }
    }
</script>
