<template>
  <div class="main-header">
    <div class="container">
      <div class="main-header__content">
        <nuxt-link
          :to="localePath({ name: 'index' })"
          class="main-header__logo"
        >
          <!-- <img :src="require(`@/assets/img/logo-new-white.png`)" alt="logo-doskaz" /> -->
          <img
            :src="require(`@/assets/img/logo-new-black.svg`)"
            alt="logo-doskaz"
          />
          <img :src="require('@/assets/logo-black.svg')" alt class="black" />
          <img :src="require('@/assets/logo-white.svg')" alt class="white" />
        </nuxt-link>

        <div class="main-header__content-in-wrapper">
          <div class="main-header__content-in">
            <div class="burger-close__wrapper">
              <span class="burger-close" @click="mobileOpened = false"></span>
            </div>
            <div class="main-header__menu">
              <nuxt-link exact :to="localePath({ name: 'index' })">
                <span>{{ $t("mainMenu.map") }}</span>
              </nuxt-link>
              <nuxt-link exact :to="localePath({ name: 'help' })">
                <span>{{ $t("mainMenu.help") }}</span>
              </nuxt-link>
              <nuxt-link :to="localePath({ name: 'about' })">
                <span>{{ $t("mainMenu.about") }}</span>
              </nuxt-link>
              <nuxt-link :to="localePath({ name: 'blog-category' })">
                <span>{{ $t("mainMenu.blog") }}</span>
              </nuxt-link>
              <nuxt-link :to="localePath({ name: 'contacts' })">
                <span>{{ $t("mainMenu.contacts") }}</span>
              </nuxt-link>
            </div>

            <div class="main-header__visual" @click="viToggle">
              <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M8.09756 12C8.09756 14.1333 9.8439 15.8691 12 15.8691C14.1463 15.8691 15.8927 14.1333 15.8927 12C15.8927 9.85697 14.1463 8.12121 12 8.12121C9.8439 8.12121 8.09756 9.85697 8.09756 12ZM17.7366 6.04606C19.4439 7.36485 20.8976 9.29455 21.9415 11.7091C22.0195 11.8933 22.0195 12.1067 21.9415 12.2812C19.8537 17.1103 16.1366 20 12 20H11.9902C7.86341 20 4.14634 17.1103 2.05854 12.2812C1.98049 12.1067 1.98049 11.8933 2.05854 11.7091C4.14634 6.88 7.86341 4 11.9902 4H12C14.0683 4 16.0293 4.71758 17.7366 6.04606ZM12.0012 14.4124C13.3378 14.4124 14.4304 13.3264 14.4304 11.9979C14.4304 10.6597 13.3378 9.57362 12.0012 9.57362C11.8841 9.57362 11.767 9.58332 11.6597 9.60272C11.6207 10.6694 10.7426 11.5227 9.65971 11.5227H9.61093C9.58166 11.6779 9.56215 11.833 9.56215 11.9979C9.56215 13.3264 10.6548 14.4124 12.0012 14.4124Z"
                  fill="#3A3A3A"
                />
              </svg>
            </div>

            <div class="main-header__language">
              <LangSelect />
            </div>
            <div class="main-header__auth">
              <nuxt-link
                v-if="!user"
                class="main-header__auth-link"
                :to="localePath({ name: 'login' })"
                >{{ $t("login.linkTitle") }}
                <img src="@/assets/icons/auth_logo.svg" alt="" />
              </nuxt-link>
              <div v-else class="logout --logout">
                <nuxt-link :to="localePath({ name: 'profile' })">
                  <img :src="avatar" alt="avatar" class="logout_avatar" />
                </nuxt-link>
                <img
                  @click="logout()"
                  src="~/assets/icons/logout.svg"
                  class="logout_exit"
                  alt="logout"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="burger-wrapper" @click="mobileOpened = true">
          <span class="burger">
            <span class="burger-line"></span>
          </span>
        </div> -->
        <mobile-menu></mobile-menu>
        <div class="burger-wrapper">
          <div v-if="!mobileMenu" @click="mainPageMobOpened">
            <img :src="require(`@/assets/icons/menu.svg`)" alt />
          </div>
          <div v-else @click="mainPageMobOpened">
            <img :src="require(`@/assets/icons/close_h.svg`)" alt />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { call, get } from "vuex-pathify";
import LangSelect from "~/components/LangSelect";
import LoginForm from "../components/LoginForm";
import { mapState } from "vuex";
import MobileMenu from "@/components/MobileMenu";

