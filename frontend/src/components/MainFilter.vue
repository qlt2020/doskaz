<template>
    <div class="main-filter">
        <div class="main-filter__mobile-top">
            <div class="main-filter__header">
                <nuxt-link :to="localePath({name: 'index'})" class="main-filter__logo --mob">
                    <img :src="require(`@/assets/logo_${$i18n.locale}.svg`)" alt/>
                </nuxt-link>

                <div class="main-filter__menu --desktop">
                    <a href="#"><span>{{ $t('mainMenu.help') }}</span></a>
                    <nuxt-link :to="localePath({name: 'about'})"><span>{{ $t('mainMenu.about') }}</span></nuxt-link>
                    <nuxt-link :to="localePath({name: 'blog-category'})"><span>{{ $t('mainMenu.blog') }}</span></nuxt-link>
                    <nuxt-link :to="localePath({name: 'contacts'})"><span>{{ $t('mainMenu.contacts') }}</span></nuxt-link>
                </div>

                <div class="spacer"></div>

                <div class="main-filter__visual --desktop" @click="enableVisualImpairedMode">
                    <svg
                            width="30"
                            height="16"
                            viewBox="0 0 30 16"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                                d="M15.0243 0C9.141 0 3.84117 3.01459 0 7.8282C3.84117 12.6904 9.141 15.6564 15.0243 15.6564C20.9076 15.6564 26.2075 12.6418 30.0486 7.8282C26.1588 3.01459 20.859 0 15.0243 0ZM15.0243 14.0519C11.5721 14.0519 8.80065 11.2804 8.80065 7.8282C8.80065 4.37601 11.5721 1.60454 15.0243 1.60454C18.4765 1.60454 21.248 4.37601 21.248 7.8282C21.248 11.2804 18.4279 14.0519 15.0243 14.0519Z"
                                fill="black"
                        />
                        <path
                                d="M17.8445 7.58509C17.1151 7.58509 16.483 7.00162 16.483 6.22366C16.483 5.73744 16.7748 5.29984 17.1638 5.05673C16.5803 4.5705 15.8023 4.32739 15.0244 4.32739C13.0795 4.32739 11.5236 5.88331 11.5236 7.8282C11.5236 9.7731 13.0795 11.329 15.0244 11.329C16.9693 11.329 18.5252 9.7731 18.5252 7.8282C18.5252 7.68234 18.5252 7.53647 18.4766 7.3906C18.2821 7.48785 18.0876 7.58509 17.8445 7.58509Z"
                                fill="black"
                        />
                    </svg>
                </div>

                <div class="main-filter__language --desktop">
                    <LangSelect/>
                </div>
                <div class="burger-wrapper" @click="mainPageMobOpened()">
                    <span class="burger">
                      <span class="burger-line"></span>
                    </span>
                </div>
            </div>

            <div class="main-filter__title --desktop">
                <nuxt-link :to="localePath({name: 'index'})" class="main-filter__logo">
                    <img :src="require(`@/assets/logo_${$i18n.locale}.svg`)" alt/>
                </nuxt-link>
                <city-selector/>
            </div>
            <div class="main-filter__search" v-click-outside="closeSearch">
                <form class="input">
                    <input type="text" :placeholder="$t('mainFilter.searchPlaceholder')"  @focus="searchFocused = true" @input="search({query: $event.target.value, cityId})" v-model="query"/>
                    <button alt="search">
                        <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                    d="M23.3397 20.1519L19.3617 16.1746C20.4101 14.5385 21.036 12.6053 21.036 10.5179C21.036 4.70874 16.3272 0 10.518 0C4.70944 0 0 4.70874 0 10.518C0 16.3273 4.70944 21.036 10.518 21.036C12.6053 21.036 14.5385 20.4101 16.1739 19.3617L20.1518 23.3397C21.0322 24.2201 22.4593 24.2201 23.3397 23.3397C24.2201 22.4593 24.2201 21.0323 23.3397 20.1519ZM10.518 18.0309C6.369 18.0309 3.00511 14.6677 3.00511 10.518C3.00511 6.36905 6.36905 3.00516 10.518 3.00516C14.6676 3.00516 18.0308 6.36905 18.0308 10.518C18.0308 14.6677 14.6676 18.0309 10.518 18.0309Z"
                                    fill="#5B6067"
                            />
                        </svg>
                    </button>
                </form>
                <div class="voice-input">
                    <button>
                        <svg
                                width="18"
                                height="24"
                                viewBox="0 0 18 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                        >
                            <g clip-path="url(#clip0)">
                                <path
                                        d="M8.90137 18.0282V22.5352"
                                        stroke="#5B6067"
                                        stroke-width="2.5"
                                        stroke-miterlimit="10"
                                />
                                <path
                                        d="M5.40845 22.5352H12.3944"
                                        stroke="#5B6067"
                                        stroke-width="2.5"
                                        stroke-miterlimit="10"
                                        stroke-linecap="round"
                                />
                                <path
                                        d="M16.3381 10.9296C16.3381 14.9859 12.9578 18.3662 8.90146 18.3662C4.84513 18.3662 1.46484 14.9859 1.46484 10.9296"
                                        stroke="#5B6067"
                                        stroke-width="2.5"
                                        stroke-miterlimit="10"
                                        stroke-linecap="round"
                                />
                                <path
                                        d="M8.90143 14.9859C6.64791 14.9859 4.84509 13.1831 4.84509 10.9296V4.16901C4.84509 1.91549 6.64791 0.112671 8.90143 0.112671C11.155 0.112671 12.9578 1.91549 12.9578 4.16901V10.9296C12.9578 13.1831 11.155 14.9859 8.90143 14.9859Z"
                                        fill="#5B6067"
                                />
                            </g>
                            <defs>
                                <clipPath id="clip0">
                                    <rect width="17.6901" height="24" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </div>
                <div class="search-sub" v-if="searchHighlights.length && searchFocused">
                    <nuxt-link :to="localePath({name: 'objects-id', params: {id: item.id}, query: {zoom: 19}})" class="search-sub__item" v-for="item in searchHighlights" :key="item.id">
                        <div class="search-sub__icon">
                            <i class="fa" :class="item.icon"></i>
                        </div>
                        <div class="search-sub__info">
                            <span class="search-sub__title">{{ item.title }}, {{ item.category }}</span>
                            <span class="search-sub__address">{{ item.address }}</span>
                        </div>
                    </nuxt-link>
                </div>
            </div>
        </div>
        <CategorySelector/>
    </div>
