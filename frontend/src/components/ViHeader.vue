<template>
    <div class="vi-header">
        <div class="vi-header__top --bcolor">
            <div class="vi-set-b">
                <div class="vi-set">
                    <span class="vi-set__title --fcolor">{{ $t('visualImpairedSettings.fontSize') }}</span>
                    <div class="vi-set__link-wrapper">
                        <span class="vi-set__link --fs --sm  --bcolor --fcolor" :class="{'--active': visualImpairedModeSettings.fontSize === 'sm'}" @click="changeFontSize('sm')">A</span>
                        <span class="vi-set__link --fs --md  --bcolor --fcolor" :class="{'--active': visualImpairedModeSettings.fontSize === 'md'}" @click="changeFontSize('md')">A</span>
                        <span class="vi-set__link --fs --lrg --bcolor --fcolor" :class="{'--active': visualImpairedModeSettings.fontSize === 'lrg'}" @click="changeFontSize('lrg')">A</span>
                    </div>
                </div>
                <div class="vi-set">
                    <span class="vi-set__title --fcolor">{{ $t('visualImpairedSettings.colorTheme') }}</span>
                    <div class="vi-set__link-wrapper">
                        <span class="vi-set__link --ctheme --white --fcolor" :class="{'--active': visualImpairedModeSettings.colorTheme === 'white'}" @click="changeColorTheme('white')">Ц</span>
                        <span class="vi-set__link --ctheme --black --bcolor --fcolor" :class="{'--active': visualImpairedModeSettings.colorTheme === 'black'}" @click="changeColorTheme('black')">Ц</span>
                    </div>
                </div>
                <div class="vi-set">
                    <span class="vi-set__title --fcolor">{{ $t('visualImpairedSettings.fontFamily') }}</span>
                    <div class="vi-set__link-wrapper">
                        <span class="vi-set__link --ff --btn --bcolor --fcolor" :class="{'--active': visualImpairedModeSettings.fontFamily === 'Lato'}" @click="changeFontFamily('Lato')">{{ $t('visualImpairedSettings.fontFamilySans') }}</span>
                        <span class="vi-set__link --ff --noto --btn --bcolor --fcolor" :class="{'--active': visualImpairedModeSettings.fontFamily === 'Noto'}" @click="changeFontFamily('Noto')">{{ $t('visualImpairedSettings.fontFamilySerif') }}</span>
                    </div>
                </div>
            </div>
            <button class="vi-switch" @click="disableVisualImpairedMode">
                <img :src="require('~/assets/visually-black.svg')" alt="" v-if="visualImpairedModeSettings.colorTheme === 'white'" />
                <img :src="require('~/assets/visually-white.svg')" alt="" v-if="visualImpairedModeSettings.colorTheme === 'black'" />
                <span class="--fcolor">{{ $t('visualImpairedSettings.normalModeSwitchTitle') }}</span>
            </button>
        </div>
        <div class="vi-header__bottom --bcolor --fcolor">
            <nuxt-link :to="localePath({name: 'index'})" class="vi__logo">
                <img :src="require('~/assets/logo-black.svg')" alt="" v-if="visualImpairedModeSettings.colorTheme === 'white'" />
                <img :src="require('~/assets/logo-white.svg')" alt="" v-if="visualImpairedModeSettings.colorTheme === 'black'" />
            </nuxt-link>
            <div class="vi__auth-b">
                <template v-if="!user">
                    <nuxt-link :to="localePath({name: 'login'})" class="vi__auth-link">{{ $t('login.viHeaderLoginTitle') }}</nuxt-link>
                    <span class="vi__auth-or">{{ $t('login.viHeaderLoginOr') }}</span>
                    <nuxt-link :to="localePath({name: 'login'})" class="vi__auth-link">{{ $t('login.viHeaderRegister') }}</nuxt-link>
                </template>
                <template v-else>
                    <nuxt-link :to="localePath({name: 'profile'})" class="vi__auth-link">
                        <username :value="name"/>
                    </nuxt-link>
                </template>
                <span class="vi__auth-text"><b>{{ $t('visualImpairedSettings.siteLanguage') }}</b></span>
                <div class="select">
                    <select :value="$i18n.locale" @change="$router.push(switchLocalePath($event.target.value))">
                        <option v-for="locale in $i18n.locales" :key="locale.code" :value="locale.code">
                            {{ locale.name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {call, get} from 'vuex-pathify'
    import Username from "~/components/Username";

    export default {
        components: {Username},
        methods: {
            disableVisualImpairedMode: call('visualImpairedModeSettings/disable'),
            ...call('visualImpairedModeSettings', [
                'changeFontFamily',
                'changeFontSize',
                'changeColorTheme'
            ])
        },
        computed: {
            name: get('authentication/name'),
            user: get('authentication/user'),
            visualImpairedModeSettings: get('visualImpairedModeSettings'),
        }
    }
</script>

<style lang="scss">

    .vi {
        input[type=checkbox].vi__input {
            display: none;
            & + label {
                padding: 0 0 0 60px;
                line-height: 40px;
                position: relative;
                cursor: pointer;
                &:before {
                    content: '';
                    width: 40px;
                    height: 40px;
                    border: 1px solid #000000;
                    top: 0;
                    left: 0;
                    position: absolute;
                    -webkit-box-sizing: border-box;
                    -moz-box-sizing: border-box;
                    box-sizing: border-box;
                }
            }
            &:checked + label {
                &:before {
                    border-width: 3px;
                    background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAyMiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIwIDJMNy42MjUgMTRMMiA4LjU0NTQ1IiBzdHJva2U9ImJsYWNrIiBzdHJva2Utd2lkdGg9IjMiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K') center no-repeat;
                }
            }
        }

        &-breadcrumb {
            margin: 0 0 30px;
            display: flex;
            &__link {
                line-height: 24px;
                display: block;
                &:before {
                    content: '/';
                    margin: 0 6px;
                }
                &:first-child {
                    &:before {
                        display: none;
                    }
                }
            }
        }

        &-pag {
            justify-content: center;
            display: flex;
            &__item {
                min-width: 50px;
                padding: 0 13px;
                width: auto !important;
                & + .vi-pag__item {
                    margin: 0 0 0 20px;
                }
            }
            &__prev, &__next {
                width: 70px;
                height: 50px;
                border: 1px solid;
                justify-content: center;
                align-items: center;
                display: flex;
                &.disabled {
                    opacity: 0.3;
                }
            }

            &__prev {

            }

            &__wrapper {
                margin: 0 0 40px;
            }
        }

        &__complaint {
            margin: 120px 0 60px;
            .vi__button {
                margin: 0 40px 0 0;
                width: 290px;
                padding: 0;
            }
        }

        &__input {
            &-b {
                margin: 40px 0;
            }
            &-wrapper {
                display: flex;
                justify-content: flex-start;
                align-items: flex-start;
                input + label {
                    margin: 0 60px 0 0;
                }
            }
        }

        &__title {
            font-weight: bold;
            text-transform: uppercase;
            &-link {
                font-size: 22px !important;
                line-height: 24px;
            }
            &-flex {
                margin: 34px 0 24px;
                align-items: center;
                display: flex;
                justify-content: space-between;
                & + .vi__line {
                    margin: 25px 0 0;
                }
            }
        }

        &-search-button {
            width: 100%;
            height: 60px;
            background: #FFFFFF;
            border: 1px solid;
            text-transform: uppercase;
            display: block;
            border-left: none;
            line-height: 58px;
            cursor: pointer;
        }

        &__label {
            display: block;
            font-weight: 700;
            margin: 0 0 20px;
            line-height: 20px;
        }

        &__line {
            display: flex;
            margin: 50px 0 0;
            align-items: flex-end;
            & + .vi__title {
                margin: 72px 0 40px;
            }
            .col {
                -ms-flex-preferred-size: 0;
                flex-basis: 0;
                -ms-flex-positive: 1;
                -webkit-box-flex: 1;
                flex-grow: 1;
                &.--city {
                    max-width: 370px;
                    min-width: 370px;
                }
                &.--padding {
                    padding: 0 !important;
                }
                &.--search {
                    max-width: 150px;
                    min-width: 150px;
                }
                &:first-child {
                    padding: 0 20px 0 0;
                }
                &:last-child {
                    padding: 0 0 0 20px;
                }
            }
        }
        .--fcolor {
            * {
                color: #000000;
            }
        }
        .--bcolor {
            border-color: #000000;
        }

        &.--lrg {
            * {
                font-size: 24px;
            }
            .vi {
                &-set {
                    &__title {
                        font-size: 22px
                    }
                    &__link {
                        &.--ctheme, &.--ff {
                            font-size: 22px;
                            &.--noto {
                                font-size: 21px;
                            }
                        }
                    }
                }
                &-switch {
                    span {
                        font-size: 22px;
                    }
                }
                &__title {
                    font-size: 40px;
                    line-height: 42px;
                }
            }
            .select {
                select {
                    font-size: 24px;
                    line-height: 26px;
                }
            }
        }
        &.--md {
            * {
                font-size: 21px;
            }
            .vi {
                &-popup {
                    &__title {
                        font-size: 29px !important;
                    }
                    &__text {
                        font-size: 21px !important;
                    }
                }
                &-add-object {
                    &__title {
                        font-size: 29px !important;
                    }
                }
                &-set {
                    &__title {
                        font-size: 19px
                    }
                    &__link {
                        &.--ctheme, &.--ff {
                            font-size: 19px;
                            &.--noto {
                                font-size: 18px;
                            }
                        }
                    }
                }
                &-switch {
                    span {
                        font-size: 19px;
                    }
                }
                &__title {
                    font-size: 38px;
                    line-height: 42px;
                }
                &-object {
                    &__history-date {
                        font-size: 19px !important;
                    }
                    &__title {
                        font-size: 36px !important;
                    }
                    &__available {
                        &-title {
                            font-size: 36px !important;
                        }
                    }
                }
                &-search {
                    &__title {
                       font-size: 28px !important;
                    }
                }
                &-blog {
                    &__list {
                        &-title {
                            font-size: 28px !important;
                        }
                        &-link {
                            font-size: 20px !important;
                        }
                    }
                    &__inside {
                        &-date {
                            font-size: 19px !important;
                        }
                    }
                }
            }
            .select {
                select {
                    font-size: 21px;
                    line-height: 23px;
                }
            }
        }
        &.--sm {
            * {
                font-size: 18px;
            }
            .vi {
                &-popup {
                    &__title {
                        font-size: 26px !important;
                    }
                    &__text {
                        font-size: 18px !important;
                    }
                }
                &-add-object {
                    &__title {
                        font-size: 26px !important;
                    }
                }
                &-set {
                    &__title {
                        font-size: 16px
                    }
                    &__link {
                        &.--ctheme, &.--ff {
                            font-size: 16px;
                            &.--noto {
                                font-size: 15px;
                            }
                        }
                    }
                }
                &-switch {
                    span {
                        font-size: 16px;
                    }
                }
                &__title {
                    font-size: 36px;
                    line-height: 42px;
                }
                &-object {
                    &__history-date {
                        font-size: 16px !important;
                    }
                    &__title {
                        font-size: 32px !important;
                    }
                    &__available {
                        &-title {
                            font-size: 32px !important;
                        }
                    }
                }
                &-search {
                    &__title {
                       font-size: 24px !important;
                    }
                }
                &-blog {
                    &__list {
                        &-title {
                            font-size: 24px !important;
                        }
                        &-link {
                            font-size: 17px !important;
                        }
                    }
                    &__inside {
                        &-date {
                            font-size: 16px !important;
                        }
                    }
                }
            }
            .select {
                select {
                    font-size: 18px;
                }
            }
        }
        &.--black {
            background: #000000;
            input[type=checkbox].vi__input {
                & + label {
                    &:before {
                        border: 1px solid #FFFFFF;
                    }
                }
                &:checked + label {
                    &:before {
                        background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAyMiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIwIDJMNy42MjUgMTRMMiA4LjU0NTQ1IiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjMiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K') center no-repeat;
                    }
                }
            }
            * {
                color: #FFFFFF;
            }
            .--fcolor {
                color: #FFFFFF;
            }
            .--bcolor {
                border-color: #FFFFFF;
            }
            .vi {
                &-popup {
                    border-color: #FFFFFF;
                    background: #000000;
                    &__close {
                        svg {
                            path {
                                stroke: #000;
                            }
                        }
                    }
                }
                &-add-object {
                    &__link.--active {
                        border-color: #FFFFFF;
                        &:before {
                            border-top-color: #FFFFFF;
                        }
                        &:after {
                            border-top-color: #000000;
                        }
                    }
                    &__textarea {
                        background: #000000;
                        color: #FFFFFF;
                    }
                }
                &-set {
                    &__link {
                        &:hover, &.--active {
                            &:before {
                                border-top-color: #FFFFFF;
                            }
                            &:after {
                                border-top-color: #000000;
                            }
                        }
                        &.--ctheme.--white {
                            background: #FFFFFF;
                            color: #000000;
                        }
                    }
                }
                &__button {
                    background: #000000;
                    color: #FFFFFF;
                    border-color: #FFFFFF;
                    &:hover {
                        background: #FFFFFF;
                        color: #000000;
                        border-color: #000000;
                    }
                }
                &-object {
                    &__tab-link {
                        &-b {
                            &:after {
                                background: #FFFFFF;
                            }
                        }
                        &.--active {
                            border-color: #FFFFFF;
                            &:after {
                                background: #000000;
                            }
                        }
                    }
                }
                &-pag {
                    &__prev, &__next {
                        border-color: #ffffff;
                        svg {
                            path {
                                stroke: #ffffff;
                            }
                        }
                    }
                }
            }
            .select {
                height: 60px;
                select {
                    color: #FFFFFF;
                    background: #000000;
                    height: 60px;
                }
                &:after {
                    border-top-color: #FFFFFF;
                }
            }
            .input {
                background: #000000;
                input {
                    color: #FFFFFF;
                    background: #000000;
                }
            }
            .vi-search-button {
                color: #FFFFFF;
                background: #000000;
            }
            #blueimp-gallery, #blueimp-video {
                background: #000000;
                color: #ffffff;
                > {
                    .close {
                        background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjYwIiBoZWlnaHQ9IjYwIiBmaWxsPSJibGFjayIvPgo8cGF0aCBkPSJNNTAgMTBMOS45OTk5OSA1MCIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4KPHBhdGggZD0iTTEwIDEwTDUwIDUwIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIvPgo8L3N2Zz4K') center no-repeat;
                    }

                    .next {
                        background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgNjAgMTAwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cmVjdCB3aWR0aD0iNjAiIGhlaWdodD0iMTAwIiBmaWxsPSJibGFjayIvPgo8cGF0aCBkPSJNMTUgODBMNDUgNTBMMTUgMjAiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=') center no-repeat;
                    }

                    .prev {
                        background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgNjAgMTAwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cmVjdCB3aWR0aD0iNjAiIGhlaWdodD0iMTAwIiBmaWxsPSJibGFjayIvPgo8cGF0aCBkPSJNNDUgMjBMMTUgNTBMNDUgODAiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=') center no-repeat;
                    }
                    .title {
                        color: #FFFFFF;
                    }
                }
            }
        }

        &.--noto {
            * {
                font-family: 'Noto Serif', serif;
            }
            .select {
                select {
                    line-height: 30px;
                }
            }
            &.--lrg {
                * {
                    font-size: 22px;
                }
                .vi {
                    &-add-object {
                        &__title {
                            font-size: 31px !important;
                        }
                    }
                    &-set {
                        &__title {
                            font-size: 21px
                        }
                        &__link {
                            &.--ctheme, &.--ff {
                                font-size: 21px;
                            }
                        }
                    }
                    &-switch {
                        span {
                            font-size: 21px;
                        }
                    }
                    &__title {
                        font-size: 39px;
                        line-height: 42px;
                    }
                    &-object {
                        &__history-date {
                            font-size: 21px !important;
                        }
                        &__title {
                            font-size: 39px !important;
                        }
                        &__available {
                            &-title {
                                font-size: 39px !important;
                            }
                        }
                    }
                    &-search {
                        &__title {
                            font-size: 31px !important;
                        }
                    }
                    &-blog {
                        &__list {
                            &-title {
                                font-size: 31px !important;
                            }
                            &-link {
                                font-size: 21px !important;
                            }
                        }
                        &__inside {
                            &-date {
                                font-size: 21px !important;
                            }
                        }
                    }
                }
                .select {
                    select {
                        font-size: 23px;
                    }
                }
            }
            &.--md {
                * {
                    font-size: 20px;
                }
                .vi {
                    &-add-object {
                        &__title {
                            font-size: 28px !important;
                        }
                    }
                    &-set {
                        &__title {
                            font-size: 18px
                        }
                        &__link {
                            &.--ctheme, &.--ff {
                                font-size: 18px;
                            }
                        }
                    }
                    &-switch {
                        span {
                            font-size: 18px;
                        }
                    }
                    &__title {
                        font-size: 37px;
                    }
                    &-object {
                        &__history-date {
                            font-size: 18px !important;
                        }
                        &__title {
                            font-size: 35px !important;
                        }
                        &__available {
                            &-title {
                                font-size: 35px !important;
                            }
                        }
                    }
                    &-search {
                        &__title {
                           font-size: 27px !important;
                        }
                    }
                    &-blog {
                        &__list {
                            &-title {
                                font-size: 27px !important;
                            }
                            &-link {
                                font-size: 19px !important;
                            }
                        }
                        &__inside {
                            &-date {
                                font-size: 19px !important;
                            }
                        }
                    }
                }
                .select {
                    select {
                        font-size: 20px;
                    }
                }
            }
            &.--sm {
                * {
                    font-size: 17px;
                }
                .vi {
                    &-add-object {
                        &__title {
                            font-size: 25px !important;
                        }
                    }
                    &-set {
                        &__title {
                            font-size: 15px
                        }
                        &__link {
                            &.--ctheme, &.--ff {
                               font-size: 15px;
                            }
                        }
                    }
                    &-switch {
                        span {
                            font-size: 15px;
                        }
                    }
                    &__title {
                        font-size: 35px;
                    }
                    &-object {
                        &__history-date {
                            font-size: 15px !important;
                        }
                        &__title {
                            font-size: 31px !important;
                        }
                        &__available {
                            &-title {
                                font-size: 31px !important;
                            }
                        }
                    }
                    &-search {
                        &__title {
                            font-size: 23px !important;
                        }
                    }
                    &-blog {
                        &__list {
                            &-title {
                                font-size: 23px !important;
                            }
                            &-link {
                                font-size: 16px !important;
                            }
                        }
                        &__inside {
                            &-date {
                                font-size: 15px !important;
                            }
                        }
                    }
                }
                .select {
                    select {
                        font-size: 17px;
                    }
                }
            }
        }
        .photo-input {
            border: 1px solid;
            font-size: 36px;
            font-weight: 700;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 140px;
            height: 140px;
            margin: 0 40px 30px 0;
            background: transparent;
            cursor: pointer;
            &__wrapper {
                display: flex;
                justify-content: flex-start;
            }
        }
        &-container {
            max-width: 1200px;
            padding: 0 20px;
            margin: 0 auto;
        }
        &-header {
            padding: 0;
            + .vi__title {
                margin: 42px 0;
            }
            &__top {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                padding: 20px 0 40px;
                border-bottom: 3px solid;
            }
            &__bottom {
                padding: 30px 0 34px;
                border-bottom: 2px solid;
                display: flex;
                justify-content: space-between;
            }
        }
        &-switch {
            border: none;
            display: flex;
            background: transparent;
            outline: none;
            align-items: center;
            margin: 0 0 16px;
            cursor: pointer;
            img {
                width: 30px;
            }
            span {
                line-height: 20px;
                margin: 0 0 0 10px;
            }
        }
        &__logo {
            width: 227px;
            display: block;
            img {
                display: block;
                width: 100%;
                height: auto;
            }
        }
        &__auth {
            &-b {
                display: flex;
                align-items: center;
                white-space: nowrap;
                .select {
                    max-width: 220px;
                    min-width: 220px;
                }
            }
            &-link {
                font-weight: 700;
            }
            &-text {
                margin: 0 20px 0 60px;
            }
            &-or {
                margin: 0 6px;
            }
        }
        &-set {
             text-align: left;
            &:first-child {
                width: 230px;
            }
            &:nth-child(2){
                width: 220px;
            }
            &-b {
                display: flex;
            }
            &__title {
                font-size: 22px;
                line-height: 20px;
                font-weight: 400;
                display: block;
                margin: 0 0 20px;
                color: inherit;
            }
            &__link {
                width: 50px;
                height: 50px;
                position: relative;
                margin: 0 0 0 10px;
                border: 1px solid;
                text-align: center;
                line-height: 48px;
                cursor: pointer;
                &-wrapper {
                    display: flex;
                }
                &.--active, &:hover {
                    border-width: 3px;
                    line-height: 44px;
                    &:before {
                        content: '';
                        position: absolute;
                        border-top: 6px solid #000000;
                        border-left: 7px solid transparent;
                        border-right: 7px solid transparent;
                        left: 50%;
                        margin: 0 0 0 -7px;
                        bottom: -9px;
                    }
                    &:after {
                        content: '';
                        position: absolute;
                        border-top: 5px solid #FFFFFF;
                        border-left: 6px solid transparent;
                        border-right: 6px solid transparent;
                        left: 50%;
                        margin: 0 0 0 -6px;
                        bottom: -5px;
                        z-index: 1;
                    }
                }
                &.--btn {
                    width: 150px;
                    margin:  0 0 0 20px;
                    font-size: 22px;
                    font-family: 'Lato', sans-serif;
                    &.--noto {
                        width: 180px;
                        font-size: 21px;
                        font-family: 'Noto Serif', serif;
                    }
                }
                &.--fs {
                    &.--sm {
                        font-size: 16px;
                    }
                    &.--md {
                        font-size: 20px;
                    }
                    &.--lrg {
                        font-size: 26px;
                    }
                }
                &.--ctheme {
                    &.--black {
                        background: #000000;
                        color: #FFFFFF;
                    }
                }
                &:first-child {
                     margin: 0;
                }
                &-wrapper {
                    display: flex;
                }
            }
        }
        &-object {
            &__tab {
                &-link {
                    position: relative;
                    padding: 0 20px;
                    z-index: 1;
                    box-sizing: border-box;
                    cursor: pointer;
                    border: 2px solid transparent;
                    border-bottom: none;
                    line-height: 56px;
                    &.--active {
                        border-color: #000000;
                        border-bottom: none;
                        &:after {
                            content: '';
                            height: 2px;
                            left: 0;
                            bottom: 0;
                            right: 0;
                            background: #FFFFFF;
                            position: absolute;
                        }
                    }
                    &-b {
                        position: relative;
                        display: flex;
                        justify-content: flex-start;
                        width: 100%;
                        &:after {
                            content: '';
                            left: 0;
                            bottom: 0;
                            right: 0;
                            height: 2px;
                            background: #000000;
                            position: absolute;
                        }
                    }
                }
            }
        }
        &__button {
            cursor: pointer;
            height: 60px;
            font-size: 24px;
            line-height: 60px;
            color: #000000;
            padding: 0 28px;
            border: 1px solid #000000;
            text-transform: uppercase;
            background: transparent;
            &:hover {
                background: #000000;
                color: #FFFFFF;
                border-color: #FFFFFF;
            }
        }
        &-search {
            &__title {
                font-size: 32px !important;
                line-height: 40px;
            }
            &__list {
                list-style: none;
                border-top: 2px solid;
                margin: 60px 0 100px;
                padding: 0;
                width: 100%;
                & + .vi__complaint {
                    margin: 100px 0 60px;
                }
            }
            &__item {
                padding: 48px 0 32px;
                border-bottom: 2px solid;
            }
            &__text {
                font-size: 24px;
                line-height: 40px;
                & + .vi-search__text {
                    margin: 0 0 0 20px;
                }
            }
            &__around {
                margin: 20px 0 0;
                display: flex;
                justify-content: space-between;
            }
        }
        .input {
            height: 60px;
            background: #ffffff;
            border: 1px solid;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 10px 0 20px;
            flex: 1 0 auto;
            input {
                color: #000000;
                background: #ffffff;
                height: 100%;
                display: block;
                width: 100%;
                outline: none;
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
            }
        }
        .select {
            height: 60px;
            position: relative;
            width: 100%;
            select {
                border: 1px solid;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                padding: 0 40px 0 20px;
                line-height: 26px;
                height: 100%;
                display: block;
                width: 100%;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                -ms-appearance: none;
                color: #000000;
                background: #FFFFFF;
            }
            &:after {
                content: '';
                position: absolute;
                width: 0;
                height: 0;
                top: 28px;
                right: 20px;
                border-top: 5px solid #000000;
                border-right: 5px solid transparent;
                border-left: 5px solid transparent;
            }
        }
    }

</style>
</style>