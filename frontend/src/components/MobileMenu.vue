<template>
  <div v-if="mobileMenu" class="main-page__mobile" :class="{ opened: mobileMenu }">
    <div class="main-page__mobile-left" :class="{ opened: mobileMenu }">
      <div class="main-page__mobile-in">
        <div class="main-page__mobile-item">
          <city-selector v-if="$route.path === '/'" class="mobile-city-selector" />
          <LangSelect />
        </div>

        <div class="main-page__mobile-item --md">
          <nuxt-link
            :to="localePath({ name: 'index' })"
            class="main-filter__logo"
          >
            <img :src="require(`@/assets/img/logo-new-white.png`)" alt />
          </nuxt-link>
        </div>

        <div
            v-if="$route.path === '/'"
            class="main-page__mobile-item"
        >
          <button
            class="btn btn_white"
            type="button"
            @click="popupOpen = true"
            v-if="currentCategory"
          >
            <img
              :src="
                require(`~/assets/icons/categories/${currentCategory.key}_blue.svg`)
              "
              class="icon"
            />
            <span>{{ $t(`startCategory.changeCategoryTitle`) }}</span>
          </button>
        </div>
        <!-- profile -->
        <!-- <div class="main-page__mobile-item">
          <div class="main-filter__menu">
            <nuxt-link :to="localePath({ name: 'login' })" v-if="!user">{{
              $t("login.linkTitle")
            }}</nuxt-link>

            <template v-if="user">
              <username :value="name" />
              <nuxt-link :to="localePath({ name: 'profile-achievements' })">{{
                $t("profile.achievements.tabTitle")
              }}</nuxt-link>
              <nuxt-link :to="localePath({ name: 'profile-objects' })">{{
                $t("profile.objects.tabTitle")
              }}</nuxt-link>
              <nuxt-link :to="localePath({ name: 'profile-tickets' })">{{
                $t("profile.tickets.tabTitle")
              }}</nuxt-link>
              <nuxt-link :to="localePath({ name: 'profile-tasks' })">{{
                $t("profile.tasks.tabTitle")
              }}</nuxt-link>
              <nuxt-link :to="localePath({ name: 'profile-comments' })">{{
                $t("profile.comments.tabTitle")
              }}</nuxt-link>
            </template>
          </div>
        </div> -->
        <div class="main-page__mobile-item">
          <div class="main-filter__menu" @click="mainPageMobOpened">
            <nuxt-link :to="localePath({ name: '/' })" class="menu-link">
              <div class="icon">
                <svg
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M3.5 10.3178C3.5 5.71789 7.34388 2 11.9934 2C16.6561 2 20.5 5.71789 20.5 10.3178C20.5 12.6357 19.657 14.7876 18.2695 16.6116C16.7388 18.6235 14.8522 20.3765 12.7285 21.7524C12.2425 22.0704 11.8039 22.0944 11.2704 21.7524C9.13474 20.3765 7.24809 18.6235 5.7305 16.6116C4.34198 14.7876 3.5 12.6357 3.5 10.3178ZM9.19423 10.5768C9.19423 12.1177 10.4517 13.3297 11.9934 13.3297C13.5362 13.3297 14.8058 12.1177 14.8058 10.5768C14.8058 9.0478 13.5362 7.77683 11.9934 7.77683C10.4517 7.77683 9.19423 9.0478 9.19423 10.5768Z"
                  />
                </svg>
              </div>

              <span>
                {{ $t("mainMenu.map") }}
              </span>
            </nuxt-link>
            <nuxt-link :to="localePath({ name: 'help' })" class="menu-link">
              <div class="icon">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M13.9729 1.52092H6.02635C3.2571 1.52092 1.52094 3.48167 1.52094 6.25642V13.7438C1.52094 16.5185 3.24885 18.4793 6.02635 18.4793H13.972C16.7504 18.4793 18.4793 16.5185 18.4793 13.7438V6.25642C18.4793 3.48167 16.7504 1.52092 13.9729 1.52092Z"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <path
                    d="M9.99515 13.6668V10.0001"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <path
                    d="M9.99075 6.52061H9.99991"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </div>

              <span>
                {{ $t("mainMenu.help") }}
              </span>
            </nuxt-link>
            <nuxt-link :to="localePath({ name: 'about' })" class="menu-link">
              <div class="icon">
                <svg
                  width="18"
                  height="19"
                  viewBox="0 0 18 19"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="#fff"
                >
                  <path
                    d="M12.5131 6.49341L7.26662 11.7959L1.29948 8.0637C0.444521 7.5288 0.622368 6.23015 1.58941 5.94735L15.757 1.79837C16.6425 1.53883 17.4632 2.36676 17.2001 3.25516L13.0087 17.4129C12.7215 18.3813 11.4303 18.5543 10.9004 17.6957L7.26384 11.7969"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </div>

              <span>
                {{ $t("mainMenu.about") }}
              </span>
            </nuxt-link>
            <nuxt-link
              :to="localePath({ name: 'blog-category' })"
              class="menu-link"
            >
              <div class="icon">
                <svg
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M15.7162 16.2234H8.4962"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <path
                    d="M15.7162 12.0369H8.4962"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <path
                    d="M11.2513 7.8601H8.4963"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M15.9086 2.7498C15.9086 2.7498 8.2316 2.7538 8.2196 2.7538C5.4596 2.7708 3.7506 4.5868 3.7506 7.3568V16.5528C3.7506 19.3368 5.4726 21.1598 8.2566 21.1598C8.2566 21.1598 15.9326 21.1568 15.9456 21.1568C18.7056 21.1398 20.4156 19.3228 20.4156 16.5528V7.3568C20.4156 4.5728 18.6926 2.7498 15.9086 2.7498Z"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </div>

              <span>
                {{ $t("mainMenu.blog") }}
              </span>
            </nuxt-link>
            <nuxt-link :to="localePath({ name: 'contacts' })" class="menu-link">
              <div class="icon">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g id="Iconly/Light/Call">
                    <g id="Call">
                      <path
                        id="Stroke 1"
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M9.60978 10.3937C12.934 13.717 13.6881 9.87226 15.8047 11.9873C17.8452 14.0273 19.018 14.436 16.4327 17.0206C16.1089 17.2809 14.0513 20.4119 6.8205 13.1831C-0.411237 5.95333 2.71798 3.8937 2.9783 3.56996C5.56988 0.978203 5.97155 2.15782 8.01207 4.19777C10.1286 6.31375 6.28554 7.07034 9.60978 10.3937Z"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      />
                    </g>
                  </g>
                </svg>
              </div>

              <span>
                {{ $t("mainMenu.contacts") }}
              </span>
            </nuxt-link>
            <div class="profile" v-if="user">
              <nuxt-link
                :to="localePath({ name: 'profile' })"
                class="menu-link"
              >
                <div class="icon">
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
                      d="M11.9848 15.3462C8.11714 15.3462 4.81429 15.931 4.81429 18.2729C4.81429 20.6148 8.09619 21.2205 11.9848 21.2205C15.8524 21.2205 19.1543 20.6348 19.1543 18.2938C19.1543 15.9529 15.8733 15.3462 11.9848 15.3462Z"
                      stroke-width="1.5"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M11.9848 12.0059C14.5229 12.0059 16.58 9.94781 16.58 7.40971C16.58 4.87162 14.5229 2.81448 11.9848 2.81448C9.44666 2.81448 7.38857 4.87162 7.38857 7.40971C7.38 9.93924 9.42381 11.9973 11.9524 12.0059H11.9848Z"
                      stroke-width="1.42857"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg>
                </div>

                <span>
                  {{ $t("mainMenu.myProfile") }}
                </span>
              </nuxt-link>
              <span class="logout" @click="logout">{{
                $t("login.logoutTitle")
              }}</span>
            </div>
            <div v-else class="profile">
              <nuxt-link to="login" class="menu-link">
                <div class="icon">
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 20 20"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M4.10736 15.8926C5.2728 17.058 6.75766 17.8517 8.37417 18.1732C9.99067 18.4948 11.6662 18.3297 13.1889 17.699C14.7117 17.0683 16.0132 16.0002 16.9288 14.6298C17.8445 13.2594 18.3333 11.6482 18.3333 10C18.3333 8.35184 17.8445 6.74068 16.9288 5.37027C16.0132 3.99986 14.7117 2.93176 13.1889 2.30102C11.6662 1.67029 9.99067 1.50527 8.37417 1.82681C6.75766 2.14835 5.2728 2.94203 4.10736 4.10746"
                      stroke="inherit"
                      stroke-width="2"
                    />
                    <path
                      d="M12.5 9.99998L13.2809 9.37528L13.7806 9.99998L13.2809 10.6247L12.5 9.99998ZM2.5 11C1.94771 11 1.5 10.5523 1.5 9.99998C1.5 9.44769 1.94771 8.99998 2.5 8.99998V11ZM9.94754 5.20862L13.2809 9.37528L11.7191 10.6247L8.3858 6.45801L9.94754 5.20862ZM13.2809 10.6247L9.94754 14.7913L8.3858 13.542L11.7191 9.37528L13.2809 10.6247ZM12.5 11H2.5V8.99998H12.5V11Z"
                      fill="inherit"
                    />
                  </svg>
                </div>

                <span>{{ $t("login.linkTitle") }}</span>
              </nuxt-link>
            </div>
          </div>
        </div>
        <div class="action" @click="mainPageMobOpened">
          <nuxt-link
            :to="localePath({ name: 'objects-add' })"
            class="btn btn_green button"
            name="add_object"
          >
            <svg
              width="14"
              height="15"
              viewBox="0 0 14 15"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M6.99992 1.99622V13.9848"
                stroke="white"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                d="M13 7.99052H1"
                stroke="white"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>

            <span class="text-button">{{ $t("index.addObjectLink") }}</span>
          </nuxt-link>
          <nuxt-link
            :to="localePath({ name: 'complaint' })"
            class="btn btn_red button"
          >
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M12 4V4C16.4183 4 20 7.58172 20 12V17.0909C20 17.9375 20 18.3608 19.8739 18.6989C19.6712 19.2425 19.2425 19.6712 18.6989 19.8739C18.3608 20 17.9375 20 17.0909 20H12C7.58172 20 4 16.4183 4 12V12"
                stroke="white"
                stroke-width="2"
              />
              <path
                d="M9 11L15 11"
                stroke="white"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                d="M5 8L5 2"
                stroke="white"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                d="M2 5L8 5"
                stroke="white"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                d="M12 15H15"
                stroke="white"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>

            <span class="text-button">{{ $t("index.makeComplaintLink") }}</span>
          </nuxt-link>
        </div>
      </div>
    </div>
    <div class="main-page__mobile-right"></div>
  </div>
