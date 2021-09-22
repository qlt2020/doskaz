<template>
  <div class="vi-top" v-if="enabled">
    <div class="container">
      <div class="vi-top__item-b">
        <div class="vi-top__item">
          <h5 class="vi-top__title">
            {{ $t("visualImpairedSettings.fontSize") }}
          </h5>
          <div class="vi-top__link-wrapper">
            <span
              class="vi-top__link --sm"
              :class="{
                '--active': fontSize === 'sm',
                '--ct-black': colorTheme === 'black',
              }"
              @click="changeFontSize('sm')"
              >A</span
            >
            <span
              class="vi-top__link --md"
              :class="{
                '--active': fontSize === 'md',
                '--ct-black': colorTheme === 'black',
              }"
              @click="changeFontSize('md')"
              >A</span
            >
            <span
              class="vi-top__link --lrg"
              :class="{
                '--active': fontSize === 'lrg',
                '--ct-black': colorTheme === 'black',
              }"
              @click="changeFontSize('lrg')"
              >A</span
            >
          </div>
        </div>
        <div class="vi-top__item">
          <h5 class="vi-top__title">
            {{ $t("visualImpairedSettings.colorTheme") }}
          </h5>
          <div class="vi-top__link-wrapper">
            <span
              class="vi-top__link --white"
              :class="{
                '--active': colorTheme === 'white',
                '--ct-black': colorTheme === 'black',
              }"
              @click="changeColorTheme('white')"
              >Ц</span
            >
            <span
              class="vi-top__link --black"
              :class="{ '--active': colorTheme === 'black' }"
              @click="changeColorTheme('black')"
              >Ц</span
            >
          </div>
        </div>
        <div class="vi-top__item">
          <h5 class="vi-top__title">
            {{ $t("visualImpairedSettings.fontFamily") }}
          </h5>
          <div class="vi-top__link-wrapper">
            <span
              class="vi-top__link --btn"
              :class="{
                '--active': fontFamily === 'lato',
                '--ct-black': colorTheme === 'black',
              }"
              @click="changeFontFamily('lato')"
              >{{ $t("visualImpairedSettings.fontFamilySans") }}</span
            >
          </div>
        </div>
        <button class="vi-switch" @click="disableVisualImpairedMode">
          <img
            :src="require('~/assets/visually-black.svg')"
            alt=""
            v-if="visualImpairedModeSettings.colorTheme === 'white'"
          />
          <img
            :src="require('~/assets/visually-white.svg')"
            alt=""
            v-if="visualImpairedModeSettings.colorTheme === 'black'"
          />
          <span class="--fcolor">{{
            $t("visualImpairedSettings.normalModeSwitchTitle")
          }}</span>
        </button>
      </div>
      <div class="vi-header__bottom --bcolor --fcolor">
        <nuxt-link :to="localePath({ name: 'index' })" class="vi__logo">
          <img
            :src="require('~/assets/logo-black.svg')"
            alt=""
            v-if="visualImpairedModeSettings.colorTheme === 'white'"
          />
          <img
            :src="require('~/assets/logo-white.svg')"
            alt=""
            v-if="visualImpairedModeSettings.colorTheme === 'black'"
          />
        </nuxt-link>
        <div class="vi__auth-b">
          <template v-if="!user">
            <nuxt-link
              :to="localePath({ name: 'login' })"
              class="vi__auth-link"
              >{{ $t("login.viHeaderLoginTitle") }}</nuxt-link
            >
            <span class="vi__auth-or">{{ $t("login.viHeaderLoginOr") }}</span>
            <nuxt-link
              :to="localePath({ name: 'login' })"
              class="vi__auth-link"
              >{{ $t("login.viHeaderRegister") }}</nuxt-link
            >
          </template>
          <template v-else>
            <nuxt-link
              :to="localePath({ name: 'profile' })"
              class="vi__auth-link"
            >
              <username :value="name" />
            </nuxt-link>
          </template>
          <span class="vi__auth-text"
            ><b>{{ $t("visualImpairedSettings.siteLanguage") }}</b></span
          >
          <div class="select">
            <select
              :value="$i18n.locale"
              @change="$router.push(switchLocalePath($event.target.value))"
            >
              <option
                v-for="locale in $i18n.locales"
                :key="locale.code"
                :value="locale.code"
              >
                {{ locale.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div class="vi__footer --bcolor">
        <a href="" class="vi__footer-link">{{ $t("mainMenu.help") }}</a>
        <nuxt-link
          :to="localePath({ name: 'about' })"
          class="vi__footer-link"
          >{{ $t("mainMenu.about") }}</nuxt-link
        >
        <nuxt-link
          :to="localePath({ name: 'blog-cat-slug' })"
          class="vi__footer-link"
          >{{ $t("mainMenu.blog") }}</nuxt-link
        >
        <nuxt-link
          :to="localePath({ name: 'contacts' })"
          class="vi__footer-link"
          >{{ $t("mainMenu.contacts") }}</nuxt-link
        >
      </div>
    </div>
  </div>
</template>

<script>
import { get, call } from "vuex-pathify";

export default {
  name: "ViTop",
  head() {
    return {
      bodyAttrs: {
        class: this.bodyClasses,
      },
    };
  },
  methods: {
    disableVisualImpairedMode: call("visualImpairedModeSettings/disable"),
    ...call("visualImpairedModeSettings", [
      "changeColorTheme",
      "changeFontSize",
      "changeFontFamily",
    ]),
  },
  computed: {
    visualImpairedModeSettings: get("visualImpairedModeSettings"),
    user: get("authentication/user"),
    name: get("authentication/name"),
    ...get("visualImpairedModeSettings", [
      "bodyClasses",
      "enabled",
      "fontFamily",
      "fontSize",
      "colorTheme",
    ]),
  },
};
</script>

<style lang="scss">
.vi-top {
  .vi__footer {
    border: none;
  }
  background: #fff;
  padding-top: 20px;
  @media all and (max-width: 1200px) {
    padding: 20px 10px;
  }
  &.hidden {
    display: none;
  }
  &__in {
    max-width: 1160px;
    margin: 0 auto;
  }
  &__title {
    font-weight: 400;
    margin: 0 0 18px;
    font-size: 22px;
    line-height: 22px;
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
  &__item {
    text-align: left;
    &:first-child {
      width: 230px;
    }
    &:nth-child(2) {
      width: 220px;
      @media all and (max-width: 480px) {
        width: 100%;
        margin: 30px 0 0;
      }
    }
    &:nth-child(3) {
      @media all and (max-width: 768px) {
        width: 100%;
        margin: 30px 0 0;
      }
    }
    &-b {
      border-bottom: 2px solid;
      display: flex;
      align-items: center;
      padding-bottom: 40px;
      @media all and (max-width: 768px) {
        flex-wrap: wrap;
      }
      .vi-switch {
        margin-left: auto;
        background: none;
        border: none;
        cursor: pointer;
      }
    }
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
    background: #ffffff;
    &-wrapper {
      display: flex;
    }
    &.--active,
    &:hover {
      border-width: 3px;
      line-height: 44px;
      &:before {
        content: "";
        position: absolute;
        border-top: 6px solid #000000;
        border-left: 7px solid transparent;
        border-right: 7px solid transparent;
        left: 50%;
        margin: 0 0 0 -7px;
        bottom: -9px;
      }
      &:after {
        content: "";
        position: absolute;
        border-top: 5px solid #ffffff;
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
      margin: 0 0 0 20px;
      font-size: 22px;
      font-family: "Lato", sans-serif;
      &.--noto {
        width: 180px;
        font-size: 21px;
        font-family: "Noto Serif", serif;
      }
    }
    &.--sm {
      font-size: 16px;
    }
    &.--md {
      font-size: 20px;
    }
    &.--lrg {
      font-size: 26px;
    }
    &.--white {
      &.--ct-black {
        background: #ffffff;
        color: #000000;
      }
    }
    &.--black {
      background: #000000;
      color: #ffffff;
      border: none;
      &.--active,
      &:hover {
        border: 3px solid #ffffff;
        &:before {
          border-top-color: #ffffff;
        }
        &:after {
          border-top-color: #000000;
        }
      }
    }
    &.--ct-black {
      &.--active,
      &:hover {
        border: 3px solid #ffffff;
        &:before {
          border-top-color: #ffffff;
        }
        &:after {
          border-top-color: #000000;
        }
      }
    }
    &:first-child {
      margin: 0;
    }
  }
}

body {
  &.sm {
    * {
      font-size: 16px;
    }
    .blog__in .input__wrapper .input {
      input {
        font-size: 16px;
      }
    }
    .blog .blog__search__btn .text {
      font-size: 18px;
    }
    .title {
      font-size: 42px;
      @media all and (max-width: 1023px) {
        font-size: 28px;
      }
      @media all and (max-width: 768px) {
        font-size: 18px;
      }
      &.--md {
        font-size: 30px;
        @media all and (max-width: 1023px) {
          font-size: 20px;
        }
        @media all and (max-width: 768px) {
          font-size: 16px;
        }
      }
      &.--small {
        font-size: 20px;
        @media all and (max-width: 1023px) {
          font-size: 18px;
        }
        @media all and (max-width: 768px) {
          font-size: 16px;
        }
      }
    }
    .label {
      font-size: 16px;
    }
    .button {
      font-size: 16px;
    }
    .select {
      select {
        font-size: 16px;
      }
    }
    .violations_error {
      font-size: 16px;
    }
    .select-text {
      select {
        font-size: 28px;
      }
    }
    .vi-top {
      &__title {
        font-size: 18px;
      }
      &__link {
        &.--white,
        &.--black {
          font-size: 18px;
        }
        &.--btn {
          font-size: 18px;
        }
      }
    }
    .represent {
      &__item {
        &-text {
          font-size: 14px;
        }
      }
      &__title {
        font-size: 28px;
      }
    }
    .contacts {
      &__text {
        font-size: 14px;
      }
      &__link {
        font-size: 24px;
        &-title {
          font-size: 24px;
        }
      }
    }
    .main-header {
      &__menu {
        line-height: 22px;
        a {
          span {
            font-size: 14px;
          }
        }
      }
    }
    .lang-select {
      &__selected,
      &__item {
        span {
          font-size: 14px;
        }
      }
    }
    .breadcrumbs {
      &__link {
        font-size: 14px;
      }
    }
    .subscribe-link {
      font-size: 14px;
    }
    .comment-form {
      textarea {
        font-size: 16px;
      }
    }
    .about {
      &__title {
        font-size: 42px;
        @media all and (max-width: 1200px) {
          font-size: 32px;
        }
        @media all and (max-width: 768px) {
          font-size: 20px;
        }
      }
      &__text {
        font-size: 16px;
        @media all and (max-width: 768px) {
          font-size: 14px;
        }
      }
      &__top-item {
        &__text,
        &-title {
          font-size: 16px;
          @media all and (max-width: 1200px) {
            font-size: 14px;
          }
        }
      }
      &__part {
        &-text {
          font-size: 16px;
        }
      }
      &__link {
        &-text {
          font-size: 16px;
          @media all and (max-width: 768px) {
            font-size: 14px;
          }
        }
      }
    }
    .blog {
      &__content {
        &-top {
          &__text {
            h3 {
              font-size: 30px;
            }
          }
        }
      }
      &__inside {
        > p {
          font-size: 16px;
        }
        &-comments {
          > h4 {
            font-size: 20px;
          }
        }
        &-date {
          font-size: 14px;
        }
      }
      &__category {
        &-link {
          span {
            font-size: 16px;
          }
        }
        &-title {
          font-size: 22px;
        }
      }
      &__item {
        &-title,
        > h3 {
          font-size: 28px;
        }
        &-text,
        > p {
          font-size: 16px;
        }
        &-link {
          font-size: 14px;
        }
      }
      &__material {
        &-link {
          span {
            font-size: 14px;
          }
        }
      }
    }
    .user {
      &-page {
        &__header {
          .menu {
            &__item {
              span {
                font-size: 16px;
              }
            }
          }
        }
      }
      &-achievments {
        &__events {
          .title {
            span {
              font-size: 20px;
            }
          }
        }
      }
      &-profile {
        &__title,
        &-email,
        &-phone {
          span {
            font-size: 16px;
          }
        }
        &__edit {
          a span {
            font-size: 14px;
          }
        }
        &__name {
          font-size: 30px;
          span {
            font-size: 30px;
          }
        }
      }
      &-task {
        &__progress {
          .progress__num {
            span {
              font-size: 14px;
            }
          }
        }
        &__title {
          span {
            font-size: 20px;
          }
        }
        &__description {
          span {
            font-size: 16px;
          }
        }
      }
      &-tasks {
        &__item {
          &-date {
            font-size: 14px;
          }
        }
      }
      &-level {
        &__stats {
          .stat__cat {
            span {
              font-size: 14px;
            }
          }
        }
        &__progress {
          .info__points {
            span {
              font-size: 14px;
            }
          }
          .info__level-num {
            span {
              font-size: 44px;
            }
          }
        }
      }
      &-objects {
        &__filter {
          .filter {
            &__dropdown {
              .dropdown {
                &__selected,
                &__item {
                  span {
                    font-size: 14px;
                  }
                }
              }
            }
            &__text {
              font-size: 14px;
            }
          }
        }
      }
    }
    .add-object {
      &__button {
        font-size: 14px;
        span {
          font-size: 14px;
        }
      }
      &__error {
        font-size: 16px;
      }
      &__top {
        &-title {
          font-size: 42px;
        }
        &-step {
          font-size: 18px;
        }
      }
      &__label {
        &-text {
          font-size: 14px;
        }
      }
      &__step {
        &-info {
          .step {
            font-size: 12px;
          }
        }
      }
      &__info {
        &-text {
          font-size: 14px;
        }
      }
      &__title {
        font-size: 20px;
        &.--lrg {
          font-size: 30px;
        }
      }
    }
    .complaint {
      &__pre {
        &-text {
          font-size: 16px;
        }
      }
      &__item {
        .label {
          font-size: 16px;
          &.--vertical {
            font-size: 17px;
          }
          &-text {
            font-size: 12px;
          }
        }
      }
    }
    .add-link {
      font-size: 14px;
    }
    .main-footer {
      &__bottom {
        a {
          font-size: 14px;
        }
      }
    }
  }
  &.md {
    * {
      font-size: 20px;
    }
    .blog__in .input__wrapper .input {
      input {
        font-size: 20px;
      }
    }
    .blog .blog__search__btn .text {
      font-size: 21px;
    }
    .title {
      font-size: 46px;
      @media all and (max-width: 1023px) {
        font-size: 32px;
      }
      @media all and (max-width: 768px) {
        font-size: 20px;
      }
      &.--md {
        font-size: 32px;
        @media all and (max-width: 1023px) {
          font-size: 22px;
        }
        @media all and (max-width: 768px) {
          font-size: 18px;
        }
      }
      &.--small {
        font-size: 24px;
        @media all and (max-width: 1023px) {
          font-size: 20px;
        }
        @media all and (max-width: 768px) {
          font-size: 18px;
        }
      }
    }
    .label {
      font-size: 18px;
    }
    .button {
      font-size: 18px;
    }
    .select {
      select {
        font-size: 18px;
      }
    }
    .violations_error {
      font-size: 18px;
    }
    .select-text {
      select {
        font-size: 30px;
      }
    }
    .vi-top {
      &__title {
        font-size: 20px;
      }
      &__link {
        &.--white,
        &.--black {
          font-size: 20px;
        }
        &.--btn {
          font-size: 20px;
        }
      }
    }
    .represent {
      &__item {
        &-text {
          font-size: 16px;
        }
      }
      &__title {
        font-size: 30px;
      }
    }
    .contacts {
      &__text {
        font-size: 16px;
      }
      &__link {
        font-size: 26px;
        &-title {
          font-size: 26px;
        }
      }
    }
    .main-header {
      &__menu {
        a {
          span {
            font-size: 18px;
          }
        }
      }
    }
    .lang-select {
      &__selected,
      &__item {
        span {
          font-size: 16px;
        }
      }
    }
    .breadcrumbs {
      &__link {
        font-size: 16px;
      }
    }
    .subscribe-link {
      font-size: 16px;
    }
    .comment-form {
      textarea {
        font-size: 18px;
      }
    }
    .about {
      &__title {
        font-size: 44px;
        @media all and (max-width: 1200px) {
          font-size: 34px;
        }
        @media all and (max-width: 768px) {
          font-size: 22px;
        }
      }
      &__text {
        font-size: 18px;
        @media all and (max-width: 768px) {
          font-size: 16px;
        }
      }
      &__top-item {
        &__text,
        &-title {
          font-size: 18px;
          @media all and (max-width: 1200px) {
            font-size: 16px;
          }
        }
      }
      &__part {
        &-text {
          font-size: 18px;
        }
      }
      &__link {
        &-text {
          font-size: 18px;
          @media all and (max-width: 768px) {
            font-size: 16px;
          }
        }
      }
    }
    .blog {
      &__content {
        &-top {
          &__text {
            h3 {
              font-size: 32px;
            }
          }
        }
      }
      &__inside {
        > p {
          font-size: 18px;
        }
        &-comments {
          > h4 {
            font-size: 22px;
          }
        }
        &-date {
          font-size: 16px;
        }
      }
      &__category {
        &-link {
          span {
            font-size: 18px;
          }
        }
        &-title {
          font-size: 26px;
        }
      }
      &__item {
        &-title,
        > h3 {
          font-size: 30px;
        }
        &-text,
        > p {
          font-size: 18px;
        }
        &-link {
          font-size: 16px;
        }
      }
      &__material {
        &-link {
          span {
            font-size: 16px;
          }
        }
      }
    }
    .user {
      &-page {
        &__header {
          .menu {
            &__item {
              span {
                font-size: 18px;
              }
            }
          }
        }
      }
      &-achievments {
        &__events {
          .title {
            span {
              font-size: 22px;
            }
          }
        }
      }
      &-profile {
        &__title,
        &-email,
        &-phone {
          span {
            font-size: 18px;
          }
        }
        &__edit {
          a span {
            font-size: 16px;
          }
        }
        &__name {
          font-size: 32px;
          span {
            font-size: 32px;
          }
        }
      }
      &-task {
        &__progress {
          .progress__num {
            span {
              font-size: 16px;
            }
          }
        }
        &__title {
          span {
            font-size: 22px;
          }
        }
        &__description {
          span {
            font-size: 18px;
          }
        }
      }
      &-tasks {
        &__item {
          &-date {
            font-size: 16px;
          }
        }
      }
      &-level {
        &__stats {
          .stat__cat {
            span {
              font-size: 16px;
            }
          }
        }
        &__progress {
          .info__points {
            span {
              font-size: 16px;
            }
          }
          .info__level-num {
            span {
              font-size: 46px;
            }
          }
        }
      }
      &-objects {
        &__filter {
          .filter {
            &__dropdown {
              .dropdown {
                &__selected,
                &__item {
                  span {
                    font-size: 18px;
                  }
                }
              }
            }
            &__text {
              font-size: 18px;
            }
          }
        }
      }
    }
    .add-object {
      &__button {
        font-size: 16px;
        span {
          font-size: 16px;
        }
      }
      &__error {
        font-size: 18px;
      }
      &__top {
        &-title {
          font-size: 44px;
        }
        &-step {
          font-size: 20px;
        }
      }
      &__label {
        &-text {
          font-size: 16px;
        }
      }
      &__step {
        &-info {
          .step {
            font-size: 14px;
          }
        }
      }
      &__info {
        &-text {
          font-size: 16px;
        }
      }
      &__title {
        font-size: 22px;
        &.--lrg {
          font-size: 32px;
        }
      }
    }
    .complaint {
      &__pre {
        &-text {
          font-size: 18px;
        }
      }
      &__item {
        .label {
          font-size: 18px;
          &.--vertical {
            font-size: 19px;
          }
          &-text {
            font-size: 14px;
          }
        }
      }
    }
    .add-link {
      font-size: 16px;
    }
    .main-footer {
      &__bottom {
        a {
          font-size: 16px;
        }
      }
    }
  }

  &.lrg {
    * {
      font-size: 24px;
    }
    .blog__in .input__wrapper .input {
      input {
        font-size: 24px;
      }
    }
    .blog .blog__search__btn .text {
      font-size: 24px;
    }
    .title {
      font-size: 48px;
      @media all and (max-width: 1023px) {
        font-size: 34px;
      }
      @media all and (max-width: 768px) {
        font-size: 22px;
      }
      &.--md {
        font-size: 34px;
        @media all and (max-width: 1023px) {
          font-size: 24px;
        }
        @media all and (max-width: 768px) {
          font-size: 20px;
        }
      }
      &.--small {
        font-size: 26px;
        @media all and (max-width: 1023px) {
          font-size: 22px;
        }
        @media all and (max-width: 768px) {
          font-size: 20px;
        }
      }
    }
    .label {
      font-size: 20px;
    }
    .button {
      font-size: 20px;
    }
    .select {
      select {
        font-size: 20px;
      }
    }
    .violations_error {
      font-size: 20px;
    }
    .select-text {
      select {
        font-size: 32px;
      }
    }
    .vi-top {
      &__title {
        font-size: 22px;
      }
      &__link {
        &.--white,
        &.--black {
          font-size: 22px;
        }
        &.--btn {
          font-size: 22px;
        }
      }
    }
    .represent {
      &__item {
        &-text {
          font-size: 18px;
        }
      }
      &__title {
        font-size: 32px;
      }
    }
    .contacts {
      &__text {
        font-size: 18px;
      }
      &__link {
        font-size: 28px;
        &-title {
          font-size: 28px;
        }
      }
    }
    .main-header {
      &__menu {
        a {
          span {
            font-size: 20px;
          }
        }
      }
    }
    .lang-select {
      &__selected,
      &__item {
        span {
          font-size: 18px;
        }
      }
    }
    .breadcrumbs {
      &__link {
        font-size: 18px;
      }
    }
    .subscribe-link {
      font-size: 18px;
    }
    .comment-form {
      textarea {
        font-size: 20px;
      }
    }
    .about {
      &__title {
        font-size: 44px;
        @media all and (max-width: 1200px) {
          font-size: 34px;
        }
        @media all and (max-width: 768px) {
          font-size: 22px;
        }
      }
      &__text {
        font-size: 18px;
        @media all and (max-width: 768px) {
          font-size: 14px;
        }
      }
      &__top-item {
        &__text,
        &-title {
          font-size: 18px;
          @media all and (max-width: 1200px) {
            font-size: 16px;
          }
        }
      }
      &__part {
        &-text {
          font-size: 18px;
        }
      }
      &__link {
        &-text {
          font-size: 18px;
          @media all and (max-width: 768px) {
            font-size: 16px;
          }
        }
      }
    }
    .blog {
      &__content {
        &-top {
          &__text {
            h3 {
              font-size: 34px;
            }
          }
        }
      }
      &__inside {
        > p {
          font-size: 20px;
        }
        &-comments {
          > h4 {
            font-size: 24px;
          }
        }
        &-date {
          font-size: 18px;
        }
      }
      &__category {
        &-link {
          span {
            font-size: 20px;
          }
        }
        &-title {
          font-size: 30px;
        }
      }
      &__item {
        &-title,
        > h3 {
          font-size: 32px;
        }
        &-text,
        > p {
          font-size: 20px;
        }
        &-link {
          font-size: 18px;
        }
      }
      &__material {
        &-link {
          span {
            font-size: 18px;
          }
        }
      }
    }
    .user {
      &-page {
        &__header {
          .menu {
            &__item {
              span {
                font-size: 20px;
              }
            }
          }
        }
      }
      &-achievments {
        &__events {
          .title {
            span {
              font-size: 24px;
            }
          }
        }
      }
      &-profile {
        &__title,
        &-email,
        &-phone {
          span {
            font-size: 20px;
          }
        }
        &__edit {
          a span {
            font-size: 18px;
          }
        }
        &__name {
          font-size: 34px;
          span {
            font-size: 34px;
          }
        }
      }
      &-task {
        &__progress {
          .progress__num {
            span {
              font-size: 18px;
            }
          }
        }
        &__title {
          span {
            font-size: 24px;
          }
        }
        &__description {
          span {
            font-size: 20px;
          }
        }
      }
      &-tasks {
        &__item {
          &-date {
            font-size: 18px;
          }
        }
      }
      &-level {
        &__stats {
          .stat__cat {
            span {
              font-size: 18px;
            }
          }
        }
        &__progress {
          .info__points {
            span {
              font-size: 18px;
            }
          }
          .info__level-num {
            span {
              font-size: 48px;
            }
          }
        }
      }
      &-objects {
        &__filter {
          .filter {
            &__dropdown {
              .dropdown {
                &__selected,
                &__item {
                  span {
                    font-size: 20px;
                  }
                }
              }
            }
            &__text {
              font-size: 20px;
            }
          }
        }
      }
    }
    .add-object {
      &__button {
        font-size: 18px;
        span {
          font-size: 18px;
        }
      }
      &__error {
        font-size: 20px;
      }
      &__top {
        &-title {
          font-size: 46px;
        }
        &-step {
          font-size: 22px;
        }
      }
      &__label {
        &-text {
          font-size: 18px;
        }
      }
      &__step {
        &-info {
          .step {
            font-size: 16px;
          }
        }
      }
      &__info {
        &-text {
          font-size: 20px;
        }
      }
      &__title {
        font-size: 24px;
        &.--lrg {
          font-size: 34px;
        }
      }
    }
    .complaint {
      &__pre {
        &-text {
          font-size: 20px;
        }
      }
      &__item {
        .label {
          font-size: 20px;
          &.--vertical {
            font-size: 21px;
          }
          &-text {
            font-size: 16px;
          }
        }
      }
    }
    .add-link {
      font-size: 18px;
    }
    .main-footer {
      &__bottom {
        a {
          font-size: 18px;
        }
      }
    }
  }

  &.sm,
  &.md,
  &.lrg {
    .social {
      font-size: 0;
    }
    .main-header {
      &__logo {
        font-size: 0;
      }
    }
  }

  &.black {
    * {
      color: #ffffff;
    }
    background: #000000;
    border-color: #ffffff;
    .blog__in {
      .input__wrapper {
        .input {
          border: 1px solid #fff;
          border-radius: 0;
          background: #000;
          input {
            background: #000;
          }
        }
      }
    }
    .vi-top {
      background: #000000;
      border-bottom: 1px solid #ffffff;
      &__title {
        color: #ffffff;
      }
      &__link {
        background: #000000;
        border-color: #ffffff;
        color: #ffffff;
      }
    }
    .popup {
      &__in {
        background: #000000;
        border: 1px solid #ffffff;
      }
    }
    .title {
      color: #ffffff;
      span {
        color: #ffffff;
      }
    }
    .button {
      border: 1px solid #ffffff;
      background-color: #000000;
    }
    .burger {
      &-line {
        background: #ffffff;
        &:before,
        &:after {
          background: #ffffff;
        }
      }
      &-close {
        &__wrapper {
          border-bottom-color: #ffffff;
        }
      }
    }
    .select {
      select {
        background: #000000;
        border-color: #ffffff;
        color: #fff;
      }
      &:after {
        border-top-color: #fff;
      }
      &-text {
        select {
          option {
            background: #000000;
            color: #ffffff;
          }
        }
        &:after {
          border-top-color: #ffffff;
        }
      }
    }
    .input {
      border-color: #ffffff;
      background: #000000;
      input {
        background: #000000;
        color: #ffffff;
      }
      button {
        svg {
          path {
            fill: #fff;
          }
        }
      }
    }
    .comment-form {
      textarea {
        border-color: #ffffff;
        background: #000000;
        color: #ffffff;
      }
    }
    .textarea {
      border-color: #ffffff;
      background: #000000;
      color: #ffffff;
    }
    .represent {
      &__title {
        color: #ffffff;
      }
      &__text {
        color: #ffffff;
      }
    }
    .checkbox {
      input[type="checkbox"] {
        display: none;
        & + label {
          background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDE2IDE2IiBmaWxsPSJub25lIj4NCgk8cmVjdCB4PSIwLjUiIHk9IjAuNSIgd2lkdGg9IjE1IiBoZWlnaHQ9IjE1IiBmaWxsPSJibGFjayIgc3Ryb2tlPSJ3aGl0ZSIvPg0KPC9zdmc+")
            left top 4px no-repeat;
          background-size: 16px;
        }

        &:checked + label {
          background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDE2IDE2IiBmaWxsPSJub25lIj4NCgk8cmVjdCB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIGZpbGw9ImJsYWNrIiBzdHJva2U9IndoaXRlIi8+DQoJPHBhdGggZD0iTTExLjUxNDYgNEw2LjcwMjkzIDguOTUyODlMNC40ODUzNiA2LjY1OTQ5TDMgOC4xODg0M0w2LjcwMjkzIDEyTDEzIDUuNTI4OTRMMTEuNTE0NiA0WiIgZmlsbD0id2hpdGUiLz4NCjwvc3ZnPg==")
            left top 4px no-repeat;
          @media all and (max-width: 768px) {
            background-position: left top 2px;
          }
        }
      }
    }
    .main-header {
      border-bottom: 1px solid #000;
      &__language {
        @media all and (max-width: 768px) {
          border-top: 1px solid #ffffff;
        }
      }
      &__content {
        border-bottom: 1px solid #ffffff;
        &-in {
          @media all and (max-width: 768px) {
            background: #000000;
            border-left: 1px solid #ffffff;
          }
        }
      }
      &__logo {
        img {
          height: 59px;
          display: none;
          &.white {
            display: block;
          }
        }
      }
      &__menu {
        a {
          span {
            color: #ffffff;
          }
          &.nuxt-link-active:before {
            background: #ffffff;
          }
        }
      }
      &__visual {
        svg {
          path {
            fill: #ffffff;
          }
        }
      }
    }
    .violations_error {
      color: #ffffff;
    }
    .required {
      label {
        &:after {
          color: #ffffff;
        }
      }
      &.error {
        &.photo-input {
          border-color: #ffffff;
        }
        .input {
          border-color: #ffffff;
        }
      }
    }
    .complaint {
      &__pre-text {
        &.--required {
          span {
            color: #ffffff;
          }
        }
      }
      &__wrapper,
      &__item {
        background: #000000;
        border-top: 1px solid #ffffff;
      }
      &__item {
        background: #000000;
        border: 1px solid #ffffff;
        .label {
          &.--vertical {
            color: #ffffff;
          }
        }
      }
    }
    .add-link {
      border-bottom-color: #ffffff;
    }
    .add-object {
      &__form {
        border: 1px solid #fff;
      }
      &__step {
        background: #ffffff;
        .step {
          &-progress {
            background: #000000;
          }
          &:before {
            background: #000000;
          }
        }
        &-info {
          .step {
            color: #ffffff;
          }
        }
      }
      &__error {
        border: 1px solid #ffffff;
        background-color: #000000;
        &:before {
          border-right-color: #ffffff;
        }
      }
      &__line {
        &.error {
          .input,
          select {
            border-color: #ffffff;
          }
        }
      }
      &__button {
        border: 1px solid #ffffff;
        background-color: #000000;
      }
      &__textarea {
        background: #000000;
        color: #ffffff;
        border-color: #ffffff;
        &::-webkit-input-placeholder {
          color: #ffffff;
        }
        &:-ms-input-placeholder {
          color: #ffffff;
        }
        &::placeholder {
          color: #ffffff;
        }
      }
      &__link {
        &:hover,
        &.active {
          background-color: #ffffff;
          color: #000000;
        }
      }
      &__info {
        &-text {
          background: #000000;
          border: 1px solid #ffffff;
          &:before {
            border-bottom-color: #ffffff;
          }
        }
        &-icon {
          svg {
            display: none;
            &.white {
              display: block;
            }
          }
        }
      }
      &__form {
        background: #000000;
      }
    }
    .lang-select {
      &__list {
        border-color: #ffffff;
        background: #000000;
      }
      &__item {
        span {
          color: #ffffff;
        }
        &:hover {
          background: #000000;
        }
        &.selected {
          background: #000000;
          span {
            color: #ffffff;
          }
        }
      }
      &__selected {
        span {
          color: #ffffff;
        }
        svg {
          path {
            fill: #ffffff;
          }
        }
      }
    }
    .label {
      color: #ffffff;
    }
    .contacts {
      &__wrapper {
        background: #000000;
        border-bottom: 1px solid #ffffff;
      }
      &__link {
        color: #ffffff;
      }
      &__text {
        color: #ffffff;
      }
      &__line {
        .error-msg {
          color: #ffffff;
        }
        &.required {
          label:after {
            color: #ffffff;
          }
          &.error {
            .textarea {
              border-color: #ffffff;
            }
          }
        }
        button {
          background: #000;
        }
      }
    }
    .about {
      &__item {
        p {
          color: #ffffff;
        }
      }
      &__top {
        &-item {
          background: #000000;
          border: 1px solid #ffffff;
          * {
            color: #ffffff;
          }
          img {
            display: none;
          }
          &__title {
            margin-top: 0;
          }
        }
      }
      &__part {
        background: #000000;
        &-text {
          color: #ffffff;
        }
      }
      &__text {
        &-b {
          background: #000000;
        }
      }
      &__link {
        &-text {
          color: #ffffff;
          a {
            color: #ffffff;
          }
        }
      }
      &__title,
      &__text {
        color: #ffffff;
      }
    }
    .blog {
      &__item {
        background: #000;
        border-color: #fff;
        &-title,
        h3,
        &-text,
        p,
        &-link,
        &-img,
        img {
          color: #ffffff;
        }
      }
      &__list {
        &-item {
          border-color: #ffffff;
        }
      }
      &__content {
        &-top {
          &__text h3 {
            color: #ffffff;
          }
        }
      }
      &__inside {
        &-content p,
        &-date {
          color: #ffffff;
        }
        &-comments {
          h4 {
            color: #ffffff;
          }
        }
      }
      &__material {
        &-link {
          span {
            color: #ffffff;
          }
        }
      }
      &__category {
        &-title {
          color: #ffffff;
        }
        &-link {
          &:hover {
            background: transparent;
            &:before {
              background: transparent;
            }
          }
          span {
            color: #ffffff;
          }
          &.isActive {
            span {
              border-color: #ffffff;
            }
          }
        }
      }
      &__side {
        &-main {
          background: #000;
          border: 1px solid #fff;
          border-radius: 0;
        }
      }
    }
    .user {
      &-profile,
      &-level,
      &-task {
        background: #000000;
        border: 1px solid #ffffff;
      }
      &-achievment {
        &__text {
          span {
            color: #ffffff;
          }
        }
        &__icon {
          img {
            display: none;
            &.black {
              display: none;
            }
            &.white {
              display: block;
            }
          }
          &.user-achievment__icon_add {
            background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjE1IC0xNSA2MCA2MCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAxNSAtMTUgNjAgNjA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOm5vbmU7c3Ryb2tlOiNGRkZGRkY7c3Ryb2tlLXdpZHRoOjI7fQ0KCS5zdDF7ZmlsbDojRkZGRkZGO30NCjwvc3R5bGU+DQo8Y2lyY2xlIGNsYXNzPSJzdDAiIGN4PSI0NSIgY3k9IjE1IiByPSIyOSIvPg0KPHBhdGggY2xhc3M9InN0MSIgZD0iTTQ3LDNoLTR2MTBIMzN2NGgxMHYxMGg0VjE3aDEwdi00SDQ3VjN6Ii8+DQo8L3N2Zz4NCg==");
          }
        }
      }
      &-achievments {
        &__events {
          .list {
            &__text {
              a {
                color: #ffffff;
              }
              color: #ffffff;
            }
            &__icon {
              &.list__icon_level {
                background: #ffffff;
                span {
                  color: #000000;
                }
              }
              &.list__icon_achievment {
                img {
                  display: none;
                  &.white {
                    display: block;
                  }
                }
              }
            }
          }
        }
      }
      &-page {
        &__row {
          padding: 0 0 30px;
        }
        &__header {
          height: 50px;
          min-height: 50px;
          background-image: none !important;
          .menu {
            background: #ffffff;
            &__item {
              span {
                color: #000000;
              }
              &.isActive,
              &:hover {
                background: #000000;
                span {
                  color: #ffffff;
                }
              }
            }
          }
        }
        &__profile {
          top: -52px;
          @media all and (max-width: 768px) {
            top: 8px;
          }
        }
        &__button {
          background: #ffffff;
          color: #000000;
        }
      }
      &-level {
        &__progress {
          .progress {
            background: rgba(123, 149, 167, 0.75);
            &__bar {
              background: #ffffff;
            }
          }
        }
      }
      &-task {
        &__progress {
          .progress {
            background: rgba(123, 149, 167, 0.75);
            &__bar {
              background: #ffffff;
            }
          }
        }
      }
      &-tasks {
        &__item-status {
          &.--current {
            background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9Ii00IDYgMjAgMjAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgLTQgNiAyMCAyMDsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4NCgkuc3Qwe2ZpbGw6bm9uZTtzdHJva2U6I0ZGRkZGRjtzdHJva2Utd2lkdGg6MztzdHJva2UtbWl0ZXJsaW1pdDoxMDt9DQo8L3N0eWxlPg0KPHBhdGggY2xhc3M9InN0MCIgZD0iTTEwLjYsMTYuNkg1LjV2LTUuMSIvPg0KPHBhdGggY2xhc3M9InN0MCIgZD0iTTE0LjUsMTZjMCw0LjctMy44LDguNS04LjUsOC41Yy00LjcsMC04LjUtMy44LTguNS04LjVjMC00LjcsMy44LTguNSw4LjUtOC41QzEwLjcsNy41LDE0LjUsMTEuMywxNC41LDE2eiIvPg0KPC9zdmc+DQo=")
              left top no-repeat;
            background-size: 20px;
          }
          &.--new {
            background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCA0IDIwIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0IDIwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KCS5zdDB7ZmlsbDojRkZGRkZGO30NCjwvc3R5bGU+DQo8ZyBpZD0iWE1MSURfM18iPg0KCTxyZWN0IGlkPSJYTUxJRF80XyIgY2xhc3M9InN0MCIgd2lkdGg9IjQiIGhlaWdodD0iMTQiLz4NCgk8cmVjdCBpZD0iWE1MSURfNl8iIHk9IjE2LjMiIGNsYXNzPSJzdDAiIHdpZHRoPSI0IiBoZWlnaHQ9IjMuNyIvPg0KPC9nPg0KPC9zdmc+DQo=")
              left 8px top no-repeat;
            background-size: 4px 20px;
          }
          &.--done {
            background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9Ii01IDggMTggMTUiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgLTUgOCAxOCAxNTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4NCgkuc3Qwe2NsaXAtcGF0aDp1cmwoI1NWR0lEXzJfKTt9DQoJLnN0MXtmaWxsOiNGRkZGRkY7fQ0KPC9zdHlsZT4NCjxnPg0KCTxkZWZzPg0KCQk8cmVjdCBpZD0iU1ZHSURfMV8iIHg9Ii01IiB5PSI4IiB3aWR0aD0iMTgiIGhlaWdodD0iMTUiLz4NCgk8L2RlZnM+DQoJPGNsaXBQYXRoIGlkPSJTVkdJRF8yXyI+DQoJCTx1c2UgeGxpbms6aHJlZj0iI1NWR0lEXzFfIiAgc3R5bGU9Im92ZXJmbG93OnZpc2libGU7Ii8+DQoJPC9jbGlwUGF0aD4NCgk8ZyBjbGFzcz0ic3QwIj4NCgkJPHBhdGggY2xhc3M9InN0MSIgZD0iTTEwLjMsOGwtOC43LDkuM2wtNC00LjNMLTUsMTUuOUwxLjcsMjNMMTMsMTAuOUwxMC4zLDh6Ii8+DQoJPC9nPg0KPC9nPg0KPC9zdmc+DQo=")
              left top no-repeat;
            background-size: 18px 14px;
          }
        }
      }
      &-objects {
        &__filter {
          .filter {
            &__text {
              color: #ffffff;
            }
            &__dropdown {
              .dropdown {
                &__item {
                  &:hover {
                    background: #ffffff;
                    span {
                      color: #000000;
                    }
                  }
                }
                &__list {
                  background: #000000;
                  border: 1px solid #ffffff;
                }
                &__selected {
                  span {
                    border-color: #ffffff;
                  }
                }
              }
            }
          }
        }
      }
      &-object {
        &__download {
          color: #ffffff;
        }
      }
      &-comments {
        &__info {
          color: #ffffff;
          strong {
            color: #ffffff;
          }
        }
      }
      &-profile {
        &__favorites {
          img {
            display: none;
            &.white {
              display: block;
            }
          }
        }
        &__content {
          &.--verified {
            &:before {
              background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MCIgaGVpZ2h0PSI3MCIgdmlld0JveD0iMCAwIDUwIDcwIiBmaWxsPSJub25lIj4NCgk8cGF0aCBkPSJNMCAwSDUwVjcwTDI1IDYwTDAgNzBWMFoiIGZpbGw9ImJsYWNrIiBzdHJva2U9IndoaXRlIi8+DQoJPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMyAzMC42NzMzTDI1LjU3MTIgNDNMMzggMzAuNjM4TDM0LjU4MTIgMjdMMjUuNTcxMiAzNS45MzZMMTYuNDkgMjcuMDM1M0wxMyAzMC42NzMzWiIgZmlsbD0id2hpdGUiLz4NCjwvc3ZnPg==");
            }
          }
        }
      }
    }
    .custom-dropdown select {
      color: #ffffff;
      background: #000000;
    }
    .breadcrumbs {
      &__link {
        color: #ffffff;
      }
    }
    .subscribe-link {
      color: #ffffff;
    }
    .pagination {
      &__prev,
      &__next {
        background: #000000;
        border: 1px solid #ffffff;
      }
      &__btn {
        background: #000000;
        border: 1px solid #ffffff;
        &_active {
          border: 3px solid #ffffff;
        }
        span {
          color: #ffffff;
        }
      }
    }
    .main-footer {
      background: #000;
      border-top: 2px solid #fff;
    }
    .blog {
      &__material-item {
        background: #000;
        &__body {
          border: 1px solid #fff;
          border-top: none;
        }
      }
    }
    .blog__content__inside {
      background: #000;
      border-color: #fff;
    }
    .contacts {
      background: #000;
    }
    .dropdown__block {
      &__selected {
        border-color: #fff;
        background: #000;
        span {
          color: #fff;
        }
        &.opened {
          border-color: #fff;
          & + .dropdown__block__list {
            border-color: #fff;
          }
        }
      }
      &__list {
        .dropdown__block__item {
          background: #000;
          span {
            color: #fff;
          }
          &:hover {
            background: #fff;
            span {
              color: #000;
            }
          }
        }
      }
    }
    .container-represent {
      background: #000;
    }
    .represent__item {
      background: #000;
    }
  }
  &.white {
    background: #ffffff;
    color: #000000;
    .blog__in {
      .input__wrapper {
        .input {
          border: 1px solid #000000;
          border-radius: 0;
        }
      }
    }
    .title {
      color: #000000;
      span {
        color: #000000;
      }
    }
    .button {
      border: 1px solid #000000;
      background-color: #ffffff;
      color: #000000;
      &.--complaint {
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDE2IDE2IiBmaWxsPSJub25lIj4NCgk8cGF0aCBkPSJNMTMuMzczNiA0LjMwNzdDMTMuODgzNCA0LjMwNzcgMTQuMjk2NyAzLjg5NDQzIDE0LjI5NjcgMy4zODQ2MkMxNC4yOTY3IDIuODc0ODIgMTMuODgzNCAyLjQ2MTU1IDEzLjM3MzYgMi40NjE1NUMxMi44NjM4IDIuNDYxNTUgMTIuNDUwNSAyLjg3NDgyIDEyLjQ1MDUgMy4zODQ2MkMxMi40NTA1IDMuODk0NDMgMTIuODYzOCA0LjMwNzcgMTMuMzczNiA0LjMwNzdaIiBmaWxsPSJibGFjayIvPg0KCTxwYXRoIGQ9Ik0xMC45MTIgMi40NjE1MUMxMS40MjE4IDIuNDYxNTEgMTEuODM1MSAyLjA0ODIzIDExLjgzNTEgMS41Mzg0M0MxMS44MzUxIDEuMDI4NjMgMTEuNDIxOCAwLjYxNTM1NiAxMC45MTIgMC42MTUzNTZDMTAuNDAyMiAwLjYxNTM1NiA5Ljk4ODk1IDEuMDI4NjMgOS45ODg5NSAxLjUzODQzQzkuOTg4OTUgMi4wNDgyMyAxMC40MDIyIDIuNDYxNTEgMTAuOTEyIDIuNDYxNTFaIiBmaWxsPSJibGFjayIvPg0KCTxwYXRoIGQ9Ik04LjQ1MDQ4IDEuODQ2MTVDOC45NjAyOCAxLjg0NjE1IDkuMzczNTYgMS40MzI4OCA5LjM3MzU2IDAuOTIzMDc3QzkuMzczNTYgMC40MTMyNzYgOC45NjAyOCAwIDguNDUwNDggMEM3Ljk0MDY4IDAgNy41Mjc0IDAuNDEzMjc2IDcuNTI3NCAwLjkyMzA3N0M3LjUyNzQgMS40MzI4OCA3Ljk0MDY4IDEuODQ2MTUgOC40NTA0OCAxLjg0NjE1WiIgZmlsbD0iYmxhY2siLz4NCgk8cGF0aCBkPSJNNS45ODg5MyAzLjA3Njg3QzYuNDk4NzQgMy4wNzY4NyA2LjkxMjAxIDIuNjYzNTkgNi45MTIwMSAyLjE1Mzc5QzYuOTEyMDEgMS42NDM5OSA2LjQ5ODc0IDEuMjMwNzEgNS45ODg5MyAxLjIzMDcxQzUuNDc5MTMgMS4yMzA3MSA1LjA2NTg2IDEuNjQzOTkgNS4wNjU4NiAyLjE1Mzc5QzUuMDY1ODYgMi42NjM1OSA1LjQ3OTEzIDMuMDc2ODcgNS45ODg5MyAzLjA3Njg3WiIgZmlsbD0iYmxhY2siLz4NCgk8cGF0aCBkPSJNMTIuNDUwNSAzLjM4NDYzVjcuMzg0NjNDMTIuNDUwNSA3LjU1NDQ4IDEyLjMxMjYgNy42OTIzMyAxMi4xNDI4IDcuNjkyMzNDMTEuOTcyOSA3LjY5MjMzIDExLjgzNTEgNy41NTQ0OCAxMS44MzUxIDcuMzg0NjNWMS41Mzg0OEg5Ljk4ODkzVjYuNzY5MjVDOS45ODg5MyA2LjkzOTEgOS44NTEwOSA3LjA3Njk0IDkuNjgxMjQgNy4wNzY5NEM5LjUxMTM5IDcuMDc2OTQgOS4zNzM1NSA2LjkzOTEgOS4zNzM1NSA2Ljc2OTI1VjAuOTIzMDk2SDcuNTI3NFY2Ljc2OTI1QzcuNTI3NCA2LjkzOTEgNy4zODk1NSA3LjA3Njk0IDcuMjE5NyA3LjA3Njk0QzcuMDQ5ODYgNy4wNzY5NCA2LjkxMjAxIDYuOTM5MSA2LjkxMjAxIDYuNzY5MjVWMi4xNTM4Nkg1LjA2NTg2VjkuODQ2MTdMMy44MTk3IDguMTQwOTRDMy40NTA0NyA3LjU3MTcxIDIuNzI5ODYgNy4zODA5NCAyLjIwMTg2IDcuNzA3MUMxLjY3NTcgOC4wNDA2MyAxLjU0NTI0IDguNzY4MDIgMS45MDk1NSA5LjMzNTRDMS45MDk1NSA5LjMzNTQgMy45MTk0IDEyLjM3NzIgNC43NzYwMSAxMy42Nzk0QzUuNjMyNjMgMTQuOTgxNiA3LjAyMDMyIDE2IDkuNjE1MzkgMTZDMTMuOTEyIDE2IDE0LjI5NjYgMTIuNjgxOSAxNC4yOTY2IDExLjY5MjNDMTQuMjk2NiAxMC43MDI4IDE0LjI5NjYgMy4zODQ2MyAxNC4yOTY2IDMuMzg0NjNIMTIuNDUwNVoiIGZpbGw9ImJsYWNrIi8+DQo8L3N2Zz4=");
      }
    }
    .popup {
      &__in {
        border: 1px solid #000000;
      }
      &__close {
        background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgdmlld0JveD0iMCAwIDIwIDIwIiBmaWxsPSJub25lIj4NCjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTYuNjY2NyA0LjAwOGUtMDZMMjAgMy4zMzMzNEwxMy4zMzMzIDEwTDIwIDE2LjY2NjdMMTYuNjY2NyAyMEwxMCAxMy4zMzMzTDMuMzMzMzMgMjBMMCAxNi42NjY3TDYuNjY2NjcgMTBMMCAzLjMzMzMzTDMuMzMzMzMgMEwxMCA2LjY2NjY3TDE2LjY2NjcgNC4wMDhlLTA2WiIgZmlsbD0iYmxhY2siLz4NCjwvc3ZnPg==")
          no-repeat center;
      }
    }
    .burger {
      &-line {
        background: #000000;
        &:before,
        &:after {
          background: #000000;
        }
      }
      &-close {
        &__wrapper {
          border-bottom: 1px solid #000000;
        }
      }
    }
    .select {
      select {
        background: #ffffff;
        border-color: #000000;
      }
      &-text {
        &:after {
          border-top-color: #000000;
        }
      }
    }
    .input {
      border-color: #000000;
      background: #ffffff;
      input {
        background: #ffffff;
        color: #000000;
      }
      button {
        svg {
          path {
            fill: #000;
          }
        }
      }
    }
    .comment-form {
      textarea {
        border-color: #000000;
        background: #ffffff;
        color: #000000;
      }
    }
    .textarea {
      border-color: #000000;
      background: #ffffff;
      color: #000000;
    }
    .represent {
      &__title {
        color: #000000;
      }
      &__text {
        color: #000000;
      }
    }
    .contacts {
      background: #fff;
      &__wrapper {
        background: #ffffff;
        border-bottom: 1px solid #000000;
      }
      &__link {
        color: #000000;
        &-label {
          color: inherit;
        }
      }
      &__text {
        color: #000000;
      }
      &__line {
        .error-msg {
          color: #000000;
        }
        &.required {
          label:after {
            color: #000000;
          }
          &.error {
            .textarea {
              border-color: #000000;
            }
          }
        }
        button {
          background: #fff;
        }
      }
    }
    .about {
      &__item {
        p {
          color: #000000;
        }
      }
      &__top {
        &-item {
          background: #ffffff;
          border: 1px solid #000000;
          * {
            color: #000000;
          }
          img {
            display: none;
          }
          &__title {
            margin-top: 0;
          }
        }
      }
      &__part {
        background: #ffffff;
        &-text {
          color: #000000;
        }
      }
      &__text {
        &-b {
          background: #ffffff;
        }
      }
      &__link {
        &-text {
          color: #000000;
          a {
            color: #000000;
          }
        }
      }
      &__title,
      &__text {
        color: #000000;
      }
    }
    .checkbox {
      input[type="checkbox"] {
        display: none;
        & + label {
          background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDE2IDE2IiBmaWxsPSJub25lIj4NCgk8cmVjdCB4PSIwLjUiIHk9IjAuNSIgd2lkdGg9IjE1IiBoZWlnaHQ9IjE1IiBmaWxsPSJ3aGl0ZSIgc3Ryb2tlPSJibGFjayIvPg0KPC9zdmc+")
            left top 4px no-repeat;
          background-size: 16px;
        }

        &:checked + label {
          background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDE2IDE2IiBmaWxsPSJub25lIj4NCgk8cmVjdCB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIGZpbGw9IndoaXRlIiBzdHJva2U9ImJsYWNrIi8+DQoJPHBhdGggZD0iTTExLjUxNDYgNEw2LjcwMjkzIDguOTUyODlMNC40ODUzNiA2LjY1OTQ5TDMgOC4xODg0M0w2LjcwMjkzIDEyTDEzIDUuNTI4OTRMMTEuNTE0NiA0WiIgZmlsbD0iYmxhY2siLz4NCjwvc3ZnPg==")
            left top 4px no-repeat;
          @media all and (max-width: 768px) {
            background-position: left top 2px;
          }
        }
      }
    }
    .main-header {
      &__language {
        @media all and (max-width: 768px) {
          border-top: 1px solid #000000;
        }
      }
      &__logo {
        img {
          height: 59px;
          display: none;
          &.black {
            display: block;
          }
        }
      }
      &__menu {
        a {
          span {
            color: #000000;
          }
          &.nuxt-link-active:before {
            background: #000000;
          }
        }
      }
      &__content {
        border-bottom: 1px solid #000;
      }
    }
    .violations_error {
      color: #000000;
    }
    .required {
      label {
        &:after {
          color: #000000;
        }
      }
      &.error {
        &.photo-input {
          border-color: #000000;
        }
        .input {
          border-color: #000000;
        }
      }
    }
    .complaint {
      &__pre-text {
        &.--required {
          span {
            color: #000000;
          }
        }
      }
      &__wrapper {
        border-top: 1px solid #000000;
        background: #ffffff;
      }
      &__item {
        background: #ffffff;
        border: 1px solid #000000;
        .label {
          &.--vertical {
            color: #000000;
          }
        }
      }
    }
    .add-link {
      color: #000000;
      border-bottom-color: #000000;
    }
    .add-object {
      &__step {
        background: #000000;
        .step {
          &-progress {
            background: #ffffff;
          }
          &:before {
            background: #ffffff;
          }
        }
        &-info {
          .step {
            color: #000000;
          }
        }
      }
      &__error {
        border: 1px solid #000000;
        background-color: #ffffff;
        color: #000000;
        &:before {
          border-right-color: #000000;
        }
      }
      &__line {
        &.error {
          .input,
          select {
            border-color: #000000;
          }
        }
      }
      &__link {
        border-color: #000000;
        &:hover,
        &.active {
          background-color: #000000;
          color: #ffffff;
        }
      }
      &__form {
        border: 1px solid #000000;
      }
      &__textarea {
        border-color: #000000;
        &::-webkit-input-placeholder {
          color: #000000;
        }
        &:-ms-input-placeholder {
          color: #000000;
        }
        &::placeholder {
          color: #000000;
        }
      }
      &__button {
        border: 1px solid #000000;
        background-color: #ffffff;
        span {
          color: #000000;
        }
      }
      &__info {
        &-text {
          background: #ffffff;
          border: 1px solid #000000;
          &:before {
            border-bottom-color: #000000;
          }
        }
        &-icon {
          svg {
            display: none;
            &.white {
              stroke: #000000;
              display: block;
            }
          }
        }
      }
    }
    .blog {
      &__item {
        &-title,
        h3,
        &-text,
        p,
        &-link,
        &-img,
        img {
          color: #000000;
        }
        &__content {
          &-link {
            color: #000;
          }
        }
      }
      &__list {
        &-item {
          border-color: #000000;
        }
      }
      &__content {
        &-top {
          &__text h3 {
            color: #000000;
          }
        }
      }
      &__inside {
        &-content p,
        &-date {
          color: #000000;
        }
        &-comments {
          h4 {
            color: #000000;
          }
        }
      }
      &__material {
        &-link {
          span {
            color: #000000;
          }
        }
      }
      &__category {
        &-title {
          color: #000000;
        }
        &-link {
          &:hover {
            background: transparent;
            &:before {
              background: transparent;
            }
          }
          span {
            color: #000000;
          }
          &.isActive {
            span {
              border-color: #000000;
            }
          }
        }
      }
      &__side {
        &-main {
          background: #fff;
          border: 1px solid #000;
          border-radius: 0;
        }
      }
    }
    .user {
      &-profile,
      &-level,
      &-task {
        background: #ffffff;
        border: 1px solid #000000;
      }
      &-achievment {
        &__text {
          span {
            color: #000000;
          }
        }
        &__icon {
          img {
            display: none;
            &.black {
              display: block;
            }
          }
          &.user-achievment__icon_add {
            background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjE1IC0xNSA2MCA2MCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAxNSAtMTUgNjAgNjA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOm5vbmU7c3Ryb2tlOiMwMDAwMDA7c3Ryb2tlLXdpZHRoOjI7fQ0KPC9zdHlsZT4NCjxjaXJjbGUgY2xhc3M9InN0MCIgY3g9IjQ1IiBjeT0iMTUiIHI9IjI5Ii8+DQo8cGF0aCBkPSJNNDcsM2gtNHYxMEgzM3Y0aDEwdjEwaDRWMTdoMTB2LTRINDdWM3oiLz4NCjwvc3ZnPg0K");
          }
        }
      }
      &-achievments {
        &__events {
          .list {
            &__text {
              a {
                color: #000000;
              }
              color: #000000;
            }
            &__icon {
              &.list__icon_level {
                background: #000000;
                span {
                  color: #ffffff;
                }
              }
              &.list__icon_achievment {
                img {
                  display: none;
                  &.black {
                    display: block;
                  }
                }
              }
            }
          }
        }
      }
      &-page {
        &__row {
          padding: 0 0 30px;
        }
        &__header {
          height: 50px;
          min-height: 50px;
          background-image: none !important;
          .menu {
            background: #000000;
            &__item {
              span {
                color: #ffffff;
              }
              &.isActive,
              &:hover {
                background: #ffffff;
                span {
                  color: #000000;
                }
              }
              &.--logout {
                &:hover {
                  svg path {
                    stroke: #000;
                  }
                }
              }
            }
          }
        }
        &__profile {
          top: -52px;
          @media all and (max-width: 768px) {
            top: 8px;
          }
        }
        &__button {
          background: #000000;
          color: #ffffff;
        }
      }
      &-level {
        &__progress {
          .progress {
            background: rgba(123, 149, 167, 0.75);
            &__bar {
              background: #000000;
            }
          }
        }
      }
      &-task {
        &__progress {
          .progress {
            background: rgba(123, 149, 167, 0.75);
            &__bar {
              background: #000000;
            }
          }
        }
      }
      &-tasks {
        &__item-status {
          &.--current {
            background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9Ii00IDYgMjAgMjAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgLTQgNiAyMCAyMDsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4NCgkuc3Qwe2ZpbGw6bm9uZTtzdHJva2U6IzAwMDAwMDtzdHJva2Utd2lkdGg6MztzdHJva2UtbWl0ZXJsaW1pdDoxMDt9DQo8L3N0eWxlPg0KPHBhdGggY2xhc3M9InN0MCIgZD0iTTEwLjYsMTYuNkg1LjV2LTUuMSIvPg0KPHBhdGggY2xhc3M9InN0MCIgZD0iTTE0LjUsMTZjMCw0LjctMy44LDguNS04LjUsOC41Yy00LjcsMC04LjUtMy44LTguNS04LjVjMC00LjcsMy44LTguNSw4LjUtOC41QzEwLjcsNy41LDE0LjUsMTEuMywxNC41LDE2eiIvPg0KPC9zdmc+DQo=")
              left top no-repeat;
            background-size: 20px;
          }
          &.--new {
            background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCA0IDIwIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0IDIwOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KCS5zdDB7ZmlsbDojMDAwMDAwO30NCjwvc3R5bGU+DQo8ZyBpZD0iWE1MSURfM18iPg0KCTxyZWN0IGlkPSJYTUxJRF80XyIgY2xhc3M9InN0MCIgd2lkdGg9IjQiIGhlaWdodD0iMTQiLz4NCgk8cmVjdCBpZD0iWE1MSURfNl8iIHk9IjE2LjMiIGNsYXNzPSJzdDAiIHdpZHRoPSI0IiBoZWlnaHQ9IjMuNyIvPg0KPC9nPg0KPC9zdmc+DQo=")
              left 8px top no-repeat;
            background-size: 4px 20px;
          }
          &.--done {
            background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9Ii01IDggMTggMTUiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgLTUgOCAxOCAxNTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4NCgkuc3Qwe2NsaXAtcGF0aDp1cmwoI1NWR0lEXzJfKTt9DQoJLnN0MXtmaWxsOiMwMDAwMDA7fQ0KPC9zdHlsZT4NCjxnPg0KCTxkZWZzPg0KCQk8cmVjdCBpZD0iU1ZHSURfMV8iIHg9Ii01IiB5PSI4IiB3aWR0aD0iMTgiIGhlaWdodD0iMTUiLz4NCgk8L2RlZnM+DQoJPGNsaXBQYXRoIGlkPSJTVkdJRF8yXyI+DQoJCTx1c2UgeGxpbms6aHJlZj0iI1NWR0lEXzFfIiAgc3R5bGU9Im92ZXJmbG93OnZpc2libGU7Ii8+DQoJPC9jbGlwUGF0aD4NCgk8ZyBjbGFzcz0ic3QwIj4NCgkJPHBhdGggY2xhc3M9InN0MSIgZD0iTTEwLjMsOGwtOC43LDkuM2wtNC00LjNMLTUsMTUuOUwxLjcsMjNMMTMsMTAuOUwxMC4zLDh6Ii8+DQoJPC9nPg0KPC9nPg0KPC9zdmc+DQo=")
              left top no-repeat;
            background-size: 18px 14px;
          }
        }
      }
      &-object {
        &__download {
          color: #000000;
        }
      }
      &-comments {
        &__info {
          color: #000000;
          strong {
            color: #000000;
          }
        }
      }
      &-profile {
        &__favorites {
          img {
            display: none;
            &.black {
              display: block;
            }
          }
        }
        &__content {
          &.--verified {
            &:before {
              background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MCIgaGVpZ2h0PSI3MCIgdmlld0JveD0iMCAwIDUwIDcwIiBmaWxsPSJub25lIj4NCgk8cGF0aCBkPSJNMCAwSDUwVjcwTDI1IDYwTDAgNzBWMFoiIGZpbGw9IndoaXRlIiBzdHJva2U9ImJsYWNrIi8+DQoJPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMyAzMC42NzMzTDI1LjU3MTIgNDNMMzggMzAuNjM4TDM0LjU4MTIgMjdMMjUuNTcxMiAzNS45MzZMMTYuNDkgMjcuMDM1M0wxMyAzMC42NzMzWiIgZmlsbD0iYmxhY2siLz4NCjwvc3ZnPg==");
            }
          }
        }
      }
    }
    .pagination {
      &__prev,
      &__next {
        background: #ffffff;
        border: 1px solid #000000;
      }
      &__btn {
        background: #ffffff;
        border: 1px solid #000000;
        &_active {
          border: 3px solid #000000;
        }
        span {
          color: #000000;
        }
      }
    }
    .main-footer {
      &__wrapper {
        background: #fff;
        border-top: 2px solid #000;
      }
      div,
      a {
        color: #000;
        border-color: #000;
      }
    }
    .dropdown__block {
      &__selected {
        border-color: #000;
        &.opened {
          border-color: #000;
          & + .dropdown__block__list {
            border-color: #000;
          }
        }
      }
      &__list {
        .dropdown__block__item {
          &:hover {
            background: #000;
            span {
              color: #fff;
            }
          }
        }
      }
    }
    .represent__item {
      background: #fff;
    }
  }
  &.black,
  &.white {
    .add-object {
      &__step {
        .step-progress {
          opacity: 0.75;
        }
      }
      &__rating {
        &-text {
          position: relative;
          margin: 0;
          left: auto;
        }
      }
      &__line {
        .col.--small.--rating {
          display: none;
        }
      }
      &__button {
        padding: 0;
        text-align: center;
        svg {
          display: none;
        }
      }
    }
    .about {
      &__top {
        &-item {
          -webkit-box-shadow: none;
          -moz-box-shadow: none;
          box-shadow: none;
        }
        &-bg {
          display: none;
        }
        .about__text-b:first-child {
          margin: 0;
        }
      }
    }
    .contacts {
      &__link {
        padding: 0;
        background: none;
        &-title {
          &:before {
            display: none;
          }
        }
      }
      &__left,
      &__right {
        box-shadow: none;
        border: 1px solid;
        border-radius: 0;
      }
      &__line {
        button {
          border-radius: 0;
        }
      }
      &__social {
        a {
          border: 1px solid;
          background-color: #000;
        }
      }
    }
    .blog {
      &__item {
        border-radius: 0;
        border: 1px solid #000;
      }
      &__material-item {
        border-radius: 0;
      }
    }
    .blog__content__inside {
      border-radius: 0;
      box-shadow: none;
      &-content {
        padding-left: 0;
        padding-right: 0;
      }
    }
    .input {
      border: 1px solid;
      border-radius: 0;
    }
    .dropdown__block {
      &__item {
        span {
          font-size: inherit;
        }
      }
      &__selected {
        box-shadow: none;
        border-radius: 0;
        border-bottom: 1px solid;
        &.opened {
          border-radius: 0;
          & + .dropdown__block__list {
            border-radius: 0;
          }
        }
      }
    }
    .textarea {
      border: 1px solid;
      border-radius: 0;
    }
    .represent__item {
      border-radius: 0;
      border: 1px solid;
      &-row__label,
      &-link {
        color: inherit;
      }
    }
  }
  &.sm {
  }
  &.md {
  }
  &.lrg {
  }
  &.noto {
    * {
      font-family: "Noto Serif", serif;
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
      background: #ffffff;
    }
    &:after {
      content: "";
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
