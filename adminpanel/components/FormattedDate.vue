<template>
    <component :is="is">{{ formattedDate }}</component>
</template>

<script>
    import format from "date-fns/format";
    import ru from "date-fns/locale/ru";

    export default {
        name: "DateFormatted",
        props: [
            'element',
            'date',
            'format'
        ],
        computed: {
            is() {
                return this.element || 'span'
            },
            formattedDate() {
                return format(new Date(this.date), this.format, {
                    locale: ru
                });
            },
        }
    }
</script>

<style scoped>

</style>
