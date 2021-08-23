<template>
    <crud-edit
        title="Редактирование пользователя"
        api-path="/api/users"
        :fields="fields"
    />
</template>

<script>
    import CrudEdit from "@/components/crud/CrudEdit";
    import Selection from "@/components/crud/edit-fields/Selection";

    export default {
        components: {CrudEdit},
        computed: {
            fields() {
                return [
                    {key: 'name', label: 'Имя'},
                    {key: 'email', label: 'Email', disabled: true},
                    {key: 'phone', label: 'Номер телефона', disabled: true},
                    {
                        key: 'roles', label: 'Роли', type: Selection, options: {
                            multiple: true,
                            options: [
                                {value: 'ROLE_ADMIN', title: 'Администратор'},
                                {value: 'ROLE_USER', title: 'Пользователь'}
                            ]
                        }
                    }
                ]
            }
        }
    }
</script>

<style scoped>

</style>
