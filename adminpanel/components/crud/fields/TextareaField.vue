<template>
    <field-wrapper :error="error" :label="label" :required="required">
        <textarea-input v-model="value" :error="error" :disabled="disabled"/>
    </field-wrapper>
</template>

<script>
    import connectField from "../connectField";
    import TextareaInput from "../edit-fields/TextareaInput";
    import FieldWrapper from "../FieldWrapper";

    export default {
        name: "TextareaField",
        components: {FieldWrapper, TextareaInput},
        props: [
            'path',
            'label',
            'required',
            'disabled'
        ],
        computed: {
            ...connectField()
        }
    }
</script>

<style scoped>

</style>
