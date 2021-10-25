<template>
  <div class="main-page" :class="{ opened: mobileOpened }">
    <IntroForm />
    <div class="main-page__map">
      <client-only>
        <MainMap :location="location" />
      </client-only>
    </div>

    <div class="main-page__options --desktop">
      <div class="head-links">
        <div class="main-filter__menu --desktop">
          <nuxt-link :to="localePath({ name: 'help' })"
            ><span>{{ $t("mainMenu.help") }}</span></nuxt-link
          >
          <nuxt-link :to="localePath({ name: 'about' })"
            ><span>{{ $t("mainMenu.about") }}</span></nuxt-link
          >
          <nuxt-link :to="localePath({ name: 'blog-category' })"
            ><span>{{ $t("mainMenu.blog") }}</span></nuxt-link
          >
          <nuxt-link :to="localePath({ name: 'contacts' })"
            ><span>{{ $t("mainMenu.contacts") }}</span></nuxt-link
          >
        </div>
        <div
          class="main-filter__visual --desktop"
          @click="enableVisualImpairedMode"
        >
          <svg
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M8.09756 12C8.09756 14.1333 9.8439 15.8691 12 15.8691C14.1463 15.8691 15.8927 14.1333 15.8927 12C15.8927 9.85697 14.1463 8.12121 12 8.12121C9.8439 8.12121 8.09756 9.85697 8.09756 12ZM17.7366 6.04606C19.4439 7.36485 20.8976 9.29455 21.9415 11.7091C22.0195 11.8933 22.0195 12.1067 21.9415 12.2812C19.8537 17.1103 16.1366 20 12 20H11.9902C7.86341 20 4.14634 17.1103 2.05854 12.2812C1.98049 12.1067 1.98049 11.8933 2.05854 11.7091C4.14634 6.88 7.86341 4 11.9902 4H12C14.0683 4 16.0293 4.71758 17.7366 6.04606ZM12.0012 14.4124C13.3378 14.4124 14.4304 13.3264 14.4304 11.9979C14.4304 10.6597 13.3378 9.57362 12.0012 9.57362C11.8841 9.57362 11.767 9.58332 11.6597 9.60272C11.6207 10.6694 10.7426 11.5227 9.65971 11.5227H9.61093C9.58166 11.6779 9.56215 11.833 9.56215 11.9979C9.56215 13.3264 10.6548 14.4124 12.0012 14.4124Z"
              fill="#3A3A3A"
            />
          </svg>
        </div>
        <LangSelect />
      </div>
      <div class="head-buttons">
        <div class="availability" :class="{ opened: availabilityToggle }">
          <span>{{ $t("showAvailability") }}</span>
          <i
            class="fas fa-check available"
            @click="toggleAccessibilityLevel('full_accessible')"
            :class="{
              isActive: accessibilityLevels.includes('full_accessible'),
            }"
          >
          </i>
          <i
            class="fas fa-minus partial-access"
            @click="toggleAccessibilityLevel('partial_accessible')"
            :class="{
              isActive: accessibilityLevels.includes('partial_accessible'),
            }"
          >
          </i>
          <i
            class="fas fa-times not-available"
            @click="toggleAccessibilityLevel('not_accessible')"
            :class="{
              isActive: accessibilityLevels.includes('not_accessible'),
            }"
          >
          </i>
        </div>
        <nuxt-link
          class="head-buttons-full_name"
          :to="localePath({ name: 'profile' })"
          v-if="isAuthenticated"
        >
          <div class="sign-button">
            <div v-if="user.avatar" class="head-buttons-avatar">
              <img :src="user.avatar" alt="avatar" />
            </div>
            {{ name || $t('mainMenu.myProfile') }}
          </div>
        </nuxt-link>
        <nuxt-link v-else :to="localePath({ name: 'login' })">
          <div class="sign-button sign-button-h">
            {{ $t("login.linkTitle") }}
            <img :src="require(`@/assets/icons/sign_in_circle.svg`)" />
          </div>
        </nuxt-link>
      </div>
    </div>
    <client-only>
      <StartCategoryForm @showDetectModal="showDetectModal" />
      <IsParticipantModal
        v-if="modal"
        @close="modal = false"
        @showCategories="selectCategoryModalVisible = true"
        @updateUserCategory="updateUserCategory"
      />
      <SelectCategoryModal
        v-if="selectCategoryModalVisible"
        @close="selectCategoryModalVisible = false"
        @updateUserCategory="updateUserCategory"
      />
      <SubscribeNotifModal
        v-if="subscribeNotifModalVisible"
        @close="subscribeNotifModalVisible = false"
        @showNextModal="subscribeNotifDoneModalVisible = true"
      />
      <SubscribeNotifDoneModal
        v-if="subscribeNotifDoneModalVisible"
        @close="subscribeNotifDoneModalVisible = false"
      />
      <SelectObjectTypeModal
        @close="openSelectTypeObject"
        v-if="objectTypeSelect"
      />
      <detect-location
        v-if="detectModal"
        @close="closeDetectModal"
        @setLocation="setLocation"
      />
    </client-only>

    <nuxt />
    <mobile-menu></mobile-menu>
    <div class="btn-change-type" v-if="!mobileOpened && !objectTypeSelect">
      <div class="button_wrap">
        <button @click="openSelectTypeObject" class="button">
          {{ $t("selectObjectType") }}
        </button>
      </div>
      <div class="stat_button-wrap">
        <StatisticsBtn 
          :page="'statisticsTotal'"
          :class="'btn_left'"
          :title="'Общая статистика'"
          :style="'margin-right: 5px'"
        />
        <StatisticsBtn
          :page="'statisticsAccess'"
          :class="'btn_right'"
          :title="'Статистика по доступности объектов'"
        />
      </div>
    </div>
  </div>
