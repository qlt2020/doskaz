<template>
    <div class="add-object">
        <ViTop/>
        <MainHeader/>
        <div class="container">
            <div class="complaint__top">
                <h2 class="title">{{ $t('objectAdding.pageHeader') }}</h2>
                <div class="add-object__link-b">
                    <span class="add-object__link"
                          v-for="form in forms"
                          :key="form.key"
                          @click="changeForm(form.key)"
                          :class="{active: form.key === selectedForm}"
                    >
                        {{ $t(`objectAdding.formType.${form.key}`) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="complaint__wrapper">
            <ObjectAddContent/>
        </div>
    </div>
</template>

<script>
    import MainHeader from "@/components/MainHeader";
    import ObjectAddContent from "@/components/object_add/ObjectAddContent.vue";
    import {get, call} from 'vuex-pathify';
    import ViTop from "@/components/ViTop";

    export default {
        head() {
            return {
                title: this.$t('objectAdding.pageHeader')
            }
        },
        middleware: ['authenticated'],
        layout: 'complaint',
        components: {
            MainHeader,
            ObjectAddContent,
            ViTop
        },
        async fetch({store}) {
            return store.dispatch('objectAdding/init')
        },
        methods: {
            ...call('objectAdding', [
                'changeForm'
            ])
        },
        computed: {
            forms: get('objectAdding/forms'),
            selectedForm: get('objectAdding/data@form')
        }
    };

</script>

<style lang="scss">
    @import "@/styles/mixins.scss";
</style>