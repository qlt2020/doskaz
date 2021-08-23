<template>
    <client-only>
        <profile-edit :profile="profile"/>
    </client-only>
</template>

<script>

    import ProfileEdit from "~/components/user/ProfileEdit";

    export default {
        components: {ProfileEdit},
        async asyncData({$axios}) {
            const {data: profile} = await $axios.get('/api/profile')
            return {
                profile
            }
        },
        head() {
            return {
                title: this.$t('profile.edit.pageTitle')
            }
        },
    }
</script>

<style lang="scss">
    @import "@/styles/mixins.scss";

    .--flex {
        display: flex;

        &-col {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            -webkit-box-flex: 1;
            flex-grow: 1;
            max-width: 100%;

            &.--sm {
                min-width: 110px;
                max-width: 110px;
            }
        }
    }

    .user-page {
        &__sms {
            padding: 0 0 0 40px;
            display: flex;
            align-items: center;
            height: 100%;
            cursor: pointer;
            transition: opacity 0.4s;

            &:hover {
                opacity: 0.7;
            }

            &-img {
                margin: 0 10px 0 0;
            }

            &-text {
                font-size: 14px;
                line-height: 14px;
            }
        }

        &__edit {
            padding: 26px 0 0;
            width: 560px;
            @media all and (max-width: 1023px){
                width: 100%;
                padding: 26px 0;
            }
        }

        &__title {
            margin: 0 0 30px;
            @media all and (max-width: 1023px){
                font-size: 18px;
                line-height: 20px;
                margin: 0 0 22px;
            }
        }

        &__text {
            font-size: 14px;
            line-height: 20px;

            &.--line {
                margin: 0;
            }
        }

        &__label {
            font-size: 16px;
            line-height: 17px;
            font-weight: 700;
            display: block;
            padding: 0 20px 0 0;
            @media all and (max-width: 1023px){
                font-size: 10px;
                line-height: 20px;
                color: #5B6067;
            }
        }

        &__button {
            width: 170px;
            line-height: 50px;
            color: #fff;
            font-size: 14px;
            border: none;
            outline: none;
            background: $blue;
            cursor: pointer;
            -webkit-transition: opacity 0.4s;
            -moz-transition: opacity 0.4s;
            -ms-transition: opacity 0.4s;
            -o-transition: opacity 0.4s;
            transition: opacity 0.4s;

            &:hover {
                opacity: 0.7;
            }
        }

        &__line {
            display: flex;
            position: relative;
            margin: 40px 0 0;
            @media all and (max-width: 1023px){
                margin: 22px 0 0;
                display: block;
            }
            .error-msg {
                display: none;
                position: absolute;
                left: 0;
                top: 100%;
                margin: 5px 0 0;
                font-size: 14px;
                line-height: 20px;
                color: $red;
            }

            .input {
                input {
                    font-size: 14px;
                }

                &.error {
                    border-color: $red;
                    position: relative;

                    .error-msg {
                        display: block;
                    }
                }
            }

            &.disabled {
                opacity: 0.5;
            }

            .col {
                position: relative;
                -ms-flex-preferred-size: 0;
                flex-basis: 0;
                -ms-flex-positive: 1;
                -webkit-box-flex: 1;
                flex-grow: 1;
                max-width: 100%;

                &.--label {
                    min-width: 150px;
                    max-width: 150px;
                    display: flex;
                    align-items: center;
                }
            }
        }
    }
</style>