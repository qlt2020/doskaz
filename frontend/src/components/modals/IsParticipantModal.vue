<template>
  <div class="modal">
    <div class="modal__content">
      <div class="modal__head">
        <div class="modal__title">
          {{ $t("login.areYouInGroup") }}
        </div>
      </div>
      <div class="modal__buttons">
        <button
          class="btn btn_green modal__buttons__item"
          @click="showCategories"
        >
          <span class="text">
            {{ $t("yes") }}
          </span>
        </button>
        <button
          class="btn btn_red modal__buttons__item"
          @click="updateUserCategory"
        >
          <span class="text">
            {{ $t("no") }}
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "IsParticipantModal",
  props: {
    visible: {
      type: Boolean,
    },
    object: {
      type: Object,
    },
  },
  data() {
    return {
      note: "",
    };
  },
  methods: {
    updateUserCategory(category) {
      this.$emit("close");
      this.$emit("updateUserCategory", "");
    },
    showCategories() {
      this.$emit("close");
      this.$emit("showCategories");
    },
  },
};
</script>

<style lang="scss" scoped>
.modal__content {
  max-width: 460px;
}
</style>
