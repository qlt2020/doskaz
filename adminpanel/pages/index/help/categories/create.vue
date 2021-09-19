<template>
    <crud-edit
        title="Создание категории помощи"
        api-path="/api/help-category"
        :fields="fields"
        edit-base-path="/help/categories"
    />
</template>

<script>
    import CrudEdit from "@/components/crud/CrudEdit";

    export default {
        components: {CrudEdit},
        computed: {
            fields() {
                return [
                    {key: 'name', label: 'Наименование на русском', required: true},
                    {key: 'name_kz', label: 'Наименование на казахском', required: true},
                    {key: 'name_en', label: 'Наименование на английском', required: true},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
