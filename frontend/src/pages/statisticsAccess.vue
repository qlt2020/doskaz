<template>
  <div class="about">
    <ViTop />
    <MainHeader v-if="!viModeEnabled" />
    <client-only>
      <div class="container">
        <loading :is-full-page="true" :active="!isLoading" />
        <div class="statisticks">
          <div class="statisticks__header row mb-4 align-items-center">
            <div
              class="statisticks__header-title statisticks__main-total-title col-md-7 col-9"
            >
              <img
                :src="require('@/assets/icons/back-arrow.svg')"
                @click="$router.back()"
                class="title-back_arrow"
              />
              {{ $t("statistics.access") }}
            </div>
            <div class="col-md-5 col-3">
              <div class="d-flex justify-content-end align-items-center">
                <button class="button b_green" @click="exportList">
                  <i class="fas fa-download" style="color:#fff"></i>
                  <span :class="!viModeEnabled ? 'button-white' : ''">{{
                    $t("objects.download")
                  }}</span>
                </button>
              </div>
            </div>
          </div>
          <div class="statisticks__filter">
            <div class="statisticks_dropdown-wrap">
              <DropdownBlock
                :options="[
                  {
                    value: 0,
                    title: this.$t('statistics.allCountry'),
                  },
                  ...citiesList,
                ]"
                v-model="selectedCity"
                @input="changeCity"
              />
            </div>
            <div class="statisticks_dropdown-wrap">
              <DropdownBlock
                :options="[
                  {
                    value: 'all',
                    title: this.$t('statistics.all'),
                  },
                  ...group,
                ]"
                v-model="selectedGroup"
              />
            </div>
            <div class="statisticks_dropdown-wrap">
              <DropdownBlock
                :options="[
                  {
                    value: 0,
                    title: this.$t('statistics.all'),
                  },
                  ...categories,
                ]"
                v-model="selectedCategory"
                @input="changeCategory"
              />
            </div>
            <div
              v-if="subCategoriesVisible && subCategoriesOptions"
              class="statisticks_dropdown-wrap"
            >
              <DropdownBlock
                :options="[
                  {
                    value: 0,
                    title: this.$t('statistics.all'),
                  },
                  ...subCategoriesOptions.subCategories,
                ]"
                v-model="selectedSubCategory"
                @input="changeSubcategory"
              />
            </div>
          </div>
          <div class="statisticks__main">
            <b-table-simple
              hover
              small
              caption-top
              responsive
              :sticky-header="true"
              :no-border-collapse="true"
              class="table"
            >
              <b-thead head-variant="light" class="table_header">
                <b-tr v-if="groupsTable">
                  <b-th colspan="1">№</b-th>
                  <b-th colspan="1" :stickyColumn="true">{{
                    $t("statistics.object")
                  }}</b-th>
                  <b-th
                    colspan="4"
                    v-for="categories in groupsTable"
                    :key="categories"
                    class="text-nowrap"
                    stackedHeading="true"
                  >
                    {{ categories }}
                  </b-th>
                </b-tr>
              </b-thead>
              <b-thead head-variant="light" class="table_header2">
                <b-tr v-if="objectsStat">
                  <b-th :stickyColumn="true"></b-th>
                  <b-th :stickyColumn="true"></b-th>
                  <template v-for="(categories, key, index) in groupsTable">
                    <b-th
                      class="statisticks__table-subtitle --green"
                      :key="categories + key + index + 17"
                      >{{ $t("statistics.general") }}</b-th
                    >
                    <b-th
                      class="statisticks__table-subtitle --green"
                      :key="categories + key + index + 1"
                      >{{
                        $t("accessibilityScore.status.full_accessible")
                      }}</b-th
                    >
                    <b-th
                      class="statisticks__table-subtitle --orange text-nowrap"
                      :key="categories + key + index + 4"
                      >{{
                        $t("accessibilityScore.status.partial_accessible")
                      }}</b-th
                    >
                    <b-th
                      class="statisticks__table-subtitle --red"
                      :key="categories + key + index + 25"
                      >{{
                        $t("accessibilityScore.status.not_accessible")
                      }}</b-th
                    >
                  </template>
                </b-tr>
              </b-thead>
              <b-tbody head-variant="light" v-if="objectsStat">
                <template v-for="(item, index) in objectsStat">
                  <b-tr :key="item.category_title + index">
                    <b-th
                      :key="item.category_title + index + 6"
                      class="statistics_table_stroke"
                      :stickyColumn="true"
                      >{{ index }}</b-th
                    >
                    <b-th
                      :key="item.category_title + index + 5"
                      class="statistics_table_stroke"
                      :stickyColumn="true"
                      >{{ $t(item.category_title) }}</b-th
                    >

                    <template v-for="(group, groupName, index) in groupsTable">
                      <b-td
                        :key="groupName + index + item.category_id + 1"
                        class="statistics_table_stroke"
                        >{{ item[`${groupName}Total`] }}</b-td
                      >
                      <b-td
                        class="--green statistics_table_stroke"
                        :key="groupName + index + item.category_id + 2"
                        >{{ item[`${groupName}_full_accessible`] }}</b-td
                      >
                      <b-td
                        class="--orange statistics_table_stroke"
                        :key="groupName + index + item.category_id + 3"
                        >{{ item[`${groupName}_partial_accessible`] }}</b-td
                      >
                      <b-td
                        class="--red statistics_table_stroke"
                        :key="groupName + index + item.category_id + 4"
                        >{{ item[`${groupName}_not_accessible`] }}</b-td
                      >
                    </template>
                  </b-tr>

                  <template
                    v-for="(subcategory, subcategoryIndex) in item.subcategory"
                  >
                    <b-tr
                      :key="
                        item.category_title +
                          (subcategoryIndex +
                            index * subcategoryIndex * subcategoryIndex)
                      "
                    >
                      <b-th
                        :key="subcategory.sub_category_id + 17 * 4"
                        class="statistics_table_stroke-subcategoria"
                        >{{ index }}.{{ subcategoryIndex + 1 }}</b-th
                      >
                      <b-th
                        :key="subcategory.sub_category_id + 19 * 5"
                        class="statistics_table_stroke-subcategoria"
                        :stickyColumn="true"
                        >{{ subcategory.sub_category_title }}</b-th
                      >
                      <template
                        v-for="(group, groupName, index) in groupsTable"
                      >
                        <b-td :key="groupName + index + item.category_id + 1">{{
                          subcategory[`${groupName}Total`]
                        }}</b-td>
                        <b-td
                          class="--green"
                          :key="
                            group +
                              index +
                              groupName +
                              (subcategory.sub_category_id + 11)
                          "
                          >{{
                            subcategory[`${groupName}_full_accessible`]
                          }}</b-td
                        >
                        <b-td
                          class="--orange"
                          :key="
                            group +
                              index +
                              groupName +
                              (subcategory.sub_category_id + 13)
                          "
                          >{{
                            subcategory[`${groupName}_partial_accessible`]
                          }}</b-td
                        >
                        <b-td
                          class="--red"
                          :key="
                            group +
                              index +
                              groupName +
                              (subcategory.sub_category_id + 15)
                          "
                          >{{
                            subcategory[`${groupName}_not_accessible`]
                          }}</b-td
                        >
                      </template>
                    </b-tr>
                  </template>
                </template>
              </b-tbody>
            </b-table-simple>
          </div>
        </div>
      </div>
    </client-only>
    <MainFooter />
  </div>
