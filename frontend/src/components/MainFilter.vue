<template>
  <div class="main-filter">
    <div class="main-filter__mobile-top">
      <div class="main-filter__header">
        <div v-if="!mobileMenu" @click="mainPageMobOpened">
          <img :src="require(`@/assets/icons/menu.svg`)" alt />
        </div>
        <div v-else @click="mainPageMobOpened">
          <img :src="require(`@/assets/icons/close_h.svg`)" alt />
        </div>
      </div>
      <city-selector class="city-selector" @open-list="emitCities" />
      <div class="main-filter__title --desktop">
        <nuxt-link
          :to="localePath({ name: 'index' })"
          class="main-filter__logo"
        >
          <img :src="require(`@/assets/img/logo-new-white.png`)" alt />
        </nuxt-link>
        <button
          class="button button_white"
          type="button"
          @click="popupOpen = true"
        >
          <img
            :src="require(`~/assets/icons/categories/${category}_blue.svg`)"
            v-if="category"
          />
          {{ $t("startCategory.changeCategoryTitle") }}
        </button>
      </div>
      <div class="main-filter__search" v-click-outside="closeSearch">
        <form class="input grey-bg">
          <svg
            width="25"
            height="18"
            viewBox="0 0 25 18"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <ellipse
              cx="7.82491"
              cy="8.82491"
              rx="6.74142"
              ry="6.74142"
              stroke="#130F26"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <path
              d="M12.5137 13.8638L15.1567 16.5"
              stroke="#130F26"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
          </svg>
          <input
            class="search-input"
            type="text"
            :placeholder="$t('mainFilter.searchPlaceholder')"
            @focus="searchFocused = true"
            @input="search({ query: $event.target.value, cityId })"
            v-model="query"
          />
          <svg
            class="mobile-filter"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            @click="openAvailabilityFilter"
          >
            <g id="Iconly/Light-Outline/Filter">
              <g id="Group 10">
                <path
                  id="Fill 1"
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M10.0801 18.5928H3.77905C3.36505 18.5928 3.02905 18.2568 3.02905 17.8428C3.02905 17.4288 3.36505 17.0928 3.77905 17.0928H10.0801C10.4941 17.0928 10.8301 17.4288 10.8301 17.8428C10.8301 18.2568 10.4941 18.5928 10.0801 18.5928Z"
                  fill="black"
                />
                <path
                  id="Fill 3"
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M19.1909 8.90039H12.8909C12.4769 8.90039 12.1409 8.56439 12.1409 8.15039C12.1409 7.73639 12.4769 7.40039 12.8909 7.40039H19.1909C19.6049 7.40039 19.9409 7.73639 19.9409 8.15039C19.9409 8.56439 19.6049 8.90039 19.1909 8.90039Z"
                  fill="black"
                />
                <g id="Group 7">
                  <mask
                    id="mask0"
                    mask-type="alpha"
                    maskUnits="userSpaceOnUse"
                    x="3"
                    y="5"
                    width="7"
                    height="7"
                  >
                    <path
                      id="Clip 6"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M3 5.00037H9.2258V11.192H3V5.00037Z"
                      fill="white"
                    />
                  </mask>
                  <g mask="url(#mask0)">
                    <path
                      id="Fill 5"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M6.11276 6.5C5.22376 6.5 4.49976 7.216 4.49976 8.097C4.49976 8.977 5.22376 9.692 6.11276 9.692C7.00276 9.692 7.72576 8.977 7.72576 8.097C7.72576 7.216 7.00276 6.5 6.11276 6.5ZM6.11276 11.192C4.39676 11.192 2.99976 9.804 2.99976 8.097C2.99976 6.39 4.39676 5 6.11276 5C7.82976 5 9.22576 6.39 9.22576 8.097C9.22576 9.804 7.82976 11.192 6.11276 11.192Z"
                      fill="black"
                    />
                  </g>
                </g>
                <path
                  id="Fill 8"
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M17.3877 16.208C16.4977 16.208 15.7737 16.924 15.7737 17.804C15.7737 18.685 16.4977 19.4 17.3877 19.4C18.2767 19.4 18.9997 18.685 18.9997 17.804C18.9997 16.924 18.2767 16.208 17.3877 16.208ZM17.3877 20.9C15.6707 20.9 14.2737 19.511 14.2737 17.804C14.2737 16.097 15.6707 14.708 17.3877 14.708C19.1037 14.708 20.4997 16.097 20.4997 17.804C20.4997 19.511 19.1037 20.9 17.3877 20.9Z"
                  fill="black"
                />
              </g>
            </g>
          </svg>
        </form>
        <!--  <div class="voice-input">
                    <button>
                        <svg
                                width="18"
                                height="24"
                                viewBox="0 0 18 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                        >
                            <g clip-path="url(#clip0)">
                                <path
                                        d="M8.90137 18.0282V22.5352"
                                        stroke="#5B6067"
                                        stroke-width="2.5"
                                        stroke-miterlimit="10"
                                />
                                <path
                                        d="M5.40845 22.5352H12.3944"
                                        stroke="#5B6067"
                                        stroke-width="2.5"
                                        stroke-miterlimit="10"
                                        stroke-linecap="round"
                                />
                                <path
                                        d="M16.3381 10.9296C16.3381 14.9859 12.9578 18.3662 8.90146 18.3662C4.84513 18.3662 1.46484 14.9859 1.46484 10.9296"
                                        stroke="#5B6067"
                                        stroke-width="2.5"
                                        stroke-miterlimit="10"
                                        stroke-linecap="round"
                                />
                                <path
                                        d="M8.90143 14.9859C6.64791 14.9859 4.84509 13.1831 4.84509 10.9296V4.16901C4.84509 1.91549 6.64791 0.112671 8.90143 0.112671C11.155 0.112671 12.9578 1.91549 12.9578 4.16901V10.9296C12.9578 13.1831 11.155 14.9859 8.90143 14.9859Z"
                                        fill="#5B6067"
                                />
                            </g>
                            <defs>
                                <clipPath id="clip0">
                                    <rect width="17.6901" height="24" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </div> -->
        <div class="search-sub" v-if="searchHighlights.length && searchFocused">
          <nuxt-link
            :to="
              localePath({
                name: 'objects-id',
                params: { id: item.id },
                query: { zoom: 19 },
              })
            "
            class="search-sub__item"
            v-for="item in searchHighlights"
            :key="item.id"
          >
            <div class="search-sub__icon">
              <i class="fa" :class="item.icon"></i>
            </div>
            <div class="search-sub__info">
              <span class="search-sub__title"
                >{{ item.title }}, {{ item.category }}</span
              >
              <span class="search-sub__address">{{ item.address }}</span>
            </div>
          </nuxt-link>
        </div>
      </div>
    </div>
    <CategorySelector />
    <AvailabilityFilter
      v-if="filterOpen"
      @close="openAvailabilityFilter()"
    ></AvailabilityFilter>
    <div class="line"></div>
    <div class="action_buttons">
      <div class="button b_green">
        <img src="@/assets/icons/plus.svg" />
        <nuxt-link
          :to="localePath({ name: 'objects-add' })"
          style="color: #ffffff"
          >{{ $t("index.addObjectLink") }}</nuxt-link
        >
      </div>
      <div style="width:10px"></div>
      <div class="button b_red" style="width: unset">
        <img src="@/assets/icons/chat_plus.svg" />
        <nuxt-link
          :to="
            localePath({
              name: 'complaint',
              query: { objectId: $route.params.id },
            })
          "
          style="color: #ffffff"
        >
          {{ $t("objects.makeComplaint") }}
        </nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>
