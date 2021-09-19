<template>
  <div class="user-page__header">
    <div class="menu">
      <div class="menu__content">
        <nuxt-link
          :to="localePath({ name: 'profile-achievements' })"
          class="menu__item"
          active-class="isActive"
        >
          <span>{{ $t("profile.achievements.tabTitle") }}</span>
        </nuxt-link>
        <nuxt-link
          :to="localePath({ name: 'profile-objects' })"
          class="menu__item"
          active-class="isActive"
        >
          <span>{{ $t("profile.objects.tabTitle") }}</span>
        </nuxt-link>
        <nuxt-link
          :to="localePath({ name: 'profile-tickets' })"
          class="menu__item"
          active-class="isActive"
        >
          <span>{{ $t("profile.tickets.tabTitle") }}</span>
        </nuxt-link>
        <nuxt-link
          :to="localePath({ name: 'profile-tasks' })"
          class="menu__item"
          active-class="isActive"
        >
          <span>{{ $t("profile.tasks.tabTitle") }}</span>
        </nuxt-link>
        <nuxt-link
          :to="localePath({ name: 'profile-comments' })"
          class="menu__item"
          active-class="isActive"
        >
          <span>{{ $t("profile.comments.tabTitle") }}</span>
        </nuxt-link>
        <nuxt-link
          :to="localePath({ name: 'profile-notifications' })"
          class="menu__item"
          active-class="isActive"
        >
          <span>{{ $t("profile.notifications.tabTitle") }}</span>
        </nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "UserPageHeader",

};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";

.user-page {
  overflow-x: hidden;

  &__button {
    width: 170px;
    line-height: 50px;
    color: #fff;
    font-size: 14px;
    border: none;
    outline: none;
    background: $blue;
    cursor: pointer;
    -webkit-transition: opacity 0.4s;
    -moz-transition: opacity 0.4s;
    -ms-transition: opacity 0.4s;
    -o-transition: opacity 0.4s;
    transition: opacity 0.4s;
    &:hover {
      opacity: 0.7;
    }
  }

  &__header {
    width: 100%;
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: flex-end;
    color: #202020;
    .menu {
      height: 52px;
      width: 100%;
      box-shadow: 0px 24px 32px rgba(0, 0, 0, 0.04),
        0px 16px 24px rgba(0, 0, 0, 0.04), 0px 4px 8px rgba(0, 0, 0, 0.04),
        0px 0px 1px rgba(0, 0, 0, 0.04);
      border-radius: 8px;
      padding: 0 10px;
      @media all and (max-width: 1100px) {
        box-shadow: none;
        border-radius: 0;
        padding: 0;
        border-bottom: 1px solid #c9c9c9;
      }
      &__content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow-y: hidden;
        overflow-x: scroll;
        max-width: 750px;
        &::-webkit-scrollbar {
          height: 6px;
        }

        &::-webkit-scrollbar-track {
          background: $tr;
          border-radius: 10px;
        }

        &::-webkit-scrollbar-thumb {
          background: transparentize(#c4c4c4, 0.5);
          border-radius: 10px;
        }
        @media (max-width: 1100px) {
          height: 60px;
        }
      }

      &__item {
        margin: 0 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        font-weight: 500;
        font-family: "SFProDisplay";
        line-height: 13px;
        color: #202020;
        height: 52px;
        white-space: nowrap;
        @media all and (max-width: 1100px) {
          /* margin: 2px; */
          height: 42px;
        }
        @media (max-width: 930px) {
          height: 42px;
        }
        @media all and (max-width: 768px) {
          font-size: 16px;
        }
        span {
          color: #202020;
        }
        &.--logout {
          position: absolute;
          right: 0;
          top: 0;
          width: 50px;
          height: 50px;
          display: block;
          padding: 15px 0 0 10px;
          svg {
            display: block;
          }
        }

        &.isActive,
        &:hover {
          border-bottom: 3px solid #2d9cdb;
          span {
            color: #2d9cdb;
          }
        }
      }
    }
  }
  &__row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 80px;
    @media all and (max-width: 768px) {
      display: block;
      margin-bottom: 0;
    }
  }

  &__profile {
    min-width: 370px;
    max-width: 370px;
    position: relative;
    font-family: "SFProDisplay";
    @media all and (max-width: 1200px) {
      min-width: 300px;
      max-width: 300px;
    }
    @media all and (max-width: 930px) {
      min-width: 280px;
      max-width: 280px;
    }
    @media all and (max-width: 768px) {
      min-width: 100%;
      max-width: 280px;
    }
    & > div {
      margin-top: 15px;
      @media all and (max-width: 768px) {
        margin-top: 0;
      }
      &:first-child {
        margin-top: 0;
      }
    }
  }

  &__tabs {
    /* max-width: calc(100% - 427px); */
    width: 100%;
    margin-left: 30px;
    @media all and (max-width: 1200px) {
      margin-left: 10px;
      max-width: calc(100% - 393px);
    }
    @media all and (max-width: 1100px) {
      max-width: calc(100% - 350px);
    }
    @media all and (max-width: 930px) {
      max-width: calc(100% - 310px);
      margin-left: 0;
    }
    @media all and (max-width: 768px) {
      max-width: calc(100% - 35px);
      margin: auto;
    }
  }
}

