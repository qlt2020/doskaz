<template>
    <yandex-map
        @click="click"
        :style="{width: '100%', height: '400px'}"
        :zoom="5"
        :coords="coords"
        :controls="[]"
        :use-object-manager="true"
        @map-was-initialized="mapWasInitialized"
        :settings="{
                    apiKey: 'c1050142-1c08-440e-b357-f2743155c1ec',
                    lang: 'ru_RU',
                    coordorder: 'latlong',
                    version: '2.1'
                }">
        <ymap-marker :coords="value" v-if="value" marker-id="point"></ymap-marker>
    </yandex-map>
</template>

<script>
    import { yandexMap, ymapMarker } from 'vue-yandex-maps'

    export default {
        components: { yandexMap, ymapMarker },
        name: "MapPoint",
        props: ['value'],
        data() {
            return {
                mapCoords: [47.74887674893552, 67.04712168264118],
                test: []
            }
        },
        methods: {
            mapWasInitialized(map) {
                this.map = map;
                if(this.value) {
                    map.setCenter(this.value, 17)
                }
            },
            click(e) {
                const point = e.get('coords');
                this.$emit('input', point)
                this.mapCoords = point;
                ymaps.geocode(point).then(res => {
                    const result = res.geoObjects.get(0);
                    if(result.getThoroughfare()) {
                        const address = [result.getThoroughfare(), result.getPremiseNumber()].filter(item => !!item).join(', ')
                        this.$emit('address', address)
                    } else {
                        this.$emit('address', '')
                    }
                });
            }
        },
        watch: {
            mapCoords: {
                handler(val) {
                    this.map.setCenter(val, 17)
                },
            }
        },
        computed: {
            settings() {
                return {
                    apiKey: 'c1050142-1c08-440e-b357-f2743155c1ec',
                    lang: 'ru_RU',
                    coordorder: 'latlong',
                    version: '2.1'
                }
            },
            coords() {
                return this.value || [47.74887674893552, 67.04712168264118]
            }
        }
    }
</script>

<style scoped>

</style>