import CategorySelector from "./../components/CategorySelector";
import LangSelect from "./../components/LangSelect";
import throttle from "lodash/throttle";
import { get, call, sync } from "vuex-pathify";
import CitySelector from "./CitySelector";
import ClickOutside from "vue-click-outside";
import AvailabilityFilter from "@/components/modals/AvailabilityFilter";

export default {
  data() {
    return {
      searchFocused: false,
      opened: false,
      filterOpen: false,
    };
  },
  directives: {
    ClickOutside,
  },
  components: {
    CitySelector,
    CategorySelector,
    LangSelect,
    AvailabilityFilter,
  },
  computed: {
    cityId: get("settings/cityId"),
    searchHighlights: get("map/searchHighlights"),
    query: sync("map/search"),
    category: sync("disabilitiesCategorySettings/category"),
    popupOpen: sync("disabilitiesCategorySettings/popupOpen"),
    mobileMenu: get("settings/mobileMenu"),
  },
  methods: {
    mainPageMobOpened: call("settings/menuOpen"),
    emitCities(event) {
      this.$emit("open-list", event);
    },
    closeSearch() {
      this.searchFocused = false;
    },
    search: throttle(call("map/search"), 1000),
    /* mainPageMobOpened() {
      this.filterOpen = false;
      this.$nuxt.$emit("mainPageMobOpened");
      this.opened = !this.opened;
    }, */
    openAvailabilityFilter() {
      this.filterOpen = !this.filterOpen;
    },
    enableVisualImpairedMode: call("visualImpairedModeSettings/enable"),
  },
};
</script>

