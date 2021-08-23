<template>
    <b-button size="sm" variant="danger" target="_blank" @click="confirmDelete()">Удалить</b-button>
</template>

<script>
    import {call} from 'vuex-pathify'

    export default {
        name: "Delete",
        props: ['item'],
        methods: {
            confirmDelete() {
                if (confirm('Вы действительно хотите удалить эту запись?')) {
                    this.deleteItem(this.item)
                }
            },
            deleteItem: call('crud/list/delete')
        }
    }
</script>

<style scoped>

</style>
