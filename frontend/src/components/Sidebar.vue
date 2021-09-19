<template>
  <div class="sidebar" v-bind:class="{ isMinified: isMinified }">
    <MainFilter @open-list="openList" />
    <city-list v-if="cityList" :cities="cities" @close="close"></city-list>

    <!--         <Timeline :posts="posts" :events="events"></Timeline>-->

    <div
      v-if="!cityList"
      class="sidebar__minified"
      v-on:click="sidebarMinify()"
    >
      <svg
        width="10"
        height="16"
        viewBox="0 0 10 16"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          fill-rule="evenodd"
          clip-rule="evenodd"
          d="M7 4.65805e-05L10 0L3 8L10 16H7L0 8L7 4.65805e-05Z"
          fill="black"
        />
      </svg>
    </div>
  </div>
</template>

<script>
import MainFilter from "~/components/MainFilter.vue";
import CityList from "@/components/CityList.vue";
import { sync, get } from "vuex-pathify";

/* import Timeline from "~/components/Timeline.vue"; */

export default {
  name: "Sidebar",
  props: ["posts", "events"],
  data() {
    return {
      isMinified: false,
      /* cityList: false, */
      cities: [],
    };
  },
  computed: {
    cityList: sync("cities/openedList"),
  },
  components: {
    MainFilter,
    CityList,
    /* Timeline */
  },
  methods: {
    openList(event) {
      this.cityList = !this.cityList;
      this.cities = event;
    },
    close() {
      this.cityList = false;
    },
    sidebarMinify() {
      this.isMinified = !this.isMinified;
    },
  },
};
</script>

<style lang="scss">
@import "./../styles/mixins.scss";

