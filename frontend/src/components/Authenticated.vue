<template>
    <div>
        <slot v-if="isAuthenticated"/>
    </div>
</template>

<script>
    export default {
        name: "Authenticated",
        data() {
            return {}
        },
        async mounted() {
            if (!this.isAuthenticated) {
                try {
                    await this.$store.dispatch('loadUser')
                } catch (e) {
                    if (e.response.status === 401) {
                        await this.$router.push({
                            name: "login",
                            query: {next: this.$route.fullPath}
                        });
                    }
                }
            }
        },
        computed: {
            isAuthenticated() {
                return !!this.$store.state.authentication.user
            }
        }
    }
</script>

<style scoped>

</style>