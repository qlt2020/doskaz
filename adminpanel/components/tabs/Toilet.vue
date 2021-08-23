<template>
    <div>
        <attributes-list :form="form" zone="toilet" :value="value.attributes" @input="update"/>
        <textarea-field label="Комментарий" :path="`${path}.comment`" :disabled="true"/>

        <h4>Оценка доступности</h4>
        <hr/>
        <accessibility-score :allow-override="true" :value="value" @input="overrideScore" :type="`toilet_${form}`"/>
    </div>
</template>

<script>
    import AttributeSelectionField from "../crud/fields/AttributeSelectionField";
    import {get} from "vuex-pathify";
    import AccessibilityScore from "../AccessibilityScore";
    import _ from "lodash";
    import TextareaField from "../crud/fields/TextareaField";
    import AttributesList from "../AttributesList";
    export default {
        name: "Toilet",
        components: {AttributesList, TextareaField, AccessibilityScore, AttributeSelectionField},
        props: ['path', 'form'],
        computed: {
            item: get('crud/edit/item'),
            value() {
                return _.get(this.item, this.path)
            }
        },
        methods: {
            update(val) {
                this.$store.commit('crud/edit/SET_PROPERTY_BY_PATH', {value: val, path: `${this.path}.attributes`})
            },
            overrideScore(val) {
                this.$store.commit('crud/edit/SET_PROPERTY_BY_PATH', {value: val, path: this.path})
            }

        }
    }
</script>

<style scoped>

</style>
