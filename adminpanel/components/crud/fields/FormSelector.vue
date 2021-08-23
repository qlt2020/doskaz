<template>
    <field-wrapper :error="error" :label="label" :required="required">

    </field-wrapper>
</template>

<script>
    import connectField from "../connectField";
    import FieldWrapper from "../FieldWrapper";
    import TextInput from "../edit-fields/TextInput";
    export default {
        name: "FormSelector",
        props: [
            'path',
            'label',
            'required',
            'placeholder'
        ],
        components: {TextInput, FieldWrapper},
        computed: {
            ...connectField()
        }
    }
</script>

<style scoped>

</style>
