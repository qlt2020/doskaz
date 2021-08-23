<template>
    <crud-list
        title="Категории блога"
        api-path="/api/admin/blog/categories"
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
                    {key: 'title', label: 'Название'}
                ]
            }
        }
    }
</script>

<style scoped>

</style>
