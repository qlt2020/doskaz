<template>
    <nuxt-link :to="`/objects/${item.objectId}`">{{ value }}</nuxt-link>
</template>

<script>
    export default {
        name: "ObjectLink",
        props: [
            'value',
            'item'
        ]
    }
</script>

<style scoped>

</style>
