<template>
    <crud-list
        title="Региональные представители"
        api-path="/api/admin/regionalRepresentatives"
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
                    {key: 'name', label: 'Имя'},
                    {key: 'email', label: 'Email'},
                    {key: 'phone', label: 'Телефон'}
                ]
            }
        }
    }
</script>
