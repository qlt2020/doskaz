<template>
    <crud-list
        title="Задания от администрации"
        api-path="/api/admin/administrationTasks"
        :create="true"
        :table-fields="fields"
    />
</template>

<script>
    import CrudList from "@/components/crud/CrudList";
    import FormattedDate from "@/components/crud/list-fields/FormattedDate";
    import Roles from "@/components/crud/list-fields/Roles";

    export default {
        components: {CrudList},
        middleware: ['authenticated'],
        computed: {
            fields() {
                return [
                    {key: 'taskName', label: 'Название'},
                    {key: 'cityName', label: 'Город'},
                    {key: 'points', label: 'Баллы за выполнение'},
                ]
            }
        }
    }
</script>
