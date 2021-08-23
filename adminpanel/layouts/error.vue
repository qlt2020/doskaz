<template>
    <div v-if="error.statusCode == 403">Access Denied</div>
    <div v-else>{{ error.statusCode}} {{error.message}}</div>
</template>

<script>
    export default {
        props: [
            'error'
        ]
    }
</script>

<style scoped>

</style>
