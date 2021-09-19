<template>
    <crud-edit
        title="Редактирование записи медиатеки"
        api-path="/api/admin/blog/posts"
        :fields="fields"
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
                    {key: 'slug', label: 'ЧПУ'},
                    {key: 'annotation', label: 'Лид на русском', type: TextareaInput},
                    {key: 'annotation_kz', label: 'Лид на казахском', type: TextareaInput},
                    {key: 'annotation_en', label: 'Лид на английском', type: TextareaInput},
                    {key: 'content', label: 'Описание на русском', type: TinyMCE},
                    {key: 'content_kz', label: 'Описание на казахском', type: TinyMCE},
                    {key: 'content_en', label: 'Описание на английском', type: TinyMCE},
                    {key: 'publishedAt', label: 'Дата публикации', type: DatePicker, required: true},
                    {key: 'isPublished', label: 'Опубликовано', type: Checkbox, defaultValue: true, initialValue: true},
                    {
                        key: 'categoryId', label: 'Категория', type: Selection, required: true, options: {
                            async asyncOptions() {
                                const {data: {items}} = await self.$axios.get('/api/admin/blog/categories', {
                                    params: {limit: 999}
                                });
                                return items.map(item => ({value: item.id, title: item.title}))
                            }
                        }
                    },
                    {key: 'image', type: ImageUpload, required: true, label: 'Главное изображение'},
                    {key: 'meta', type: MetaData, label: 'Мета-данные'},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
