<template>
    <li>
        <nuxt-link exact :to="path" :class="{active: isActive}">
            <slot/>
        </nuxt-link>
    </li>
</template>

<script>
    export default {
        name: "SidebarLink",
        props: [
            'path',
        ],
        computed: {
            isActive() {
                return this.$route.fullPath.startsWith(this.path)
            }
        }
    }
</script>

<style scoped>

</style>
