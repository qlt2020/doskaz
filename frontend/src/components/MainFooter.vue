<template>
  <div class="main-footer__wrapper">
    <div class="main-footer">
      <div class="container">
        <div class="main-footer__content">
          <nuxt-link
            :to="localePath({ name: 'index' })"
            class="main-footer__logo"
          >
            <img
              :src="
                viModeEnabled
                  ? visualImpairedModeSettings.colorTheme === 'black'
                    ? require(`@/assets/img/logo-vi-white.svg`)
                    : require(`@/assets/img/logo-vi-black.svg`)
                  : require(`@/assets/img/logo-new-white.svg`)
              "
              alt="logo-doskaz"
            />
          </nuxt-link>
          <div class="main-footer__privacy">
            {{ getPrivacy }}
          </div>
          <a href="https://qlt.kz/" class="main-footer__qlt" target="_blank">
            <div class="main-footer__qlt-text">
              {{ $t("siteDeveloped") }}
            </div>
            <img
              class="main-footer__qlt-logo"
              :src="
                viModeEnabled
                  ? require(`@/assets/qlt_logo.svg`)
                  : require(`@/assets/qlt_logo.svg`)
              "
              alt="logo-doskaz"
            />
          </a>
          <div class="main-footer__bottom">
            <nuxt-link :to="'/privacy_policy.pdf'" target="_blank">
              {{ $t("privacyPolicy") }}
            </nuxt-link>
            <nuxt-link :to="'/community_guidelines.pdf'" target="_blank">
              {{ $t("communityGuidelines") }}
            </nuxt-link>
            <nuxt-link :to="'/privacy_agreement.pdf'" target="_blank">
              {{ $t("privacyAgreement") }}
            </nuxt-link>
            <nuxt-link :to="'/terms-of-service.pdf'" target="_blank">
              {{ $t("termsOfUse") }}
            </nuxt-link>
          </div>
        </div>
      </div>
    </div>
    <div class="main-footer__mobile">
      <div class="container">
        <div class="main-footer__mobile__content">
          {{ getPrivacy }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getDate } from "date-fns";
import { call, get } from "vuex-pathify";
export default {
  data() {
    return {};
  },
  computed: {
    viModeEnabled: get("visualImpairedModeSettings/enabled"),
    visualImpairedModeSettings: get("visualImpairedModeSettings"),
    getPrivacy() {
      var date = new Date();
      return `Doskaz.kz (c) ${date.getFullYear()} Все права защищены`;
    },
  },
};
</script>

<style lang="scss">
@import "./../styles/mixins.scss";
.main-footer__wrapper {
  background: #252525;
}
.main-footer {
  padding: 15px 0;
  &__content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
  }
  &__privacy {
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    font-family: "SFProDisplay";
    line-height: 24.5px;
  }
  &__qlt {
    display: block;
    &-text {
      color: #fff;
      font-weight: 500;
      font-family: "SFProDisplay";
      font-size: 14px;
      line-height: 14.8px;
    }
  }
  &__mobile {
    display: none;
    background: #1d1d1d;
    &__content {
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      font-family: "SFProDisplay";
      line-height: 24.5px;
      text-align: center;
    }
  }
  &__bottom {
    width: 100%;
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
    a {
      color: #fff;
      border-bottom: 1px solid #fff;
      font-family: "SFProDisplay";
      font-size: 12px;
      font-weight: 300;
    }
  }
  &__logo {
    img {
      width: 130px;
    }
  }
}

@media screen and (max-width: 991px) {
  .main-footer {
    &__privacy {
      display: none;
    }
    &__mobile {
      display: block;
      padding: 22px 0;
    }
  }
}

@media screen and (max-width: 767px) {
  .main-footer__bottom {
    flex-direction: column;
    align-items: center;
    a {
      margin-top: 10px;
    }
  }
}
</style>
