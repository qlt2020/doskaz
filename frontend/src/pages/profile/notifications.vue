<template>
  <user-notifications :items="items" :pages="pages" />
</template>

<script>
import UserNotifications from "@/components/user/UserNotifications";

export default {
  head() {
    return {
      title: this.$t("profile.notifications.tabTitle")
    };
  },
  components: {
    UserNotifications
  },
  async asyncData({ $axios, query }) {
    const { data } = await $axios.get('/api/notifications/list', {
      params: {
        page: query.page || 1
      }
    });
    return data;
  },
  watchQuery: ["page"]
};
</script>
