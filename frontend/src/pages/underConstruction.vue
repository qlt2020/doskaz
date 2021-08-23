<template>
    <div class="error-page" v-bind:style="{'background-image': 'url(' + require('./../assets/img/page-under-construction.png') + ')'}">
        <div class="main-header --light">
            <div class="main-header__content">
                <nuxt-link :to="localePath({name: 'index'})" class="main-header__logo">
                    <img :src="require(`@/assets/logo_${$i18n.locale}.svg`)" alt />
                </nuxt-link>
                <div class="main-header__language">
                    <LangSelect />
                </div>
            </div>
        </div>
        <div class="container">
            <div class="error-page__content">
                <h2 class="error-page__title">Ведутся работы</h2>
                <p class="error-page__text">К сожалению, версия на казахском языке пока ещё в работе.</p>
                <div class="error-page__link-b">
                    <nuxt-link :to="switchLocalePath('ru')" class="error-page__link">Вернуться на главную</nuxt-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LangSelect from "./../components/LangSelect";
    export default {
        components: {
            LangSelect
        },
        mounted() {
            if(this.$route.name.endsWith('ru')) {
                return this.$router.push(this.localePath('/'))
            }
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