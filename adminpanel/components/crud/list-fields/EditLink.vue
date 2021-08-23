<template>
    <nuxt-link :to="`${$route.path}/${item.id}`">{{ value }}</nuxt-link>
</template>

<script>
    export default {
        name: "EditLink",
        props: [
            'value',
            'item'
        ]
    }
</script>

<style scoped>

</style>