</template>

<script>
import IntroForm from "./../components/IntroForm.vue";
import StartCategoryForm from "./../components/StartCategoryForm.vue";
import ObjectModal from "./../components/ObjectModal.vue";
import MainMap from "./../components/MainMap.vue";
import LoginForm from "../components/LoginForm";
import LangSelect from "./../components/LangSelect";
import CitySelector from "./../components/CitySelector";
import { sync, call, get } from "vuex-pathify";
import Username from "../components/Username";
import IsParticipantModal from "../components/modals/IsParticipantModal";
import SelectCategoryModal from "../components/modals/SelectCategoryModal";
import SubscribeNotifModal from "../components/modals/SubscribeNotifModal";
import SubscribeNotifDoneModal from "../components/modals/SubscribeNotifDoneModal";
import SelectObjectTypeModal from "@/components/modals/SelectObjectTypeModal";
import DetectLocation from "@/components/modals/AutoDetectLocation";
import MobileMenu from "@/components/MobileMenu";
import StatisticsBtn from "~/components/statistics/StatisticsBtn";

export default {
  data() {
    return {
      mobileOpened: false,
      availabilityToggle: false,
      isParticipantModalVisible: false,
      selectCategoryModalVisible: false,
      subscribeNotifModalVisible: false,
      subscribeNotifDoneModalVisible: false,
      objectTypeSelect: false,
      location: [],
      detectModal: false,
    };
  },
  components: {
    Username,
    LoginForm,
    IntroForm,
    StartCategoryForm,
    ObjectModal,
    MainMap,
    LangSelect,
    CitySelector,
    IsParticipantModal,
    SelectCategoryModal,
    SubscribeNotifModal,
    SubscribeNotifDoneModal,
    SelectObjectTypeModal,
    DetectLocation,
    MobileMenu,
    StatisticsBtn,
  },
  computed: {
    currentCategory: get("disabilitiesCategorySettings/currentCategory"),
    popupOpen: sync("disabilitiesCategorySettings/popupOpen"),
    category: sync("disabilitiesCategorySettings/category"),
    user: sync("authentication/user"),
    name: get("authentication/name"),
    modal: sync("authentication/modal"),
    isCitySelected: get("settings/citySelected"),
    ...sync("map", ["accessibilityLevels"]),
    isAuthenticated() {
      return !!this.user;
    },
  },
  created() {
    this.$nuxt.$on("mainPageMobOpened", this.mobileOpenedTrue);
  },
  beforeDestroy() {
    this.$nuxt.$off("mainPageMobOpened");
  },
  mounted() {
    if (this.$route.query.cat == "null") {
      // console.log(this.$router);
      this.selectCategory("hearing");
      this.$router.replace({ ...this.$route, query: {} });
    }
  },
  methods: {
    showDetectModal() {
      setTimeout(() => {
        if (!this.isCitySelected) {
          this.detectModal = true;
        }
      }, 100);
    },
    setLocation(event) {
      this.location = event;
    },
    closeDetectModal() {
      this.detectModal = false;
    },
    mobileOpenedTrue: function(count) {
      this.mobileOpened = !this.mobileOpened;
    },
    mobileOpenedFalse: function(count) {
      this.mobileOpened = false;
    },
    ...call("disabilitiesCategorySettings", ["selectCategory", "init"]),
    enableVisualImpairedMode: call("visualImpairedModeSettings/enable"),
    ...call("map", ["toggleCategory", "toggleAccessibilityLevel"]),
    async updateUserCategory(category) {
      var newUser = {
        ...this.user,
      };
      newUser.category = category;
      var result = await this.$store.dispatch(
        "authentication/updateProfile",
        newUser
      );
      if (category !== "") {
        this.subscribeNotifModalVisible = true;
      }
    },
    openSelectTypeObject() {
      this.objectTypeSelect = !this.objectTypeSelect;
    },
  },
  head() {
    return {
      title: this.$t("meta.title"),
      meta: [
        {
          hid: "description",
          name: "description",
          content: this.$t("meta.description"),
        },
        {
          hid: "keywords",
          name: "keywords",
          content: this.$t("meta.keywords"),
        },
      ],
    };
  },
};
</script>

