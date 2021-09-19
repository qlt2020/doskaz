<template>
  <div class="user-page">
    <ViTop />
    <MainHeader />
    <div class="profile-container">
      <div class="complaint__top profile-top">
        <h2 class="title">
          <img
            :src="require('@/assets/icons/back-arrow.svg')"
            @click="$router.back()"
            class="title-back_arrow"
          />
          {{ $t("profile.title") }}
        </h2>
      </div>
      <div class="user-page__row">
        <div class="user-page__profile">
          <UserProfile />
          <UserLevel />
          <UserTask />
        </div>
        <div class="user-page__tabs">
          <user-page-header />
          <nuxt-child />
        </div>
      </div>
    </div>
    <mobile-menu></mobile-menu>
    <MainFooter />
  </div>
</template>

<script>
import MainHeader from "~/components/MainHeader";
import UserPageHeader from "~/components/user/UserPageHeader.vue";
import UserProfile from "~/components/user/UserProfile";
import UserLevel from "~/components/user/UserLevel";
import UserTask from "~/components/user/UserTask";
import UserObjects from "~/components/user/UserObjects";
import ViTop from "~/components/ViTop";
import MainFooter from "@/components/MainFooter";
import MobileMenu from "@/components/MobileMenu";

export default {
  middleware: ["authenticated"],
  components: {
    MainHeader,
    UserPageHeader,
    UserProfile,
    UserLevel,
    UserTask,
    UserObjects,
    ViTop,
    MainFooter,
    MobileMenu,
  },
  async fetch({ store }) {
    await Promise.all([
      store.dispatch("authentication/loadUser"),
      store.dispatch("awards/load"),
    ]);
  },
};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";

.profile-top {
  @media (max-width: 768px) {
    padding: 14px 15px 15px;
  }
}
.profile-container {
  width: 100%;
  max-width: 1200px;
  padding: 0 15px;
  margin: 0 auto;
  @media (max-width: 768px) {
    padding: 0;
  }
}

.complaint {
  &__top {
    padding: 46px 0 51px;
    .title {
      display: flex;
      align-items: center;
      @media (max-width: 768px) {
        font-size: 28px;
        font-weight: 700;
      }
    }
    @media (max-width: 1024px) {
      padding: 25px 0 35px;
    }
    @media (max-width: 768px) {
      padding: 23px 15px 23px;
    }
  }
}

.user-page {
  .main-header__content {
    border: none;
  }
}

//my Objects tabs

