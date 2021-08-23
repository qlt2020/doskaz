<template>
    <field-wrapper :error="error" :label="label" :required="required">
        <map-point v-model="value" @address="$emit('address', $event)"/>
    </field-wrapper>
</template>

<script>
    import FieldWrapper from "../FieldWrapper";
    import MapPoint from "../edit-fields/MapPoint";
    import connectField from "../connectField";

    export default {
        name: "MapPointField",
        components: {MapPoint, FieldWrapper},
        props: [
            'path',
            'label',
            'required'
        ],
        computed: {
            ...connectField()
        }
    }
</script>

<style scoped>

</style>
