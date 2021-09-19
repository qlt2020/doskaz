<template>
  <div class="user-objects">
    <div class="user-objects__head user-notifications__head">
      <h3 class="user-profile__mob-title">
        {{ $t("profile.tickets.tabTitle") }}
      </h3>
      <nuxt-link
        :to="localePath({ name: 'complaint' })"
        class="button button__complaint"
      >
        <img src="@/assets/icons/chat_plus.svg" />
        {{ $t("profile.tickets.makeComplaintButton") }}
      </nuxt-link>
    </div>
    <div class="user-objects__filter --between">
      <div class="filter">
        <div class="filter__text">
          {{ $t("profile.sort") + " " + $t("profile.sortByDate") }}
        </div>
        <DropdownBlock :options="sortOptions" v-model="sort" />
      </div>
    </div>
    <div class="user-objects__content user-objects__content-tickets">
      <UserTicket
        v-for="complaint in complaints"
        :key="complaint.id"
        :ticket-id="complaint.id"
        :ticketImg="complaint.image"
        :ticketStreet="complaint.street"
        :ticketCity="complaint.city_name"
        :ticketTitle="complaint.title"
        :ticket-type="complaint.type"
        :ticketDate="complaint.date"
        :visitPurpose="complaint.visit_purpose"
      />
    </div>

    <div class="user-tickets__pagination">
      <pagination :pages="pages" v-if="pages > 1" />
    </div>
  </div>
</template>

<script>
import UserTicket from "./UserTicket";
import Pagination from "../Pagination";
import Dropdown from "../Dropdown";
import DropdownBlock from "@/components/DropdownBlock";

export default {
  props: ["pages", "complaints"],
  components: {
    Dropdown,
    Pagination,
    UserTicket,
  },
  data() {
    return {};
  },
  computed: {
    sortOptions() {
      return [
        { value: "date desc", title: this.$t("profile.sortNewestFirst") },
        { value: "date asc", title: this.$t("profile.sortOldestFirst") },
      ];
    },
    sort: {
      get() {
        return this.$route.query.sort || "date desc";
      },
      set(v) {
        this.$router.push({ ...this.$route, query: { sort: v } });
      }
    }
  },
};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";
.user-tickets__item {
  margin-bottom: 20px;
  @media (max-width: 768px) {
    padding: 24px 15px;
  }
}
.user-tickets__info {
  @media (max-width: 768px) {
    display: flex;
  }
}
.user-tickets__subtitle {
  font-size: 14px;
  font-weight: 500;
  color: #535353;
  margin-top: -9px;
}

.user-tickets-purpose p {
  font-size: 18px;
  font-weight: 400;
  color: #202020;
  font-family: SFProDisplay;
}
</style>
