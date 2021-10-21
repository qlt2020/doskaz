<template>
  <div class="user-notifications">
    <div class="user-notifications__head">
      <h3 class="user-profile__mob-title">
        {{ $t("profile.notifications.tabTitle") }}
      </h3>
      <template v-if="profile.category">
        <button
          v-if="subscribed"
          class="user-notifications__head__btn"
          @click="unsubscribe"
        >
          {{ $t("profile.notifications.unsubscribe") }}
        </button>
        <button
          v-else
          class="user-notifications__head__btn subscribe"
          @click="subscribe"
        >
          {{ $t("profile.notifications.title") }}
        </button>
      </template>
    </div>
    <div class="user-notifications__list">
      <UserNotification
        v-for="(item, index) in items"
        :key="index"
        :item="item"
      />
    </div>
    <!--		<div class="user-comments__pagination">-->
    <!--			<pagination :pages="pages" v-if="pages > 1"/>-->
    <!--		</div>-->
    <SubscribeNotifModal
      v-if="subscribeNotifModalVisible"
      @close="subscribeNotifModalVisible = false"
      @showNextModal="subscribeNotifDoneModalVisible = true"
    />
    <SubscribeNotifDoneModal
      v-if="subscribeNotifDoneModalVisible"
      @close="subscribeNotifDoneModalVisible = false"
    />
    <div class="user-tickets__pagination">
      <pagination :pages="pages" v-if="pages > 1" />
    </div>
  </div>
</template>

<script>
import UserNotification from "./UserNotification";
import Pagination from "../Pagination";
import { get } from "vuex-pathify";
import SubscribeNotifModal from "../modals/SubscribeNotifModal";
import SubscribeNotifDoneModal from "../modals/SubscribeNotifDoneModal";

export default {
  name: "UserNotifications",
  props: {
    items: {
      type: Array
    },
    pages: {
      type: Number,
      default: 1
    }
  },
  components: {
    Pagination,
    UserNotification,
    SubscribeNotifModal,
    SubscribeNotifDoneModal
  },
  data() {
    return {
      subscribeNotifModalVisible: false,
      subscribeNotifDoneModalVisible: false
    }
  },
  computed: {
    subscribed: get("notifications/subscribed"),
    profile: get('authentication/user')
  },
  methods: {
    async unsubscribe() {
      await this.$store.dispatch("notifications/unsubscribe");
    },
    subscribe() {
      this.subscribeNotifModalVisible = true
    }
  }
};
</script>