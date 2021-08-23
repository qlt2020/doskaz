<template>
    <nuxt/>
</template>

<script>

</script>

<style lang="scss">
    @import "@/styles/mixins.scss";

    .complaint {
        &__wrapper {
            padding: 30px 0 60px;
            background: #F1F8FC;
        }
        &__top {
            padding: 46px 0 23px;
        }
        &__pre-text {
            margin: 43px 0 0;
            font-size: 16px;
            line-height: 30px;
            &.--required {
                margin: 19px 0 0;
                span {color:#E0202E;}
            }
        }
    }
</style>