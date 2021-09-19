<template>
  <div class="modal" v-if="show">
    <div class="modal__content">
      <div class="modal__head">
        <div class="modal__title">{{ $t("ready") }}!</div>
        <div class="close-button" @click="show = false">
          <img src="@/assets/icons/close_h.svg" alt="" />
        </div>
      </div>
      <p class="message">
        {{ $t("objectAdding.objectAddedmessage") }}
      </p>
      <div class="modal__buttons">
        <button class="btn btn_confirm" @click="show = false">
          <span>Ok</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PostAdditionMessage",
  data() {
    return { show: false };
  },
  methods: {
    close() {
      this.show = false;
    },
  },
  mounted() {
    this.show = !!this.$cookies.get("objectAdditionSubmitted");
    this.$cookies.remove("objectAdditionSubmitted");
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
