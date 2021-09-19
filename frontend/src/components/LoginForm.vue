<template>
  <div class="login-form isOpened">
    <div class="login-form__content">
      <div class="login-form__card" :class="{ expanded: expanded }">
        <div class="header">
          <div class="header__img">
            <img :src="require('~/assets/logo_doskaz.svg')" />
          </div>
          <div class="header__title">
            <span>{{ $t("login.popupTitle") }}</span>
          </div>
          <div class="close" @click="loginFormClose">
            <img :src="require('~/assets/icons/cross.png')" />
          </div>
        </div>

        <template v-if="!$route.query.popup">
          <login-social-buttons />
          <div class="phone-form">
            <div
              class="phone-form__switch phone-advice"
              @click="showPhoneAuthForm = true"
              :class="{ active: showPhoneAuthForm }"
            >
              <span>{{ $t("login.phoneLoginAdvice") }}</span>
            </div>
            <phone-auth-form
              v-if="showPhoneAuthForm"
              @expanded="expanded = true"
            />
          </div>
        </template>
        <div class="phone-form" v-else style="margin-top: 40px">
          <div class="phone-form__switch">
            <span>{{ $t("login.profileFillingAdvice") }}</span>
            <phone-auth-form-points />
          </div>
        </div>
        <div class="intro-files__container">
          {{ $t('youAccept.main') }}
          <nuxt-link :to="'/privacy_policy.pdf'" target="_blank">{{ $t("youAccept.privacyPolicy") }}</nuxt-link> и
          <nuxt-link :to="'/terms-of-service.pdf'" target="_blank">{{ $t("youAccept.termsOfUse") }}</nuxt-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PhoneAuthForm from "./PhoneAuthForm";
import PhoneAuthFormPoints from "./PhoneAuthFormPoints";
import LoginSocialButtons from "./LoginSocialButtons";

export default {
  components: { LoginSocialButtons, PhoneAuthForm, PhoneAuthFormPoints },
  data() {
    return {
      showPhoneAuthForm: false,
      expanded: false
    };
  },
  methods: {
    loginFormClose() {
      this.$router.push(this.localePath({ name: "index" }));
    }
  }
};
</script>

<style lang="scss">
@import "./../styles/mixins.scss";

.login-form {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1100;
  display: none;

  &.isOpened {
    display: block;
  }

  &__bg {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    z-index: 0;
    background: rgba(0, 0, 0, 0.7);
  }

  &__content {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  &__card {
    background: $white;
    position: relative;
    width: 490px;
    border-radius: 10px;

    @media screen and (max-width: 768px) {
      width: 100%;
      height: 100%;
      display: grid;
      grid-template-rows: 1fr auto 1fr;
      border-radius: unset;
      overflow: scroll;

      &.expanded {
        grid-template-rows: 1fr auto 2fr;
      }
    }

    .close {
      grid-column-start: 2;
      grid-row-start: 1;
      cursor: pointer;
      margin-left: auto;
    }

    .header {
      display: grid;
      padding: 25px 35px 0;
      grid-row-gap: 10px;
      grid-template-columns: auto auto;

      @media screen and (max-width: 768px) {
        padding: 50px 28px;
        grid-template-rows: auto 1fr;
      }

      &__title {
        font-family: Montserrat;
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        width: 300px;
        padding-bottom: 10px;
      }
    }

    .buttons {
      padding: 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;

      @media screen and (max-width: 768px) {
        display: grid;
        grid-row-gap: 10px;
        grid-column-gap: 20px;
        grid-template-rows: auto auto auto;
        grid-template-columns: 1fr 1fr 1fr;
        justify-items: center;
        padding: 0 28px;
      }

      .button {
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: $tr;
        transition: opacity 0.3s;
        cursor: pointer;
        border: 1px solid #2d9cdb;
        border-radius: 5px;

        @media screen and (max-width: 768px) {
          grid-row: 3;
          width: 100%;
        }

        &:hover {
          opacity: 0.7;
        }

        &.button_google {
          width: 260px;
          background: #2d9cdb;
          border: none;

          @media screen and (max-width: 768px) {
            grid-column-start: 1;
            grid-column-end: 4;
            grid-row: 2;
            width: 100%;
          }

          img {
            margin-right: 10px;
          }

          span {
            font-weight: normal;
            font-size: 16px;
            line-height: 20px;
            text-align: center;
            color: #ffffff;
          }
        }

        svg {
          display: block;
        }
      }
    }

    .phone-form {
      padding: 0 30px 30px;

      @media screen and (max-width: 768px) {
        padding: 25px;
      }

      &__switch {
        &.phone-advice {
          text-decoration: underline;
          text-underline-position: under;
        }
        font-weight: 500;
        font-size: 14px;
        line-height: 20px;
        text-align: center;
        cursor: pointer;
        font-family: Montserrat;
        font-style: normal;
      }
    }

    .intro-files__container {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      font-size: 14px;
      padding: 25px 15px;
      width: 100%;
      text-align: center;
      a {
        color: #2d9cdb;
      }
    }
  }
}
</style>
