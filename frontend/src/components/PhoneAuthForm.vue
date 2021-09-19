<template>
  <div class="phone-form__form">
    <div class="input" :class="{ input_error: phoneError }">
      <input
        type="text"
        :placeholder="$t('login.phoneNumberInputPlaceholder')"
        v-mask="'+7##########'"
        v-model="phoneAuth.number"
        :disabled="confirmationResult"
      />
      <div class="input__error-text">
        <span>{{ phoneError }}</span>
      </div>
    </div>
    <div
      class="input"
      v-if="confirmationResult"
      :class="{ input_error: codeError }"
    >
      <input
        type="text"
        :placeholder="$t('login.smsCodeInputPlaceholder')"
        v-model="phoneAuth.code"
      />
      <div class="input__error-text">
        <span>{{ codeError }}</span>
      </div>
    </div>
    <div class="help">
      <span>{{ $t("login.smsCodeTimeoutMessage", { time: 15 }) }}</span>
    </div>
    <button
      class="button"
      id="sign-in-button"
      @click="sendSmsCode"
      v-if="!confirmationResult"
    >
      <span>{{ $t("login.sendSmsCodeButtonTitle") }}</span>
    </button>
    <button class="button" @click="signIn" v-if="confirmationResult">
      <span>{{ $t("login.confirmSmsCodeButtonTitle") }}</span>
    </button>
  </div>
</template>

<script>
import firebase from "firebase/app";
import "firebase/auth";
import get from "lodash/get";
import VueMask from "v-mask";
import Vue from "vue";
Vue.use(VueMask);

const errorMessages = {
  "auth/invalid-phone-number": "login.invalidPhoneNumberMessage",
  "auth/invalid-verification-code": "login.invalidSmsCodeMessage"
};

export default {
  name: "PhoneAuthForm",
  data() {
    return {
      phoneAuth: {
        number: "+7",
        code: null
      },
      errors: {
        number: null,
        code: null
      },
      confirmationResult: null
    };
  },
  mounted() {
    if (!firebase.apps.length) {
      firebase.initializeApp({
        apiKey: process.env.NUXT_ENV_FIREBASE_API_KEY
      });
      firebase.auth().languageCode = "ru";
      this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
        "sign-in-button",
        {
          size: "invisible"
        }
      );
    }
  },
  methods: {
    async sendSmsCode() {
      if (!this.phoneAuth.number) {
        return;
      }
      this.confirmationResult = null;
      try {
        this.confirmationResult = await firebase
          .auth()
          .signInWithPhoneNumber(this.phoneAuth.number, this.recaptchaVerifier);
        this.$emit("expanded");
      } catch (e) {
        this.$sentry.captureException(e);
        this.errors.number = e.code;
      }
    },
    async signIn() {
      if (!this.phoneAuth.code) {
        return;
      }
      try {
        const result = await this.confirmationResult.confirm(
          this.phoneAuth.code
        );
        const idToken = await result.user.getIdToken();
        await this.$store.dispatch("authentication/phoneAuthenticate", idToken);
      } catch (e) {
        this.$sentry.captureException(e);
        this.errors.code = e.code;
      }
    }
  },
  computed: {
    phoneError() {
      return get(errorMessages, [this.errors.number], this.errors.number);
    },
    codeError() {
      return this.$t(get(errorMessages, [this.errors.code], this.errors.code));
    }
  }
};
</script>

<style lang="scss" scoped>
@import "./../styles/mixins.scss";

.phone-form__form {
  padding-top: 30px;

  .input {
    display: block;
    width: 100%;
    height: 50px;
    border: 1px solid #7b95a7;
    box-sizing: border-box;
    margin-top: 20px;
    position: relative;
    border: none;
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: normal;
    font-size: 18px;
    line-height: 26px;
    color: #696969;

    &:first-child {
      margin-top: 0;
    }

    &.input_error {
      border-color: $red;

      .input {
        &__error-text {
          opacity: 1;
        }
      }
    }

    input {
      display: block;
      width: 100%;
      height: 100%;
      border: none;
      padding: 0 20px;

      &::placeholder {
        color: #696969;
      }
    }

    &__error-text {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      font-size: 14px;
      line-height: 20px;
      color: $red;
      opacity: 0;
      pointer-events: none;
    }
  }

  .help {
    padding: 30px 0;
    text-align: center;
    margin: auto;
    width: 70%;
    span {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      font-size: 14px;
      line-height: 17px;
      text-align: center;
      color: #a3a3a3;
    }
  }

  .button {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 50px;
    width: 204px;
    background: #2d9cdb;
    cursor: pointer;
    border: none;
    border-radius: 10px;
    margin: auto;
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: 600;
    font-size: 16px;
    line-height: 17px;

    &:hover {
      opacity: 0.7;
    }

    span {
      font-weight: bold;
      font-size: 16px;
      line-height: 20px;
      color: #ffffff;
    }
  }
}
</style>
