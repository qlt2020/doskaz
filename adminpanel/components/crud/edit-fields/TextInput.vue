<template>
    <input type="text" class="form-control" :class="{'is-invalid': error}" :placeholder="placeholder" v-model="val"/>
</template>

<script>
    export default {
        name: "TextInput",
        props: [
            'error',
            'value',
            'placeholder'
        ],
        computed: {
            val: {
                get() {
                    return this.value
                },
                set(v) {
                    this.$emit('input', v)
                }
            }
        }
    }
</script>

<style scoped>

</style>
