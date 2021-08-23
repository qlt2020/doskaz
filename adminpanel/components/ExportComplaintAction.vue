<template>
    <b-button size="sm" variant="success" :href="`/api/complaints/${item.id}/pdf`" target="_blank">Просмотр</b-button>
</template>

<script>
    export default {
        name: "ExportComplaintAction",
        props: ['item']
    }
</script>

<style scoped>

</style>
