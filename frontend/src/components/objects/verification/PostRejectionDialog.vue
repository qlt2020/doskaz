<template>
  <div class="popup__wrapper">
    <div class="popup__scroll">
      <div class="popup__in --md">
        <div class="popup__head" style="width: 100%;">
          <span class="popup__title">{{
            $t("profile.objects.verificationFoundError")
          }}</span>
          <div class="close" @click="$emit('closed')">
            <img :src="require('~/assets/icons/cross.png')" />
          </div>
        </div>
        <p class="popup__text" style="text-align: start">
          {{$t("profile.objects.verificationErrorText")}}
        </p>
        <div class="popup__buttons" style="justify-content: center; width: 100%">
          <button
            type="button"
            class="popup__button --yes"
            style="text-align: center; padding: 14px 0 16px 14px"
          >
            <span style="margin: 0" @click="$emit('want-to-help')">
              {{$t("profile.objects.iWantToHelp")}}
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PostRejectionDialog"
};
</script>

<style lang="scss" scoped>
.popup {
  &__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;

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
    .popup__button {
      border-radius: 10px;
      width: 100%;
      padding: unset;
      height: 60px;
      text-align: center;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: bold;
      font-size: 18px;
      line-height: 19px;
      color: #ffffff;
      &.--yes {
        background: #27ae60;
      }
    }
  }
}
</style>
