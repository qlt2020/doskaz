<template>
    <div class="lang-select" v-click-outside="close">
        <div class="lang-select__selected" @click="selectLang = !selectLang">
            <span>{{ currentLocale.name }}</span>
            <svg width="8" height="4" viewBox="0 0 8 4" fill="none" xmlns="http://www.w3.org/2000/svg"
                 class="lang-select__arrow-desktop">
                <path d="M4 4L3.49691e-07 2.54292e-07L8 9.53674e-07L4 4Z" fill="#333333"/>
            </svg>
            <svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg"
                 class="lang-select__arrow-mobile">
                <path d="M1 13L7 7L1 1" stroke="#7B95A7" stroke-width="2"/>
            </svg>
        </div>
        <div class="lang-select__list" v-if="selectLang">
            <div
                    class="lang-select__item"
                    v-for="locale in $i18n.locales"
                    :key="locale.code"
                    :class="{selected: $i18n.locale === locale.code}"
                    @click="$router.push(switchLocalePath(locale.code))"
            >
                <span>{{ locale.name }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import ClickOutside from 'vue-click-outside'

    export default {
        data() {
            return {
                selectLang: false,
            };
        },
        methods: {
            close() {
                this.selectLang = false
            }
        },
        computed: {
            currentLocale() {
                return this.$i18n.locales.find(locale => this.$i18n.locale === locale.code)
            }
        },
        directives: {
            ClickOutside
        },
    };
</script>

<style lang="scss">
    @import "./../styles/mixins.scss";

    .lang-select {
        position: relative;
        cursor: pointer;

        @media all and (max-width: 768px) {
            padding: 0 14px 0 20px;
        }

        &__selected {
            display: flex;
            align-items: center;
            justify-content: flex-end;

            @media all and (max-width: 768px) {
                justify-content: space-between;
            }

            span {
                font-size: 14px;
                line-height: 20px;
                color: #333333;
            }

            .lang-select__arrow-desktop {
                display: block;
                margin-left: 4px;
                @media all and (max-width: 768px) {
                    display: none;
                }
            }

            .lang-select__arrow-mobile {
                display: none;
                @media all and (max-width: 768px) {
                    display: block;
                }
            }
        }

        &__list {
            display: block;
            position: absolute;
            top: 100%;
            left: -15px;
            background: #ffffff;
            z-index: 3;
            border: 1px solid $stroke;
            padding: 10px 0;
            @media all and (max-width: 1023px) {
                right: -4px;
                left: auto;
            }
            @media all and (max-width: 768px) {
                top: auto;
                bottom: 100%;
                right: 14px;
                margin: 0 0 14px;
            }
        }

        &__item {
            font-size: 14px;
            line-height: 30px;
            color: #333333;
            padding: 0 14px;
            transition: background 0.3s;
            text-align: left;
            width: 98px;
            @media all and (max-width: 1023px) {
                text-align: right;
            }

            &:hover, &.selected {
                background: $light-gray;
                font-weight: 700;
            }
        }
    }
</style>