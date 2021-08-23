<template>
    <form>
        <b-alert variant="danger" show v-if="operationResult && operationResult.statusCode === 400">
            Ошибка при сохранении данных! Заполните все обязательные поля
        </b-alert>
        <b-alert variant="success" show v-if="operationResult && operationResult.statusCode < 400">
            Данные сохранены
        </b-alert>


        <select2-field label="Форма" path="zones.type" :options="formOptions"/>
        <b-tabs content-class="mt-3" v-model="tab">
            <b-tab title="Общая информация" active>
                <input-field path="title" label="Наименование" :required="true"/>
                <input-field path="otherNames" label="Другие наименования" :required="false"/>
                <textarea-field label="Описание" path="description"/>
                <select2-field label="Категория" v-model="categoryId" :options="categoryOptions" :required="true"/>
                <select2-field label="Подкатегория" path="categoryId" :required="true"
                               :options="subCategoryOptions"/>
                <input-field path="address" label="Адрес" :required="true"/>
                <map-point-field path="point" label="Точка на карте" :required="true"
                                 @address="address = $event"/>
                <text-input-collection path="videos" label="Видео"/>
                <images-collection path="photos" label="Фото"/>
            </b-tab>

            <b-tab v-for="tab in tabs" :key="tab.key" :title="tab.title">
                <zone :form="form" :zone="tab.group" :zone-property="tab.key"/>
            </b-tab>
        </b-tabs>
        <slot></slot>
    </form>
</template>

<script>
    import Select2Field from "../crud/fields/Select2Field";
    import InputField from "../crud/fields/InputField";
    import TextareaField from "../crud/fields/TextareaField";
    import MapPointField from "../crud/fields/MapPoint";
    import TextInputCollection from "../crud/fields/TextInputCollection";
    import ImagesCollection from "../crud/fields/ImagesCollection";
    import Zone from "./Zone";
    import flatMap from "lodash/flatMap";
    import {get, sync} from "vuex-pathify";

    export default {
        name: "ObjectForm",
        components: {Zone, ImagesCollection, TextInputCollection, MapPointField, TextareaField, InputField, Select2Field},
        props: [
            'categories',
            'attributes'
        ],
        data() {
            return {
                category: null,
                tab: 0
            }
        },
        mounted() {
            const subCategory = flatMap(this.categories, category => category.subCategories.map(subCategory => ({
                ...subCategory,
                parentCategoryId: category.id
            })))
                .find(category => category.id === this.subCategoryId)

            if(subCategory) {
                this.categoryId = subCategory.parentCategoryId;
            }

        },
        computed: {
            ...get('crud/edit', [
                'isLoading',
                'operationResult'
            ]),
            categoryId: {
                get() {
                    return this.category ? this.category.id : null
                },
                set(categoryId) {
                    this.category = this.categories.find(category => category.id === categoryId)
                }
            },
            categoryOptions() {
                return [{value: null, title: 'Не выбрано'}].concat(this.categories.map(category => ({
                    value: category.id,
                    title: category.title
                })));
            },
            ...sync('crud/edit/item@', {
                subCategoryId: 'categoryId',
                address: 'address'
            }),
            zones: get('crud/edit/item@zones'),
            ...get('crud/edit/item@zones', {
                form: 'type'
            }),
            subCategoryOptions() {
                if (this.category) {
                    return [{value: null, title: 'Не выбрано'}].concat(this.category.subCategories.map(category => ({
                        value: category.id,
                        title: category.title
                    })));
                }
                return [{value: null, title: 'Не выбрано'}];
            },
            formOptions() {
                return [
                     {value: 'small', title: 'Простая'},
                    {value: 'middle', title: 'Средняя'},
                    {value: 'full', title: 'Сложная'}
                ]
            },
            tabs() {
                return [
                    {title: 'Парковка', key: 'parking', group: 'parking'},
                    {title: 'Входная группа #1', key: 'entrance1', group: 'entrance'},
                    {title: 'Входная группа #2', key: 'entrance2', group: 'entrance'},
                    {title: 'Входная группа #3', key: 'entrance3', group: 'entrance'},
                    {title: 'Пути движения по объекту', key: 'movement', group: 'movement'},
                    {title: 'Зона оказания услуги', key: 'service', group: 'service'},
                    {title: 'Туалет', key: 'toilet', group: 'toilet'},
                    {title: 'Навигация', key: 'navigation', group: 'navigation'},
                    {title: 'Доступность услуги', key: 'serviceAccessibility', group: 'serviceAccessibility'},
                    {title: 'Доступность и безопасность услуг для детей до 7 лет', key: 'kidsAccessibility', group: 'kidsAccessibility'},
                ].filter(tab => (this.zones || {})[tab.key])
            }
        },
        watch: {
            categoryId: {
                handler(newVal, prevVal) {
                    if (prevVal !== null) {
                        this.subCategoryId = null
                    }
                }
            }
        },
    }
</script>

<style scoped>

</style>
