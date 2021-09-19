<template>
    <crud-edit
        title="Создание регионального представителя"
        api-path="/api/admin/regionalRepresentatives"
        :fields="fields"
        edit-base-path="/regionalRepresentatives"
    />
</template>

<script>
    import CrudEdit from "@/components/crud/CrudEdit";
    import Selection from "@/components/crud/edit-fields/Selection";
    import ImageUpload from "@/components/crud/edit-fields/ImageUpload";

    export default {
        components: {CrudEdit},
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
                                const {data: items} = await self.$axios.get('/api/cities', {
                                    params: {limit: 999}
                                });
                                return [{value: null, title: 'Не выбрано'}].concat(items.map(item => ({value: item.id, title: item.name})))
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
