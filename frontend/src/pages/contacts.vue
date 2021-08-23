<template>
    <div class="contacts">
        <ViTop/>
        <MainHeader/>
        <div class="container">
            <div class="contacts__top">
                <h2 class="title">{{ $t('contacts.title') }}</h2>
            </div>
        </div>
        <div class="contacts__wrapper vld-parent" ref="contactsContainer">
            <div class="container">
                <div class="contacts__content">
                    <div class="contacts__left">
                        <p class="contacts__text">{{ $t('contacts.contactMessage') }}</p>
                        <div class="contacts__link-list">
                            <div class="contacts__link-item">
                                <a href="tel:+77013462177" class="contacts__link --phone">8(701) 223-4630</a>
                            </div>
                            <div class="contacts__link-item">
                                <a href="mailto:info@doskaz.kz" class="contacts__link --mail">info@doskaz.kz</a>
                            </div>
                        </div>
                        <p class="contacts__text">{{ $t('contacts.callCenterWorkingHours', {from: '09:00', to: '18:00'}) }}</p>
                        <div class="contacts__social">
                            <a href="" target="_blank" rel="noreferrer noopener"
                               class="contacts__social-link --fcb"></a>
                            <a href="" target="_blank" rel="noreferrer noopener"
                               class="contacts__social-link --inst"></a>
                        </div>
                    </div>
                    <div class="contacts__right">
                        <div class="contacts__line required" :class="{error: !!violations.name}">
                            <label for="c_name" class="label">{{ $t('contacts.form.nameLabel') }}</label>
                            <div class="input">
                                <input id="c_name" type="text" v-model="feedback.name">
                                <span class="error-msg">{{ violations.name }}</span>
                            </div>
                        </div>
                        <div class="contacts__line required" :class="{error: !!violations.email}">
                            <label class="label">{{ $t('contacts.form.emailLabel') }}</label>
                            <div class="input">
                                <input type="email" v-model="feedback.email">
                                <span class="error-msg">{{ violations.email }}</span>
                            </div>
                        </div>
                        <div class="contacts__line required" :class="{error: !!violations.text}">
                            <label class="label">{{ $t('contacts.form.messageLabel') }}</label>
                            <textarea class="textarea" v-model="feedback.text"></textarea>
                            <span class="error-msg">{{ violations.text }}</span>
                        </div>
                        <div class="contacts__line">
                            <button type="button" class="button" @click.prevent="leaveFeedback">{{ $t('contacts.form.submitButtonLabel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="represent">
                <div class="represent__title">{{ $t('contacts.chooseRegionalRepresentativeLabel') }}
                    <span class="select-text">
                        <select v-model="city">
                            <option v-for="city in availableCities" :key="city.id"
                                    :value="city.id">{{ city.name }}</option>
                        </select>
                    </span>
                </div>
                <p class="represent__text">{{ $t('contacts.regionalRepresentativesText') }}</p>
                <div class="represent__list">
                    <div class="represent__item" v-for="item in regionalRepresentativesFromCity" :key="item.id">
                        <img :src="item.image" class="represent__item-img">
                        <h4 class="represent__item-name">{{ item.name }}</h4>
                        <span class="represent__item-text">{{ item.department }}</span>
                        <a :href="`mailto:${item.email}`" class="represent__item-link">{{ item.email }}</a>
                        <a :href="`tel:${item.phone}`" class="represent__item-link">{{ item.phone }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup__wrapper" v-show="formSent">
            <div class="popup__scroll">
                <div class="popup__in">
                    <span class="popup__close" v-on:click="formSent = false"></span>
                    <h3 class="popup__title">{{ $t('contacts.form.submittedPopupTitle') }}</h3>
                    <p class="popup__text">{{ $t('contacts.form.submittedPopupMessage') }}</p>
                    <div class="popup__buttons --center">
                        <button class="button" @click="formSent = false">Ок</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MainHeader from "@/components/MainHeader";
    import ViTop from "@/components/ViTop";
    import mapValidationErrors from "../mapValidationErrors";

    export default {
        components: {MainHeader, ViTop},
        head() {
            return {
                title: this.$t('contacts.title')
            }
        },
        async asyncData({$axios}) {
            const [regionalRepresentatives, cities] = await Promise.all([
                $axios.$get('/api/regionalRepresentatives'),
                $axios.$get('/api/cities'),
            ])

            return {
                regionalRepresentatives,
                cities
            }
        },
        data() {
            return {
                formSent: false,
                city: null,
                errors: [],
                feedback: {
                    name: '',
                    email: '',
                    text: ''
                }
            }
        },
        mounted() {
            this.city = this.cities[0].id
        },
        computed: {
            availableCities() {
                const regionalRepresentativesCities = this.regionalRepresentatives.map(item => item.cityId)
                return this.cities.filter(city => regionalRepresentativesCities.includes(city.id))
            },
            regionalRepresentativesFromCity() {
                return this.regionalRepresentatives.filter(item => item.cityId === this.city)
            },
            violations() {
                return mapValidationErrors(this.errors)
            }
        },
        methods: {
            async leaveFeedback() {
                const loader = this.$loading.show({
                    container: this.$refs.contactsContainer
                });
                try {
                    this.errors = [];
                    const {data, status} = await this.$axios.post('/api/feedback', this.feedback, {
                        validateStatus: status => status <= 400
                    })

                    if (status === 400) {
                        this.errors = data.errors.violations
                        return;
                    }
                    this.formSent = true;
                    this.feedback = {
                        name: '',
                        email: '',
                        text: ''
                    }
                } catch (e) {
                    throw e
                } finally {
                    loader.hide()
                }
            }
        }
    }
</script>

<style lang="scss">
    @import "@/styles/mixins.scss";

    .represent {
        padding: 68px 0 60px;
        @media all and (max-width: 1200px) {
            padding: 36px 0 40px;
        }

        &__title {
            font-weight: bold;
            font-size: 32px;
            line-height: 40px;
            margin: 0 0 20px;
            @media all and (max-width: 1200px) {
                font-size: 22px;
                line-height: 30px;
                .select-text select {
                    font-size: 22px;
                    line-height: 30px;
                }
            }
            @media all and (max-width: 768px) {
                font-size: 16px;
                line-height: 20px;
                .select-text select {
                    font-size: 16px;
                    line-height: 20px;
                }
            }
        }

        &__text {
            font-size: 16px;
            line-height: 30px;
            @media all and (max-width: 1200px) {
                line-height: 24px;
            }
            @media all and (max-width: 768px) {
                font-size: 14px;
                line-height: 20px;
            }
        }

        &__list {
            font-size: 0;
            margin: 20px 0 0;
        }

        &__item {
            position: relative;
            vertical-align: top;
            width: 450px;
            margin: 30px 0 0;
            padding: 4px 40px 0 130px;
            display: inline-block;
            @media all and (max-width: 1200px) {
                width: 50%;
            }
            @media all and (max-width: 768px) {
                width: 100%;
                padding: 4px 0 0 80px;
                margin: 0;
                & + .represent__item {
                    margin: 20px 0 0;
                }
            }

            &.disabled {
                &:after {
                    content: '';
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.8);
                    z-index: 1;
                }
            }

            &-img {
                position: absolute;
                left: 0;
                top: 0;
                width: 110px;
                height: auto;
                @media all and (max-width: 768px) {
                    width: 64px;
                }
            }

            &-name {
                font-size: 16px;
                line-height: 20px;
                margin: 0;
            }

            &-text {
                font-size: 14px;
                line-height: 20px;
                margin: 11px 0 14px;
                display: block;
            }

            &-link {
                display: block;
                margin: 9px 0 0;
                font-size: 16px;
                color: $black;
                -webkit-transition: opacity 0.4s;
                -moz-transition: opacity 0.4s;
                -ms-transition: opacity 0.4s;
                -o-transition: opacity 0.4s;
                transition: opacity 0.4s;

                &:hover {
                    opacity: 0.7;
                }
            }
        }
    }

    .contacts {
        &__wrapper {
            padding: 54px 0 60px;
            background: #F1F8FC;
            @media all and (max-width: 768px) {
                padding: 35px 0 20px;
            }
        }

        &__top {
            padding: 46px 0 54px;
            @media all and (max-width: 1200px) {
                padding: 28px 0 32px;
            }
            @media all and (max-width: 768px) {
                padding: 14px 0 18px;
            }
        }

        &__content {
            display: flex;
            justify-content: space-between;
            @media all and (max-width: 768px) {
                flex-direction: column;
            }
        }

        &__left {
            width: 600px;
            max-width: 600px;
            min-width: 600px;
            padding: 50px 200px 0 0;
            @media all and (max-width: 1200px) {
                width: 320px;
                max-width: 320px;
                min-width: 320px;
                padding: 30px 20px 0 0;
            }
            @media all and (max-width: 768px) {
                width: 100%;
                max-width: 100%;
                min-width: 100%;
                padding: 0 0 30px;
                text-align: center;
            }
        }

        &__right {
            width: 560px;
            max-width: 560px;
            min-width: 560px;
            @media all and (max-width: 1200px) {
                width: 350px;
                max-width: 350px;
                min-width: 350px;
            }
            @media all and (max-width: 768px) {
                width: 100%;
                max-width: 100%;
                min-width: 100%;
            }
        }

        &__text {
            font-size: 16px;
            line-height: 20px;
            @media all and (max-width: 768px) {
                font-size: 12px;
            }
        }

        &__link {
            color: $black;
            font-weight: 600;
            font-size: 32px;
            line-height: 40px;
            display: block;
            padding: 0 0 0 40px;
            background-position: left center;
            background-repeat: no-repeat;
            -webkit-transition: color 0.4s;
            -moz-transition: color 0.4s;
            -ms-transition: color 0.4s;
            -o-transition: color 0.4s;
            transition: color 0.4s;
            @media all and (max-width: 1200px) {
                font-size: 22px;
            }
            @media all and (max-width: 768px) {
                font-size: 16px;
                line-height: 20px;
                padding: 22px 0 0;
                background-size: 16px;
                background-position: top center;
            }

            &:hover {
                color: $blue;
            }

            &.--phone {
                background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIyLjc5OTMgMTcuMzcxN1YyMC42MjM0QzIyLjgwMDYgMjAuOTI1MiAyMi43Mzg2IDIxLjIyNCAyMi42MTc0IDIxLjUwMDZDMjIuNDk2MyAyMS43NzcyIDIyLjMxODUgMjIuMDI1NSAyMi4wOTU3IDIyLjIyOTVDMjEuODcyOCAyMi40MzM2IDIxLjYwOTcgMjIuNTg5IDIxLjMyMzEgMjIuNjg1N0MyMS4wMzY2IDIyLjc4MjQgMjAuNzMzIDIyLjgxODMgMjAuNDMxOCAyMi43OTExQzE3LjA4OTggMjIuNDI4NyAxMy44Nzk3IDIxLjI4OSAxMS4wNTkyIDE5LjQ2MzZDOC40MzUxNiAxNy43OTk1IDYuMjEwNDIgMTUuNTc5MSA0LjU0Mjk4IDEyLjk2MDNDMi43MDc1NCAxMC4xMzI2IDEuNTY1MzEgNi45MTMyNCAxLjIwODgzIDMuNTYyOTRDMS4xODE2OSAzLjI2MzIxIDEuMjE3MzggMi45NjExMiAxLjMxMzYzIDIuNjc1OTFDMS40MDk4OCAyLjM5MDcgMS41NjQ1OCAyLjEyODYxIDEuNzY3ODggMS45MDYzNEMxLjk3MTE5IDEuNjg0MDcgMi4yMTg2MyAxLjUwNjQ4IDIuNDk0NDcgMS4zODQ4OEMyLjc3MDMxIDEuMjYzMjggMy4wNjg1IDEuMjAwMzQgMy4zNzAwNSAxLjIwMDA2SDYuNjI4MThDNy4xNTUyNCAxLjE5NDg4IDcuNjY2MjEgMS4zODExNSA4LjA2NTg0IDEuNzI0MTVDOC40NjU0NyAyLjA2NzE1IDguNzI2NDkgMi41NDM0OCA4LjgwMDI2IDMuMDY0MzVDOC45Mzc3OCA0LjEwNDk1IDkuMTkyODEgNS4xMjY2OSA5LjU2MDQ5IDYuMTEwMDhDOS43MDY2MSA2LjQ5ODAzIDkuNzM4MjMgNi45MTk2NSA5LjY1MTYyIDcuMzI0OTlDOS41NjUgNy43MzAzMyA5LjM2Mzc3IDguMTAyMzkgOS4wNzE3NyA4LjM5NzA5TDcuNjkyNSA5Ljc3MzYzQzkuMjM4NTQgMTIuNDg3MiAxMS40ODk4IDE0LjczNCAxNC4yMDg3IDE2LjI3N0wxNS41ODggMTQuOTAwNEMxNS44ODMzIDE0LjYwOSAxNi4yNTYxIDE0LjQwODIgMTYuNjYyMiAxNC4zMjE3QzE3LjA2ODQgMTQuMjM1MyAxNy40OTA4IDE0LjI2NjggMTcuODc5NiAxNC40MTI3QzE4Ljg2NDkgMTQuNzc5NiAxOS44ODg3IDE1LjAzNDIgMjAuOTMxMyAxNS4xNzE0QzIxLjQ1ODkgMTUuMjQ1NyAyMS45NDA3IDE1LjUxMDkgMjIuMjg1MSAxNS45MTY2QzIyLjYyOTYgMTYuMzIyMyAyMi44MTI2IDE2Ljg0MDEgMjIuNzk5MyAxNy4zNzE3WiIgc3Ryb2tlPSIjMEY2QkY1IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K);
            }

            &.--mail {
                background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyNCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwKSI+CjxwYXRoIGQ9Ik0zLjI3MjczIDEuMDY2NjVIMjAuNzI3M0MyMS45MjczIDEuMDY2NjUgMjIuOTA5MSAyLjAyNjY1IDIyLjkwOTEgMy4xOTk5OFYxNkMyMi45MDkxIDE3LjE3MzMgMjEuOTI3MyAxOC4xMzMzIDIwLjcyNzMgMTguMTMzM0gzLjI3MjczQzIuMDcyNzMgMTguMTMzMyAxLjA5MDkxIDE3LjE3MzMgMS4wOTA5MSAxNlYzLjE5OTk4QzEuMDkwOTEgMi4wMjY2NSAyLjA3MjczIDEuMDY2NjUgMy4yNzI3MyAxLjA2NjY1WiIgc3Ryb2tlPSIjMEY2QkY1IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8cGF0aCBkPSJNMjIuOTA5MSAzLjE5OTk1TDEyIDEwLjY2NjZMMS4wOTA5MSAzLjE5OTk1IiBzdHJva2U9IiMwRjZCRjUiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjwvZz4KPGRlZnM+CjxjbGlwUGF0aCBpZD0iY2xpcDAiPgo8cmVjdCB3aWR0aD0iMjQiIGhlaWdodD0iMTkuMiIgZmlsbD0id2hpdGUiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4K);
            }

            &-list {
                margin: 18px 0 22px;
            }

            &-item {
                margin: 24px 0 0;

                &:first-child {
                    margin: 0;
                }
            }
        }

        &__line {
            position: relative;
            margin: 24px 0 0;

            .error-msg {
                display: none;
                position: absolute;
                left: 0;
                top: 100%;
                line-height: 20px;
                font-size: 14px;
                color: $red;
                margin: 5px 0 0;
            }

            &.required {
                z-index: 1;

                label {
                    &:after {
                        content: "*";
                        color: #e0202e;
                        margin: 0 0 0 5px;
                    }
                }

                &.error {
                    .input {
                        position: relative;
                        border-color: $red;
                    }

                    .error-msg {
                        display: block;
                    }

                    .textarea {
                        border-color: $red;
                        display: block;
                    }
                }
            }

            button {
                cursor: pointer;
                font-weight: bold;
                font-size: 16px;
                min-width: 260px;
                line-height: 50px;
                background: $blue;
                color: #FFFFFF;
                border: none;
                -webkit-transition: opacity 0.4s;
                -moz-transition: opacity 0.4s;
                -ms-transition: opacity 0.4s;
                -o-transition: opacity 0.4s;
                transition: opacity 0.4s;
                outline: none;

                &:hover {
                    opacity: 0.7;
                }
            }

            label {
                margin: 0 0 6px;
            }

            textarea {
                min-height: 110px;
            }

            &:first-child {
                margin: 0;
            }

            &:last-child {
                margin: 40px 0 0;
            }
        }

        &__social {
            font-size: 0;
            margin: 34px 0 0;

            &-link {
                vertical-align: top;
                display: inline-block;
                border-radius: 11px;
                background-color: $blue;
                background-position: center;
                background-repeat: no-repeat;
                margin: 0 0 0 24px;
                height: 36px;
                width: 36px;
                transition: opacity 0.4s;

                &:first-child {
                    margin: 0;
                }

                &:hover {
                    opacity: 0.7;
                }

                &.--fcb {
                    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOSIgaGVpZ2h0PSIxOCIgdmlld0JveD0iMCAwIDkgMTgiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik01Ljk0IDUuOTRWNC40MUM1Ljk0IDMuNiA2LjAzIDMuMTUgNy4yOSAzLjE1SDguOTFWMEg2LjNDMy4xNSAwIDEuOTggMS41MyAxLjk4IDQuMDVWNS45NEgwVjlIMS45OFYxOEg1Ljk0VjlIOC41NUw5IDUuOTRINS45NFoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=);
                }

                &.--inst {
                    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjgiIGhlaWdodD0iMjgiIHZpZXdCb3g9IjAgMCAyOCAyOCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE5LjAwNjMgMC45NjQxMTFIOC45OTM3NUM0LjU1NzAzIDAuOTY0MTExIDAuOTU3MDMxIDQuNTY0MTEgMC45NTcwMzEgOS4wMDA4M1YxOS4wMDYzQzAuOTU3MDMxIDIzLjQ0MyA0LjU1IDI3LjAzNiA4Ljk5Mzc1IDI3LjAzNkgxOC45OTkyQzIzLjQzNTkgMjcuMDM2IDI3LjAyODkgMjMuNDQzIDI3LjAyODkgMTkuMDA2M1Y5LjAwMDgzQzI3LjAzNTkgNC41NTcwOCAyMy40NDMgMC45NjQxMTEgMTkuMDA2MyAwLjk2NDExMVpNMjQuNTMyOCAxOC4wNDNDMjQuNTMyOCAyMS42MjkgMjEuNjI4OSAyNC41MzI5IDE4LjA0MyAyNC41MzI5SDkuOTU3MDNDNi4zNzEwOSAyNC41MzI5IDMuNDY3MTkgMjEuNjI5IDMuNDY3MTkgMTguMDQzVjkuOTU3MDhDMy40NjcxOSA2LjM3MTE0IDYuMzcxMDkgMy40NjcyNCA5Ljk1NzAzIDMuNDY3MjRIMTguMDQzQzIxLjYyODkgMy40NjcyNCAyNC41MzI4IDYuMzc4MTcgMjQuNTMyOCA5Ljk1NzA4VjE4LjA0M1oiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0xNC4zNTE2IDcuMTc5NjlDMTAuNTU0NyA3LjE3OTY5IDcuNDgyMDMgMTAuMjUyMyA3LjQ4MjAzIDE0LjA0OTJDNy40ODIwMyAxNy44MzkxIDEwLjU1NDcgMjAuOTE4NyAxNC4zNTE2IDIwLjkxODdDMTguMTQxNCAyMC45MTg3IDIxLjIyMTEgMTcuODQ2MSAyMS4yMjExIDE0LjA0OTJDMjEuMjE0MSAxMC4yNTIzIDE4LjE0MTQgNy4xNzk2OSAxNC4zNTE2IDcuMTc5NjlaTTE0LjM1MTYgMTguMTc2NkMxMi4wNjY0IDE4LjE3NjYgMTAuMjE3MiAxNi4zMjczIDEwLjIxNzIgMTQuMDQyMkMxMC4yMTcyIDExLjc1NyAxMi4wNjY0IDkuOTA3ODEgMTQuMzUxNiA5LjkwNzgxQzE2LjYyOTcgOS45MDc4MSAxOC40ODU5IDExLjc1NyAxOC40ODU5IDE0LjA0MjJDMTguNDc4OSAxNi4zMjczIDE2LjYyOTcgMTguMTc2NiAxNC4zNTE2IDE4LjE3NjZaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMjIuNDg2NyA3LjA3NDI3QzIyLjQ4NjcgOC4wMzc1NSAyMS43MDYyIDguODE4MDIgMjAuNzQzIDguODE4MDJDMTkuNzc5NyA4LjgxODAyIDE4Ljk5OTIgOC4wMzc1NSAxOC45OTkyIDcuMDc0MjdDMTguOTk5MiA2LjExMDk5IDE5Ljc3OTcgNS4zMjM0OSAyMC43NDMgNS4zMjM0OUMyMS43MDYyIDUuMzIzNDkgMjIuNDg2NyA2LjEwMzk1IDIyLjQ4NjcgNy4wNzQyN1oiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=);
                }
            }
        }
    }
</style>