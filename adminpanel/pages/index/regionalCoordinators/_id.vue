<template>
    <crud-edit
        title="Редактирование регионального координатора"
        api-path="/api/admin/regionalCoordinators"
        :fields="fields"
    />
</template>

<script>
    import CrudEdit from "@/components/crud/CrudEdit";
    import Selection from "@/components/crud/edit-fields/Selection";

    export default {
        components: {CrudEdit},
        async asyncData({$axios}) {
            const [{data: cities}, {data: {items: users}}] = await Promise.all([
                $axios.get('/api/cities'),
                $axios.get('/api/users', {
                    params: {
                        limit: 1000
                    }
                }),
            ])
            return {cities, users}
        },
        computed: {
            fields() {
                return [
                    {
                        key: 'userId', label: 'Пользователь', type: Selection, options: {
                            options: this.users.map(user => ({
                                value: user.id,
                                title: user.name
                            }))
                        }
                    },
                    {
                        key: 'cities', label: 'Города', type: Selection, options: {
                            multiple: true,
                            options: this.cities.map(city => ({
                                value: city.id,
                                title: city.name
                            }))
                        }
                    }
                    /*{key: 'name', label: 'Имя'},
                    {key: 'email', label: 'Email', disabled: true},
                    {key: 'phone', label: 'Номер телефона', disabled: true},
                   */
                ]
            }
        }
    }
</script>

<style scoped>

</style>