.user-object {
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  justify-content: flex-start;

  &__download {
    line-height: 20px;
    font-size: 14px;
    color: $text;
    padding: 0 0 0 30px;
    background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9Ii0yIDQgMjQgMjQiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+DQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KCS5zdDB7ZmlsbDpub25lO3N0cm9rZTojN0I5NUE3O3N0cm9rZS13aWR0aDoyO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDt9DQo8L3N0eWxlPg0KPHBhdGggY2xhc3M9InN0MCIgZD0iTTIxLDE5Ljd2NC45YzAsMS40LTEuMSwyLjQtMi40LDIuNEgxLjRDMC4xLDI3LTEsMjUuOS0xLDI0LjZ2LTQuOSIvPg0KPHBvbHlsaW5lIGNsYXNzPSJzdDAiIHBvaW50cz0iMy45LDEzLjYgMTAsMTkuNyAxNi4xLDEzLjYgIi8+DQo8bGluZSBjbGFzcz0ic3QwIiB4MT0iMTAiIHkxPSIxOS43IiB4Mj0iMTAiIHkyPSI1Ii8+DQo8L3N2Zz4NCg==")
      no-repeat left top 1px;
    background-size: 20px;
    -webkit-transition: opacity 0.3s;
    -moz-transition: opacity 0.3s;
    -ms-transition: opacity 0.3s;
    -o-transition: opacity 0.3s;
    transition: opacity 0.3s;

    &:hover {
      opacity: 0.7;
    }
  }

  &__text {
    margin: 11px 0 0;
    font-size: 16px;
    line-height: 20px;
    @media all and (max-width: 1023px) {
      font-size: 14px;
    }
  }

  &__image {
    width: 110px;
    position: relative;
    @media all and (max-width: 1023px) {
      width: 80px;
    }
    @media all and (max-width: 768px) {
      width: 60px;
    }
    .image {
      width: 100%;
      height: 80px;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      position: relative;
      z-index: 1;
      @media all and (max-width: 768px) {
        height: 60px;
      }
    }

    .type {
      position: absolute;
      top: 0;
      left: 0;
      z-index: 2;
      width: 30px;
      height: 30px;
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      @media all and (max-width: 768px) {
        width: 20px;
        height: 20px;
      }
    }
  }

  &__description {
    flex: 1 0 auto;
    max-width: calc(100% - 150px);
    margin-left: 40px;
    @media all and (max-width: 1023px) {
      max-width: calc(100% - 100px);
      margin-left: 20px;
    }
    @media all and (max-width: 768px) {
      max-width: calc(100% - 70px);
      margin-left: 10px;
    }
  }

  &__title {
    font-weight: bold;
    font-size: 18px;
    line-height: 20px;
    color: #333333;
    min-height: 40px;
    margin-top: 6px;
    @media all and (max-width: 1023px) {
      font-size: 16px;
      line-height: 20px;
    }

    &.--ticket {
      min-height: 0;
    }
  }

  &__params {
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    justify-content: flex-start;
    flex-wrap: wrap;
    margin-top: 12px;
    position: relative;

    .user-object__download {
      position: absolute;
      right: 0;
      bottom: 0;
    }
  }

  &__param {
    font-size: 14px;
    line-height: 20px;
    color: #5b6067;
    margin-left: 30px;
    @media all and (max-width: 1023px) {
      font-size: 12px;
      line-height: 20px;
    }
    @media all and (max-width: 768px) {
      margin: 0 30px 0 0;
    }
    &-comments {
      @media all and (max-width: 768px) {
        display: inline-block;
        padding: 0 0 0 20px;
        background: url("data:image/svg+xml;base64, PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE1IDEwLjMzMzNDMTUgMTAuNzQ1OSAxNC44MzYxIDExLjE0MTYgMTQuNTQ0NCAxMS40MzMzQzE0LjI1MjcgMTEuNzI1IDEzLjg1NyAxMS44ODg5IDEzLjQ0NDQgMTEuODg4OUg0LjExMTExTDEgMTVWMi41NTU1NkMxIDIuMTQzIDEuMTYzODkgMS43NDczMyAxLjQ1NTYxIDEuNDU1NjFDMS43NDczMyAxLjE2Mzg5IDIuMTQzIDEgMi41NTU1NiAxSDEzLjQ0NDRDMTMuODU3IDEgMTQuMjUyNyAxLjE2Mzg5IDE0LjU0NDQgMS40NTU2MUMxNC44MzYxIDEuNzQ3MzMgMTUgMi4xNDMgMTUgMi41NTU1NlYxMC4zMzMzWiIgc3Ryb2tlPSIjN0I5NUE3IiBzdHJva2Utb3BhY2l0eT0iMC43IiBzdHJva2Utd2lkdGg9IjEuMyIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=")
          left center no-repeat;
        background-size: 14px;
      }
    }
    &-complaint {
      @media all and (max-width: 768px) {
        display: inline-block;
        padding: 0 0 0 20px;
        background: url("data:image/svg+xml;base64, PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNCAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyLjEyMDQgMy4yODU3MUMxMS42MzQ3IDMuMjg1NzEgMTEuMjQwOSAzLjY3OTUxIDExLjI0MDkgNC4xNjUyOVY3QzExLjI0MDkgNy4xNTc3MSAxMS4xMDk1IDcuMjg1NzEgMTAuOTQ3NyA3LjI4NTcxQzEwLjc4NTggNy4yODU3MSAxMC42NTQ1IDcuMTU3NzEgMTAuNjU0NSA3VjIuNDUxQzEwLjY1NDUgMS45NjUyMyAxMC4yNjA3IDEuNTcxNDMgOS43NzQ4OSAxLjU3MTQzVjEuNTcxNDNDOS4yODkxMiAxLjU3MTQzIDguODk1MzIgMS45NjUyMyA4Ljg5NTMyIDIuNDUxVjYuNDI4NTdDOC44OTUzMiA2LjU4NjI5IDguNzYzOTcgNi43MTQyOSA4LjYwMjEzIDYuNzE0MjlDOC40NDAyOSA2LjcxNDI5IDguMzA4OTQgNi41ODYyOSA4LjMwODk0IDYuNDI4NTdWMS44Nzk1N0M4LjMwODk0IDEuMzkzOCA3LjkxNTE0IDEgNy40MjkzNiAxVjFDNi45NDM1OSAxIDYuNTQ5NzkgMS4zOTM4IDYuNTQ5NzkgMS44Nzk1N1Y2LjQyODU3QzYuNTQ5NzkgNi41ODYyOSA2LjQxODQ0IDYuNzE0MjkgNi4yNTY2IDYuNzE0MjlDNi4wOTQ3NSA2LjcxNDI5IDUuOTYzNCA2LjU4NjI5IDUuOTYzNCA2LjQyODU3VjMuMDIyNDNDNS45NjM0IDIuNTM2NjYgNS41Njk2IDIuMTQyODYgNS4wODM4MyAyLjE0Mjg2VjIuMTQyODZDNC41OTgwNSAyLjE0Mjg2IDQuMjA0MjUgMi41MzY2NiA0LjIwNDI1IDMuMDIyNDNWOC45ODU2OEM0LjIwNDI1IDkuMDgxNzkgNC4wODE5MSA5LjEyMjU3IDQuMDI0MjUgOS4wNDU2OEwzLjAxNjgzIDcuNzAyMjlDMi42NjUgNy4xNzM3MSAxLjk3ODM0IDYuOTk2NTcgMS40NzUyMyA3LjI5OTQzQzAuOTczODcxIDcuNjA5MTQgMC44NDk1NTcgOC4yODQ1NyAxLjE5NjcgOC44MTE0M0MxLjE5NjcgOC44MTE0MyAzLjExMTgyIDExLjYzNiAzLjkyODA3IDEyLjg0NTFDNC43NDQzMSAxNC4wNTQzIDYuMDY2NjEgMTUgOC41MzkzOCAxNUMxMi42MzM1IDE1IDEzIDExLjkxODkgMTMgMTFDMTMgMTAuMjg2NyAxMyA2LjAzMTU1IDEzIDQuMTYzNzhDMTMgMy42NzgwMSAxMi42MDYyIDMuMjg1NzEgMTIuMTIwNCAzLjI4NTcxVjMuMjg1NzFaIiBzdHJva2U9IiM3Qjk1QTciIHN0cm9rZS1vcGFjaXR5PSIwLjciIHN0cm9rZS13aWR0aD0iMS4zIi8+Cjwvc3ZnPgo=")
          left center no-repeat;
        background-size: 14px;
      }
    }
    .yes {
      display: none;
      color: $green;
    }

    .no {
      display: none;
      color: $red;
    }

    &:first-child {
      margin-left: 0;
      width: 140px;
    }

    &.--ticket:first-child {
      width: 80px;
    }

    &.yes {
      .yes {
        display: block;
      }
    }

    &.no {
      .no {
        display: block;
      }
    }
  }
}

