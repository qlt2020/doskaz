<template>
  <div class="main-header">
    <div class="main-header__content" :class="{ opened: mobileOpened }">
      <nuxt-link :to="localePath({name: 'index'})" class="main-header__logo">
        <img :src="require(`@/assets/logo_${$i18n.locale}.svg`)" alt />
        <img :src="require('@/assets/logo-black.svg')" alt class="black"/>
        <img :src="require('@/assets/logo-white.svg')" alt class="white"/>
      </nuxt-link>

      <div class="main-header__content-in-wrapper">
        <div class="main-header__content-in">
          <div class="burger-close__wrapper">
            <span class="burger-close" @click="mobileOpened = false"></span>
          </div>
          <div class="main-header__menu">
            <a href="#">
              <span>{{ $t('mainMenu.help') }}</span>
            </a>
            <nuxt-link :to="localePath({name: 'about'})">
              <span>{{ $t('mainMenu.about') }}</span>
            </nuxt-link>
            <nuxt-link :to="localePath({name: 'blog-category'})">
              <span>{{ $t('mainMenu.blog') }}</span>
            </nuxt-link>
            <nuxt-link :to="localePath({name: 'contacts'})">
              <span>{{ $t('mainMenu.contacts') }}</span>
            </nuxt-link>
          </div>

          <div class="main-header__visual" @click="viToggle">
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

          <div class="main-header__language">
            <LangSelect />
          </div>
        </div>
      </div>

      <div class="burger-wrapper" @click="mobileOpened = true">
        <span class="burger">
          <span class="burger-line"></span>
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import {call} from 'vuex-pathify'
import LangSelect from "~/components/LangSelect";
export default {
  data() {
    return {
      mobileOpened: false,
      currentClassList: ''
    }
  },
  components: {
    LangSelect
  },
  methods: {
    viToggle: call('visualImpairedModeSettings/toggle')
  }
};
</script>

<style lang="scss">
@import "./../styles/mixins.scss";

.burger {
  padding: 7px 0;
  display: block;
  &-line {
    display: block;
    width: 100%;
    background: #7B95A7;
    height: 3px;
    position: relative;
    &:after, &:before {
      content:'';
      width: 100%;
      position: absolute;
      height: 3px;
      left: 0;
      background: #7B95A7;
    }
    &:before {
      top: -7px;
    }
    &:after {
      bottom: -7px;
    }
  }
  &-wrapper {
    cursor: pointer;
    width: 25px;
    height: 25px;
    padding: 4px 0 4px 5px;
    right: 0;
    top: 0;
  }
  &-close {
    cursor: pointer;
    height: 25px;
    width: 25px;
    background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxNCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEzIDFMMSAxMyIgc3Ryb2tlPSIjN0I5NUE3IiBzdHJva2Utd2lkdGg9IjIiLz4KPHBhdGggZD0iTTEgMUwxMyAxMyIgc3Ryb2tlPSIjN0I5NUE3IiBzdHJva2Utd2lkdGg9IjIiLz4KPC9zdmc+Cg==') right center no-repeat;
    &__wrapper {
      padding: 0 20px 0 0;
      display: none;
      height: 58px;
      border-bottom: 1px solid #7B95A7;
    }
  }
}

.main-header {
  position: relative;
  width: 100%;
  &.--light {
    .main-header__content {
      justify-content: space-between;
      border: none;
    }
  }

  &__content {
    padding: 17px 20px 14px;
    max-width: 1800px;
    margin: 0 auto;
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
    position: relative;
    border-bottom: 1px solid #7B95A7;
    .burger-wrapper {
      display: none;
      position: relative;
    }
    @media all and (max-width: 768px) {
      justify-content: space-between;
      .burger-wrapper {
        display: block;
      }
    }

    &:after {
      content: "";
      display: none;
      position: absolute;
      bottom: 0;
      left: 80px;
      right: 80px;
      border-bottom: 1px solid #7b95a7;
    }
    &-in {
      width: 100%;
      display: flex;
      @media all and (max-width: 768px) {
        width: 270px;
        background: #ffffff;
        flex-direction: column;
      }
      &-wrapper {
          flex: 1 1 auto;
          @media all and (max-width: 768px) {
            position: fixed;
            display: none;
            z-index: 10;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(0,0,0,0.5);
          }
      }
    }

    &.opened {
      .main-header__content-in-wrapper {
        display: flex;
        justify-content: flex-end;
      }
      .burger-close__wrapper {
        display: flex;
        align-items: center;
        justify-content: flex-end;
      }
    }
  }

  &__logo {
    display: block;
    margin-right: 73px;
    font-size: 0;
    img {
      &.black, &.white {
        display: none;
      }
    }
    @media all and (max-width: 1023px) {
      margin-right: 30px;
      img {
        width: 100px;
        height: auto;
      }
    }
  }

  &__menu {
    flex: 1 0 auto;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    @media all and (max-width: 768px) {
      flex-direction: column;
      padding: 20px;
    }

    a {
      font-size: 16px;
      line-height: 20px;
      color: #333333;
      margin-left: 20px;
      position: relative;
      @media all and (max-width: 1023px) {
        font-size: 14px;
      }
      @media all and (max-width: 768px) {
        width: 100%;
        display: block;
        margin: 20px 0 0;
        &:first-child {
          margin: 0;
        }
      }

      &:first-child {
        margin-left: 0;
      }

      &:hover {
        opacity: 0.7;
      }
      &.nuxt-link-active {
        font-weight: 700;
        &:before {
          content: '';
          position: absolute;
          bottom: -33px;
          left: -8px;
          right: -8px;
          height: 4px;
          background: $blue;
          @media all and (max-width: 1023px) {
            bottom: -17px;
            left: -5px;
            right: -5px;
            height: 3px;
          }
          @media all and (max-width: 768px) {
            display: none;
          }
        }
      }
    }
  }

  &__visual {
    margin: 0 15px;
    cursor: pointer;
    transition: opacity 0.3s;
    padding: 2px 0 0;
    @media all and (max-width: 768px) {
      display: none;
    }

    &:hover {
      opacity: 0.7;
    }
  }

  &__language {
    @media all and (max-width: 768px) {
      border-top: 1px solid #7B95A7;
      padding: 14px 0 22px;
    }
  }
}
</style>