export default {
  data() {
    return {
      mobileOpened: false,
      currentClassList: "",
      opened: false,
    };
  },
  components: {
    LangSelect,
    LoginForm,
    MobileMenu,
  },
  computed: {
    avatar() {
      return this.user.avatar || require("~/assets/img/user/default.svg");
    },
    ...mapState({
      user: (state) => state.authentication.user,
    }),
    user: get("authentication/user"),
    mobileMenu: get("settings/mobileMenu"),
  },
  methods: {
    viToggle: call("visualImpairedModeSettings/toggle"),
    logout: call("authentication/logout"),
    mainPageMobOpened: call("settings/menuOpen"),
  },
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
    background: #000000;
    height: 3px;
    position: relative;
    &:after,
    &:before {
      content: "";
      position: absolute;
      height: 3px;
      left: 0;
      background: #000000;
    }
    &:before {
      width: 75%;
      top: -7px;
    }
    &:after {
      bottom: -7px;
      width: 42%;
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
    background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxNCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEzIDFMMSAxMyIgc3Ryb2tlPSIjN0I5NUE3IiBzdHJva2Utd2lkdGg9IjIiLz4KPHBhdGggZD0iTTEgMUwxMyAxMyIgc3Ryb2tlPSIjN0I5NUE3IiBzdHJva2Utd2lkdGg9IjIiLz4KPC9zdmc+Cg==")
      right center no-repeat;
    &__wrapper {
      padding: 0 20px 0 0;
      display: none;
      height: 58px;
      border-bottom: 1px solid #7b95a7;
    }
  }
}

.main-header {
  position: relative;
  width: 100%;
  min-height: 70px;
  font-size: 16px;
  font-weight: 500;
  font-family: "Montserrat";
  box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06), 0px 2px 6px rgba(0, 0, 0, 0.04),
    0px 0px 1px rgba(0, 0, 0, 0.04);
  .logout {
    display: flex;
    &_avatar {
      width: 46px;
      height: 46px;
      border-radius: 50%;
      margin-right: 13px;
      object-fit: cover;
    }
    &_exit {
      cursor: pointer;
    }
  }
  @media (max-width: 1023px) {
    position: fixed;
    z-index: 123;
    width: 100%;
    .container {
      background: white;
    }
  }
  &.--light {
    .main-header__content {
      justify-content: space-between;
      border: none;
    }
  }

  &__content {
    padding: 17px 0 14px;
    margin: 0 auto;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    position: relative;
    .burger-wrapper {
      display: none;
      position: relative;
    }
    @media all and (max-width: 1023px) {
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
      align-items: center;
      @media all and (max-width: 1023px) {
        width: 270px;
        background: #ffffff;
        flex-direction: column;
      }
      &-wrapper {
        flex: 1 1 auto;
        @media all and (max-width: 1023px) {
          position: fixed;
          display: none;
          z-index: 10;
          top: 0;
          right: 0;
          bottom: 0;
          left: 0;
          background: rgba(0, 0, 0, 0.5);
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
    margin-right: 90px;
    font-size: 0;
    img {
      width: 100px;
      height: auto;
      &.black,
      &.white {
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

  &__auth-link {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 500;
    img {
      width: 20px;
      height: 20px;
      margin-left: 13px;
    }
  }

  &__menu {
    flex: 1 0 auto;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    @media all and (max-width: 1023px) {
      flex-direction: column;
      padding: 20px;
    }

    a {
      line-height: 20px;
      color: #333333;
      margin-left: 35px;
      position: relative;
      @media all and (max-width: 1023px) {
        font-size: 14px;
      }
      @media all and (max-width: 1023px) {
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
        color: #2d9cdb;
        span {
          color: #2d9cdb;
        }
        &:before {
          content: "";
          position: absolute;
          bottom: -24px;
          left: -8px;
          right: -8px;
          height: 3px;
          background: #2d9cdb;
          @media all and (max-width: 1023px) {
            bottom: -17px;
            left: -5px;
            right: -5px;
            height: 3px;
          }
          @media all and (max-width: 1023px) {
            display: none;
          }
        }
      }
      &.nuxt-link-active {
        font-weight: 600;
        color: #2d9cdb;
        span {
          color: #2d9cdb;
        }
        &:before {
          content: "";
          position: absolute;
          bottom: -24px;
          left: -8px;
          right: -8px;
          height: 3px;
          background: #2d9cdb;
          @media all and (max-width: 1023px) {
            bottom: -17px;
            left: -5px;
            right: -5px;
            height: 3px;
          }
          @media all and (max-width: 1023px) {
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
    @media all and (max-width: 1023px) {
      display: none;
    }

    &:hover {
      opacity: 0.7;
    }
  }

  &__language {
    margin-right: 40px;
    @media all and (max-width: 1023px) {
      border-top: 1px solid #7b95a7;
      padding: 14px 0 22px;
    }
  }
}
</style>