</template>

<script>
import MainHeader from "@/components/MainHeader";
import ViTop from "@/components/ViTop";
import MainFooter from "@/components/MainFooter";
import BackBtn from "@/components/BackBtn";
import { get } from "vuex-pathify";
import DropdownBlock from "@/components/DropdownBlock";
import "@/plugins/bootstrap-plugin.js";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  name: "StatisticsAccess",
  components: {
    MainHeader,
    ViTop,
    MainFooter,
    BackBtn,
    DropdownBlock,
    Loading,
  },
  data() {
    return {
      usersTitleList: {
        options: {
          movement: this.$t("statistics.statisticsTotal.movement"),
          vision: this.$t("statistics.statisticsTotal.vision"),
          limb: this.$t("statistics.statisticsTotal.limb"),
          hearing: this.$t("statistics.statisticsTotal.hearing"),
          temporal: this.$t("statistics.statisticsTotal.temporal"),
          babyCarriage: this.$t("statistics.statisticsTotal.babycarriage"),
          missingLimbs: this.$t("statistics.statisticsTotal.missinglimbs"),
          pregnant: this.$t("statistics.statisticsTotal.pregnant"),
          intellectual: this.$t("statistics.statisticsTotal.intellectual"),
          agedPeople: this.$t("statistics.statisticsTotal.agedpeople"),
          kids: this.$t("statistics.statisticsTotal.withchild"),
        },
      },
      group: [
        {
          value: "movement",
          title: this.$t("statistics.statisticsTotal.movement"),
        },
        {
          value: "vision",
          title: this.$t("statistics.statisticsTotal.vision"),
        },
        {
          value: "limb",
          title: this.$t("statistics.statisticsTotal.limb"),
        },
        {
          value: "hearing",
          title: this.$t("statistics.statisticsTotal.hearing"),
        },
        {
          value: "temporal",
          title: this.$t("statistics.statisticsTotal.temporal"),
        },
        {
          value: "babyCarriage",
          title: this.$t("statistics.statisticsTotal.babycarriage"),
        },
        {
          value: "missingLimbs",
          title: this.$t("statistics.statisticsTotal.missinglimbs"),
        },
        {
          value: "pregnant",
          title: this.$t("statistics.statisticsTotal.pregnant"),
        },
        {
          value: "intellectual",
          title: this.$t("statistics.statisticsTotal.intellectual"),
        },
        {
          value: "agedPeople",
          title: this.$t("statistics.statisticsTotal.agedpeople"),
        },
        {
          value: "kids",
          title: this.$t("statistics.statisticsTotal.withchild"),
        },
      ],
      selectedCity: 0,
      selectedCategory: 0,
      selectedSubCategory: 0,
      selectedGroup: "all",
      subCategoriesOptions: {
        subCategories: [],
      },
      subCategoriesVisible: false,
    };
  },
  async mounted() {
    this.$store.commit("statistics/filterObjectsReset");
    this.$store.dispatch("objectAdding/init");
    await this.$store.dispatch("statistics/getObjectsStatTable");
  },
  computed: {
    viModeEnabled: get("visualImpairedModeSettings/enabled"),
    isLoading: get("statistics/isLoading"),
    objectsStat: get("statistics/getObjectsStatTable"),
    groupsTable() {
      if (this.selectedGroup == "all") {
        return this.usersTitleList.options;
      } else {
        const changeGroup = {};
        changeGroup[this.selectedGroup] = this.usersTitleList.options[
          this.selectedGroup
        ];
        return changeGroup;
      }
    },
    cities: get("cities/list"),
    categories: get("objectAdding/categories"),
    citiesList() {
      const cities = [...this.cities];
      cities.shift(0);
      return cities;
    },
  },
  methods: {
    async changeCategory(value) {
      if (value == 0) {
        this.subCategoriesVisible = false;
        this.selectedCategory = 0;
      } else {
        await this.getSubCategories(value);
        this.selectedCategory = value;
        this.subCategoriesVisible = true;
      }
      this.$store.commit("statistics/filterObjectsStat", {
        field: "main_category_id",
        value: value,
      });
      this.$store.commit("statistics/filterObjectsStat", {
        field: "category_id",
        value: 0,
      });
      await this.$store.dispatch("statistics/getObjectsStatTable");
    },
    changeSubcategory(value) {
      this.$store.commit("statistics/filterObjectsStat", {
        field: "category_id",
        value: value,
      });
      this.selectedSubCategory = value;
      this.$store.dispatch("statistics/getObjectsStatTable");
    },
    changeCity(value) {
      this.$store.commit("statistics/filterObjectsStat", {
        field: "city_id",
        value: value,
      });
      this.selectedCity = value;
      this.$store.dispatch("statistics/getObjectsStatTable");
    },
    async getSubCategories(value) {
      const categories = [...this.categories];
      this.subCategoriesOptions = await categories.find(
        (cat) => cat.id == value
      );
      this.subCategoriesVisible = true;
    },
    async exportList() {
      window.open(
        `/api/objects/statistic/export/excel?city_id=${
          this.selectedCity ? this.selectedCity : 0
        }&main_category_id=${
          this.selectedCategory ? this.selectedCategory : 0
        }&category_id=${
          this.selectedSubCategory ? this.selectedSubCategory : 0
        }&group=${this.selectedGroup}`,
        "_blank"
      );
    },
  },
};
</script>

