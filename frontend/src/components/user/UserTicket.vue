<template>
  <div>
    <div class="user-tickets__item user-notifications__item">
      <div class="user-tickets__info user-notifications__info">
        <div class="user-notifications__title">
          {{ ticketTitle }}
        </div>
        <div class="user-notifications__date">
          {{ $dateFns.format(new Date(ticketDate), "dd.MM.yyyy") }}
        </div>
      </div>
      <div class="user-tickets__subtitle">
        <span v-if="ticketCity">
          г. {{ticketCity}},
        </span>
        <span v-if="ticketStreet">
          ул. {{ ticketStreet }}
        </span>
      </div>
      <div class="user-notifications__description">
        <p class="user-comments__text">{{ typeText }}</p>
      </div>
      <div class="user-tickets-purpose">
        <p class="purpose-text">{{visitPurpose}}</p>
      </div>
      <a class="user-comments__btn" :href="ticketLink" target="_blank">
        Скачать жалобу
      </a>
    </div>
  </div>
</template>

<script>
import FormattedDate from "~/components/FormattedDate";

export default {
  components: { FormattedDate },
  props: [
    "ticketId",
    "ticketImg",
    "ticketTitle",
    "ticketDate",
    "ticketType",
    "ticketStreet",
    "ticketCity",
    "visitPurpose"
  ],
  computed: {
    ticketLink() {
      return `/api/complaints/${this.ticketId}/doc`;
    },
    typeText() {
      return this.$t(`complaintTypes.${this.ticketType}`);
    }
  }
};
</script>

<style lang="scss">
  .purpose-text {
    display: block;
    display: -webkit-box;
    max-height: 8.6em;
    -webkit-line-clamp: 5;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>