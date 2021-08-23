<template>
    <div>
        <normal-object-view :zones="zones" v-if="!viModeEnabled" :mobileOpened="mobileOpened"/>
        <vi-object-view :zones="zones" v-if="viModeEnabled"/>
    </div>
</template>

<script>
    import {sync, get, call} from "vuex-pathify";
    import map from 'lodash/map'
    import objectZones from "~/objectZones";
    import NormalObjectView from "~/components/objects/NormalObjectView";
    import ViObjectView from "~/components/objects/ViObjectView";

    export default {
        components: {ViObjectView, NormalObjectView},
        layout({store}) {
            return store.get('visualImpairedModeSettings/enabled') ? 'default' : 'main'
        },
        props: [
            'mobileOpened'
        ],
        head() {
            return {
                title: this.object.title
            }
        },
        async fetch({store, params, query, error}) {
            try {
                await store.dispatch('objectAdding/init')
                await store.dispatch('object/load', params.id)
                store.commit('map/SET_COORDINATES_AND_ZOOM', {
                    coordinates: store.state.object.item.coordinates,
                    zoom: process.server ? 19 : query.zoom
                })
            } catch (e) {
                if (e.response && e.response.status) {
                    return error({statusCode: e.response.status})
                }
                return error({})
            }
        },
        computed: {
            object: get('object/item'),
            ...sync('map', [
                'coordinates',
                'zoom',
                'coordinatesAndZoom'
            ]),
            zones() {
                return objectZones
            },
            viModeEnabled: get('visualImpairedModeSettings/enabled'),
            userCategory: get('disabilitiesCategorySettings/currentCategory'),
        },
        watch: {
            '$route.query.t'() {
                this.coordinatesAndZoom = {
                    coordinates: this.object.coordinates,
                    zoom: this.$route.query.zoom
                }
            },
            userCategory() {
                this.reloadObject()
            }
        },
        destroyed() {
            this.coordinatesAndZoom = null;
        },
        methods: {
            reloadObject: call('object/reload')
        }
    };
</script>