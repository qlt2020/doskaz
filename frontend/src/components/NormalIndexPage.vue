<template>
    <div>
        <sidebar :posts="posts" :events="events"/>
        <post-submit-message/>
        <post-addition-message/>
    </div>
</template>

<script>
import Sidebar from "~/components/Sidebar";
import PostSubmitMessage from "~/components/complaint/PostSubmitMessage";
import PostAdditionMessage from "~/components/object_add/PostAdditionMessage";

export default {
    name: "NormalIndexPage",
    components: {PostAdditionMessage, PostSubmitMessage, Sidebar},
    data() {
        return {
            posts: [],
            events: []
        }
    },
    async fetch() {
        const [{items: posts}, events] = await Promise.all([
            this.$axios.$get('/api/blog/posts'),
            this.$axios.$get('/api/events')
        ])
        this.posts = posts;
        this.events = events
    }
}
</script>

<style scoped>

</style>