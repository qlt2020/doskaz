<template>
  <div class="modal">
    <div class="modal__content">
      <div class="modal__head">
        <div class="modal__title">
          {{ edit ? $t("login.subscribeNewNotification") : $t("login.subscribeNotification") }}
        </div>
      </div>
      <div class="modal__body">
        <p class="text">
          {{ edit ? $t("login.subscribeNewNotificationText") : $t('login.subscribeNotificationWarning') }}
        </p>
      </div>
      <div class="modal__buttons">
        <button
          class="btn btn_green modal__buttons__item"
          @click="subscribe"
        >
          <span class="text">
            {{ $t("yes") }}
          </span>
        </button>
        <button class="btn btn_red modal__buttons__item" @click="close">
          <span class="text">
            {{ $t("no") }}
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { get } from "vuex-pathify";

export default {
  name: "SubscribeNotifModal",
  props: {
    visible: {
      type: Boolean
    },
    object: {
      type: Object
    },
    edit: {
      type: Boolean
    }
  },
  data() {
    return {};
  },
  computed: {
    profile: get('authentication/user')
  },
  methods: {
    close() {
      this.$emit("close");
    },
    async subscribe() {
      await this.$store.dispatch("notifications/subscribe", this.profile.category);
      this.$emit("close");
      this.$emit("showNextModal");
    }
  }
};
</script>

<style lang="scss" scoped>
.modal__body {
  text-align: center;
  .text {
    margin-top: 14px;
    font-family: "SFProDisplay", sans-serif;
    font-size: 16px;
    font-weight: 300;
    line-height: 19px;
    text-align: left;
  }
}
.modal__content {
  max-width: 460px;
}
</style>
