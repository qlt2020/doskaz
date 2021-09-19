<template>
    <field-wrapper :error="error" :label="label" :required="required">
        <select class="form-control" v-model="val">
            <option v-for="option in options" :key="option.value" :value="option.value">{{option.title}}</option>
        </select>
    </field-wrapper>
</template>

<script>
    import FieldWrapper from "../FieldWrapper";
    import Selection from "../edit-fields/Selection";
    import get from "lodash/get";

    export default {
        name: "Select2Field",
        components: {Selection, FieldWrapper},
        props: [
            'path',
            'label',
            'required',
            'disabled',
            'value',
            'options'
        ],
        computed: {
            val: {
                get() {
                    return this.path ? get(this.$store.get('crud/edit/item'), this.path) : this.value
                },
                set(v) {
                    if (this.path) {
                        this.$store.commit('crud/edit/SET_PROPERTY_BY_PATH', {value: v, path: this.path})
                    } else {
                        this.$emit('input', v)
                    }
                }
            },
            error() {
                return get(this.$store.get('crud/edit/validationErrors'), this.path)
            }
        }
    }
</script>

<style scoped>

</style>
