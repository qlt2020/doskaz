<template>
    <div>
        <field-wrapper v-for="(field, index) in formFields" :key="index" :label="field.label"
                       :error="error[field.key]"
                       :required="field.required">
            <component
                :is="field.type"
                :value="value[field.key]"
                @input="update(field.key, $event)"
                :disabled="field.disabled"
                :options="field.options"
                :label="field.label"
            />
        </field-wrapper>
    </div>
</template>

<script>
    import TextInput from "./edit-fields/TextInput";
    import FieldWrapper from "./FieldWrapper";

    export default {
        name: "Fields",
        components: {FieldWrapper},
        beforeMount() {
            const initialValues = {}
            this.formFields.forEach(field => {
                if(field.defaultValue) {
                    if(!this.value[field.key]) {
                        initialValues[field.key] = field.defaultValue
                    }
                }
            })
            if(Object.values(initialValues).length) {
                this.$emit('input', {
                    ...this.value,
                    ...initialValues
                })
            }
        },
        props: {
            value: {
                type: Object,
                default() {
                    return {}
                }
            },
            fields: Array,
            error: {
                default() {
                    return {}
                }
            }
        },
        methods: {
            update(key, val) {
                this.$emit('input', {
                    ...this.value,
                    [key]: val
                })
            }
        },
        computed: {
            formFields() {
                return this.fields.map(field => {
                    return {
                        key: field.key,
                        type: field.type || TextInput,
                        label: field.label,
                        required: field.required,
                        disabled: field.disabled,
                        options: field.options,
                        defaultValue: field.defaultValue
                    }
                })
            }
        },
    }
</script>

<style scoped>

</style>
