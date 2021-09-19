<template>
  <div class="user-tasks">
    <h3 class="user-profile__mob-title">{{ $t("profile.tasks.tabTitle") }}</h3>
    <div class="user-objects__filter --between">
      <div class="filter">
        <div class="filter__text">
          {{ $t("profile.sort") + " " + $t("profile.sortByDate") }}
        </div>
        <DropdownBlock :options="sortOptions" v-model="sort" />
      </div>
    </div>
    <div class="user-tasks__content">
      <UserTasksItem
        v-for="(task, index) in items"
        :key="index"
        :tasksItemStatus="task.completedAt ? '--done' : '--new'"
        :tasksItemDate="task.createdAt"
        :tasksItemText="task.title"
        :tasksItemPoints="task.points"
      />
    </div>

    <div class="user-tickets__pagination">
      <pagination :pages="pages" v-if="pages > 1" />
    </div>
  </div>
</template>

<script>
import UserTasksItem from "./UserTasksItem";
import Pagination from "../Pagination";
import Dropdown from "@/components/Dropdown";
import DropdownBlock from "@/components/DropdownBlock";

export default {
  name: "UserTasks",
  props: ["pages", "items"],
  data() {
    return {
      sort: "createdAt desc",
    };
  },
  components: {
    Dropdown,
    Pagination,
    UserTasksItem,
  },
  computed: {
    sortOptions() {
      return [
        { value: "createdAt desc", title: this.$t("profile.sortNewestFirst") },
        { value: "createdAt asc", title: this.$t("profile.sortOldestFirst") },
      ];
    },
  },
  watch: {
    sort: {
      handler(v) {
        this.$router.push({
          ...this.$route,
          query: {
            sort: v,
          },
        });
      },
    },
  },
};
</script>

<style lang="scss">
@import "./../../styles/mixins.scss";

.user-tasks {
  padding: 35px 0 0;
  @media all and (max-width: 768px) {
    padding: 30px 0;
  }

  &__content {
    display: grid;
    grid-row-gap: 20px;
    grid-auto-rows: 80px;
    @media screen and (max-width: 768px) {
      grid-auto-rows: auto;
    }
  }

  &__item {
    justify-content: space-between;
    display: flex;
    position: relative;
    background: #ffffff;
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
      0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    border-radius: 10px;
    align-items: center;
    padding: 0 25px;

    @media screen and (max-width: 768px) {
      padding: 15px 15px;
    }

    &-left {
      display: flex;
      justify-content: flex-start;
      align-items: center;

      @media screen and (max-width: 768px) {
        display: grid;
        grid-template-columns: 36px 150px;
        grid-row-gap: 17px;
        grid-column-gap: 15px;
      }
    }

    &-status {
      width: 36px;
      height: 36px;
      margin-right: 15px;

      &.--new {
        background: url("@/assets/icons/task-current.svg") no-repeat center;
        background-size: contain;
      }

      &.--current {
        background: url("@/assets/icons/task-current.svg") no-repeat center;
      }

      &.--done {
        background: url("@/assets/icons/task-done.svg") no-repeat center;
      }
    }

    &-date {
      width: 100px;
      padding-right: 10px;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 300;
      font-size: 14px;
      line-height: 17px;
      color: #202020;
    }

    &-text {
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 500;
      font-size: 18px;
      line-height: 21px;
      color: #202020;
      width: 410px;
      @media all and (max-width: 768px) {
        grid-column-start: 1;
        grid-column-end: 3;
        width: unset;
        height: fit-content;
      }
    }

    &-points {
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 600;
      font-size: 20px;
      line-height: 24px;
      text-align: right;
      color: #202020;
      @media all and (max-width: 768px) {
        align-self: baseline;
      }
    }
  }
}
</style>
