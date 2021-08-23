<template>
    <textarea v-model="val" class="form-control" rows="5" :class="{'is-invalid': error}"></textarea>
</template>

<script>
    export default {
        name: "TextareaInput",
        props: [
            'value',
            'error'
        ],
        computed: {
            val: {
                get() {
                    return this.value
                },
                set(v) {
                    this.$emit('input', v)
                }
            },
        }
    }
</script>

<style scoped>

</style>
