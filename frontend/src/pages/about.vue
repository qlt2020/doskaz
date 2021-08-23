<template>
    <div class="about">
        <ViTop/>
        <MainHeader/>
        <div class="about__top">
            <div class="about__top-bg" :style="{'background-image': 'url(' + require('./../assets/img/files/about-top-bg.jpg') + ')'}"></div>
            <div class="container">
                <div class="about__text-b">
                    <h3 class="about__title">{{ $t('about.title') }}</h3>
                    <p class="about__text">{{ $t('about.text') }}</p>
                </div>
                <div class="about__text-b">
                    <h3 class="about__title">{{ $t('about.map') }}</h3>
                    <p class="about__text">{{ $t('about.accessibility') }}</p>
                </div>
                <div class="about__top-list">
                    <div class="about__top-item">
                        <img :src="require('@/assets/icons/green.svg')" class="about__top-item__img"/>
                        <h4 class="about__top-item__title">{{ $t('about.accessible') }}</h4>
                        <p class="about__top-item__text">{{ $t('about.accessibleDescription') }}</p>
                    </div>
                    <div class="about__top-item">
                        <img :src="require('@/assets/icons/yellow.svg')" class="about__top-item__img"/>
                        <h4 class="about__top-item__title">{{ $t('about.partialAccessible') }}</h4>
                        <p class="about__top-item__text">{{ $t('about.partialAccessibleDescription') }}</p>
                    </div>
                    <div class="about__top-item">
                        <img :src="require('@/assets/icons/red.svg')" class="about__top-item__img"/>
                        <h4 class="about__top-item__title">{{ $t('about.notAccessible') }}</h4>
                        <p class="about__top-item__text">{{ $t('about.notAccessibleDescription') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="about__part">
            <div class="container">
                <h2 class="about__title">{{ $t('about.participant') }}</h2>
                <div class="about__part-content">
                    <div class="about__part-img">
                        <img :src="require('@/assets/icons/about/about-part-bg.svg')"/>
                        <img :src="require('@/assets/icons/about/about-part.png')"/>
                    </div>
                    <p class="about__part-text">{{ $t('about.participantDescription') }}</p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="about__item-b">
                <h2 class="about__title">{{ $t('about.text1') }}</h2>
                <div class="about__item">
                    <img :src="require('@/assets/icons/about/about-map.png')"/>
                    <p>{{ $t('about.text2') }}</p>
                </div>
                <div class="about__item">
                    <img :src="require('@/assets/icons/about/about-doc.png')" class="--right"/>
                    <p>{{ $t('about.text3') }}</p>
                </div>
                <div class="about__item">
                    <img :src="require('@/assets/icons/about/about-social.png')"/>
                    <p>{{ $t('about.text4') }}</p>
                </div>
            </div>
            <div class="about__text-b">
                <h3 class="about__title">{{ $t('about.text5') }}</h3>
                <p class="about__text">{{ $t('about.text6') }}</p>
            </div>
            <div class="about__link-b">
                <div class="about__link-item">
                    <h3 class="title --md">{{ $t('about.text7') }}</h3>
                    <div class="about__link-logo">
                        <a href="https://www.ef-ca.kz/" target="_blank" rel="noreferrer noopener" class="about__link">
                            <img :src="require('@/assets/icons/about/efca.svg')"/>
                        </a>
                        <a href="https://www.oft.kz" target="_blank" rel="noreferrer noopener" class="about__link">
                            <img :src="require('@/assets/icons/about/tandau.svg')"/>
                        </a>
                        <a href="https://www.facebook.com/etshkz/" target="_blank" rel="noreferrer noopener" class="about__link">
                            <img :src="require('@/assets/icons/about/erekshe.svg')"/>
                        </a>
                    </div>
                    <p class="about__link-text">{{ $t('about.text8') }} «<nuxt-link :to="localePath({name: 'contacts'})">{{ $t('about.contacts') }}</nuxt-link>» {{ $t('about.text9')}}.</p>
                </div>
                <div class="about__link-item">
                    <h3 class="title --md">{{ $t('about.financing') }}</h3>
                    <div class="about__link-logo">
                        <a href="https://eeas.europa.eu/delegations/kazakhstan_ru" target="_blank" rel="noreferrer noopener" class="about__link">
                            <img :src="require('@/assets/icons/about/es.svg')"/>
                        </a>
                        <a href="https://kz.usembassy.gov/ru/" target="_blank" rel="noreferrer noopener" class="about__link">
                            <img :src="require('@/assets/icons/about/usembassy.svg')"/>
                        </a>
                    </div>
                    <p class="about__link-text">{{ $t('about.financingDescription') }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MainHeader from "@/components/MainHeader";
    import ViTop from "@/components/ViTop";

    export default {
        components: {MainHeader, ViTop}
    }

</script>

<style lang="scss">
    @import "@/styles/mixins.scss";

    .about {
        padding: 0 0 90px;
        @media all and (max-width: 768px) {
            padding: 0 0 40px;
        }
        .main-header__content {
            border: none;
        }
        &__link {
            &-b {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin: 110px 0 0;
                @media all and (max-width: 1023px) {
                    margin: 60px 0 0;
                    display: block;
                    text-align: center;
                }
                .title {
                    @media all and (max-width: 1023px) {
                        font-size: 24px;
                        line-height: 30px;
                    }
                }
            }
            &-item {
                width: 500px;
                min-width: 500px;
                text-align: left;
                @media all and (max-width: 1023px) {
                    margin: 60px auto 0;
                    display: block;
                    text-align: center;
                }
                @media all and (max-width: 768px) {
                    width: 100%;
                    min-width: 100%;
                }
            }
            &-text {
                font-size: 16px;
                line-height: 30px;
                @media all and (max-width: 768px) {
                    font-size: 14px;
                    line-height: 20px;
                }
                a {
                    text-decoration: underline;
                }
            }
            &-logo {
                min-height: 131px;
                margin: 52px 0 30px;
                align-items: top;
                display: flex;
                @media all and (max-width: 1023px) {
                    min-height: 0;
                    margin: 24px 0 20px;
                    justify-content: center;
                }
                @media all and (max-width: 768px) {
                    flex-wrap: wrap;
                    margin: 0 0 20px;
                }
                a {
                    margin: 0 0 0 80px;
                    transition: opacity 0.3s;
                    &:hover {
                        opacity: 0.7;
                    }
                    &:first-child {
                        margin: 0;
                        @media all and (max-width: 768px) {
                            margin: 24px 30px 0;
                        }
                    }
                    @media all and (max-width: 768px) {
                        margin: 24px 14px 0;
                    }
                }
            }
        }
        &__title {
            font-weight: 600;
            font-size: 48px;
            line-height: 50px;
            text-align: center;
            margin: 0;
            @media all and (max-width: 1200px) {
                font-size: 32px;
                line-height: 36px;
            }
            @media all and (max-width: 768px) {
                font-size: 20px;
                line-height: 28px;
            }
        }
        &__item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 80px 0 0;
            @media all and (max-width: 1023px) {
                align-items: flex-start;
            }
            @media all and (max-width: 768px) {
                margin: 42px 0 0;
                display: block;
            }
            p {
                font-size: 16px;
                line-height: 30px;
                text-align: left;
                width: calc(100% - 290px);
                min-width: calc(100% - 290px);
                @media all and (max-width: 1023px) {
                    width: calc(100% - 190px);
                    min-width: calc(100% - 190px);
                }
                @media all and (max-width: 768px) {
                    text-align: center;
                    width: 100%;
                    margin: 18px 0 0;
                    font-size: 14px;
                    line-height: 20px;
                }
            }
            img {
                width: 260px;
                min-width: 260px;
                &.--right {
                    order: 1;
                    @media all and (max-width: 768px) {
                        order: 0;
                    }
                }
                @media all and (max-width: 1023px) {
                    width: 160px;
                    min-width: 160px;
                }
                @media all and (max-width: 768px) {
                    margin: 0 auto;
                    display: block;
                }
            }
            &-b {
                padding: 106px 0 60px;
                @media all and (max-width: 1023px) {
                    padding: 53px 20px 50px;
                }
                @media all and (max-width: 1023px) {
                    padding: 30px 0;
                }
            }
        }
        &__part {
            padding: 106px 0 196px;
            background: $light-gray;
            .about__title {
                position: relative;
                z-index: 1;
            }
            @media all and (max-width: 1200px) {
                padding: 53px 0 50px;
            }
            @media all and (max-width: 1023px) {
                padding: 53px 20px 50px;
            }
            @media all and (max-width: 768px) {
                padding: 30px 0;
            }
            &-content {
                display: flex;
                margin: 64px 0 0;
                @media all and (max-width: 1023px) {
                    display: block;
                    margin: 0;
                }
            }
            &-img {
                width: 560px;
                min-width: 560px;
                margin: 7px 40px 0 0;
                position: relative;
                @media all and (max-width: 1200px) {
                    width: 460px;
                    min-width: 460px;
                    margin: 7px 30px 0 0;
                }
                @media all and (max-width: 1023px) {
                    width: 300px;
                    min-width: 300px;
                    margin: 37px auto 24px;
                }
                @media all and (max-width: 768px) {
                    width: 240px;
                    min-width: 240px;
                }
                img {
                    display: block;
                    width: 100%;
                    height: auto;
                    position: relative;
                    &:first-child {
                        position: absolute;
                        width: (552/560)*100%;
                        height: auto;
                        left: -11.8%;
                        top: -21%;
                    }
                }
            }
            &-text {
                font-size: 16px;
                line-height: 30px;
                z-index: 1;
                position: relative;
                @media all and (max-width: 768px) {
                    font-size: 14px;
                    line-height: 20px;
                }
            }
        }
        &__text {
            font-size: 16px;
            line-height: 30px;
            margin: 63px 0 0;
            @media all and (max-width: 1200px) {
                margin: 27px 0 0;
            }
            @media all and (max-width: 768px) {
                font-size: 14px;
                line-height: 20px;
                margin: 14px 0 0;
            }
            &-b {
                background: #FFFFFF;
                padding: 66px 150px 11px;
                @media all and (max-width: 1200px) {
                    padding: 53px 40px 11px;
                }
                @media all and (max-width: 768px) {
                    padding: 20px 20px 11px;
                }
                & + .about__top-text-b {
                    margin: 20px 0 0;
                }
            }
        }
        &__top {
            max-width: 1800px;
            padding: 0 20px 120px;
            margin: 0 auto;
            @media all and (max-width: 1800px) {
                padding: 0 0 60px;
            }
            .about__text-b {
                @media all and (max-width: 1200px) {
                    margin-left: 20px;
                    margin-right: 20px;
                }
                @media all and (max-width: 768px) {
                    margin-left: 0;
                    margin-right: 0;
                }
                &:first-child {
                    margin: -300px 0 0;
                    @media all and (max-width: 1200px) {
                        margin: -150px 20px 0;
                    }
                    @media all and (max-width: 768px) {
                        margin: -150px 0 0;
                    }
                }
            }
            &-bg {
                height: 500px;
                background-position: center;
                background-size: cover;
                @media all and (max-width: 1200px) {
                    height: 275px;
                }
            }
            &-list {
                display: flex;
                justify-content: space-between;
                margin: 20px 0 0;
                @media all and (max-width: 768px) {
                    display: block;
                }
            }
            &-item {
                padding: 40px 30px;
                background: #FFFFFF;
                box-shadow: 0 0 5px rgba(123, 149, 167, 0.15), 0 4px 40px rgba(123, 149, 167, 0.25);
                width: calc((100% - 110px)/3);
                @media all and (max-width: 1200px) {
                    padding: 29px 20px 20px;
                }
                @media all and (max-width: 1023px) {
                    width: calc((100% - 60px)/3);
                }
                @media all and (max-width: 768px) {
                    width: 100%;
                    margin: 24px 0 0;
                }
                &__img {
                    display: block;
                    width: 40px;
                    @media all and (max-width: 1023px) {
                        width: 30px;
                    }
                }
                &__title {
                    font-weight: bold;
                    font-size: 16px;
                    line-height: 20px;
                    margin: 24px 0 5px;
                    @media all and (max-width: 1023px) {
                        font-size: 14px;
                        line-height: 20px;
                        margin: 15px 0 5px;
                    }
                }
                &__text {
                    font-size: 16px;
                    line-height: 30px;
                    margin: 5px 0 0;
                    @media all and (max-width: 1023px) {
                        font-size: 14px;
                        line-height: 20px;
                    }
                }
            }
        }
    }
</style>