.sidebar {
  position: fixed;
  top: 100px;
  left: 25px;
  bottom: 0;
  width: 600px;
  height: 85%;
  background: $tr;
  transform: translateX(0);
  transition: transform 0.3s;
  display: flex;
  gap: 20px;
  /* flex-direction: column; */
  align-items: stretch;
  justify-content: flex-start;
  /* padding-left: 40px; */
  padding-right: 30px;
  z-index: 5;
  @media all and (max-width: 1366px) {
    top: 100px;
    width: 500px;
  }
  @media all and (max-width: 1023px) {
    width: 670px;
    padding-left: 0;
  }
  @media all and (max-width: 768px) {
    width: 100%;
    top: 0;
    left: 0;
    right: 0;
    padding: 0;
    height: fit-content;
  }

  &.isMinified {
    transform: translateX(calc(-100% + 5px));

    .sidebar {
      &__minified {
        svg {
          transform: rotate(180deg);
        }
      }
    }
  }

  &__minified {
    position: absolute;
    top: 15px;
    right: 0;
    width: 30px;
    height: 60px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    background: $white;
    cursor: pointer;
    border-radius: 0px 10px 10px 0px;

    @media all and (max-width: 768px) {
      display: none;
    }

    &:before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      border-left: 1px solid transparentize(#7b95a7, 0.5);
    }

    &:hover {
      svg {
        opacity: 0.7;
      }
    }

    svg {
      transition: transform 0.3s, opacity 0.3s;
      transform: rotate(0deg);
    }
  }

  .main-filter {
    background: $white;
    width: 100%;
    flex: 1 0 auto;
    margin-bottom: 10px;
    border-radius: 10px;
    box-shadow: 0px 16px 24px #00000029;
    @media all and (max-width: 1366px) {
      max-height: calc(100% - 50px);
      /* padding: 15px 0 15px 30px; */
    }

    @media all and (max-width: 768px) {
      width: 100%;
      margin: 0;
      height: 50px;
      max-height: 50px;
      padding: 0;
      border-radius: 0;
    }
    &__header {
      @media all and (max-width: 768px) {
        padding: 0;
        width: 60px;
        height: 50px;
        .main-filter__logo {
          display: none;
        }
        .burger-wrapper {
          width: 60px;
          height: 50px;
          padding: 16px 20px 17px;
        }
      }
    }
    &__mobile-top {
      @media all and (max-width: 768px) {
        height: 50px;
        display: flex;
        flex-direction: row-reverse;
      }
    }
    &__search {
      @media all and (max-width: 768px) {
        padding: 10px 0px 10px 10px;
        width: calc(100% - 60px);
        /* border-right: 1px solid rgba(123, 149, 167, 0.3); */
        .input {
          /* flex-direction: row-reverse; */
          /* margin: 10px; */
          border: none;
          padding: 10px;
          button {
            height: 50px;
            width: 40px;
            svg {
              width: 16px;
              height: 16px;
            }
          }
        }
        .voice-input {
          button {
            border: none;
          }
        }
      }
    }
    &__header {
    }
  }

  .main-timeline {
    background: $white;
    width: 100%;
    flex: 1 0 auto;
    max-height: 500px;
    padding: 30px 0px 34px 40px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: flex-start;

    &__tab-links {
      width: calc(100% - 40px);
      margin-right: 40px;
      border-bottom: 1px solid #7b95a7;
      display: flex;
      flex-direction: row;
      align-items: flex-end;
      justify-content: flex-start;
    }

    &__tab-link {
      font-size: 16px;
      line-height: 20px;
      color: $black;
      display: block;
      position: relative;
      margin-left: 25px;
      padding-bottom: 10px;
      cursor: pointer;
      transition: opacity 0.3s;

      &:first-child {
        margin-left: 0;
      }

      &:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        border-bottom: 3px solid #0f6bf5;
        opacity: 0;
        transition: opacity 0.3s;
      }

      &:hover {
        opacity: 0.7;
      }

      &.isActive {
        font-weight: 700;

        &:after {
          opacity: 1;
        }
      }

      &.main-timeline__tab-link_blog {
        .new {
          position: relative;
          top: -8px;
          font-size: 10px;
          line-height: 20px;
          color: $black;
          font-weight: 400;
        }
      }

      &.main-timeline__tab-link_user {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        align-items: center;

        .avatar {
          width: 30px;
          height: 30px;
          border-radius: 50%;
          display: block;
          margin-right: 10px;
          background-position: center;
          background-size: cover;
          background-repeat: no-repeat;
        }
      }
    }

    &__tabs {
      display: block;
      margin-top: 30px;
      flex: 1 0 auto;
      max-height: calc(100% - 41px);
    }

    &__tab {
      display: none;

      &.isActive {
        display: block;
      }

      &.main-timeline__tab_blog {
        position: relative;
        overflow-x: hidden;
        overflow-y: auto;
        height: 100%;
        padding-right: 30px;

        &::-webkit-scrollbar {
          width: 10px;
        }

        &::-webkit-scrollbar-track {
          background: $tr;
        }

        &::-webkit-scrollbar-thumb {
          background: transparentize(#c4c4c4, 0.5);
        }

        .item {
          display: flex;
          flex-direction: row;
          justify-content: flex-start;
          align-items: flex-start;
          margin-top: 30px;
          transition: opacity 0.3s;

          &:first-child {
            margin-top: 0;
          }

          &:nth-last-child(1) {
            margin-bottom: 30px;
          }

          &:hover {
            opacity: 0.7;
          }

          &.item_link {
            position: relative;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: $white;

            &:hover {
              opacity: 1;
            }

            a {
              margin-left: 180px;
              transition: opacity 0.3s;

              &:hover {
                opacity: 0.7;
              }

              span {
                font-size: 16px;
                line-height: 20px;
                color: $black;
                text-decoration: underline;
              }
            }
          }

          &__img {
            width: 150px;
            height: 110px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            margin-right: 30px;
          }

          &__info {
            display: block;
            flex: 1 0 auto;
            max-width: calc(100% - 180px);
            position: relative;
            top: -8px;
          }

          &__title {
            margin-bottom: 2px;
          }

          &__text {
            margin-bottom: 0px;
          }

          &__date {
            font-size: 14px;
            color: #5b6067;
            margin-bottom: 0px;

            span {
              margin-left: 14px;

              &:first-child {
                margin-left: 0;
              }
            }
          }
        }
      }
    }
  }
}
</style>
