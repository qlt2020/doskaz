<template>
  <div class="modal">
    <div class="modal__content">
      <div class="modal__head">
        <div class="modal__title">
          {{ selectedCity ? $t("login.subscribeNotificationDoneSelected", {
            category: $t(`disabilityCategories.${profile.category}`),
            city: selectedCity
          }) :
            $t("login.subscribeNotificationDone", {
              category: $t(`disabilityCategories.${profile.category}`)
            })
          }}
        </div>
        <div class="close-button" @click="close">
          <img src="@/assets/icons/close_h.svg" alt="" />
        </div>
      </div>
      <div class="modal__body">
        <img src="@/assets/img/subscribe_notif_modal_image.svg" alt="" />
      </div>
      <div class="modal__buttons">
        <button class="btn btn_green modal__buttons__item" @click="close">
          <span class="text">
            {{ $t("login.ok") }}
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { get } from "vuex-pathify";

export default {
  name: "SubscribeNotifDoneModal",
  props: {
    visible: {
      type: Boolean
    },
    object: {
      type: Object
    }
  },
  data() {
    return {};
  },
  computed: {
    profile: get('authentication/user'),
    cities: get("cities/list"),
    selectedCity() {
      return this.profile.city_id ? this.cities.find(c => c.id === this.profile.city_id).name : null
    }
  },
  methods: {
    close() {
      this.$emit("close");
    },
    showNextModal() {
      this.$emit("close");
      this.$emit("showNextModal");
    }
  }
};
</script>

<style lang="scss" scoped>
.modal {
  &__content {
    max-width: 460px;
  }
  &__body {
    text-align: center;
  }
  &__buttons {
    margin-top: 0;
  }
}
</style>
