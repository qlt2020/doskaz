<template>
    <b-form-checkbox v-model="val">{{ label }}</b-form-checkbox>
</template>

<script>
    export default {
        name: "Checkbox",
        props: [
            'value',
            'label'
        ],
        computed: {
            val: {
                get() {return this.value},
                set(v) {this.$emit('input', v)}
            }
        }
    }
</script>

<style scoped>

</style>
