<template>
  <div class="popup__wrapper">
    <div class="popup__scroll">
      <div class="popup__in --md">
        <div class="popup__head" style="text-align: start; width: 100%">
          <span class="popup__title">{{
            $t("objects.verificationPopupTitle")
          }}</span>
          <div class="close" @click="$emit('closed')">
            <img :src="require('~/assets/icons/cross.png')" />
          </div>
        </div>
        <p class="popup__text" style="text-align: start;">
          {{ $t("objects.verificationPopupQuestion", { objectName }) }}
        </p>
        <div class="popup__buttons" style="width: 100%">
          <button
            type="button"
            class="popup__button --yes"
            @click.prevent="$emit('confirmed')"
          >
            {{ $t("yes") }}
          </button>
          <button
            type="button"
            class="popup__button --no"
            @click.prevent="$emit('rejected')"
          >
            {{ $t("no") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PopupDialog",
  props: ["objectName"]
};
</script>

<style lang="scss" scoped>
.popup {
  &__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;

    @media screen and (max-width: 768px) {
      align-items: baseline;
    }

    .close {
      cursor: pointer;
    }
    .popup__title {
      margin: 0;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: bold;
      font-size: 22px;
      line-height: 26px;
      color: #3a3a3a;
      @media screen and (max-width: 768px) {
        font-size: 18px;
        line-height: 21px;
      }
    }
  }
  &__text {
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: 300;
    font-size: 16px;
    line-height: 21px;
    color: #000000;
  }
  &__in {
    @media screen and (max-width: 768px) {
      margin: unset;
      max-width: 100%;
    }
    &.--md {
      width: 461px;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
      border-radius: 10px;
      padding: 25px;
    }
  }
  &__buttons {
    @media screen and (max-width: 768px) {
      flex-direction: unset;
    }
    .popup__button {
      border-radius: 10px;
      width: 196px;
      padding: unset;
      height: 60px;
      text-align: center;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: bold;
      font-size: 18px;
      line-height: 19px;
      color: #ffffff;
      @media screen and (max-width: 768px) {
        width: 144px;
        margin: unset;
      }
      &.--yes {
        background: #27ae60;
      }
    }
  }
}
</style>
