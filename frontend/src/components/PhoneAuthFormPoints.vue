<template>
  <div class="phone-form__point-b">
    <div class="phone-form__point">
      <span class="phone-form__point-num">20</span>
      <span class="phone-form__point-text">
        {{ $t('registrationPoints') }}
      </span>
    </div>
    <div class="phone-form__point">
      <span class="phone-form__point-num">50</span>
      <span class="phone-form__point-text">
        {{ $t('profileEditPoints') }}
      </span>
    </div>
    <button
      class="profile-link-button"
      @click="openModals"
    >
      {{ $t('login.ok') }}
    </button>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  methods: {
    openModals() {
      this.$store.dispatch("authentication/setModalForFirstTime");
    }
  }
};
</script>

<style lang="scss" scoped>
@import "./../styles/mixins.scss";

.phone-form {
  padding-top: 30px;

  &__point {
    margin: 30px 0;
    padding: 0 60px;
    display: flex;
    @media screen and (max-width: 768px){
      padding: 0 15px;
    }

    &-num {
      background: #2D9CDB;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      font-size: 22px;
      line-height: 50px;
      color: #ffffff;
      font-weight: 700;
    }

    &-text {
      font-size: 14px;
      line-height: 20px;
      color: #5b6067;
      width: calc(100% - 50px);
      font-weight: 400;
      padding: 0 0 0 10px;
      text-align: left;
      display: flex;
      align-items: center;
    }
  }

  .profile-link-button {
    margin: auto;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 80%;
    height: 50px;
    background: #2D9CDB;
    border-radius: 10px;
    transition: opacity 0.3s;
    cursor: pointer;
    border: none;
    font-family: Montserrat;
    font-style: normal;
    font-weight: normal;
    font-size: 16px;
    line-height: 17px;
    @media screen and (max-width: 768px){
      width: 80%;
    }

    &:hover {
      opacity: 0.7;
    }
  }
}
</style>
