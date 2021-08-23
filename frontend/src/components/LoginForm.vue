<template>
    <div class="login-form isOpened">
        <div class="login-form__bg"></div>
        <div class="login-form__content">
            <div class="login-form__card">
                <div class="close" @click="loginFormClose">
                    <svg
                            width="20"
                            height="20"
                            viewBox="0 0 20 20"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M16.6667 4.008e-06L20 3.33334L13.3333 10L20 16.6667L16.6667 20L10 13.3333L3.33333 20L0 16.6667L6.66667 10L0 3.33333L3.33333 0L10 6.66667L16.6667 4.008e-06Z"
                                fill="white"
                        />
                    </svg>
                </div>
                <div class="header">
                    <div class="header__title">
                        <span>{{ $t('login.popupTitle') }}</span>
                    </div>
                    <div class="header__img">
                        <img :src="require('~/assets/login-logo.svg')" alt/>
                    </div>
                </div>

                <template v-if="!$route.query.popup">
                    <login-social-buttons/>
                    <div class="phone-form">
                        <div class="phone-form__switch" @click="showPhoneAuthForm = true" :class="{active: showPhoneAuthForm}">
                            <span>{{ $t('login.phoneLoginAdvice') }}</span>
                        </div>
                        <phone-auth-form v-if="showPhoneAuthForm"/>
                    </div>
                </template>
                <div class="phone-form" v-else style="margin-top: 40px">
                    <div class="phone-form__switch">
                        <span>{{ $t('login.profileFillingAdvice') }}</span>
                        <phone-auth-form-points/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PhoneAuthForm from "./PhoneAuthForm";
    import PhoneAuthFormPoints from "./PhoneAuthFormPoints";
    import LoginSocialButtons from "./LoginSocialButtons";

    export default {
        components: {LoginSocialButtons, PhoneAuthForm, PhoneAuthFormPoints},
        data() {
            return {
                showPhoneAuthForm: false
            }
        },
        methods: {
            loginFormClose() {
                this.$router.push(this.localePath({name: 'index'}))
            }
        },
    };
</script>

<style lang="scss">
    @import "./../styles/mixins.scss";

    .login-form {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1100;
        display: none;

        &.isOpened {
            display: block;
        }

        &__bg {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            z-index: 0;
            background: rgba(0, 0, 0, 0.7);
        }

        &__content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        &__card {
            background: $white;
            position: relative;
            width: 550px;

            .close {
                position: absolute;
                top: 0;
                right: -40px;
                cursor: pointer;
                transition: opacity 0.3s;

                &:hover {
                    opacity: 0.7;
                }
            }

            .header {
                width: 100%;
                height: 140px;
                background: #d5e9fc;
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                padding: 10px 10px 10px 40px;

                &__title {
                    font-weight: bold;
                    font-size: 22px;
                    line-height: 30px;
                    color: #333333;
                    width: 300px;
                    padding-bottom: 10px;
                }
            }

            .buttons {
                padding: 40px 40px 30px;
                display: flex;
                align-items: center;
                justify-content: space-between;

                .button {
                    width: 50px;
                    height: 50px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    border: 1px solid #7b95a7;
                    background: $tr;
                    transition: opacity 0.3s;
                    cursor: pointer;

                    &:hover {
                        opacity: 0.7;
                    }

                    &.button_google {
                        width: 260px;
                        background: #0f6bf5;
                        border: none;

                        svg {
                            margin-right: 10px;
                        }

                        span {
                            font-weight: bold;
                            font-size: 16px;
                            line-height: 20px;
                            text-align: center;
                            color: #ffffff;
                        }
                    }

                    svg {
                        display: block;
                    }
                }
            }

            .phone-form {
                padding: 0 40px 40px;

                &__switch {
                    font-weight: bold;
                    font-size: 16px;
                    line-height: 20px;
                    text-align: center;
                    color: #333333;
                    cursor: pointer;
                }
            }
        }
    }
</style>