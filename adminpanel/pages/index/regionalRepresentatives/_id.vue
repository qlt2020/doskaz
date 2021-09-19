<template>
    <crud-edit
        title="Редактирование регионального представителя"
        api-path="/api/admin/regionalRepresentatives"
        :fields="fields"
    />
</template>

<script>
    import CrudEdit from "@/components/crud/CrudEdit";
    import Selection from "@/components/crud/edit-fields/Selection";
    import ImageUpload from "@/components/crud/edit-fields/ImageUpload";

    export default {
        components: {CrudEdit},
        async asyncData({$axios}) {
            const {data} = await $axios.get('/api/cities')
            const cities = [{value: null, title: 'Не выбрано'}].concat(data.map(item => ({
                value: item.id,
                title: item.name
            })))
            return {cities}
        },
        computed: {
            fields() {
                const self = this;

                return [
                    {key: 'name', label: 'Имя', required: true},
                    {key: 'phone', label: 'Телефон', required: true},
                    {key: 'email', label: 'Email', required: true},
                    {
                        key: 'cityId', label: 'Город', type: Selection, required: true, options: {
                            async asyncOptions() {
                                return self.cities
                            }
                        }
                    },
                    {key: 'photo', type: ImageUpload, required: true, label: 'Фото'},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
