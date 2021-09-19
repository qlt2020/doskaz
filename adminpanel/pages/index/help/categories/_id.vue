<template>
    <crud-edit
        title="Редактирование категории помощи"
        api-path="/api/help-category"
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
