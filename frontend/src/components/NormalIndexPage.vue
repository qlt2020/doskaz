<template>
  <div>
    <sidebar :posts="posts" :events="events" />
    <div class="stat_button-wrap">
      <StatisticsBtn
        :page="'statisticsAccess'"
        :class="'btn_right'"
        :title="'Статистика по доступности объектов'"
      />
      <StatisticsBtn 
        :page="'statisticsTotal'"
        :class="'btn_left'"
        :title="'Общая статистика'"
      />
    </div>
    <post-submit-message />
    <post-addition-message />
  </div>
</template>

<script>
import Sidebar from "~/components/Sidebar";
import PostSubmitMessage from "~/components/complaint/PostSubmitMessage";
import PostAdditionMessage from "~/components/object_add/PostAdditionMessage";
import StatisticsBtn from "~/components/statistics/StatisticsBtn"

export default {
  name: "NormalIndexPage",
  components: { PostAdditionMessage, PostSubmitMessage, Sidebar, StatisticsBtn },
  data() {
    return {
      posts: [],
      events: []
    };
  },
  async fetch() {
    const [{ items: posts }, events] = await Promise.all([
      this.$axios.$get("/api/blog/posts"),
      this.$axios.$get("/api/events")
    ]);
    this.posts = posts;
    this.events = events;
  }
};
</script>

<style scoped lang="scss">
  .stat_button-wrap {
    @media all and (max-width: 1024px) {
      display: none;
    }
  }
  .btn_left {
    left: 25px;
  }
  .btn_right {
    left: 320px;
    @media all and (max-width: 1366px) {
      left: 270px;

    }
  }

</style>
