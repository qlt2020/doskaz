<template>
  <UserObjects :pages="pages" :objects="items" />
</template>

<script>
import UserObjects from "~/components/user/UserObjects";

export default {
  name: "userObjectsTab",
  head() {
    return {
      title: this.$t("profile.objects.tabTitle"),
    };
  },
  components: {
    UserObjects,
  },
  async asyncData({ $axios, query }) {
    const { data } = await $axios.get("/api/profile/objects", {
      params: {
        ...query,
        page: query.page || 1,
      },
    });
    return data;
  },
  watchQuery: true,
};
</script>
