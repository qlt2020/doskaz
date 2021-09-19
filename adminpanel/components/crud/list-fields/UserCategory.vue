<template>
    <div>
        {{rolesFormatted}}
    </div>
</template>

<script>
    export default {
        name: "UserCategory",
        props: ['value'],
        computed: {
            rolesFormatted() {
                return this.value ? this.roles[this.value] : ''
            },
            roles() {
                return {
                    movement: "Люди, передвигающиеся на кресло-коляске",
                    vision: "Люди с инвалидностью по зрению",
                    limb: "Люди с нарушениями опорно-двигательного аппарата",
                    hearing: "Люди с инвалидностью по слуху",
                    temporal: "Временно травмированные люди",
                    babyCarriage: "Люди с детскими колясками",
                    missingLimbs: "Люди с отсутствующими конечностями",
                    pregnant: "Беременные женщины",
                    intellectual: "Люди с интеллектуальной инвалидностью",
                    agedPeople: "Пожилые люди",
                    withChild: "Семьи с детьми до 7 лет",
                    justView: "Просто посмотреть"
                }
            }
        }
    }
</script>

<style scoped>

</style>
