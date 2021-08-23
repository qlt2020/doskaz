<template>
    <div>
        <attributes-list :form="form" v-model="zoneAttributes" :zone="zone" :key="`${tab.key}_${form}`"/>
        <h4>Оценка доступности</h4>
        <hr/>
        <accessibility-score allow-override  v-model="zoneValue" :type="`${zone}_${form}`"/>
    </div>
</template>

<script>
    import AttributesList from "../AttributesList";
    import {sync} from 'vuex-pathify'
    import AccessibilityScore from "../AccessibilityScore";
    export default {
        name: "Zone",
        components: {AccessibilityScore, AttributesList},
        props: [
            'form',
            'zone',
            'zoneProperty'
        ],
        data() {
            return {
                tab: 0
            }
        },
        computed: {
            zoneValue: sync('crud/edit/item@zones[:zoneProperty]'),
            zoneAttributes: sync('crud/edit/item@zones[:zoneProperty].attributes'),
        }
    }
</script>

<style scoped>

</style>
