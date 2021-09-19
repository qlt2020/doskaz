<template>
  <div class="login-form" :class="{ isOpened: popupOpen }">
    <div class="modal__content">
      <div class="modal__head">
        <div class="modal__title">
          {{
            selectedCategory
              ? $t("startCategory.changeCategoryTitle")
              : $t("startCategory.title")
          }}
        </div>
        <div class="close-button" @click="selectCat('justView')">
          <img src="@/assets/icons/close_h.svg" alt="" />
        </div>
      </div>
      <p
        v-if="!selectedCategory"
        class="login-form__text"
        v-html="$t('startCategory.adviceText')"
      ></p>
      <div class="start-cat__list">
        <button
          class="start-cat__item"
          @click="selectCat(category.key)"
          v-for="category in disabilityCategoriesList"
          :key="category.key"
          :class="{ active: category.key === selectedCategory }"
        >
          <span class="start-cat__icon">
            <img
              :src="require(`~/assets/icons/categories/${category.key}.svg`)"
            />
          </span>
          <span class="start-cat__text">{{
            $t(`disabilityCategories.${category.key}`)
          }}</span>
        </button>
      </div>
      <button
        v-if="!selectedCategory"
        class="start-cat__link"
        @click="selectCat('justView')"
      >
        {{ $t("startCategory.justView") }}
      </button>
    </div>
  </div>
</template>

<script>
import { call, get } from "vuex-pathify";

export default {
  computed: {
    ...get("disabilitiesCategorySettings", {
      popupOpen: "popupOpen",
      selectedCategory: "category",
      categories: "categories",
    }),
    disabilityCategoriesList() {
      return this.categories.filter(({ key }) => key !== "justView");
    },
  },
  methods: {
    ...call("disabilitiesCategorySettings", ["selectCategory", "init"]),
    selectCat(value) {
      this.selectCategory(value);
      this.$emit("showDetectModal");
    },
  },
};
</script>

<style lang="scss" scoped>
@import "./../styles/mixins.scss";
.modal-title {
  display: flex;
  justify-content: space-between;
  &._mb {
    margin-bottom: 20px;
  }
  .title {
    font-size: 22px;
    font-weight: 700;
    font-family: "SFProDisplay";
  }
}
.login-form {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1100;
  display: none;
  .modal__content {
    display: flex;
    max-width: 700px;
    flex-direction: column;
    @media all and (max-width: 640px) {
      display: flex;
      flex-direction: column;
      overflow-x: auto;
    }
    .login-form {
      &__text {
        font-family: "SFProDisplay";
        font-style: normal;
        font-weight: 300;
        font-size: 16px;
        line-height: 21.5px;
        margin: 10px 0px;
        @media all and (max-width: 991px) {
          display: none;
        }
      }
    }
    .close-button {
      display: none;
      @media all and (max-width: 991px) {
        display: block;
      }
    }
  }

  &.isOpened {
    display: flex;
  }
}

.start-cat {
  &__link {
    border: none;
    background: transparent;
    cursor: pointer;
    font-family: "SFProDisplay";
    font-size: 16px;
    font-weight: 500;
    line-height: 20px;
    color: #2d9cdb;
    text-decoration: underline;
    &:hover {
      opacity: 0.7;
    }
  }

  &__list {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    /* overflow: scroll; */

    @media all and (max-width: 640px) {
      flex: 1;
      padding-left: 0;
    }
  }

  &__item {
    display: flex;
    align-items: center;
    width: calc(50% - 3px);
    margin: 0 0 8px;
    border: none;
    background: transparent;
    outline: none;
    padding: 7px;
    cursor: pointer;
    border: 1px solid transparent;
    border-radius: 10px;
    &.active {
      border: 1px solid #2d9cdb;
      background: rgba(45, 156, 219, 0.1);
    }
    &:last-child {
      margin-bottom: 0;
    }
    @media all and (max-width: 640px) {
      width: 100%;
      position: relative;
      &:after {
        content: "";
        width: 100%;
        height: 1px;
        position: absolute;
        bottom: -5px;
        background: rgba(201, 201, 201, 0.29);
      }
      &:last-child {
        margin-bottom: 0;
        &:after {
          display: none;
        }
      }
    }

    &:hover {
      background: #f1f8fc;
      font-weight: 700;
    }

    &.active {
      background: #f1f8fc;
      font-weight: 700;
    }
  }

  &__icon {
    width: 40px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    font-size: 0;
    background: #2d9cdb;
    border-radius: 10px;
    display: inline-block;
    flex: none;
    @media all and (max-width: 768px) {
      height: 40px;
      width: 40px;
      line-height: 40px;
    }
    svg {
      display: inline-block;
      vertical-align: middle;
      max-width: 20px;
      max-height: 20px;
      @media all and (max-width: 768px) {
        max-width: 20px;
        max-height: 20px;
      }
    }

    img {
      display: inline-block;
      vertical-align: middle;
      max-width: 20px;
      max-height: 20px;
      @media all and (max-width: 768px) {
        max-width: 20px;
        max-height: 20px;
      }
    }
  }

  &__text {
    height: 40px;
    width: 280px;
    font-size: 14px;
    font-weight: 500;
    line-height: 20px;
    padding: 0 0 0 10px;
    text-align: left;
    color: #000000;
    display: flex;
    align-items: center;
    @media all and (max-width: 768px) {
      font-size: 14px;
      line-height: 18px;
      height: 40px;
      width: 100%;
    }
  }
}
</style>
