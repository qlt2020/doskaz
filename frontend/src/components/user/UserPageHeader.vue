<template>
    <div class="user-page__header" v-bind:style="{'background-image': 'url(' + require('~/assets/user-bg.png') + ')'}">
        <div class="menu">
            <div class="menu__content">
                <nuxt-link :to="localePath({name: 'profile-achievements'})" class="menu__item" active-class="isActive">
                    <span>{{ $t('profile.achievements.tabTitle') }}</span>
                </nuxt-link>
                <nuxt-link :to="localePath({name: 'profile-objects'})" class="menu__item" active-class="isActive">
                    <span>{{ $t('profile.objects.tabTitle') }}</span>
                </nuxt-link>
                <nuxt-link :to="localePath({name: 'profile-tickets'})" class="menu__item" active-class="isActive">
                    <span>{{ $t('profile.tickets.tabTitle') }}</span>
                </nuxt-link>
                <nuxt-link :to="localePath({name: 'profile-tasks'})" class="menu__item" active-class="isActive">
                    <span>{{ $t('profile.tasks.tabTitle') }}</span>
                </nuxt-link>
                <nuxt-link :to="localePath({name: 'profile-comments'})" class="menu__item" active-class="isActive">
                    <span>{{ $t('profile.comments.tabTitle') }}</span>
                </nuxt-link>
                <a class="menu__item --logout" @click="logout()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M12 15.3339V17.5876C12 18.3689 11.3654 19 10.5797 19H2.42033C1.63462 19 1 18.3689 1 17.5876V2.41235C1 1.63105 1.63462 1 2.42033 1H10.5797C11.3654 1 12 1.63105 12 2.41235V4.66611" stroke="white" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round"/>
                        <path d="M19 10H7" stroke="white" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round"/>
                        <path d="M16 13L19 10.0169L16 7" stroke="white" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import {call} from 'vuex-pathify'

    export default {
        name: 'UserPageHeader',
        methods: {
            logout: call('authentication/logout')
        }
    }
</script>

<style lang="scss">
    @import "@/styles/mixins.scss";

    .user-page {
        overflow-x: hidden;

        .container {
            @media all and (max-width: 768px){
                padding: 0;
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

        &__header {
            width: 100%;
            min-height: 180px;
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: flex-end;
            @media all and (max-width: 768px){
                display: none;
            }
            .menu {
                height: 50px;
                width: 100%;
                background: rgba(0, 0, 0, 0.7);
                padding: 0 0 0 50%;
                @media all and (max-width: 1280px) {
                    padding: 0 0 0 550px;
                }
                @media all and (max-width: 1200px) {
                    padding: 0 0 0 490px;
                }
                @media all and (max-width: 1024px) {
                    padding: 0 0 0 360px;
                }

                &__content {
                    margin-left: -130px;
                    display: flex;
                    justify-content: flex-start;
                    align-items: center;
                    position: relative;
                 }

                &__item {
                    padding: 0 20px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    font-size: 16px;
                    line-height: 20px;
                    color: #ffffff;
                    height: 50px;
                    @media all and (max-width: 1200px) {
                        padding: 0 10px;
                        font-size: 14px;
                    }
                    span {
                        color: #ffffff;
                    }
                    &.--logout {
                         position: absolute;
                         right: 0;
                         top: 0;
                         width: 50px;
                         height: 50px;
                         display: block;
                         padding: 15px 0 0 10px;
                        svg {
                            display: block;
                        }
                    }

                    &.isActive, &:hover {
                        background: transparentize(#0f6bf5, 0.25);
                    }
                }
            }
        }

        &__row {
             display: flex;
             justify-content: flex-start;
             align-items: flex-start;
             flex-direction: row;
            @media all and (max-width: 768px) {
                display: block;
            }
        }

        &__profile {
            width: 390px;
            position: relative;
            top: -110px;
            @media all and (max-width: 1280px) {
                width: 330px;
            }
            @media all and (max-width: 1024px) {
                width: 200px;
            }
            @media all and (max-width: 768px) {
                top: 0;
                width: 100%;
            }
            & > div {
                margin-top: 10px;
                &:first-child {
                    margin-top: 0;
                }
            }
        }

        &__tabs {
            flex: 1 0 auto;
            max-width: calc(100% - 450px);
            margin-left: 60px;
            @media all and (max-width: 1200px) {
                margin-left: 20px;
            }
            @media all and (max-width: 1023px) {
                max-width: calc(100% - 200px);
            }
            @media all and (max-width: 768px){
                max-width: 100%;
                width: 100%;
                margin: 0;
                padding: 0 20px;
            }
        }
    }
</style>