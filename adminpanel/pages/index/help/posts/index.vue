<template>
    <crud-list
        title="Записи помощи"
        api-path="/api/help"
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
                    {key: 'category_name', label: 'Категория'}
                ]
            }
        }
    }
</script>

<style scoped>

</style>
