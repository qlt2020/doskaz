<template>
    <div class="phone-form__form">
        <div class="input" :class="{input_error: phoneError}">
            <input type="text" :placeholder="$t('login.phoneNumberInputPlaceholder')" v-mask="'+7##########'" v-model="phoneAuth.number" :disabled="confirmationResult"/>
            <div class="input__error-text">
                <span>{{ phoneError }}</span>
            </div>
        </div>
        <div class="input" v-if="confirmationResult" :class="{input_error: codeError}">
            <input type="text" :placeholder="$t('login.smsCodeInputPlaceholder')" v-model="phoneAuth.code"/>
            <div class="input__error-text">
                <span>{{ codeError }}</span>
            </div>
        </div>
        <div class="help">
            <svg
                    width="36"
                    height="32"
                    viewBox="0 0 36 32"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
            >
                <path
                        d="M23.4911 1.2832H12.6883C11.3424 1.2832 10.2444 2.3812 10.2444 3.72712V28.2726C10.2444 29.6185 11.3424 30.7165 12.6883 30.7165H23.4911C24.8371 30.7165 25.9351 29.6185 25.9351 28.2726V23.3493V21.4013V3.72712C25.9705 2.3812 24.8725 1.2832 23.4911 1.2832Z"
                        stroke="#0F6BF5"
                        stroke-width="1.5"
                        stroke-miterlimit="10"
                        stroke-linecap="round"
                />
                <path
                        d="M29.5478 20.5158C30.1145 19.1344 30.3979 17.6114 30.3979 15.9821C30.3979 14.3529 30.0791 12.8653 29.5478 11.4485"
                        stroke="#0F6BF5"
                        stroke-width="1.5"
                        stroke-miterlimit="10"
                        stroke-linecap="round"
                />
                <path
                        d="M6.63164 11.484C6.06494 12.8654 5.78159 14.3884 5.78159 16.0177C5.78159 17.6469 6.10036 19.1345 6.63164 20.5513"
                        stroke="#0F6BF5"
                        stroke-width="1.5"
                        stroke-miterlimit="10"
                        stroke-linecap="round"
                />
                <path
                        d="M3.72727 6.70239C1.99174 9.39425 1 12.5465 1 15.9822C1 19.4179 1.99174 22.6056 3.72727 25.262"
                        stroke="#0F6BF5"
                        stroke-width="1.5"
                        stroke-miterlimit="10"
                        stroke-linecap="round"
                />
                <path
                        d="M32.4876 25.262C34.2231 22.5702 35.2149 19.4179 35.2149 15.9822C35.2149 12.5465 34.2231 9.35883 32.4876 6.70239"
                        stroke="#0F6BF5"
                        stroke-width="1.5"
                        stroke-miterlimit="10"
                        stroke-linecap="round"
                />
                <path
                        d="M17.1157 28.2726H19.0992"
                        stroke="#0F6BF5"
                        stroke-width="1.5"
                        stroke-miterlimit="10"
                        stroke-linecap="round"
                />
                <path
                        d="M15.1677 3.72717H21.0472"
                        stroke="#0F6BF5"
                        stroke-width="1.5"
                        stroke-miterlimit="10"
                        stroke-linecap="round"
                />
            </svg>
            <span>{{ $t('login.smsCodeTimeoutMessage', {time: 15}) }}</span>
        </div>
        <button class="button" id="sign-in-button" @click="sendSmsCode" v-if="!confirmationResult">
            <span>{{ $t('login.sendSmsCodeButtonTitle') }}</span>
        </button>
        <button class="button" @click="signIn" v-if="confirmationResult">
            <span>{{ $t('login.confirmSmsCodeButtonTitle') }}</span>
        </button>
    </div>
</template>

<script>
    import firebase from 'firebase/app'
    import 'firebase/auth'
    import get from 'lodash/get'
    import VueMask from 'v-mask'
    import Vue from 'vue'
    Vue.use(VueMask);

    const errorMessages = {
        'auth/invalid-phone-number': 'login.invalidPhoneNumberMessage',
        'auth/invalid-verification-code': 'login.invalidSmsCodeMessage'
    };

    export default {
        name: "PhoneAuthForm",
        data() {
            return {
                phoneAuth: {
                    number: '+7',
                    code: null
                },
                errors: {
                    number: null,
                    code: null
                },
                confirmationResult: null
            };
        },
        mounted() {
            if (!firebase.apps.length) {
                firebase.initializeApp({
                    apiKey: process.env.NUXT_ENV_FIREBASE_API_KEY
                });
                firebase.auth().languageCode = 'ru';
                this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('sign-in-button', {
                    'size': 'invisible'
                });
            }
        },
        methods: {
            async sendSmsCode() {
                if (!this.phoneAuth.number) {
                    return
                }
                this.confirmationResult = null;
                try {
                    this.confirmationResult = await firebase.auth().signInWithPhoneNumber(this.phoneAuth.number, this.recaptchaVerifier);
                } catch (e) {
                    this.$sentry.captureException(e)
                    this.errors.number = e.code
                }
            },
            async signIn() {
                if (!this.phoneAuth.code) {
                    return
                }
                try {
                    const result = await this.confirmationResult.confirm(this.phoneAuth.code);
                    const idToken = await result.user.getIdToken();
                    await this.$store.dispatch('authentication/phoneAuthenticate', idToken);
                } catch (e) {
                    this.$sentry.captureException(e)
                    this.errors.code = e.code
                }
            }
        },
        computed: {
            phoneError() {
                return get(errorMessages, [this.errors.number], this.errors.number)
            },
            codeError() {
                return this.$t(get(errorMessages, [this.errors.code], this.errors.code))
            }
        }
    };
</script>

<style lang="scss" scoped>
    @import "./../styles/mixins.scss";

    .phone-form__form {
        padding-top: 30px;

        .input {
            display: block;
            width: 100%;
            height: 50px;
            background: $tr;
            border: 1px solid #7b95a7;
            box-sizing: border-box;
            margin-top: 20px;
            position: relative;

            &:first-child {
                margin-top: 0;
            }

            &.input_error {
                border-color: $red;

                .input {
                    &__error-text {
                        opacity: 1;
                    }
                }
            }

            input {
                display: block;
                width: 100%;
                height: 100%;
                border: none;
                font-size: 16px;
                line-height: 20px;
                color: $black;
                padding: 0 20px;

                &::placeholder {
                    color: #5b6067;
                }
            }

            &__error-text {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                font-size: 14px;
                line-height: 20px;
                color: $red;
                opacity: 0;
                pointer-events: none;
            }
        }

        .help {
            display: flex;
            padding: 30px 0;
            justify-content: flex-start;
            align-items: center;

            svg {
                display: block;
                margin-right: 10px;
            }

            span {
                font-size: 14px;
                line-height: 14px;
                color: #5b6067;
            }
        }

        .button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 50px;
            background: $blue;
            transition: opacity 0.3s;
            cursor: pointer;
            border: none;

            &:hover {
                opacity: 0.7;
            }

            span {
                font-weight: bold;
                font-size: 16px;
                line-height: 20px;
                color: #ffffff;
            }
        }
    }
</style>