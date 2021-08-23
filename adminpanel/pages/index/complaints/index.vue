<template>
    <crud-list
        title="Жалобы"
        :create="false"
        api-path="/api/complaints"
        :table-fields="fields"
        :actions="[]"
        :custom-actions="actions"
    />
</template>

<script>
    import CrudList from "@/components/crud/CrudList";
    import FormattedDate from "@/components/crud/list-fields/FormattedDate";
    import ExportComplaintAction from "@/components/ExportComplaintAction";
    export default {
        components: {CrudList},
        middleware: ['authenticated'],
        computed: {
            fields() {
                return [
                    {key: 'fullName', label: 'Заявитель'},
                    {key: 'createdAt', type: FormattedDate, label: 'Дата подачи'}
                ]
            },
            actions() {
                return [
                    ExportComplaintAction
                ]
            }
        }
    }
</script>

<style scoped>

</style>
