<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Создание задания от администрации</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center"></div>
            </div>
        </div>
        <b-card>
            <field-wrapper :required="true" label="Наименование" :error="errors.name">
                <input type="text" class="form-control" v-model.trim="form.name"/>
            </field-wrapper>

            <field-wrapper :required="true" label="Количество баллов за выполнение" :error="errors.pointsReward">
                <input type="number" class="form-control" v-model.number="form.pointsReward"/>
            </field-wrapper>

            <field-wrapper label="Город">
                <select class="form-control" v-model="form.cityId">
                    <option v-for="city in cityOptions" :key="city.key" :value="city.key">{{city.title}}</option>
                </select>
            </field-wrapper>

            <field-wrapper label="Категория">
                <select class="form-control" v-model="form.categoryId">
                    <option v-for="category in categoryOptions" :key="category.key" :value="category.key">
                        {{category.title}}
                    </option>
                </select>
            </field-wrapper>

            <field-wrapper label="Подкатегория">
                <select class="form-control" :disabled="!form.categoryId" v-model="form.subCategoryId">
                    <option v-for="subCategory in subCategoryOptions" :key="subCategory.key" :value="subCategory.key">
                        {{subCategory.title}}
                    </option>
                </select>
            </field-wrapper>

            <field-wrapper label="Область">
                <yandex-map
                    @map-was-initialized="mapWasInitialized"
                    :style="{width: '100%', height: '400px'}"
                    :zoom.sync="zoom"
                    :coords.sync="coords"
                    :controls="[]"
                    :settings="{
                    apiKey: 'c1050142-1c08-440e-b357-f2743155c1ec',
                    lang: 'ru_RU',
                    coordorder: 'latlong',
                    version: '2.1'
                }">
                </yandex-map>
            </field-wrapper>

            <button type="button" class="btn btn-primary" @click.prevent="submit">Сохранить</button>
        </b-card>
    </div>
</template>

<script>
    import FieldWrapper from "../../../components/crud/FieldWrapper";
    import {yandexMap, ymapMarker} from 'vue-yandex-maps'
    import mapValidationErrors from "~/mapValidationErrors";

    export default {
        data() {
            return {
                zoom: 5,
                coords: [47.74887674893552, 67.04712168264118],
                errors: {},
                form: {
                    name: null,
                    pointsReward: 0,
                    cityId: null,
                    categoryId: null,
                    subCategoryId: null,
                    area: null
                }
            }
        },
        components: {FieldWrapper, yandexMap},
        async asyncData({$axios}) {
            const [{data: cities}, {data: categories}] = await Promise.all([
                $axios.get('/api/cities'),
                $axios.get('/api/objects/categories'),
            ])
            return {cities, categories}
        },
        computed: {
            cityOptions() {
                return [
                    {key: null, title: 'Не выбрано'},
                    ...this.cities.map(city => ({key: city.id, title: city.name}))
                ]
            },
            categoryOptions() {
                return [
                    {key: null, title: 'Не выбрано'},
                    ...this.categories.map(category => ({key: category.id, title: category.title}))
                ]
            },
            subCategoryOptions() {
                const category = this.categories.find(category => category.id === this.form.categoryId);
                if (category) {
                    return [
                        {key: null, title: 'Не выбрано'},
                        ...category.subCategories.map(item => ({key: item.id, title: item.title}))
                    ]
                }
                return [
                    {key: null, title: 'Не выбрано'},
                ]
            }
        },
        methods: {
            mapWasInitialized(map) {
                this.mapInstance = map
                const polygon = new ymaps.Polygon([], {
                    editorDrawingCursor: "crosshair",
                    editorMaxPoints: 5,
                    fillColor: '#00FF00',
                    strokeColor: '#0000FF',
                    strokeWidth: 5
                })

                map.geoObjects.add(polygon);
                const stateMonitor = new ymaps.Monitor(polygon.editor.state);
                stateMonitor.add("drawing", function (newValue) {
                    polygon.options.set("strokeColor", newValue ? '#FF0000' : '#0000FF');
                });
                polygon.editor.startDrawing();
                polygon.events.add('geometrychange', (e) => {
                    this.form.area = polygon.geometry.getCoordinates()[0]
                })
            },
            async submit() {
                this.errors = {};
                const {status, data} = await this.$axios.post('/api/admin/administrationTasks', this.form, {
                    validateStatus: status => status <= 400
                })
                if (status === 400) {
                    this.errors = mapValidationErrors(data.errors.violations)
                }
                else {
                    await this.$router.push(`/administrationTasks/${data.id}`)
                }
            }
        },
        watch: {
            'form.cityId'(val) {
                if (!val) {
                    return;
                }
                const city = this.cities.find(city => city.id === val)
                if (city) {
                    this.mapInstance.setBounds(city.bounds)
                }
            },
            'form.categoryId'() {
                this.form.subCategoryId = null
            }
        }
    }
</script>

<style scoped>

</style>
