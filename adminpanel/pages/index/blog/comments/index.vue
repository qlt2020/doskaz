<template>
    <crud-list
        title="Комментарии к медиатеке"
        api-path="/api/admin/comments"
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
import {call} from "vuex-pathify";
export default {
    components: {CrudList},
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
            await this.$axios.get(`/api/admin/comments/decline/${id}`);
            await this.load()
        },
        async accept(id) {
            await this.$axios.get(`/api/admin/comments/accept/${id}`);
            await this.load()
        }
    }
}
</script>

<style scoped>

</style>
