<template>
    <user-comments :pages="pages" :items="items"/>
</template>

<script>
    import UserComments from "~/components/user/UserComments";

    export default {
        head() {
            return {
                title: this.$t('profile.comments.tabTitle')
            }
        },
        components: {
            UserComments
        },
        async asyncData({$axios, query}) {
            const {data} = await $axios.get('/api/profile/comments', {
                params: {
                    page: query.page || 1,
                    sort: query.sort
                }
            })
            return data
        },
        watchQuery: ['page', 'sort']
    };
</script>