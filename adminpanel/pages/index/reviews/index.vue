<template>
    <crud-list
        title="Отзывы на объект"
        api-path="/api/admin/reviews"
        :table-fields="fields"
        :actions="actions"
        @accept="accept"
        @decline="decline"
    >
    </crud-list>
</template>

<script>
import CrudList from "~/components/crud/CrudList";
import ReviewStatus from "~/components/crud/list-fields/ReviewStatus";
import ObjectsFilter from "../../../components/objects/ObjectsFilter";
import {call} from "vuex-pathify";
export default {
    components: {ObjectsFilter, CrudList},
    computed: {
        actions() {
            if(this.$store.state.authentication.user.roles.includes('ROLE_ADMIN')) {
                return ['accept', 'decline']
            }
            return []
        },
        fields() {
            return [
                {key: 'author', label: 'Пользователь',},
                {key: 'text', label: 'Текст отзыва'},
                {key: 'is_published', label: 'Статус', type: ReviewStatus},
            ]
        }
    },
    methods: {
        ...call('crud/list/*'),
        async decline(id) {
            await this.$axios.get(`/api/admin/reviews/decline/${id}`);
            await this.load()
        },
        async accept(id) {
            await this.$axios.get(`/api/admin/reviews/accept/${id}`);
            await this.load()
        }
    }
}
</script>

<style scoped>

</style>
