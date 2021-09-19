<template>
  <div class="popup__wrapper">
    <div class="popup__scroll">
      <div class="popup__in --sm">
        <div class="popup__head" style="width: 100%">
          <span class="popup__title">{{
            $t("objects.review.popupTitle")
          }}</span>
          <div class="close" @click="close">
            <img :src="require('~/assets/icons/cross.png')" />
          </div>
        </div>
        <textarea
          v-model.trim="reviewText"
          class="popup__textarea textarea"
          :placeholder="$t('objects.review.textareaPlaceholder')"
          :disabled="reviewSubmitting"
        />
        <span class="popup__textarea-text">{{
          $t("objects.review.textareaHelpText")
        }}</span>
        <div class="popup__buttons" style="width: 100%; margin: unset">
          <div class="timeline__tab-link timeline__tab-link_user">
            <user-avatar class="avatar" style="width: 41px; height: 41px; border-radius: 50%; background-size: cover;" :value="avatar" />
            <username class="name" style="text-align: start" :value="name" />
          </div>
          <button
            type="button"
            class="button"
            @click="createReview"
            :disabled="reviewText.length < 20 || reviewSubmitting"
          >
            {{ $t("objects.review.submitButtonTitle") }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { get, call } from "vuex-pathify";
import Username from "~/components/Username";
import UserAvatar from "~/components/UserAvatar";

export default {
  components: { UserAvatar, Username },
  middleware: ["authenticated"],
  data() {
    return {
      reviewSubmitting: false,
      reviewText: ""
    };
  },
  computed: {
    avatar: get("authentication/user.avatar"),
    name: get("authentication/name")
  },
  methods: {
    submitReview: call("object/submitReview"),
    async createReview() {
      this.reviewSubmitting = true;
      await this.submitReview(this.reviewText);
      this.reviewText = "";
      this.reviewSubmitting = false;
      this.$emit("review-submitted");
      this.close();
    },
    close() {
      this.$router.push(
        this.localePath({
          name: "objects-id",
          params: {
            id: this.$route.params.id
          },
          query: {
            tab: "reviews"
          }
        })
      );
    }
  }
};
</script>

<style lang="scss" scoped>
.popup {
  &__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;

    @media screen and (max-width: 768px) {
      margin: unset;
    }

    .close {
      cursor: pointer;
    }
    .popup__title {
      margin: 0;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: bold;
      font-size: 22px;
      line-height: 26px;
      color: #3a3a3a;

      @media screen and (max-width: 768px) {
        font-size: 18px;
        line-height: 21px;
      }
    }
  }
  &__in {
    @media screen and (max-width: 768px) {
      margin: unset;
      max-width: 100%;
    }
    &.--sm {
      width: 461px;
      height: 473px;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
      border-radius: 10px;
      padding: 33px 25px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;

      @media screen and (max-width: 768px) {
        width: 100%;
        height: 423px;
      }
    }
  }
  &__textarea {
    @media screen and (max-width: 768px) {
      font-size: 16px;
      line-height: 18px;
    }
  }
  &__textarea-text {
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: 300;
    font-size: 16px;
    line-height: 21px;
    color: #828282;
    @media screen and (max-width: 768px) {
      margin: unset;
    }
  }
  &__buttons {
    @media screen and (max-width: 768px) {
      margin: unset;
      flex-direction: unset;
    }
    .timeline__tab-link {
      padding-bottom: 0;
      width: fit-content;
      display: grid;
      grid-column-gap: 20px;
      grid-template-columns: auto auto;
      align-items: center;
    }
    .button {
      background: #2d9cdb;
      border-radius: 10px;
      width: 160px;
      padding: unset;
      height: 50px;
      text-align: center;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 600;
      font-size: 16px;
      line-height: 17px;
      color: #ffffff;
      cursor: pointer;
      &:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }
      @media screen and (max-width: 768px) {
        width: 127px;
        margin-left: 20px;
      }
    }
  }
}
</style>
