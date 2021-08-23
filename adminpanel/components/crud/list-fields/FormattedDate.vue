<template>
    <div>
        {{ formattedValue }}
    </div>
</template>

<script>
    import {format} from 'date-fns'

    export default {
        name: "FormattedDate",
        props: {
            value: String,
            options: {
                type: Object,
                default() {
                    return {
                        format: 'yyyy-MM-dd HH:mm'
                    }
                }
            }
        },
        computed: {
            formattedValue() {
                return this.value ? format(new Date(this.value), this.options.format) : ''
            }
        }
    }
</script>

<style scoped>

</style>
