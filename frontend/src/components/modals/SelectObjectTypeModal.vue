<template>
  <div class="select-object-type">
    <div class="title">
      <span>{{ $t("selectObjectType") }}</span>
      <div class="close-button" @click="close">
        <img src="@/assets/icons/close_h.svg" alt="" />
      </div>
    </div>
    <div class="category-list">
      <template v-if="selectedCategory === null">
        <div
          @click="selectCategory(cat)"
          class="category-item"
          v-for="cat in categories"
          :key="cat.id"
        >
          <div class="icon-back">
            <img :src="cat.icon" />
          </div>

          <div class="category__text">
            {{ cat.title }}
          </div>
        </div>
      </template>
      <template v-else>
        <p class="sub-cat-button" @click="selectedCategory = null">
          <img src="@/assets/icons/back_arrow.svg" />
          {{ selectedCategory.title }}
        </p>

        <div
          @click="toggleCategory(subcat.id)"
          class="category-item"
          v-for="subcat in selectedCategory.subCategories"
          :class="{
            checked_subcategory: selectedCategories.includes(subcat.id),
          }"
          :key="subcat.id"
        >
          <div class="icon-back">
            <img :src="subcat.icon" />
          </div>

          <div class="category__text">
            {{ subcat.title }}
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import { call, get, sync } from "vuex-pathify";

export default {
  name: "SelectTypeObjectModal",
  data() {
    return {
      selectedCategory: null,
      availabilityToggle: false,
    };
  },
  methods: {
    selectCategory(cat) {
      this.selectedCategory = cat;
    },
    ...call("map", ["toggleCategory", "toggleAccessibilityLevel"]),
    close() {
      this.$emit("close");
    },
  },
  computed: {
    ...sync("map", ["accessibilityLevels"]),
    selectedCategories: get("map/selectedCategories"),
    categories() {
      return this.$store.state.objectCategories.categories;
    },
  },
};
</script>

<style lang="scss" scoped>
.select-object-type {
  position: absolute;
  top: 50px;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 15px;
  background: white;
  z-index: 15;
  .title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    span {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      font-size: 18px;
    }
  }
  .category-list {
    overflow-x: hidden;
    overflow-y: auto;
    display: flex;
    gap: 10px 0px;
    flex-direction: column;
    margin-top: 25px;
    height: 90%;
    .category-item {
      display: flex;
      align-items: center;
      gap: 12px;
      .icon-back {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        min-width: 40px;
        height: 40px;
        background: #2d9cdb;
        border-radius: 10px;
      }
    }
    .sub-cat-button {
      background: white;
      width: max-content;
      padding: 5px 15px;
      border-radius: 10px;
      box-shadow: 0px 16px 24px #0000001f;
    }
  }
}
</style>