.user-objects {
  &__head {
    margin-bottom: 20px;
  }
  padding: 35px 0 0;

  &__filter {
    margin-bottom: 55px;
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: flex-start;
    @media all and (max-width: 1023px) {
      margin-bottom: 35px;
    }

    .object-side__button {
      margin: -15px 0 0;
    }

    &.--between {
      justify-content: space-between;
      margin-bottom: 40px;
      @media all and (max-width: 768px) {
        margin-bottom: 20px;
      }
    }

    & > * {
      margin-left: 35px;

      &:first-child {
        margin-left: 0;
      }
    }

    .filter {
      display: grid;
      grid-row-gap: 17px;

      .select {
        height: 60px;
        font-size: 18px;
        &::after {
          top: 26px;
        }
        select {
          @media (max-width: 768px) {
            font-size: 18px;
            width: 161px;
          }
        }
        @media (max-width: 768px) {
          width: 161px;
        }
      }

      &__text {
        font-family: SFProDisplay;
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 19px;
        color: #3a3a3a;
      }

      &__dropdown {
        position: relative;
        bottom: -1px;

        .dropdown {
          position: relative;
          cursor: pointer;

          &__selected {
            font-size: 14px;
            line-height: 20px;
            color: #333333;
            display: flex;
            align-items: center;
            @media all and (max-width: 1023px) {
              font-size: 12px;
              line-height: 18px;
            }

            &:after {
              content: "";
              background-image: url("data:image/svg+xml,%3Csvg width='8' height='4' viewBox='0 0 8 4' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4 4L3.49691e-07 2.54292e-07L8 9.53674e-07L4 4Z' fill='%23333333'/%3E%3C/svg%3E%0A");
              width: 8px;
              height: 4px;
              display: block;
              margin-left: 4px;
            }

            span {
              border-bottom: 1px dashed #333;
            }

            &.opened + .dropdown__list {
              display: block;
            }
          }

          &__list {
            display: none;
            position: absolute;
            top: 100%;
            left: -15px;
            background: #ffffff;
            z-index: 3;
            border: 1px solid #7b95a7;
            padding: 4px 0;
            margin: 6px 0 0;
          }

          &__item {
            font-size: 14px;
            line-height: 30px;
            color: #333333;
            padding: 0 14px;
            -webkit-transition: background 0.3s;
            transition: background 0.3s;
            text-align: left;
            white-space: nowrap;

            &:hover {
              background: #f1f8fc;
            }
          }
        }
      }

      .button {
        margin: -15px 0 0;
        &.--complaint {
          @media all and (max-width: 768px) {
            font-size: 0;
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 50%;
            padding: 0;
            background-position: center;
          }
        }
      }
    }
  }

  &__content {
    margin-bottom: 60px;
    &-tickets {
      margin-bottom: 20px;
    }
    @media all and (max-width: 1023px) {
      margin-bottom: 30px;
    }

    & > * {
      margin-top: 20px;
      @media all and (max-width: 1023px) {
        margin-top: 15px;
      }

      &:first-child {
        margin-top: 0;
      }
    }

    & > .--ticket {
      margin-top: 35px;

      &:first-child {
        margin-top: 0;
      }
      .user-object__image {
        @media all and (max-width: 768px) {
          display: none;
        }
      }
      .user-object__description {
        @media all and (max-width: 768px) {
          width: 100%;
          max-width: 100%;
          margin: 0;
        }
      }
    }
  }

  &__pagination {
  }
  @media all and (max-width: 768px) {
    margin-top: 30px;
    padding: 0;
  }
}
</style>
