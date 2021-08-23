<template>
    <crud-list
        title="Пользователи"
        api-path="/api/users"
        :create="false"
        :table-fields="fields"
        :actions="['edit']"
        :custom-actions="customActions"
    >
        <filter-block slot="filter"/>
    </crud-list>
</template>

<script>
    import CrudList from "@/components/crud/CrudList";
    import FormattedDate from "@/components/crud/list-fields/FormattedDate";
    import Roles from "@/components/crud/list-fields/Roles";
    import FilterBlock from "../../../components/FilterBlock";
    import UserAwardsAction from "../../../components/UserAwardsAction";

    export default {
        components: {FilterBlock, CrudList},
        middleware: ['authenticated'],
        computed: {
            customActions() {
                return [
                    UserAwardsAction
                ]
            },
            fields() {
                return [
                    {key: 'name', label: 'Имя'},
                    {key: 'email', label: 'Email'},
                    {key: 'phone', label: 'Телефон'},
                    {key: 'roles', label: 'Роли', type: Roles},
                    {key: 'createdAt', label: 'Дата регистрации', type: FormattedDate},
                ]
            }
        }
    }
</script>
