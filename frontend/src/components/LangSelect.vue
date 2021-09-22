<template>
  <div class="lang-select" v-click-outside="close">
    <div class="lang-select__selected" @click="selectLang = !selectLang">
      <span>{{ currentLocale.name }}</span>
      <svg
        width="8"
        height="4"
        viewBox="0 0 8 4"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        class="lang-select__arrow-desktop"
      >
        <path
          d="M4 4L3.49691e-07 2.54292e-07L8 9.53674e-07L4 4Z"
          fill="#2D9CDB"
        />
      </svg>
      <svg
        width="9"
        height="6"
        viewBox="0 0 9 6"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        class="lang-select__arrow-mobile"
        :class="selectLang ? 'open' : ''"
      >
        <path d="M1 1L4.5 4.5L8 1" stroke="#3A3A3A" stroke-width="1.5" />
      </svg>
    </div>
    <div class="modal-bd" v-if="selectLang">
      <div class="lang-select__list" @click="close">
        <div class="lang-select-title">
          <span>Выберите язык</span>
          <!-- <div class="close-button" @click="close">
            <img src="@/assets/icons/close_h.svg" alt="" />
          </div> -->
        </div>
        <nuxt-link
          class="lang-select__item"
          v-for="locale in $i18n.locales"
          :key="locale.code"
          :class="{ selected: $i18n.locale === locale.code }"
          :to="switchLocalePath(locale.code)"
        >
          <i v-show="locale.code === $i18n.locale" class="fas fa-check"></i>
          <span>{{ locale.name }}</span>
        </nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>
import ClickOutside from "vue-click-outside";

export default {
  data() {
    return {
      selectLang: false,
    };
  },
  methods: {
    close() {
      this.selectLang = false;
    },
  },
  computed: {
    currentLocale() {
      return this.$i18n.locales.find(
        (locale) => this.$i18n.locale === locale.code
      );
    },
  },
  directives: {
    ClickOutside,
  },
};
</script>

<style lang="scss">
@import "./../styles/mixins.scss";

.lang-select {
  position: relative;
  cursor: pointer;

  @media all and (max-width: 768px) {
    padding: 0 14px 0 20px;
  }

  &__selected {
    display: flex;
    align-items: center;
    justify-content: flex-end;

    @media all and (max-width: 768px) {
      justify-content: space-between;
    }

    span {
      font-size: 14px;
      line-height: 20px;
      /* color: #2d9cdb; */
    }

    .lang-select__arrow-desktop {
      display: block;
      margin-left: 4px;
      @media all and (max-width: 768px) {
        display: none;
      }
    }

    .lang-select__arrow-mobile {
      display: none;
      @media all and (max-width: 768px) {
        display: block;
        margin-left: 10px;
        &.open {
          transform: rotate(90deg);
        }
      }
    }
  }

  &__list {
    display: flex;
    flex-direction: column;
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translate(-50%, 0);
    background: #ffffff;
    z-index: 3;
    border-radius: 14px;
    box-shadow: 0px 16px 24px 0px #0000000f;
    width: 345px;
    @media all and (max-width: 1023px) {
      position: fixed;
      top: unset;
      border: none;
      padding: 0 15px;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    }
    .lang-select-title {
      padding: 20px 15px;
      font-weight: 500;
      // @media all and (max-width: 768px) {
      //   display: flex;
      //   justify-content: space-between;
      //   font-family: Montserrat;
      //   font-style: normal;
      //   font-weight: 500;
      //   font-size: 18px;
      //   margin-bottom: 20px;
      // }
    }
  }

  &__item {
    font-size: 16px;
    line-height: 30px;
    padding: 10px 15px;
    transition: background 0.3s;
    text-align: left;
    width: 100%;
    position: relative;
    &:after {
      content: "";
      position: absolute;
      left: 15px;
      right: 15px;
      bottom: -0.5px;
      height: 1px;
      background: #c9c9c9;
      opacity: 0.3;
    }
    &:last-child {
      &:after {
        display: none;
      }
    }

    @media all and (max-width: 1023px) {
      width: 100%;
      padding: 10px;
      position: relative;
      font-weight: 500;
      font-size: 16px;
      &:after {
        content: "";
        width: 100%;
        height: 1px;
        position: absolute;
        bottom: 0px;
        left: 0;
        margin: 5px;
        background: rgba(233, 233, 233, 0.5);
      }
      &:last-child {
        margin-bottom: 0;
        &:after {
          display: none;
        }
      }
    }

    &:hover,
    &.selected {
      display: flex;
      align-items: center;
      gap: 10px;
      /* background: $light-gray; */
      font-weight: 700;
      span {
        color: #2d9cdb;
      }
    }
    .fa-check {
      font-size: 10px;
      color: #2d9cdb;
    }
  }
  .modal-bd {
    @media all and (max-width: 1023px) {
      position: fixed;
      bottom: 0;
      top: 0;
      left: 0;
      background-color: #00000085;
      width: 100%;
      z-index: 3;
      display: flex;
      align-items: center;
    }
  }
}
</style>