<style lang="scss" scoped>
@import "@/styles/mixins.scss";
@import "bootstrap/dist/css/bootstrap.css";
@import "bootstrap-vue/dist/bootstrap-vue.css";

.statisticks .button {
  border-radius: 10px;
  height: 54px;
}

.statisticks .button-white {
  color: #ffffff;
}

.statisticks .table {
  position: relative;
}

.statisticks .table .table_header {
  position: sticky;
  position: -webkit-static;
  top: 0;
  z-index: 3;
}

.statisticks .table .table_header2 {
  position: sticky;
  position: -webkit-static;
  top: 39px;
  z-index: 3;
}
@media not all and (min-resolution: 0.001dpcm) {
  .statisticks .table {
    .table_header,
    .table_header2 {
      position: relative;
      z-index: 3;
    }
  }
  .b-table-sticky-header > .table.b-table > thead > tr > th {
    z-index: 3;
  }
}

.statisticks .statisticks__filter {
  margin-bottom: 43px;
  display: flex;
  justify-content: flex-start;
  flex-wrap: wrap;
}

.statisticks .statisticks_dropdown-wrap {
  margin-right: 10px;
  margin-bottom: 20px;
  @media all and (max-width: 768px) {
    width: 100%;
  }
}

.statisticks tbody {
  color: #262626;
  background-color: #ffffff;
  border: 1px solid #f0f0f0;
}

