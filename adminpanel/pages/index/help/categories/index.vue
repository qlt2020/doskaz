<template>
    <crud-list
        title="Категории помощи"
        api-path="/api/help-category"
        :table-fields="fields"
    />
</template>

<script>
    import CrudList from "@/components/crud/CrudList";
    export default {
        components: {CrudList},
        middleware: ['authenticated'],
        computed: {
            fields() {
                return [
                    {key: 'name', label: 'Название'}
                ]
            }
        }
    }
</script>

<style scoped>

</style>