//tickets tabs
.user-notifications {
  padding: 35px 0 0;
  @media all and (max-width: 1023px) {
    padding: 28px 0 0;
  }
  &__head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    @media screen and (max-width: 768px) {
      display: grid;
      grid-row-gap: 15px;
    }
    .user-profile__mob-title {
      margin: 0;
    }
    &__btn {
      border: none;
      padding: 15px 25px;
      cursor: pointer;
      background: #f2994a;
      border-radius: 10px;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: bold;
      font-size: 16px;
      line-height: 17px;
      color: #ffffff;
      @media screen and (max-width: 768px) {
        font-size: 16px;
      }

      &.subscribe {
        background: #27ae60;
      }
    }
  }
  &__list {
    margin: 30px 0;
    display: grid;
    grid-row-gap: 20px;
    @media screen and (max-width: 768px) {
      margin: 15px 0;
    }
  }
  &__item {
    display: grid;
    grid-row-gap: 15px;
    background: #ffffff;
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
      0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    border-radius: 10px;
    padding: 25px;
  }
  &__btn {
    background: #2d9cdb;
    border-radius: 10px;
    width: 200px;
    height: 50px;
    border-style: none;
    text-align: center;
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: bold;
    font-size: 18px;
    line-height: 19px;
    color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  &__description {
    display: grid;
    font-family: SFProDisplay;
    grid-row-gap: 5px;
    word-break: break-all;
  }
  &__category {
    display: grid;
    align-items: center;
    grid-auto-flow: column;
    width: fit-content;
    grid-column-gap: 10px;
    .cat-img {
      background: #2d9cdb;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
      border-radius: 4px;
      width: 25px;
      height: 25px;
      display: flex;
      align-items: center;
    }
    span {
      font-family: Montserrat;
      font-style: normal;
      font-weight: 500;
      font-size: 14px;
      line-height: 17px;
      color: #717171;
      &::after {
        content: "/";
        margin-left: 10px;
      }
      &:last-child {
        color: #000000;
        &::after {
          content: "";
        }
      }
    }
  }
  &__text {
    font-family: Montserrat;
    font-style: normal;
    font-weight: bold;
    font-size: 20px;
    line-height: 24px;
    color: #535353;
  }
  &__info {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: 500;
    .user-notifications__title {
      display: grid;
      .name {
        font-size: 20px;
        line-height: 24px;
        color: #202020;
      }
      .category {
        font-size: 16px;
        line-height: 21px;
        color: #2d9cdb;
      }

      @media screen and (max-width: 768px) {
        grid-row: 2;
      }
    }
    .user-notifications__date {
      font-size: 14px;
      line-height: 21px;
      color: #2d9cdb;
    }
    @media screen and (max-width: 768px) {
      display: grid;
    }
  }
  &__body {
    display: grid;
    grid-template-columns: 1fr 3fr;
    grid-row-gap: 25px;
    @media screen and (max-width: 768px) {
      grid-template-columns: unset;
    }
    &__item {
      display: grid;
      font-family: SFProDisplay;
      font-style: normal;
      grid-row-gap: 10px;

      &.descrip {
        grid-column-start: 1;
        grid-column-end: 3;
        @media screen and (max-width: 768px) {
          grid-column-start: unset;
          grid-column-end: unset;
        }
      }
    }
    &__label {
      font-weight: 600;
      font-size: 16px;
      line-height: 17px;
      color: #717171;
    }
    &__text {
      font-weight: 500;
      font-size: 18px;
      line-height: 19px;
      color: #202020;

      &.descrip {
        font-weight: 300;
        display: block;
        display: -webkit-box;
        max-height: 8.6em;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
      }
    }
  }
}

