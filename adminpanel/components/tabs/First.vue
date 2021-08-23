<template>
    <div>
        <input-field :path="`${path}.name`" label="Наименование" :required="true"/>
        <input-field :path="`${path}.otherNames`" label="Другие наименования" :required="true"/>
        <input-field :path="`${path}.address`" label="Адрес" :required="true"/>
        <map-point-field :path="`${path}.point`" label="Точка на карте" :required="true" @address="updateAddress"/>
        <select2-field label="Категория" v-model="categoryId" :options="categoryOptions"/>
        <select2-field label="Подкатегория" :path="`${path}.categoryId`" :required="true" :options="subCategoryOptions"/>
        <textarea-field label="Описание" :path="`${path}.description`" :required="true"></textarea-field>
        <text-input-collection :path="`${path}.videos`" label="Видео"/>
        <images-collection :path="`${path}.photos`" label="Фото"/>
    </div>
</template>

<script>
    import MapPointField from "../crud/fields/MapPoint";
    import InputField from "../crud/fields/InputField";
    import Select2Field from "../crud/fields/Select2Field";
    import {get} from 'vuex-pathify'
    import flatMap from 'lodash/flatMap'
    import FieldWrapper from "../crud/FieldWrapper";
    import TextareaField from "../crud/fields/TextareaField";
    import ImagesCollection from "../crud/fields/ImagesCollection";
    import TextInputCollection from "../crud/fields/TextInputCollection";

    export default {
        name: "First",
        components: {
            TextInputCollection,
            ImagesCollection, TextareaField, FieldWrapper, Select2Field, InputField, MapPointField},
        props: [
            'path'
        ],
        methods: {
            updateAddress(newAddress) {
                this.$store.commit('crud/edit/SET_PROPERTY_BY_PATH', {value: newAddress, path: 'form.first.address'})
            },
            async loadCategories() {
                const {data} = await this.$axios.get('/api/objectCategories')
                this.categories = data;
                const subCategory = flatMap(data, category => category.subCategories.map(subCategory => ({
                    ...subCategory,
                    parentCategoryId: category.id
                })))
                    .find(category => category.id === this.subCategoryId)

                this.categoryId = subCategory.parentCategoryId;
            }
        },
        computed: {
            categoryOptions() {
                return [{value: null, title: 'Не выбрано'}].concat(this.categories.map(category => ({
                    value: category.id,
                    title: category.title
                })))
            },
            subCategoryOptions() {
                if (this.category) {
                    return [{value: null, title: 'Не выбрано'}].concat(this.category.subCategories.map(category => ({
                        value: category.id,
                        title: category.title
                    })));
                }
                return [{value: null, title: 'Не выбрано'}];
            },
            categoryId: {
                get() {
                    return this.category ? this.category.id : null
                },
                set(categoryId) {
                    this.category = this.categories.find(category => category.id === categoryId)
                }
            },
            subCategoryId: get('crud/edit/item@form.first.categoryId'),
            videos: get('crud/edit/item@form.first.videos'),
            photos: get('crud/edit/item@form.first.photos')
        },
        data() {
            return {
                category: null,
                categories: []
            }
        },
        mounted() {
            this.loadCategories()
        },
        watch: {
            categoryId: {
                handler(newVal, prevVal) {
                    if(prevVal !== null) {
                        this.$store.commit('crud/edit/SET_PROPERTY_BY_PATH', {value: null, path: 'form.first.categoryId'})
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
