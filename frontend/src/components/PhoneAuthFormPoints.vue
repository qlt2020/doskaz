<template>
    <div class="phone-form__point-b">
        <div class="phone-form__point">
            <span class="phone-form__point-num">20</span>
            <span class="phone-form__point-text">За регистрацию вам начислено 20 баллов</span>
        </div>
        <div class="phone-form__point">
            <span class="phone-form__point-num">50</span>
            <span class="phone-form__point-text">Заполните информацию о себе и получите ещё 50 баллов</span>
        </div>
        <nuxt-link :to="localePath('/profile')" class="profile-link-button">{{ $t('login.profileLinkTitle') }}
        </nuxt-link>
    </div>
</template>

<script>
    export default {

        data() {
            return {};
        },

    }
</script>

<style lang="scss" scoped>
    @import "./../styles/mixins.scss";

    .phone-form {
        padding-top: 30px;

        &__point {
            margin: 30px 0;
            padding: 0 110px 0 90px;
            display: flex;

            &-num {
                background: #D5E9FC;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                font-size: 22px;
                line-height: 50px;
                color: #0F6BF5;
                font-weight: 700;
            }

            &-text {
                font-size: 14px;
                line-height: 20px;
                color: #5B6067;
                width: calc(100% - 50px);
                font-weight: 400;
                padding: 0 0 0 10px;
                text-align: left;
                display: flex;
                align-items: center;
            }
        }

        .profile-link-button {
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 50px;
            background: #0F6BF5;
            transition: opacity 0.3s;
            cursor: pointer;
            border: none;

            &:hover {
                opacity: 0.7;
            }
        }
    }
</style>