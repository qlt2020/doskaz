<template>
    <crud-list
        title="Региональные координаторы"
        api-path="/api/admin/regionalCoordinators"
        :create="true"
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
                    {key: 'name', label: 'Имя'},
                    {key: 'cityNames', label: 'Города'},
                ]
            }
        }
    }
</script>
