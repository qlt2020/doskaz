<template>
    <crud-list
        title="Объекты"
        api-path="/api/admin/objects"
        :table-fields="fields"
        :actions="actions"
    >
        <objects-filter slot="filter" />
    </crud-list>
</template>

<script>
import CrudList from "~/components/crud/CrudList";
import FormattedDate from "~/components/crud/list-fields/FormattedDate";
import EditLink from "~/components/crud/list-fields/EditLink";
import ObjectsFilter from "../../../components/objects/ObjectsFilter";
export default {
    components: { ObjectsFilter, CrudList },
    computed: {
        actions() {
            //   if(this.$store.state.authentication.user.roles.includes('ROLE_ADMIN')) {
            //       return ['edit', 'delete']
            //   }
            return ["edit"];
        },
        fields() {
            return [
                { key: "title", label: "Наименование", type: EditLink },
                { key: "address", label: "Адрес" },
                { key: "city", label: "Город" },
                { key: "category", label: "Категория" },
                { key: "createdBy", label: "Пользователь" },
                {
                    key: "createdAt",
                    label: "Дата создания",
                    type: FormattedDate
                }
            ].filter(i => {
                return i.key === "createdBy";
                // ? this.$store.state.authentication.user.roles.includes(
                //       "ROLE_ADMIN"
                //   )
                // : true;
            });
        }
    }
};
</script>

<style scoped></style>
