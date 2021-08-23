<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Редактирование запроса на добавление объекта</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center"></div>
            </div>
        </div>
        <b-card>
            <loading :active="isLoading" :is-full-page="false"/>
            <form>
                <b-tabs content-class="mt-3" v-model="tab">
                    <b-tab v-for="tab in tabs" :key="tab.key" :title="tab.title">
                        <!-- <template v-slot:title v-if="tab.key === 'entrance2' || tab.key === 'entrance3'">
                             <span class="mr-2">{{tab.title}}</span>
                             <b-btn-close title="Удалить"/>
                         </template>-->
                        <component
                            :is="tab.component"
                            :form="item.form.form"
                            :path="`form.${tab.key}`"
                        ></component>
                    </b-tab>
                </b-tabs>
                <button type="button" class="btn btn-primary" @click.prevent="submit">Сохранить</button>
                <button type="button" class="btn btn-secondary" @click.prevent="approve" :disabled="item.approvedAt">
                    Принять
                </button>
            </form>
        </b-card>
    </div>
</template>

<script>
    import {get, call, sync} from 'vuex-pathify'
    import CrudEdit from "@/components/crud/CrudEdit";
    import Loading from "vue-loading-overlay";
    import 'vue-loading-overlay/dist/vue-loading.css';
    import Fields from "@/components/crud/Fields";
    import First from "@/components/tabs/First";
    import Parking from "@/components/tabs/Parking";
    import Entrance from "@/components/tabs/Entrance";
    import Movement from "@/components/tabs/Movement";
    import Service from "@/components/tabs/Service";
    import Toilet from "@/components/tabs/Toilet";
    import Navigation from "@/components/tabs/Navigation";
    import ServiceAccecssibility from "@/components/tabs/ServiceAccecssibility";
    import KidsAccecssibility from "@/components/tabs/KidsAccecssibility";

    export default {
        components: {Fields, CrudEdit, Loading},
        async fetch({store, params: {id}}) {
            store.dispatch('crud/edit/reset');
            store.set('crud/edit/apiPath', '/api/admin/addingRequests')
            await Promise.all([
                store.dispatch('crud/edit/loadItem', id),
                store.dispatch('objectAdding/init', id),
            ])
        },
        data() {
            return {
                tab: 0
            }
        },
        computed: {
            ...get('crud/edit', [
                'validationErrors'
            ]),
            ...sync('crud/edit', [
                'isLoading',
                'item',
                'id'
            ]),
            tabs() {
                return [
                    {title: 'Общая информация', key: 'first', component: First},
                    {title: 'Парковка', key: 'parking', component: Parking},
                    {title: 'Входная группа #1', key: 'entrance1', component: Entrance},
                    {title: 'Входная группа #2', key: 'entrance2', component: Entrance},
                    {title: 'Входная группа #3', key: 'entrance3', component: Entrance},
                    {title: 'Пути движения по объекту', key: 'movement', component: Movement},
                    {title: 'Зона оказания услуги', key: 'service', component: Service},
                    {title: 'Туалет', key: 'toilet', component: Toilet},
                    {title: 'Навигация', key: 'navigation', component: Navigation},
                    {title: 'Доступность услуги', key: 'serviceAccessibility', component: ServiceAccecssibility},
                    {title: 'Доступность и безопасность услуг для детей до 7 лет', key: 'kidsAccessibility', component: KidsAccecssibility},
                ].filter(tab => (this.item || {form: {}}).form[tab.key])
            }
        },
        methods: {
            ...call('crud/edit', [
                'reset',
                'loadItem',
                'updateItem',
            ]),
            submitForm: call('crud/edit/submit'),
            async submit() {
                try {
                    await this.submitForm();
                } catch (e) {
                    if (this.validationErrors['first']) {
                        this.tab = 0
                    }
                }
            },
            async approve() {
                try {
                    await this.submitForm();
                    this.isLoading = true;
                    await this.$axios.post(`/api/admin/addingRequests/${this.$route.params.id}/approve`)
                    await this.$store.dispatch('crud/edit/loadItem', this.$route.params.id);
                } catch (e) {
                    if (this.validationErrors['first']) {
                        this.tab = 0
                    }
                }
            }
        },
    }
</script>

<style scoped>

</style>
