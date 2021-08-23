<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Создание объекта</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center"></div>
            </div>
        </div>
        <b-card>
            <object-form :attributes="attributes" :categories="categories"/>
            <button type="button" class="btn btn-primary" @click.prevent="submit">Сохранить</button>
        </b-card>
    </div>
</template>

<script>
    import ObjectForm from "../../../components/objects/ObjectForm";
    import {call, get} from "vuex-pathify";
    export default {
        components: {ObjectForm},
        async asyncData({$axios}) {
            const [{data: categories}, {data: attributes}] = await Promise.all([
                $axios.get('/api/objectCategories'),
                $axios.get('/api/objects/attributes'),
            ]);
            return {categories, attributes}
        },
        async fetch({store}) {
            store.dispatch('crud/edit/reset');
            store.set('crud/edit/apiPath', '/api/admin/objects')
            store.set('crud/edit/item', {
                title: null,
                description: null,
                categoryId: null,
                address: null,
                point: null,
                videos: [],
                photos: [],
                zones: {
                    type: 'full',
                    parking: {
                        attributes: {},
                        overriddenScore: null,
                    },
                    entrance1: {
                        attributes: {},
                        overriddenScore: null,
                    },
                    entrance2: null,
                    entrance3: null,
                    movement: {
                        attributes: {},
                        overriddenScore: null,
                    },
                    navigation: {
                        attributes: {},
                        overriddenScore: null,
                    },
                    service: {
                        attributes: {},
                        overriddenScore: null,
                    },
                    toilet: {
                        attributes: {},
                        overriddenScore: null,
                    },
                    serviceAccessibility: {
                        attributes: {},
                        overriddenScore: null,
                    }
                }
            })
            await Promise.all([
                store.dispatch('objectAdding/init')
            ])
        },
        computed: {
            item: get('crud/edit/item')
        },
        methods: {
            submitForm: call('crud/edit/submit'),
            async submit() {
                console.log(this.$bvToast)
                await this.submitForm();
                if(this.item.id) {
                    await this.$nuxt.$router.replace(`/objects/${this.item.id}`)
                }
            }
        }
    }
</script>

<style scoped>

</style>
