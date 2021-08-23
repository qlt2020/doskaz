<template>
    <UserTickets :complaints="items" :pages="pages"/>
</template>

<script>
    import UserTickets from "~/components/user/UserTickets";

    export default {
        components: {
            UserTickets
        },
        async asyncData({$axios, query}) {
            const {data} = await $axios.get('/api/profile/complaints', {
                params: {
                    ...query,
                    page: query.page || 1
                }
            })
            return data
        },
        watchQuery: true
    };
</script>

<style lang="scss">
    @import "@/styles/mixins.scss";

    .user-page {
        .main-header__content {
            border: none;
        }
    }
</style>