.statisticks .table .thead-light th {
  color: #262626;
  font-weight: 600;
}

.statisticks .statisticks__table-subtitle {
  font-size: 12px;
}

.statisticks .table-sm td,
.table-sm th {
  padding: 1rem 1rem;
}

.statisticks .statistics_table_stroke {
  padding: 1rem;
  font-weight: 600;
}

.statisticks .statistics_table_stroke-subcategoria {
  font-weight: 400;
}

.statisticks .table-responsive::-webkit-scrollbar {
  width: 7px;
  height: 7px;
}

.statisticks .table-responsive::-webkit-scrollbar-track {
  background: white;
}
.statisticks .table-responsive::-webkit-scrollbar-thumb {
  background: #d7d5d5;
}

.statisticks .b-table-sticky-header > .table.b-table > thead > tr > th {
  padding: 0.5rem 1rem;
}

.statisticks {
  padding: 40px 0;
  min-height: calc(100vh - 195px);
  @media all and (max-width: 1024px) {
    padding: 90px 0;
  }
}

.statisticks .statisticks__header-title {
  font-weight: 700;
  font-size: 28px;
  font-family: "SFProDisplay";
  margin: 0;
  color: #000000;
}

.statisticks__block-fix-width {
  min-width: 350px;
}

.statisticks__block-fix-width .select2 {
  top: 80px;
  left: 25px;
}

.select-stat {
  width: 140px;
  border: none;
  position: absolute;
  right: 0;
}

.statisticks .select2 {
  position: absolute;
  right: 15px;
  z-index: 9;
  width: 140px;
}

.statisticks .statisticks__main .--green {
  color: #219653 !important;
}

.statisticks .statisticks__main .--orange {
  color: #f2994a !important;
}

.statisticks .statisticks__main .--red {
  color: #eb5757 !important;
}

.b-table-sticky-header {
  max-height: 60vh;
}

@media all and (max-width: 768px) {
  .statisticks {
    padding: 90px 0 30px;
  }

  .statisticks .button {
    width: 54px;
    span {
      display: none;
    }
  }

  .statisticks .statisticks__header-title {
    font-size: 22px;
  }

  .statisticks .statisticks__filter {
    margin-bottom: 10px;
  }

  .statisticks .statisticks__header-title {
    font-size: 22px;
  }

  .statisticks .table .table_header {
    position: inherit;
  }
  .b-table-sticky-header > .table.b-table > thead > tr > th {
    position: inherit;
  }

  .b-table-sticky-header > .table.b-table > thead > tr > .b-table-sticky-column,
  .b-table-sticky-header > .table.b-table > tbody > tr > .b-table-sticky-column,
  .b-table-sticky-header > .table.b-table > tfoot > tr > .b-table-sticky-column,
  .table-responsive > .table.b-table > thead > tr > .b-table-sticky-column,
  .table-responsive > .table.b-table > tbody > tr > .b-table-sticky-column,
  .table-responsive > .table.b-table > tfoot > tr > .b-table-sticky-column {
    position: inherit;
  }
}
</style>