</template>

<script>
    import CategorySelector from "./../components/CategorySelector";
    import LangSelect from "./../components/LangSelect";
    import throttle from 'lodash/throttle'
    import {get, call, sync} from 'vuex-pathify'
    import CitySelector from "./CitySelector";
    import ClickOutside from 'vue-click-outside'

    export default {
        data(){
            return {
                searchFocused: false
            }
        },
        directives: {
            ClickOutside
        },
        components: {
            CitySelector,
            CategorySelector,
            LangSelect
        },
        computed: {
            cityId: get('settings/cityId'),
            searchHighlights: get('map/searchHighlights'),
            query: sync('map/search')
        },
        methods: {
            closeSearch() {
                this.searchFocused = false
            },
            search: throttle(call('map/search'), 1000),
            mainPageMobOpened() {
                this.$nuxt.$emit('mainPageMobOpened');
            },
            enableVisualImpairedMode: call('visualImpairedModeSettings/enable')
        }
    };
</script>

<style lang="scss">
    @import "./../styles/mixins.scss";

    .search-sub {
        position: absolute;
        left: 0;
        top: 80px;
        border: 1px solid $stroke;
        border-top: none;
        background: #FFFFFF;
        width: 500px;
        padding: 8px 0;
        z-index: 3;
        @media all and (max-width: 768px){
            top: 50px;
            width: calc(100% + 1px);
            border-color: rgba(123, 149, 167, 0.3);
        }
        &__item {
            height: 50px;
            width: 100%;
            padding: 10px 20px;
            display: flex;
        }
        &__icon {
            width: 20px;
            i {
                color: $stroke;
                opacity: 0.5;
                font-size: 12px;
            }
        }
        &__info {
            width: calc(100% - 20px);
        }
        &__title {
            font-size: 14px;
            line-height: 18px;
            display: block;
            white-space: nowrap;
            overflow: hidden;
        }
        &__address {
            font-size: 12px;
            line-height: 18px;
            opacity: 0.9;
            display: block;
            white-space: nowrap;
            overflow: hidden;
        }
    }

    .main-filter {
        background: $white;
        width: 100%;
        flex: 1 0 auto;
        margin-bottom: 10px;
        padding: 15px 0 15px 40px;
        max-height: calc(100% - 400px);

        &__header {
            display: flex;
            align-items: center;
            padding-right: 40px;
            .burger {
                &-wrapper {
                    display: none;
                    @media all and (max-width: 1023px) {
                        display: block;
                    }
                }
            }
        }

        &__language {
            &.--desktop {
                @media all and (max-width: 1023px) {
                    display: none;
                }
            }
        }

        &__menu {
            display: flex;
            justify-content: flex-start;
            align-items: center;

            &.--desktop {
                @media all and (max-width: 1023px) {
                    display: none;
                }
            }

            a {
                font-size: 14px;
                line-height: 20px;
                color: #333333;
                transition: opacity 0.3s;
                margin-right: 14px;

                &:last-child {
                    margin-right: 0;
                }

                &:hover {
                    opacity: 0.7;
                }
            }
        }

        &__visual {
            margin: 0 15px;
            cursor: pointer;
            transition: opacity 0.3s;

            &:hover {
                opacity: 0.7;
            }

            &.--desktop {
                @media all and (max-width: 1023px) {
                    display: none;
                }
            }
        }

        &__title {
            padding-top: 20px;
            padding-right: 40px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-end;
            @media all and (max-width: 1366px) {
                padding-right: 30px;
            }
            &.--desktop {
                @media all and (max-width: 1023px) {
                    display: none;
                }
            }
        }

        &__logo {
            display: block;
            @media all and (max-width: 1366px) {
                img {
                    width: 146px;
                    height: auto;
                }
            }
            &.--mob {
                display: none;
                img {
                    width: 100px;
                    height: auto;
                }
                @media all and (max-width: 1023px) {
                    display: block;
                }
            }
        }

        &__geo {
            margin-bottom: 4px;
            position: relative;
            z-index: 2;

            &-city {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
                cursor: pointer;
                transition: opacity 0.3s;

                &:hover {
                    opacity: 0.7;
                }

                span {
                    font-size: 16px;
                    line-height: 20px;
                    color: #333333;
                    border-bottom: 1px dashed #333333;
                    @media all and (max-width: 768px) {
                        font-siZE: 14px;
                        line-height: 18px;
                    }
                }

                svg {
                    margin-right: 7px;
                }
            }

            &-sub {
                margin: 16px 0 0;
                position: absolute;
                border: 1px solid $stroke;
                left: 0;
                top: 100%;
                background: #ffffff;
                display: flex;
                @media all and (max-width: 768px) {
                    margin: 10px 0 0;
                }
            }

            &-list {
                padding: 15px 0;
                max-height: 480px;
                overflow-x: hidden;
                overflow-y: auto;
                @media all and (max-width: 768px) {
                    padding: 8px 0;
                    max-height: 144px;
                }

                &::-webkit-scrollbar {
                    width: 10px;
                }

                &::-webkit-scrollbar-track {
                    background: $tr;
                }

                &::-webkit-scrollbar-thumb {
                    background: transparentize(#c4c4c4, 0.5);
                }
            }

            &-item {
                display: block;
                cursor: pointer;
                font-size: 16px;
                line-height: 30px;
                padding: 0 23px 0 20px;
                min-width: 260px;
                white-space: nowrap;
                background: transparent;
                transition: background 0.3s;
                @media all and (max-width: 768px) {
                    font-size: 14px;
                    line-height: 22px;
                    min-width: 200px;
                    padding: 0 8px;
                }

                &__title {
                    font-size: 14px;
                    line-height: 20px;
                    color: $stroke;
                    margin: 0 0 4px;
                    display: block;
                }

                &.selected, &:hover {
                    background: $light-gray;
                    font-weight: 700;
                }
            }
        }

        &__search {
            padding-right: 40px;
            padding-top: 30px;
            padding-bottom: 30px;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            position: relative;
            @media all and (max-width: 1366px) {
                padding-right: 30px;
                padding-top: 24px;
                padding-bottom: 24px;
            }
            .input {
                margin-right: 10px;
                @media all and (max-width: 1366px) {
                    height: 40px;
                }

                input {
                    width: calc(100% - 40px);
                }
                button {
                    svg {
                        @media all and (max-width: 1366px) {
                            width: 20px;
                            height:20px;
                        }
                    }
                }
            }

            .voice-input {
                display: block;

                button {
                    border: 1px solid #7b95a7;
                    height: 50px;
                    width: 50px;
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    align-items: center;
                    background: $tr;
                    cursor: pointer;
                    @media all and (max-width: 1366px) {
                        height: 40px;
                        width: 40px;
                    }
                }
            }
        }
    }
</style>