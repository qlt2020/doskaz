<template>
    <crud-edit
        title="Создание записи помощи"
        api-path="/api/admin/help"
        :fields="fields"
        edit-base-path="/help/posts"
    />
</template>

<script>
    import CrudEdit from "@/components/crud/CrudEdit";
    import MetaData from "@/components/crud/edit-fields/MetaData";
    import TextareaInput from "@/components/crud/edit-fields/TextareaInput";
    import TinyMCE from "@/components/crud/edit-fields/TinyMCE";
    import DatePicker from '@/components/crud/edit-fields/DatePicker'
    import Checkbox from "@/components/crud/edit-fields/Checkbox";
    import Selection from "@/components/crud/edit-fields/Selection";
    import ImageUpload from "@/components/crud/edit-fields/ImageUpload";

    export default {
        components: {CrudEdit},
        computed: {
            fields() {
                const self = this;

                return [
                    {key: 'title', label: 'Заголовок на русском', required: true},
                    {key: 'title_kz', label: 'Заголовок на казахском', required: true},
                    {key: 'title_en', label: 'Заголовок на английском', required: true},
                    {key: 'description', label: 'Описание на русском', type: TinyMCE},
                    {key: 'description_kz', label: 'Описание на казахском', type: TinyMCE},
                    {key: 'description_en', label: 'Описание на английском', type: TinyMCE},
                    {
                        key: 'category', label: 'Категория', type: Selection, required: true, options: {
                            async asyncOptions() {
                                const {data: {items}} = await self.$axios.get('/api/help-category', {
                                    params: {limit: 999}
                                });
                                return [{value: null, title: 'Не выбрано'}].concat(items.map(item => ({value: +item.id, title: item.name})))
                            }
                        }
                    },
                    {key: 'image', type: ImageUpload, required: true, label: 'Изображение на русском'},
                    {key: 'image_kz', type: ImageUpload, required: true, label: 'Изображение на казахском'},
                    {key: 'image_en', type: ImageUpload, required: true, label: 'Изображение на английском'}
                ]
            }
        }
    }
</script>

<style scoped>

</style>
