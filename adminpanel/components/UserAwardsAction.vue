<template>
    <nuxt-link class="btn btn-sm btn-secondary" :to="`/users/${item.id}/awards`">Награды</nuxt-link>
</template>

<script>
    export default {
        name: "UserAwardsAction",
        props: ['item']
    }
</script>

<style scoped>

</style>