//comment-tabs
.user-comments {
  .user-objects__filter {
    margin-bottom: 0;
  }

  padding: 35px 0 0;
  @media all and (max-width: 1023px) {
    padding: 28px 0 0;
  }
  &__list {
    margin: 30px 0;
    display: grid;
    grid-row-gap: 20px;
  }
  &__item {
    display: grid;
    grid-row-gap: 15px;
    background: #ffffff;
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
      0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    border-radius: 10px;
    padding: 25px;
    .user-comments__subtitle {
      font-size: 14px;
      font-weight: 500;
      margin-top: -9px;
      color: #535353;
    }
  }
  &__image {
    width: 115px;
    height: 115px;
    background-size: cover;
    border-radius: 10px;
    @media all and (max-width: 1023px) {
      width: 71px;
      height: 71px;
    }
  }
  &__btn {
    background: #2d9cdb;
    border-radius: 10px;
    width: 200px;
    height: 50px;
    border-style: none;
    text-align: center;
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: bold;
    font-size: 18px;
    line-height: 19px;
    color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;

    &.notifs {
      @media all and (max-width: 768px) {
        grid-row: 3;
      }
    }
  }
  &__description {
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: normal;
    font-size: 18px;
    line-height: 21px;
    color: #202020;
    word-break: break-all;
  }
  &__text {
    font-size: 18px;
    font-weight: 600;
    line-height: 21px;
    color: #202020;
  }
  &__info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: SFProDisplay;
    font-style: normal;
    font-weight: 500;
    .user-comments__title {
      font-size: 20px;
      line-height: 24px;
      color: #202020;
    }
    .user-comments__date {
      font-size: 14px;
      line-height: 21px;
      color: #2d9cdb;
    }
  }
}
</style>
