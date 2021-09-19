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
                    {key: 'city', label: 'Город', disabled: true},
                    {key: 'gender', label: 'Пол', disabled: true, type: Selection,
                        options: {
                            options: [
                                {value: 'f', title: 'женский'},
                                {value: 'm', title: 'мужской'},
                                {value: 'u', title: 'неизвестно'}
                            ]
                        }
                    },
                    {key: 'category', label: 'Категория маломобильных групп населения', disabled: true, type: Selection,
                        options: {
                            options: [
                                {value: 'movement', title: 'Люди, передвигающиеся на кресло-коляске'},
                                {value: 'vision', title: 'Люди с инвалидностью по зрению'},
                                {value: 'limb', title: 'Люди с нарушениями опорно-двигательного аппарата'},
                                {value: 'hearing', title: 'Люди с инвалидностью по слуху'},
                                {value: 'temporal', title: 'Временно травмированные люди'},
                                {value: 'babyCarriage', title: 'Люди с детскими колясками'},
                                {value: 'missingLimbs', title: 'Люди с отсутствующими конечностями'},
                                {value: 'pregnant', title: 'Беременные женщины'},
                                {value: 'intellectual', title: 'Люди с интеллектуальной инвалидностью'},
                                {value: 'agedPeople', title: 'Пожилые люди'},
                                {value: 'withChild', title: 'Семьи с детьми до 7 лет'},
                                {value: 'justView', title: 'Просто посмотреть'}
                            ]
                        }
                    },
                    {key: 'birthday', label: 'Дата рождения', disabled: true},
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