</template>

<script>
import { call, get, sync } from "vuex-pathify";

import LangSelect from "./../components/LangSelect";
import CitySelector from "./../components/CitySelector";

export default {
  name: "MobileMenu",
  components: {
    LangSelect,
    CitySelector,
  },

  computed: {
    currentCategory: get("disabilitiesCategorySettings/currentCategory"),
    user: get("authentication/user"),
    mobileMenu: get("settings/mobileMenu"),
    name: get("authentication/name"),
    popupOpen: sync("disabilitiesCategorySettings/popupOpen"),
  },
  methods: {
    logout: call("authentication/logout"),
    mainPageMobOpened: call("settings/menuOpen"),
  },
};
</script>

<style lang="scss" scoped>
@import "./../styles/mixins.scss";

.main-page {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: #e5e5e5;
  overflow: hidden;

  &__mobile {
    display: none;
    position: fixed;
    z-index: -1;
    left: 0;
    top: 50px;
    bottom: 0;
    right: 0;
    /* background: rgba(0,0,0,.5); */
    @media all and (max-width: 1023px) {
      display: block;
    }
    &.opened {
      z-index: 11;
    }
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
      @media all and (max-width: 1023px) {
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
      @media all and (max-width: 1023px) {
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
      .action {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 20px;
        position: absolute;
        left: 0;
        bottom: 0px;
        right: 0;
        .button {
          width: 100%;
        }
      }
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
          padding: 0px 17px;
        }
      }
      .main-filter {
        &__menu {
          display: block;
          width: 100%;
          .profile {
            display: flex;
            margin-top: 20px;
            align-items: center;
            justify-content: space-between;
            .logout {
              font-family: "SFProDisplay";
              font-style: normal;
              font-weight: 500;
              font-size: 16px;
              color: #e86161;
            }
          }
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
            .icon {
              width: 25px;
              display: flex;
              align-items: center;
              justify-content: center;
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
      @media all and (max-width: 768px) {
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
      @media screen and (min-width: 1900px) {
        width: 550px;
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
      @media all and (max-width: 768px) {
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
  @media screen and (max-width: 991px) {
    .btn-change-type {
      position: absolute;
      width: 100%;
      bottom: 20px;
      z-index: 2;
      display: flex;
      justify-content: center;
      div {
        background-color: #ffffff;
        padding: 7px;
        border-radius: 10px;
        width: 80%;
        display: flex;
        justify-content: center;
        button {
          width: 100%;
          border-radius: 10px;
          font-family: Montserrat;
          font-weight: 500;
        }
      }
    }
  }
}
</style>
