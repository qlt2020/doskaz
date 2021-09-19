<template>
    <crud-edit
        title="Создание категории медиатеки"
        api-path="/api/admin/blog/categories"
        :fields="fields"
        edit-base-path="/blog/categories"
    />
</template>

<script>
    import CrudEdit from "@/components/crud/CrudEdit";
    import MetaData from "@/components/crud/edit-fields/MetaData";

    export default {
        components: {CrudEdit},
        computed: {
            fields() {
                return [
                    {key: 'title', label: 'Наименование на русском', required: true},
                    {key: 'title_kz', label: 'Наименование на казахском', required: true},
                    {key: 'title_en', label: 'Наименование на английском', required: true},
                    {key: 'slug', label: 'ЧПУ'},
                    {key: 'meta', type: MetaData, label: 'Мета-данные'},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
