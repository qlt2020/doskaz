<template>
  <div class="buttons">
    <span class="title">Войти</span>
    <a class="button button_google" @click="openPopup('google')">
      <img :src="require('~/assets/icons/google.png')" />
      <span>Войти через Google</span>
    </a>
    <button class="button" @click="openPopup('vkontakte')">
      <img :src="require('~/assets/icons/socialNetworks/vk.png')" />
    </button>
    <button class="button" @click="openPopup('facebook')">
      <img :src="require('~/assets/icons/socialNetworks/facebook.png')" />
    </button>
    <button class="button" @click="openPopup('mailru')">
      <img :src="require('~/assets/icons/socialNetworks/mailru.png')" />
    </button>
  </div>
</template>

<script>
import { call } from "vuex-pathify";
import open from "oauth-open";

export default {
  name: "LoginSocialButtons",
  computed: {
    providers() {
      return {
        facebook: {
          width: 580,
          height: 400
        },
        google: {
          width: 452,
          height: 633
        },
        vkontakte: {
          width: 668,
          height: 380
        },
        mailru: {
          width: 446,
          height: 295
        }
      };
    }
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
    oauthAuthenticate: call("authentication/oauthAuthenticate")
  }
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
