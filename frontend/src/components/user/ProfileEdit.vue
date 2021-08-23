<template>
    <div class="user-page__edit">
        <h3 class="user-page__title">{{ $t('profile.edit.pageTitle') }}</h3>
        <p class="user-page__text --line">{{ $t('profile.edit.pointsInfo') }} {{ $tc('profile.edit.pointsCount', 70) }}</p>
        <p class="user-page__text --line">{{ $t('profile.edit.personalDataInfo') }}</p>
        <div class="user-page__line">
            <div class="col --label">
                <label class="user-page__label">{{ $t('profile.edit.lastNameLabel') }}</label>
            </div>
            <div class="col">
                <div class="input" :class="{error: !!violations.lastName}">
                    <input type="text" v-model.trim="profile.lastName">
                </div>
            </div>
        </div>
        <div class="user-page__line">
            <div class="col --label">
                <label class="user-page__label">{{ $t('profile.edit.firstNameLabel') }}</label>
            </div>
            <div class="col">
                <div class="input" :class="{error: !!violations.firstName}">
                    <input type="text" v-model.trim="profile.firstName">
                </div>
            </div>
        </div>
        <div class="user-page__line">
            <div class="col --label">
                <label class="user-page__label">{{ $t('profile.edit.middleNameLabel') }}</label>
            </div>
            <div class="col">
                <div class="input" :class="{error: !!violations.middleName}">
                    <input type="text" v-model.trim="profile.middleName">
                </div>
            </div>
        </div>
        <div class="user-page__line">
            <div class="col --label">
                <label class="user-page__label">{{ $t('profile.edit.emailLabel') }}</label>
            </div>
            <div class="col">
                <div class="input" :class="{error: !!violations.email}">
                    <input type="email" v-model.trim="profile.email">
                    <span class="error-msg">{{ violations.email }}</span>
                </div>
            </div>
        </div>
        <div class="user-page__line">
            <div class="col --label">
                <label class="user-page__label">{{ $t('profile.edit.phoneLabel') }}</label>
            </div>
            <div class="col">
                <div class="input" :class="{error: !!violations.phone}">
                    <client-only>
                        <input type="text" v-imask="{mask: '{+7}(000)000-00-00', lazy: false, unmask: true}"
                               :value="profile.phone" @accept="profile.phone = $event.detail.unmaskedValue"/>
                    </client-only>
                    <span class="error-msg">{{ violations.phone }}</span>
                </div>
            </div>
        </div>
        <div class="user-page__line">
            <div class="col --label">
                <label class="user-page__label">{{ $t('profile.edit.smsCodeLabel') }}</label>
            </div>
            <div class="col --flex">
                <div class="input --flex-col --sm " :class="{error: !!smsError}">
                    <input type="text" v-model.trim="smsCode">
                    <span class="error-msg">{{ smsError }}</span>
                </div>
                <div class="--flex-col">
                    <div class="user-page__sms" @click="sendSmsCode">
                        <img class="user-page__sms-img" :src="require('~/assets/icons/sms-phone.svg')"/>
                        <span class="user-page__sms-text" v-if="!smsWait">{{ $t('profile.edit.sendSmsButton') }}</span>
                        <span class="user-page__sms-text"
                              v-else>{{ $t('profile.edit.resendSmsCodeMessage') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="user-page__line" :class="{disabled: !profile.abilities.includes('status_change')}">
            <div class="col --label">
                <label class="user-page__label">{{ $t('profile.edit.statusLabel') }}</label>
            </div>
            <div class="col">
                <div class="input" :class="{error: !!violations.status}">
                    <input type="text" v-model.trim="profile.status" :readonly="!profile.abilities.includes('status_change')" :placeholder="profile.abilities.includes('status_change') ? '' : $t('profile.edit.statusInputPlaceholder')">
                </div>
            </div>
        </div>
        <div class="user-page__line">
            <div class="col --label"></div>
            <div class="col">
                <button type="button" class="user-page__button" @click.prevent="submit">{{ $t('profile.edit.saveButton') }}</button>
            </div>
        </div>
        <div id="recaptcha_verifier"></div>
    </div>
</template>

<script>
    import {call} from 'vuex-pathify'
    import mapValidationErrors from "@/mapValidationErrors";
    import firebase from 'firebase/app'
    import 'firebase/auth'

    export default {
        name: "ProfileEdit",
        props: [
            'profile'
        ],

        data() {
            return {
                errors: [],
                codeSent: false,
                smsWait: false,
                smsErrorCode: null,
                smsCode: ''
            }
        },
        mounted() {
            if (!firebase.apps.length) {
                firebase.initializeApp({
                    apiKey: process.env.NUXT_ENV_FIREBASE_API_KEY
                });
                firebase.auth().languageCode = 'ru';
                this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha_verifier', {
                    'size': 'invisible'
                });
            }
        },
        methods: {
            async sendSmsCode() {
                if (this.smsWait || !this.profile.phone || this.profile.phone.length !== 12) {
                    return
                }
                this.smsWait = true;
                const timeout = setTimeout(() => {
                    this.smsWait = false
                }, 15 * 1000)
                this.smsErrorCode = null;
                this.confirmationResult = null;
                try {
                    this.confirmationResult = await firebase.auth().signInWithPhoneNumber(this.profile.phone, this.recaptchaVerifier);
                    this.codeSent = true
                } catch (e) {
                    this.smsErrorCode = e.code
                }
            },
            async confirmSmsCode() {
                if (!this.smsCode) {
                    return
                }
                try {
                    const result = await this.confirmationResult.confirm(this.smsCode);
                    this.profile.phoneChangeToken = await result.user.getIdToken();
                } catch (e) {
                    this.smsErrorCode = e.code
                }
            },
            async submit() {
                this.smsErrorCode = null;
                this.errors = [];
                const loader = this.$loading.show();
                if (this.codeSent && this.smsCode) {
                    await this.confirmSmsCode()
                }
                if (this.smsErrorCode) {
                    loader.hide()
                    return;
                }

                try {
                    const {data, status} = await this.$axios.put('/api/profile', this.profile, {
                        validateStatus: status => status <= 400
                    })
                    if (status === 400) {
                        this.errors = data.errors.violations
                        return;
                    }
                    this.smsErrorCode = null;
                    this.codeSent = false
                    this.smsCode = null
                    await this.loadUser()
                } catch (e) {
                    throw e
                } finally {
                    loader.hide()
                }

            },
            ...call('authentication', ['loadUser'])
        },
        computed: {
            violations() {
                return mapValidationErrors(this.errors)
            },
            smsError() {
                const messages = {
                    'auth/invalid-verification-code': this.$t('profile.edit.invalidVerificationCodeError'),
                    'auth/too-many-requests': this.$t('profile.edit.tooManyRequestsError')
                }

                if (!this.smsErrorCode) {
                    return;
                }

                return messages[this.smsErrorCode] || this.$t('profile.edit.errorPleaseRetry')
            }
        }
    }
</script>

<style scoped>

</style>