<template>
  <div class="modal">
    <div class="modal__content">
      <div class="modal__head">
        <div class="modal__title">
          {{ $t("objectAdding.presenceMessageHeader") }}!
        </div>
        <div class="close-button" @click="close">
          <img src="@/assets/icons/close_h.svg" alt="" />
        </div>
      </div>
      <p class="message">
        {{ $t("objectAdding.presenceMessage", { name }) }}
      </p>
      <div class="modal__buttons">
        <button class="btn btn_confirm" @click="close">
          <span>Ok</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PresenceMessage",
  props: {
    name: String,
  },
  methods: {
    close() {
      this.$emit("closed");
    },
  },
};
</script>
<style scoped lang="scss">
.modal-title {
  font-style: normal;
  font-weight: bold;
  font-size: 22px;
  margin-bottom: 10px;
  @media all and (max-width: 991px) {
    font-size: 16px;
  }
}
.message {
  font-family: "SFProDisplay";
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: inherit;
  width: 400px;
  @media all and (max-width: 991px) {
    width: 300px;
  }
}
.btn_confirm {
  width: 100%;
  background: #27ae60;
  span {
    font-family: "SFProDisplay";
    font-style: normal;
    font-weight: bold;
    font-size: 18px;
  }
}
</style>
