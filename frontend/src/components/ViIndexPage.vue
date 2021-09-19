<template>
  <div class="vi" :class="viSettingsClasses">
    <div class="vi-container">
      <ViHeader></ViHeader>
      <div class="vi__line">
        <div class="col">
          <label class="vi__label --fcolor">{{
            $t("index.selectDisabilitiesCategory")
          }}</label>
          <div class="select">
            <select
              class="--bcolor"
              :value="selectedDisabilitiesCategory.key"
              @change="selectDisabilitiesCategory($event.target.value)"
            >
              <option
                v-for="category in disabilitiesCategorySettings"
                :key="category.key"
                :value="category.key"
              >
                {{ $t(`disabilityCategories.${category.key}`) }}
              </option>
            </select>
          </div>
        </div>
        <div class="col --city">
          <label class="vi__label --fcolor">{{ $t("index.selectCity") }}</label>
          <div class="select">
            <select
              class="--bcolor"
              :value="selectedCity.id"
              @change="selectCity($event.target.value)"
            >
              <option v-for="city in cities" :key="city.id" :value="city.id">{{
                city.name
              }}</option>
            </select>
          </div>
        </div>
      </div>
      <h3 class="vi__title --fcolor">{{ $t("index.search") }}</h3>
      <div class="vi__line">
        <div class="col --padding">
          <label class="vi__label --fcolor">{{
            $t("index.searchLabel")
          }}</label>
          <div class="input --bcolor">
            <input type="text" v-model="searchQuery" />
          </div>
        </div>
        <div class="col --padding --search">
          <button
            type="button"
            class="vi-search-button --bcolor"
            @click="search"
          >
            {{ $t("index.searchSubmitButton") }}
          </button>
        </div>
      </div>
      <div class="vi__input-b">
        <label class="vi__label">{{ $t("index.selectAccessibility") }}</label>
        <div class="vi__input-wrapper">
          <input
            id="r1"
            type="checkbox"
            class="vi__input"
            v-model="accessibilityLevels.full_accessible"
          /><label for="r1">{{
            $t("index.accessibilityFilter.full_accessible")
          }}</label>
          <input
            id="r2"
            type="checkbox"
            class="vi__input"
            v-model="accessibilityLevels.partial_accessible"
          /><label for="r2">{{
            $t("index.accessibilityFilter.partial_accessible")
          }}</label>
          <input
            id="r3"
            type="checkbox"
            class="vi__input"
            v-model="accessibilityLevels.not_accessible"
          /><label for="r3">{{
            $t("index.accessibilityFilter.not_accessible")
          }}</label>
        </div>
      </div>
      <div class="vi__line">
        <div class="col">
          <label class="vi__label --fcolor">{{
            $t("index.selectCategory")
          }}</label>
          <div class="select">
            <select
              class="--bcolor"
              :value="selectedCategoryId"
              @change="
                selectedCategoryId = $event.target.value;
                selectedSubCategoryId = null;
              "
            >
              <option :value="null">{{
                $t("index.emptyCategoryOption")
              }}</option>
              <option
                v-for="category in objectCategories"
                :key="category.id"
                :value="category.id"
              >
                {{ category.title }}
              </option>
            </select>
          </div>
        </div>
        <div class="col">
          <label class="vi__label --fcolor">{{
            $t("index.selectSubCategory")
          }}</label>
          <div class="select">
            <select class="--bcolor" v-model="selectedSubCategoryId">
              <option :value="null">{{
                $t("index.emptySubCategoryOption")
              }}</option>
              <option
                v-for="subCategory in subCategories"
                :key="subCategory.id"
                :value="subCategory.id"
              >
                {{ subCategory.title }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <ul class="vi-search__list">
        <li class="vi-search__item" v-for="item in results" :key="item.id">
          <h3 class="vi-search__title">
            <nuxt-link
              :to="localePath({ name: 'objects-id', params: { id: item.id } })"
              >{{ item.title }}
            </nuxt-link>
          </h3>
          <div class="vi-search__around">
            <div>
              <span class="vi-search__text">{{ item.category }}</span>
              <span class="vi-search__text">{{ item.address }}</span>
            </div>
            <span class="vi-search__text">{{
              $t(`accessibilityScore.status.${item.score}`) | capitalize
            }}</span>
          </div>
        </li>
      </ul>
      <div class="vi__complaint">
        <button
          @click="$router.push(localePath({ name: 'objects-add' }))"
          type="button"
          class="vi__button"
        >
          {{ $t("index.addObjectLink") }}
        </button>
        <button
          @click="$router.push(localePath({ name: 'complaint' }))"
          type="button"
          class="vi__button"
        >
          {{ $t("index.makeComplaintLink") }}
        </button>
      </div>
      <ViFooter></ViFooter>
    </div>
  </div>
</template>

<script>
import { get, call } from "vuex-pathify";
import ViHeader from "~/components/ViHeader";
import ViFooter from "~/components/ViFooter";
import capitalize from "lodash/capitalize";

export default {
  name: "ViIndexPage",
  components: {
    ViHeader,
    ViFooter,
  },
  data() {
    return {
      selectedCategoryId: null,
      selectedSubCategoryId: null,
      searchQuery: "",
      accessibilityLevels: {
        full_accessible: true,
        partial_accessible: true,
        not_accessible: true,
      },
      results: [],
      viPopupExample: true,
    };
  },
  async fetch() {
    if (!this.selectedDisabilitiesCategory) {
      this.selectDisabilitiesCategory("justView");
    }
    this.selectedCategoryId = this.$route.query.categoryId || null;
    this.selectedSubCategoryId = this.$route.query.subCategoryId || null;
    await this.search();
  },
  methods: {
    selectCity: call("settings/select"),
    selectDisabilitiesCategory: call(
      "disabilitiesCategorySettings/selectCategory"
    ),
    async search() {
      this.results = await this.$axios.$get("/api/objects/filter", {
        params: {
          query: this.searchQuery,
          cityId: this.selectedCity.id,
          disabilitiesCategory: this.selectedDisabilitiesCategory.category,
          accessibilityLevels: Object.keys(this.accessibilityLevels).filter(
            (key) => this.accessibilityLevels[key] === true
          ),
          subCategoryId: this.selectedSubCategoryId,
        },
      });
    },
  },
  filters: {
    capitalize(val) {
      return capitalize(val);
    },
  },
  computed: {
    disabilitiesCategorySettings: get(
      "disabilitiesCategorySettings/categories"
    ),
    selectedDisabilitiesCategory: get(
      "disabilitiesCategorySettings/currentCategory"
    ),
    cities: get("cities/list"),
    objectCategories: get("objectCategories/categories"),
    selectedCity: get("cities/selectedCity"),
    visualImpairedModeSettings: get("visualImpairedModeSettings"),
    viSettingsClasses() {
      return {
        "--noto": this.visualImpairedModeSettings.fontFamily === "Noto",
        "--black": this.visualImpairedModeSettings.colorTheme === "black",
        "--sm": this.visualImpairedModeSettings.fontSize === "sm",
        "--md": this.visualImpairedModeSettings.fontSize === "md",
        "--lrg": this.visualImpairedModeSettings.fontSize === "lrg",
      };
    },
    subCategories() {
      const category = this.objectCategories.find(
        (category) => category.id == this.selectedCategoryId
      );
      if (!category) {
        return [];
      }
      return category.subCategories;
    },
  },
};
</script>

<style lang="scss">
.vi {
  font-family: "Lato", sans-serif;
  background: #ffffff;
  &-popup {
    border: 2px solid;
    padding: 50px 60px 60px;
    width: 100%;
    max-width: 680px;
    position: relative;
    text-align: center;
    background: #fff;
    &__close {
      width: 60px;
      height: 60px;
      position: absolute;
      cursor: pointer;
      right: -100px;
      top: -10px;
    }
    &__wrapper {
      position: fixed;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: #737373;
      z-index: 100;
    }
    &__title {
      font-size: 32px !important;
      line-height: 40px;
      margin: 0 0 24px;
      color: #000000;
    }
    &__text {
      font-size: 24px !important;
      line-height: 47px;
      margin: 0 0 50px;
    }
    &__button {
      display: flex;
      justify-content: space-between;
      .vi__button {
        width: 100%;
        &.--half {
          width: calc(50% - 20px);
        }
      }
    }
    &-b {
      display: flex;
      justify-content: center;
      align-items: center;
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow-y: auto;
    }
  }
}
</style>
