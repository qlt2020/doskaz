<template>
    <crud-edit
        title="Редактирование записи блога"
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
                    {key: 'title', label: 'Заголовок', required: true},
                    {key: 'slug', label: 'ЧПУ'},
                    {key: 'annotation', label: 'Лид', type: TextareaInput},
                    {key: 'content', label: 'Описание', type: TinyMCE},
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
