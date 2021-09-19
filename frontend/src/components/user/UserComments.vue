<template>
  <div class="user-comments">
    <h3 class="user-profile__mob-title">
      {{ $t("profile.comments.tabTitle") }}
    </h3>
    <div class="user-objects__filter">
      <div class="filter">
        <div class="filter__text">
          {{ $t("profile.sort") + " " + $t("profile.sortByDate") }}
        </div>
        <!-- <div class="select">
          <select v-model="filterValue">
            <option
              v-for="(type, index) in options"
              :value="type.value"
              :key="index"
            >
              {{ type.title }}
            </option>
          </select>
        </div> -->
        <DropdownBlock :options="options" v-model="filterValue" />
      </div>
    </div>
    <div class="user-comments__list">
      <UserComment v-for="item in items" :key="item.id" :item="item" />
    </div>
    <div class="user-comments__pagination">
      <pagination :pages="pages" v-if="pages > 1" />
    </div>
  </div>
</template>

<script>
import UserComment from "./UserComment";
import Pagination from "../Pagination";
import Dropdown from "../Dropdown";
import DropdownBlock from "@/components/DropdownBlock";

export default {
  name: "UserComments",
  props: ["pages", "items"],
  components: {
    Dropdown,
    Pagination,
    UserComment,
  },
  methods: {
    toggleDropdown(event) {
      event.currentTarget.classList.toggle("opened");
    },
  },
  computed: {
    options() {
      return [
        { value: "date desc", title: this.$t("profile.sortNewestFirst") },
        { value: "date asc", title: this.$t("profile.sortOldestFirst") },
      ];
    },
    filterValue: {
      get() {
        return this.$route.query.sort || "date desc";
      },
      set(v) {
        this.$router.push({ ...this.$route, query: { sort: v } });
      },
    },
  },
};
</script>
