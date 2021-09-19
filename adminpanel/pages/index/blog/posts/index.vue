<template>
    <crud-list
        title="Записи медиатеки"
        api-path="/api/admin/blog/posts"
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
                    {key: 'title', label: 'Заголовок'},
                    {key: 'categoryTitle', label: 'Категория'}
                ]
            }
        }
    }
</script>

<style scoped>

</style>
