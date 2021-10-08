<template>
  <div class="buttons">
    <span class="title">Войти</span>
    <a class="button button_google" @click="openPopup('google')">
      <img
        :src="
          !viModeEnabled
            ? require('~/assets/img/social/login-google.svg')
            : require('~/assets/img/social/login-google-black.svg')
        "
      />
      <span>Войти через Google</span>
    </a>
    <button class="button" @click="openPopup('vkontakte')">
      <img
        :src="
          !viModeEnabled
            ? require('~/assets/img/social/login-vk.svg')
            : require('~/assets/img/social/login-vk-black.svg')
        "
      />
    </button>
    <button class="button" @click="openPopup('facebook')">
      <img
        :src="
          !viModeEnabled
            ? require('~/assets/img/social/login-facebook.svg')
            : require('~/assets/img/social/login-facebook-black.svg')
        "
      />
    </button>
    <button class="button" @click="openPopup('mailru')">
      <img
        :src="
          !viModeEnabled
            ? require('~/assets/img/social/login-mailru.svg')
            : require('~/assets/img/social/login-mailru-black.svg')
        "
      />
    </button>
  </div>
</template>

<script>
import { get, call } from "vuex-pathify";
import open from "oauth-open";

export default {
  name: "LoginSocialButtons",
  computed: {
    viModeEnabled: get("visualImpairedModeSettings/enabled"),
    providers() {
      return {
        facebook: {
          width: 580,
          height: 400,
        },
        google: {
          width: 452,
          height: 633,
        },
        vkontakte: {
          width: 668,
          height: 380,
        },
        mailru: {
          width: 446,
          height: 295,
        },
      };
    },
  },
  methods: {
    openPopup(provider) {
      open(
        `/api/oauth/${provider}/redirect`,
        this.providers[provider],
        (err, code) => {
          if (err) {
            return;
          }
          console.log(code);
          this.oauthAuthenticate({ code: code.code, provider });
        }
      );
    },
    oauthAuthenticate: call("authentication/oauthAuthenticate"),
  },
};
</script>
<style lang="scss" scoped>
.title {
  @media screen and (max-width: 768px) {
    grid-row: 1;
    margin-right: auto;
    display: block;
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: bold;
    font-size: 28px;
    line-height: 33px;
    color: #000000;
    margin-bottom: 20px;
  }
  display: none;
}
</style>
