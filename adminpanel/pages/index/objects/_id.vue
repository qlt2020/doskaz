<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Редактирование объекта</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div
                    class="d-flex justify-content-end align-items-center"
                ></div>
            </div>
        </div>
        <b-card>
            <loading :active="isLoading" :is-full-page="false" />

            <object-form :attributes="attributes" :categories="categories">
                <button
                    type="button"
                    class="btn btn-primary"
                    @click.prevent="submit"
                >
                    Сохранить
                </button>
                <a
                    @click="resetCockieCategory"
                    :href="`/objects/${$route.params.id}`"
                    target="_blank"
                    class="btn btn-link"
                    >Просмотр на сайте</a
                >
            </object-form>
        </b-card>
    </div>
</template>

<script>
import { get, sync, call } from "vuex-pathify";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import InputField from "../../../components/crud/fields/InputField";
import TextareaField from "../../../components/crud/fields/TextareaField";
import Select2Field from "../../../components/crud/fields/Select2Field";
import flatMap from "lodash/flatMap";
import MapPointField from "../../../components/crud/fields/MapPoint";
import TextInputCollection from "../../../components/crud/fields/TextInputCollection";
import FormSelector from "../../../components/crud/fields/FormSelector";
import Zone from "../../../components/objects/Zone";
import ImagesCollection from "../../../components/crud/fields/ImagesCollection";
import ObjectForm from "../../../components/objects/ObjectForm";

export default {
    components: {
        ObjectForm,
        ImagesCollection,
        Zone,
        FormSelector,
        TextInputCollection,
        MapPointField,
        Select2Field,
        TextareaField,
        InputField,
        Loading
    },
    data() {
        return {
            category: null,
            tab: 0
        };
    },
    async asyncData({ $axios }) {
        const [{ data: categories }, { data: attributes }] = await Promise.all([
            $axios.get("/api/objectCategories"),
            $axios.get("/api/objects/attributes")
        ]);
        return { categories, attributes };
    },
    async fetch({ store, params: { id } }) {
        store.dispatch("crud/edit/reset");
        store.set("crud/edit/apiPath", "/api/admin/objects");
        await Promise.all([
            store.dispatch("crud/edit/loadItem", id),
            store.dispatch("objectAdding/init")
        ]);
    },
    async mounted() {
        await this.$store.dispatch('nuxtServerInit');
    },
    methods: {
        async resetCockieCategory() {
            await this.$store.dispatch('nuxtServerInit');
            this.$store.dispatch('settings/selectUserCategory', null);
        },
        submitForm: call("crud/edit/submit"),
        async submit() {
            try {
                await this.submitForm();
            } catch (e) {
                //if (this.validationErrors['first']) {
                this.tab = 0;
                // }
            }
        }
    }
};
</script>

<style scoped></style>
