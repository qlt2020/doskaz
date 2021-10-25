<template>
  <div class="user-objects">
    <div class="user-object-title">
      <h3 class="user-profile__mob-title">
        {{ $t("profile.objects.tabTitle") }}
      </h3>

      <nuxt-link
        class="btn btn_green"
        :to="localePath({ name: 'objects-add' })"
      >
        <img src="@/assets/icons/plus.svg" />
        <span>{{ $t("index.addObjectLink") }}</span>
      </nuxt-link>
    </div>

    <div class="user-objects__filter">
      <div class="filter">
        <div class="filter__text --mob-hide">{{ $t("profile.sort") }}</div>
        <dropdown-block
          :options="sortOptions"
          v-model="filter.sort"
        ></dropdown-block>
        <!-- <div class="filter__dropdown">
          <dropdown :options="sortOptions" v-model="filter.sort" />
        </div> -->
      </div>
      <div class="filter">
        <div class="filter__text --mob-hide">
          {{ $t("profile.objects.show") }}
        </div>
        <dropdown-block
          :options="scoreOptions"
          v-model="filter.overallScore"
        ></dropdown-block>
        <!-- <div class="filter__dropdown">
          <dropdown :options="scoreOptions" v-model="filter.overallScore" />
        </div> -->
      </div>
    </div>
    <div class="user-objects__content">
      <UserObject
        v-for="object in objects"
        :key="object.id"
        :objectLink="{ name: 'objects-id', params: { id: object.id } }"
        :objectTitle="object.title"
        :objectImg="object.image"
        :objectCatIcon="object.category_icon"
        :objectCategoryTitle="object.category_title"
        :objectCategorySubtitle="object.sub_category_title"
        :status="object.overallScore"
        :objectDate="object.date"
        :objectComments="object.reviewsCount"
        :objectReports="object.complaintsCount"
        :objectAddress="object.address"
        :objectId="object.id"
      />
    </div>
    <div class="user-objects__pagination" v-if="pages > 1">
      <pagination :pages="pages" />
    </div>
  </div>
</template>

<script>
import UserObject from "./UserObject";
import Pagination from "./../Pagination";
/* import Dropdown from "../Dropdown"; */
import DropdownBlock from "../DropdownBlock.vue";

export default {
  components: {
    /* Dropdown, */
    UserObject,
    Pagination,
    DropdownBlock,
  },
  data() {
    return {
      filter: {
        sort: "date desc",
        overallScore: undefined,
      },
    };
  },
  props: ["pages", "objects"],
  mounted() {
    this.filter = {
      sort: "date desc",
      overallScore: "all",
      ...this.$route.query,
    };
  },
  methods: {
    toggleDropdown(event) {
      event.currentTarget.classList.toggle("opened");
    },
  },
  computed: {
    sortOptions() {
      return [
        { value: "date desc", title: this.$t("profile.sortNewestFirst") },
        { value: "date asc", title: this.$t("profile.sortOldestFirst") },
      ];
    },
    scoreOptions() {
      return [
        { value: "all", title: this.$t("profile.objects.showAll") },
        {
          value: "full_accessible",
          title: this.$t("profile.objects.showFullAccessible"),
        },
        {
          value: "partial_accessible",
          title: this.$t("profile.objects.showPartialAccessible"),
        },
        {
          value: "not_accessible",
          title: this.$t("profile.objects.showNotAccessible"),
        },
      ];
    },
  },
  watch: {
    filter: {
      handler(v) {
        this.$router.push({ ...this.$route, query: v });
      },
      deep: true,
    },
  },
};
</script>

<style lang="scss">
.user-object-title {
  display: flex;
  justify-content: space-between;
}
.btn {
  img {
    margin-left: 10px;
  }
}
</style>
