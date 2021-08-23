<template>
    <div>
        {{rolesFormatted}}
    </div>
</template>

<script>
    export default {
        name: "Roles",
        props: ['value'],
        computed: {
            rolesFormatted() {
                return this.value.map(role => this.roles[role]).join(', ')
            },
            roles() {
                return {
                    ROLE_USER: 'Пользователь',
                    ROLE_ADMIN: 'Администратор'
                }
            }
        }
    }
</script>

<style scoped>

</style>
