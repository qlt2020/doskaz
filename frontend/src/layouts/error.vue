<template>
    <div class="error-page" v-bind:style="{'background-image': 'url(' + require('~/assets/img/page-error-bg.png') + ')'}">
        <div class="main-header --light">
            <div class="main-header__content">
                <nuxt-link :to="localePath({name: 'index'})" class="main-header__logo">
                    <img :src="require(`@/assets/logo_${$i18n.locale}.svg`)" alt />
                    <img :src="require('~/assets/logo-black.svg')" class="black" alt />
                    <img :src="require('~/assets/logo-white.svg')" class="white" alt />
                </nuxt-link>
                <div class="main-header__language">
                    <LangSelect />
                </div>
            </div>
        </div>
        <div class="container">
            <div class="error-page__content" v-if="error.statusCode == '404'">
                <h2 class="error-page__title">{{ $t('error.title', {code: error.statusCode}) }}</h2>
                <p class="error-page__text">{{ $t('error.error404.message') }}</p>
                <p class="error-page__text">{{ $t('error.error404.searchAdvice') }}</p>
                <div class="error-page__search">
                    <div class="input --search">
                        <input type="search" :placeholder="$t('error.error404.searchPlaceholder')">
                    </div>
                </div>
            </div>
            <div class="error-page__content" v-if="error.statusCode == '500'">
                <h2 class="error-page__title">{{ $t('error.title', {code: error.statusCode}) }}</h2>
                <p class="error-page__text">{{ $t('error.error500.message') }}</p>
                <p class="error-page__text">{{ $t('error.error500.advice') }}</p>
                <div class="error-page__link-b">
                    <a href="/" class="error-page__link">{{ $t('error.error500.linkToMainPageTitle') }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LangSelect from "~/components/LangSelect";
    export default {
        props: ['error'],
        components: {
            LangSelect
        }
    }
</script>

<style  lang="scss">
    .error-page {
        background-size: 100%;
        background-position: center bottom;
        min-height: 100vh;
        background-repeat: no-repeat;
        &__content {
            padding: 45px 0 30px;
        }
        &__title {
            font-weight: 600;
            font-size: 48px;
            line-height: 50px;
            margin: 0 0 43px;
        }
        &__text {
            max-width: 580px;
            font-size: 16px;
            line-height: 30px;
            margin: 20px 0;
        }
        &__search {
            width: 560px;
        }
        &__link {
            font-size: 18px;
            line-height: 20px;
            display: inline-block;
            border-bottom: 1px solid;
            transition: opacity 0.4s;
            &:hover {
                opacity: 0.7;
            }
            &-b {
                margin: 44px 0 0;
            }
        }
    }
</style>