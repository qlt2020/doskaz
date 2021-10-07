<template>
    <div>
        {{status}}
    </div>
</template>

<script>
export default {
    name: "ReviewStatus",
    props: ['value'],
    computed: {
        status() {
            return this.statuses[this.value]
        },
        statuses() {
            return {
                false: 'Не опубликован',
                true: 'Опубликован'
            }
        }
    }
}
</script>

<style scoped>

</style>
