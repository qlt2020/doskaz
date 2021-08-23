<template>
    <crud-list
        title="Обратная связь"
        :create="false"
        api-path="/api/admin/feedback"
        :table-fields="fields"
        :actions="['delete']"
    />
</template>

<script>
import CrudList from "@/components/crud/CrudList";
import FormattedDate from "@/components/crud/list-fields/FormattedDate";

export default {
    components: {CrudList},
    middleware: ['authenticated'],
    computed: {
        fields() {
            return [
                {key: 'name', label: 'Имя'},
                {key: 'email', label: 'email'},
                {key: 'text', label: 'Текст'},
                {key: 'created_at', type: FormattedDate, label: 'Дата'}
            ]
        }
    }
}
</script>

<style scoped>

</style>
