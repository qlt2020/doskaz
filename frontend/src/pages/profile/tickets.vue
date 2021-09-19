<template>
  <UserTickets :complaints="items" :pages="pages" />
</template>

<script>
import UserTickets from "~/components/user/UserTickets";

export default {
  components: {
    UserTickets
  },
  data() {
    return {}
  },
  async asyncData({ $axios, query }) {
    const { data } = await $axios.get("/api/profile/complaints", {
      params: {
        sort: query.sort,
        page: query.page || 1
      }
    });
    return data;
  },
  watchQuery: ["page", "sort"]
};
</script>

<style lang="scss">

.user-page {
  .main-header__content {
    border: none;
  }
}
</style>
