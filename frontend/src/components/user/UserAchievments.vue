<template>
  <div class="user-achievments">
    <div class="user-achievments__achievments">
      <UserAchievment
        v-for="award in awards"
        :key="award.id"
        :text="award.title"
        :award-type="award.type"
      />
    </div>

    <div class="user-achievments__events">
      <div class="title">
        <span>{{ $t("profile.achievements.title") }}</span>
      </div>
         <div class="list">
            <user-event v-for="event in events" :key="event.id" :event="event"/>
          </div>
    </div>
  </div>
</template>

<script>
import UserAchievment from "./UserAchievment";
import UserEvent from "../userEvents/UserEvent";

export default {
  components: {
    UserEvent,
    UserAchievment
  },
  props: ["events", "awards"]
};
</script>

<style lang="scss">
.user-achievments {
  padding-top: 40px;
  @media all and (max-width: 1023px) {
    padding-top: 28px;
  }
  @media all and (max-width: 768px) {
    padding: 28px 0;
  }
  &__achievments {
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    justify-content: flex-start;
    flex-wrap: wrap;
    @media all and (max-width: 768px) {
      margin: 24px 0 0;
    }
    & > * {
      width: 33.33%;
      padding-right: 40px;
      margin-bottom: 40px;
      @media all and (max-width: 1023px) {
        width: 50%;
        padding-right: 30px;
        margin-bottom: 30px;
      }
      @media all and (max-width: 520px) {
        width: 100%;
        padding-right: 0;
      }
    }
  }

  &__events {
    padding-top: 5px;

    .title {
      font-weight: 600;
      font-size: 24px;
      line-height: 30px;
      color: #333333;
      margin-bottom: 26px;
      @media all and (max-width: 1023px) {
        margin-bottom: 28px;
      }
      @media all and (max-width: 1023px) {
        font-size: 18px;
        line-height: 20px;
      }
      @media all and (max-width: 768px) {
        font-size: 24px;
      }
    }

    .list {
      &__item {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        margin-top: 49px;
        position: relative;
        box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
          0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
        border-radius: 10px;
        padding: 22px;
        &-wrap {
          display: flex;
          align-items: center;
          font-family: "SFProDisplay";
        }
        @media all and (max-width: 768px) {
          display: block;
          padding: 11px 15px 19px;
          margin: 15px 0 0;
        }

        &:first-child {
          margin-top: 0;
        }
      }

      &__date {
        font-size: 14px;
        font-weight: 500;
        line-height: 20px;
        font-family: "SFProDisplay";
        margin-bottom: 17px;
        span {
          color: #2d9cdb;
        }
      }

      &__icon {
        min-width: 45px;
        min-height: 45px;
        margin-right: 16px;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;

        &.list__icon_level {
          border-radius: 50%;
          background: #2d9cdb;
          display: flex;
          justify-content: center;
          align-items: center;
          span {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
          }
        }

        &.list__icon_achievment {
          img {
            width: 100%;
            height: auto;
            &.black,
            &.white {
              display: none;
            }
          }
        }
      }

      &__text {
        display: block;
        font-weight: 400;
        font-size: 18px;
        line-height: 20px;
        color: #202020;
        @media all and (max-width: 768px) {
          max-width: 100%;
          width: 100%;
        }
        span {
          font-weight: bold;
          color: #333;
        }
        a {
          font-weight: bold;
          color: #333;
          transition: opacity 0.3s;
          &:hover {
            opacity: 0.7;
          }
        }
      }
    }
  }
}
</style>
