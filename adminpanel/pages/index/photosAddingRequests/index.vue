<template>
    <crud-list
        title="Запросы на добавление фото"
        api-path="/api/admin/photosAdding"
        :table-fields="fields"
        :actions="actions"
        :create="false"
    >
    </crud-list>
</template>

<script>
import CrudList from "~/components/crud/CrudList";
import FormattedDate from "~/components/crud/list-fields/FormattedDate";
import EditLink from "~/components/crud/list-fields/EditLink";
import ObjectsFilter from "../../../components/objects/ObjectsFilter";
import ObjectLink from "@/components/crud/list-fields/ObjectLink";

export default {
    components: {ObjectsFilter, CrudList},
    computed: {
        actions() {
            return ['edit', 'delete']
        },
        fields() {
            return [
                {key: 'object', label: 'Объект', type: ObjectLink},
                {key: 'createdAt', label: 'Дата поступления', type: FormattedDate},
            ]
        }
    }
}
</script>

<style scoped>

</style>
