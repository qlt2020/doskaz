<template>
    <field-wrapper :error="error" :label="label" :required="required">
       <text-input v-model="value" :placeholder="placeholder" :error="error"/>
    </field-wrapper>
</template>

<script>
    import connectField from "../connectField";
    import FieldWrapper from "../FieldWrapper";
    import TextInput from "../edit-fields/TextInput";
    export default {
        name: "InputField",
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
