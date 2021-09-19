<template>
  <div v-if="loaded" class="modal location">
    <div class="modal__content" v-click-outside="close">
      <div class="modal__head">
        <div class="modal-title">
          <i18n path="detectedLocation.modalText">
            <br place="break" />
            <span class="city" place="span">Нур-Султан</span>
          </i18n>
        </div>
        <div class="close-button" @click="close">
          <img src="@/assets/icons/close_h.svg" alt="" />
        </div>
      </div>
      <div class="buttons">
        <button class="btn btn_green" @click="close">
          {{ $t("detectedLocation.buttonYes") }}
        </button>
        <button class="btn btn_red" @click="openCityList">
          {{ $t("detectedLocation.buttonNo") }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { sync, get, call } from "vuex-pathify";
import ClickOutside from "vue-click-outside";

export default {
  name: "DetectLocation",
  computed: {
    city: get("detectLocation/cityName"),
    location: get("detectLocation/position"),
    loaded: get("detectLocation/loaded"),
    openedCityList: sync("cities/openedList"),
  },
  directives: {
    ClickOutside,
  },

  methods: {
    mainPageMobOpened: call("settings/menuOpen"),
    close() {
      this.$emit("close");
      this.setCitySelected();
    },
    openCityList() {
      this.mainPageMobOpened();
      this.$emit("close");
      setTimeout(() => {
        this.openedCityList = true;
      }, 100);
    },
    setCitySelected: call("settings/setCitySeleted"),
  },
};
</script>

<style lang="scss" scoped>
.location {
  background: none;
  @media all and (max-width: 991px) {
    top: 50px;
  }
  .modal__content {
    position: absolute;
    top: 10px;
    right: 10px;
  }
}
.modal-title {
  font-style: normal;
  font-weight: bold;
  font-size: 22px;
  margin-right: 30px;
  @media all and (max-width: 991px) {
    font-size: 16px;
  }
  .city {
    color: #2d9cdb;
  }
}
.buttons {
  display: flex;
  justify-content: space-around;
  gap: 15px;
  margin-top: 20px;
  button {
    width: 100%;
    font-style: normal;
    font-weight: bold;
    font-size: 18px;
    color: #ffffff;
    padding: 30px 0px;
    @media all and (max-width: 991px) {
      font-size: 14px;
    }
  }
}
</style>
