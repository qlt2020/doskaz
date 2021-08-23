<template>
    <crud-edit
        title="Редактирование категории блога"
        api-path="/api/admin/blog/categories"
        :fields="fields"
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
                    {key: 'title', label: 'Наименование', required: true},
                    {key: 'slug', label: 'ЧПУ'},
                    {key: 'meta', type: MetaData, label: 'Мета-данные'},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