<style lang="scss">
@import "./../styles/mixins.scss";

.search-sub {
  position: absolute;
  left: 0;
  top: 80px;
  border: 1px solid $stroke;
  border-top: none;
  background: #ffffff;
  width: 500px;
  padding: 8px 0;
  z-index: 3;
  @media all and (max-width: 768px) {
    top: 50px;
    width: calc(100% + 1px);
    border-color: rgba(123, 149, 167, 0.3);
  }
  &__item {
    height: 50px;
    width: 100%;
    padding: 10px 20px;
    display: flex;
  }
  &__icon {
    width: 20px;
    i {
      color: $stroke;
      opacity: 0.5;
      font-size: 12px;
    }
  }
  &__info {
    width: calc(100% - 20px);
  }
  &__title {
    font-size: 14px;
    line-height: 18px;
    display: block;
    white-space: nowrap;
    overflow: hidden;
  }
  &__address {
    font-size: 12px;
    line-height: 18px;
    opacity: 0.9;
    display: block;
    white-space: nowrap;
    overflow: hidden;
  }
}

.main-filter {
  background: $white;
  width: 100%;
  flex: 1 0 auto;
  margin-bottom: 10px;
  padding: 25px;
  max-height: calc(100% - 100px);

  &__header {
    display: flex;
    align-items: center;
    padding-right: 40px;
    justify-content: center;
    div {
      display: none;
      @media all and (max-width: 1023px) {
        display: block;
      }
    }
    .burger {
      &-wrapper {
        display: none;
        @media all and (max-width: 1023px) {
          display: block;
        }
      }
    }
  }

  &__language {
    &.--desktop {
      @media all and (max-width: 1023px) {
        display: none;
      }
    }
  }

  &__menu {
    display: flex;
    justify-content: flex-start;
    align-items: center;

    &.--desktop {
      @media all and (max-width: 1023px) {
        display: none;
      }
    }

    a {
      font-size: 14px;
      line-height: 20px;
      color: #333333;
      font-weight: 500;
      transition: opacity 0.3s;
      margin-right: 20px;

      &:last-child {
        margin-right: 0;
      }

      &:hover {
        opacity: 0.7;
      }
      @media screen and (max-width: 1366px) {
        margin-right: 14px;
      }
    }
  }

  &__visual {
    margin: 0 15px 0 auto;
    cursor: pointer;
    transition: opacity 0.3s;

    &:hover {
      opacity: 0.7;
    }

    @media screen and (max-width: 1366px) {
      margin-left: 25px;
      justify-content: center;
    }

    &.--desktop {
      @media all and (max-width: 1023px) {
        display: none;
      }
    }
  }

  &__title {
    padding-top: 20px;
    /* padding-right: 25px; */
    display: flex;
    justify-content: space-between;
    align-items: center;
    @media all and (max-width: 1366px) {
      /* padding-right: 30px; */
    }
    &.--desktop {
      @media all and (max-width: 1023px) {
        display: none;
      }
      .button_white {
        display: flex;
        background: #ffffff;
        border: 2px solid #2d9cdb;
        border-radius: 10px;
        color: #333333;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        font-weight: 500;
      }
    }
  }

  &__logo {
    display: block;
    img {
      width: 110px;
      height: auto;
    }
    &.--mob {
      display: none;
      img {
        width: 100px;
        height: auto;
      }
      @media all and (max-width: 1023px) {
        display: block;
      }
    }
  }

  &__geo {
    /* margin-bottom: 4px; */
    position: relative;
    z-index: 2;

    &-city {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: flex-start;
      cursor: pointer;
      transition: opacity 0.3s;
      min-width: auto;
      &:hover {
        opacity: 0.7;
      }

      span {
        font-size: 14px;
        font-weight: 600;
        font-style: normal;
        color: $blue;
        margin-right: 10px;
      }

      svg {
        margin-right: 7px;
      }
    }

    &-sub {
      margin: 16px 0 0;
      position: absolute;
      border: 1px solid $stroke;
      left: 0;
      top: 100%;
      background: #ffffff;
      display: none;
      @media all and (max-width: 768px) {
        display: flex;
        position: fixed;
        top: auto;
        bottom: auto;
        right: 0;
        left: 0;
        border-radius: 10px;
        border: none;
        padding: 15px;
        margin: 25px;
        box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.062);
      }
    }

    &-list {
      padding: 15px 0;
      max-height: 480px;
      overflow-x: hidden;
      overflow-y: auto;
      @media all and (max-width: 768px) {
        padding: 8px 0;
        width: 100%;
      }

      &::-webkit-scrollbar {
        width: 10px;
      }

      &::-webkit-scrollbar-track {
        background: $tr;
      }

      &::-webkit-scrollbar-thumb {
        background: transparentize(#c4c4c4, 0.5);
      }
    }

    &-item {
      display: block;
      cursor: pointer;
      font-size: 16px;
      line-height: 30px;
      padding: 0 23px 0 20px;
      min-width: 260px;
      white-space: nowrap;
      background: transparent;
      transition: background 0.3s;

      @media all and (max-width: 1023px) {
        width: 100%;
        padding: 10px;
        position: relative;
        font-weight: 500;
        font-size: 16px;
        &:after {
          content: "";
          width: 100%;
          height: 1px;
          position: absolute;
          bottom: 0px;
          left: 0;
          margin: 5px;
          background: rgba(233, 233, 233, 0.5);
        }
        &:last-child {
          margin-bottom: 0;
          &:after {
            display: none;
          }
        }
      }
      &__title {
        font-size: 14px;
        line-height: 20px;
        color: $stroke;
        margin: 0 0 4px;
        display: block;
      }

      &.selected {
        color: #2d9cdb;
        i {
          font-size: 10px;
          color: #2d9cdb;
        }
      }
      &:hover {
        background: $light-gray;
        font-weight: 700;
      }
    }
    .modal-bd {
      @media all and (max-width: 991px) {
        position: fixed;
        bottom: 0;
        top: 0;
        left: 0;
        background-color: #00000085;
        width: 100%;
        z-index: 3;
      }
    }
  }

  &__search {
    padding-top: 30px;
    padding-bottom: 30px;
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
    position: relative;
    @media all and (max-width: 1366px) {
      /* padding-right: 30px; */
      padding-top: 24px;
      padding-bottom: 24px;
    }
    .search-input {
      border-radius: 10px;
    }
    .input {
      /* margin-right: 10px; */
      @media all and (max-width: 1366px) {
        height: 40px;
      }
      .mobile-filter {
        display: none;
        @media all and (max-width: 991px) {
          display: block;
        }
      }
      input {
        width: calc(100% - 40px);
      }
      button {
        svg {
          @media all and (max-width: 1366px) {
            width: 20px;
            height: 20px;
          }
        }
      }
      &.grey-bg {
        background: #f2f2f2;
      }
    }

    .voice-input {
      display: block;

      button {
        border: 1px solid #7b95a7;
        height: 50px;
        width: 50px;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        background: $tr;
        cursor: pointer;
        @media all and (max-width: 1366px) {
          height: 40px;
          width: 40px;
        }
      }
    }
  }
  .action_buttons {
    @media screen and (max-width: 991px) {
      display: none;
    }
    display: flex;
    justify-content: space-around;
    .b_green {
      display: flex;
      align-items: center;
      justify-content: space-evenly;
      border-radius: 10px;
      background-color: #27ae60;
      padding: 0px 10px;
    }
    .b_red {
      display: flex;
      align-items: center;
      justify-content: space-evenly;
      border-radius: 10px;
      background-color: #eb5757;
      padding: 0px 10px;
    }
  }
  .line {
    border: 1px solid #d6dadc;
    margin-bottom: 15px;
    @media all and (max-width: 768px) {
      border: none;
    }
  }
  .city-selector {
    @media screen and (max-width: 991px) {
      display: none;
    }
  }
}
</style>
