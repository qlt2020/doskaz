<template>
    <b-card>
        <b-form ref="form">
            <b-row>
                <b-col md="12" lg="6" xl="4">
                    <b-form-group label="Название">
                        <b-form-input v-model.trim="filter.title"/>
                    </b-form-group>
                </b-col>
                <b-col md="12" lg="6" xl="4">
                    <b-form-group label="Город">
                        <b-form-select class="form-control" :options="cities" v-model="filter.cityId" value-field="id" text-field="name">
                            <template v-slot:first>
                                <b-form-select-option value="all">Все</b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-form-group>
                </b-col>
                <b-col md="12" lg="6" xl="4">
                    <b-form-group label="Адрес">
                        <b-form-input v-model.trim="filter.address"/>
                    </b-form-group>
                </b-col>

                <b-col md="12" lg="6" xl="4">
                    <b-form-group label="Категория">
                        <b-form-select class="form-control" v-model="filter.categoryId" :options="categories" value-field="id" text-field="title">
                            <template v-slot:first>
                                <b-form-select-option value="all">Все</b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-form-group>
                </b-col>
                <b-col md="12" lg="6" xl="4">
                    <b-form-group label="Подкатегория">
                        <b-form-select class="form-control" v-model="filter.subCategoryId" :options="subCategories"  value-field="id" text-field="title">
                            <template v-slot:first>
                                <b-form-select-option value="all">Все</b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-form-group>
                </b-col>
                <b-col md="12" lg="6" xl="4">
                   <b-row>
                       <b-col md="6" lg="6" xl="6">
                           <b-form-group label="Дата, от">
                               <b-form-input type="date" v-model="filter.dateFrom"/>
                           </b-form-group>
                       </b-col>
                       <b-col md="6" lg="6" xl="6">
                           <b-form-group label="Дата, до">
                               <b-form-input type="date" v-model="filter.dateTo"/>
                           </b-form-group>
                       </b-col>
                   </b-row>
                </b-col>

            </b-row>
            <b-row>
                <b-col>
                    <b-btn variant="primary" @click="submit">Применить</b-btn>
                    <b-btn variant="secondary" class="ml-3" @click="reset">Сброс</b-btn>
                </b-col>
            </b-row>
        </b-form>
    </b-card>
</template>

<script>
import {call, get} from 'vuex-pathify'
export default {
    name: "ObjectsFilter",
    data() {
        return {
            filter: {
                title: '',
                address: '',
                cityId: 'all',
                categoryId: 'all',
                subCategoryId: 'all',
                dateFrom: null,
                dateTo: null
            }
        }
    },
    methods: {
        reset() {
            this.$refs.form.reset()
            this.resetFilter()
        },
        loadCities: call('cities/load'),
        loadCategories: call('objectCategories/load'),
        resetFilter: call('crud/list/resetFilter'),
        applyFilter: call('crud/list/applyFilter'),
        submit() {
            this.applyFilter(this.filter)
        }
    },
    computed: {
        cities: get('cities/items'),
        categories: get('objectCategories/items'),
        subCategories() {
            const category = this.categories.find(cat => cat.id === this.filter.categoryId)
            if(!category) {
                return []
            }
            return category.subCategories
        }
    },
    watch: {
        'filter.categoryId'() {
            this.filter.subCategoryId = 'all'
        }
    },
    mounted() {
        this.loadCities()
        this.loadCategories()
    }
}
</script>

<style scoped>

</style>
