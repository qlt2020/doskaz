<template>
  <div>
    <user-tasks :pages="pages" :items="items" />
    <div class="popup__wrapper" v-if="addTaskPopup">
      <div class="popup__scroll">
        <div class="popup__in">
          <span class="popup__close" v-on:click="addTaskPopup = false"></span>
          <h5 class="popup__title">Первое задание</h5>
          <p class="popup__text">
            Заполните свой профиль и получите 50 баллов!
          </p>
          <button type="button" class="user-page__button">
            Начать задание
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import UserTasks from "~/components/user/UserTasks";

export default {
  data() {
    return {
      addTaskPopup: false
    };
  },
  components: {
    UserTasks
  },
  watchQuery: true,
  async asyncData({ $axios, query, store }) {
    const { data } = await $axios.get("/api/profile/tasks", {
      params: {
        ...query,
        page: query.page || 1,
        cityId: store.state.settings.cityId
      }
    });
    return data;
  }
};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";
</style>