<style lang="scss">
@import "./../styles/mixins.scss";

.main-page {
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: #e5e5e5;
  overflow: hidden;
  &.opened {
    .main-page__mobile {
      display: block;
    }
  }
  &__mobile {
    /* display: none; */
    position: fixed;
    z-index: 11;
    left: 0;
    top: 50px;
    bottom: 0;
    right: 0;
    /* background: rgba(0,0,0,.5); */
    .main-filter__logo {
      img {
        width: 137px;
        height: auto;
      }
    }
    .main-header__language {
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 53px;
      border-top: 1px solid rgba(123, 149, 167, 0.3);
    }
    .lang-select {
      padding: 0 40px;
      @media all and (max-width: 768px) {
        padding: 0 20px;
      }
    }
    &-close {
      cursor: pointer;
      width: 36px;
      height: 36px;
      justify-content: center;
      align-items: center;
      display: flex;
      margin: 0 -11px 0 0;
      svg {
        width: 14px;
        height: 14px;
      }
    }
    &-right {
      position: absolute;
      left: 350px;
      top: 0;
      right: 0;
      bottom: 0;
      cursor: pointer;
      @media all and (max-width: 768px) {
        left: 0;
        right: auto;
        width: calc(100% - 270px);
      }
    }
    &-left {
      width: 350px;
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      background: #ffffff;
      overflow-y: auto;
      right: -400px;
      @media all and (max-width: 768px) {
        width: 270px;
        left: auto;
        top: 0;
      }
      &.opened {
        right: 0px;
        transition: 0.5s;
      }
    }
    &-in {
      position: relative;
      width: 100%;
      min-height: 100%;
      /* padding: 0 0 64px; */
    }
    &-item {
      display: flex;
      padding: 25px 40px;
      justify-content: space-between;
      align-items: center;
      /* border-top: 1px solid rgba(123, 149, 167, 0.3);
      &:first-child {
        border: none;
      } */
      @media all and (max-width: 768px) {
        padding: 20px;
      }
      &.--md {
        padding: 15px 40px;
        @media all and (max-width: 768px) {
          padding: 15px 20px;
        }
      }
      .main-filter {
        &__menu {
          display: block;
          width: 100%;
          a {
            display: block;
            margin: 20px 0 0;
            &:first-child {
              margin: 0;
            }
          }
          .button {
            line-height: 1;
            width: 100%;
            margin: 20px 0 0;
            display: flex;
            justify-content: center;
            cursor: pointer;
            /* background: transparent; */
            padding: 10px 10px;
            gap: 10px;
            span {
              font-style: normal;
              font-weight: 500;
              font-size: 16px;
            }
            img,
           /*  svg {
              width: 25px;
              height: 25px;
              padding: 4px;
            } */
            &_blue {
              background: $blue;
              img,
              svg {
                background: $blue;
              }
            }
            &_green {
              background: $green;
              img,
              svg {
                background: $green;
              }
            }
            &_red {
              background: $red;
              img,
              svg {
                background: $red;
              }
            }
            &_white {
              background: $white;
              img,
              svg {
                background: $white;
              }
            }
          }
          .menu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            fill: #3a3a3a;
            stroke: #3a3a3a;
            &:hover {
              fill: #2d9cdb;
              stroke: #2d9cdb;
              opacity: 1;
            }
            span {
              &:hover {
                color: #2d9cdb;
              }
            }
          }
        }
      }
      .mobile-city-selector {
        @media screen and (max-width: 991px) {
          display: block;
        }
      }
    }
  }
  &__map {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    z-index: 1;
  }
  &__options {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    position: absolute;
    top: 20px;
    left: 25px;
    right: 20px;
    z-index: 10;
    &.--desktop {
      @media all and (max-width: 1023px) {
        display: none;
      }
    }
    .head-links {
      display: flex;
      align-items: center;
      background-color: $white;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
      width: 570px;
      @media screen and (max-width: 1366px) {
        width: auto;
        justify-content: center;
      }
    }
    .head-buttons {
      display: flex;
      gap: 20px;
      &-full_name {
        display: flex;
        .head-buttons-avatar {
          height: 100%;
          box-shadow: none;
          padding: 0;
          margin-right: 10px;
          img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
          }
        }
      }

      div {
        background-color: #ffffff;
        box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
          0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
        display: flex;
        padding: 10px;
        border-radius: 10px;
        align-items: center;
      }
      .availability {
        gap: 10px;
        span {
          font-family: "Montserrat";
          font-weight: 500;
          font-size: 14px;
        }
        i {
          cursor: pointer;
        }
        .available {
          display: flex;
          width: 40px;
          height: 40px;
          align-items: center;
          justify-content: center;
          color: #27ae60;
          background-color: $white;
          border: 1px solid #27ae60;
          border-radius: 10px;
          &.isActive {
            color: $white;
            background-color: #27ae60;
          }
        }
        .partial-access {
          display: flex;
          width: 40px;
          height: 40px;
          align-items: center;
          justify-content: center;
          color: #f2994a;
          background-color: $white;
          border: 1px solid #f2994a;
          border-radius: 10px;
          &.isActive {
            color: $white;
            background-color: #f2994a;
          }
        }
        .not-available {
          display: flex;
          width: 40px;
          height: 40px;
          align-items: center;
          justify-content: center;
          color: #eb5757;
          background-color: $white;
          border: 1px solid #eb5757;
          border-radius: 10px;
          &.isActive {
            color: $white;
            background-color: #eb5757;
          }
        }
      }
      .sign-button {
        display: flex;
        background: #ffffff;
        border-radius: 10px;
        color: #333333;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        font-weight: 500;
        &-h {
          height: 100%;
        }
        img {
          margin-left: 5px;
        }
      }
    }
    @media all and (max-width: 1023px) {
      flex-direction: column;
    }
    .button {
      border: none;
      width: 40px;
      height: 40px;
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      transition: opacity 0.3s;
      margin-right: 20px;
      @media all and (max-width: 1023px) {
        margin: 0 0 10px;
      }
      &:last-child {
        margin-right: 0;
        @media all and (max-width: 1023px) {
          margin: 0;
        }
      }
      &:hover {
        opacity: 0.7;
      }
      &_red {
        background: $red;
      }
      &_green {
        background: $green;
      }
      &_blue {
        background: $blue;
      }
    }
  }
  &__actions {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
    position: absolute;
    bottom: 20px;
    right: 20px;
    z-index: 10;
    @media all and (max-width: 1023px) {
      flex-direction: column;
    }
    &.--desktop {
      @media all and (max-width: 1023px) {
        display: none;
      }
    }
    .button {
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      transition: opacity 0.3s;
      margin-right: 10px;
      @media all and (max-width: 1023px) {
        margin: 0 0 10px;
      }
      &:last-child {
        margin-right: 0;
        @media all and (max-width: 1023px) {
          margin: 0;
        }
      }
      &:hover {
        opacity: 0.7;
      }
      &_red {
        background: $red;
      }
      &_green {
        background: $green;
      }
      &_blue {
        background: $blue;
      }
      &_white {
        background: $white;
      }
    }
  }

  .btn-change-type {
    display: none;
  }
  .stat_button-wrap {
    margin-left: 10px;
    display: flex;
  }

  @media screen and (max-width: 1023px) {
    .btn-change-type {
      position: absolute;
      width: 100%;
      bottom: 20px;
      z-index: 2;
      display: flex;
      justify-content: center;
      .button_wrap {
        background-color: #ffffff;
        padding: 7px;
        border-radius: 10px;
        width: calc(80% - 58px);
        display: flex;
        justify-content: center;
        button {
          width: 100%;
          border-radius: 10px;
          font-family: Montserrat;
          font-weight: 500;
        }
      }
      .stat_btn {
        position: inherit;
        width: 58px;
        height: 100%;
        
        img {
          margin: 0;
        }
        span {
          text-align: start;
          @media all and (max-width: 1024px) {
            display: none;
          }
        }
      }
    }
  }
  .main-filter__visual {
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
}
</style>
