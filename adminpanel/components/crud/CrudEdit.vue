<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">{{ title }}</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <!--<nuxt-link :to="`${$route.path}/create`" class="btn btn-info d-none d-lg-block m-l-15" v-if="create"><i
                        class="fa fa-plus-circle"></i> Создать
                    </nuxt-link>-->
                </div>
            </div>
        </div>
        <b-card>
            <loading :active="isLoading" :is-full-page="false"/>
            <edit-form :fields="fields" :editBasePath="editBasePath"/>
        </b-card>
    </div>
</template>

<script>
    import {call, get, sync} from 'vuex-pathify'
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import EditForm from "./EditForm";


    export default {
        name: "CrudEdit",
        components: {EditForm, Loading},
        props: {
            title: String,
            apiPath: String,
            fields: {
                type: Array,
                default() {
                    return []
                }
            },
            editBasePath: String
        },
        async beforeMount() {
            this.reset();
            this.$store.set('crud/edit/apiPath', this.apiPath)
            if (this.$route.params.id) {
                await this.loadItem(this.$route.params.id);
            }
        },
        methods: {
            ...call('crud/edit/', [
                'reset',
                'loadItem'
            ])
        },
        computed: {
            ...get('crud/edit/', [
                'isLoading',
                'item'
            ])
        }
    }
</script>

<style scoped>

</style>
