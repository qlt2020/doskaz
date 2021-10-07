<template>
  <div class="about">
    <ViTop />
    <MainHeader v-if="!viModeEnabled" />
    <div class="about__top">
      <div class="about__top-bg">
        <div class="wrapper" style="text-align: start">
          <span class="title">
            {{ $t("mainMenu.about") }}
          </span>
          <p class="text">
            {{ $t("about.header") }}
          </p>
        </div>
      </div>
    </div>
    <div class="about-wrapper">
      <div class="containers-wrap">
        <section id="what_it_is" class="about__container">
          <span class="about__container__title">{{
            $t("about.section1")
          }}</span>
          <div
            class="about__container__text"
            v-html="$t('about.section1Text')"
          />
        </section>
        <section id="for_whom" class="about__container">
          <span class="about__container__title">{{
            $t("about.section2")
          }}</span>
          <div style="display: flex">
            <div
              class="about__container__text"
              v-html="$t('about.section2Text')"
            />
            <img
              class="for-whom__img"
              :src="require('@/assets/img/about/for_whom.png')"
            />
          </div>
        </section>
        <section id="how_works" class="about__container">
          <span class="about__container__title">{{
            $t("about.section3")
          }}</span>
          <div
            class="about__container__text"
            v-html="$t('about.section3Text')"
          />
          <div class="about__container__how-works">
            <div class="item green">
              <div class="info">
                <div class="icon">
                  <img
                    :src="require('@/assets/img/about/available_icon.svg')"
                  />
                </div>
                <span class="title__green">{{ $t("about.accessible") }}</span>
              </div>
              <div class="desc">
                {{ $t("about.section3Access") }}
              </div>
            </div>
            <div class="item yellow">
              <div class="info">
                <div class="icon">
                  <img :src="require('@/assets/img/about/partial_icon.svg')" />
                </div>
                <span class="title__yellow">{{
                  $t("about.partialAccessible")
                }}</span>
              </div>
              <div class="desc">
                {{ $t("about.section3PartAccess") }}
              </div>
            </div>
            <div class="item red">
              <div class="info">
                <div class="icon">
                  <img
                    :src="require('@/assets/img/about/not_access_icon.svg')"
                  />
                </div>
                <span class="title__red">{{ $t("about.notAccessible") }}</span>
              </div>
              <div class="desc">
                {{ $t("about.section3NoAccess") }}
              </div>
            </div>
          </div>
          <div
            class="about__container__text"
            v-html="$t('about.section3Text2')"
          />
        </section>
        <!--        <section id="how_use_web" class="about__container">-->
        <!--          <span class="about__container__title">{{ $t('about.section4') }}</span>-->
        <!--          -->
        <!--        </section>-->
        <!--        <section id="how_use_ios" class="about__container">-->
        <!--          <span class="about__container__title">{{ $t('about.section5') }}</span>-->
        <!--          -->
        <!--        </section>-->
        <!--        <section id="how_use_android" class="about__container">-->
        <!--          <span class="about__container__title">{{ $t('about.section6') }}</span>-->
        <!--          -->
        <!--        </section>-->
        <section id="what_available_now" class="about__container">
          <span class="about__container__title">{{
            $t("about.section7")
          }}</span>
          <div class="about__container__steps">
            <div class="step">
              <img :src="require('@/assets/img/about/step1.svg')" />
              <span>
                {{ $t("about.section7Text") }}
                <nuxt-link :to="localePath({ name: 'index' })"
                  ><span
                    :style="
                      !viModeEnabled
                        ? 'color: #1534EC; font-weight: bold'
                        : 'font-weight: bold'
                    "
                    >"{{ $t("meta.title") }}"</span
                  ></nuxt-link
                ></span
              >
            </div>
            <div class="step">
              <img :src="require('@/assets/img/about/step2.png')" />
              <div
                style="width: 100%;"
                v-html="
                  !viModeEnabled
                    ? $t('about.section7Text2')
                    : $t('about.section7Text2Black')
                "
              />
            </div>
            <div class="step">
              <img :src="require('@/assets/img/about/step3.png')" />
              <div>
                {{ $t("about.section7Text3") }}
                <div
                  style="margin: auto;margin-top: 15%;width: fit-content;display: grid;align-items: center;grid-template-columns: auto auto auto auto;grid-column-gap: 5px;justify-items: center;"
                >
                  <div class="social-icon" @click="share('fb')">
                    <img
                      style="margin: auto"
                      :src="
                        !viModeEnabled
                          ? require('@/assets/img/social/fcb.svg')
                          : require('@/assets/img/social/fcb-black.svg')
                      "
                    />
                  </div>
                  <div class="social-icon" @click="share('vk')">
                    <img
                      style="margin: auto"
                      :src="
                        !viModeEnabled
                          ? require('@/assets/img/social/vk.svg')
                          : require('@/assets/img/social/vk-black.svg')
                      "
                    />
                  </div>
                  <div class="social-icon" @click="share('twitter')">
                    <img
                      style="margin: auto; width: 15px; height: 15px;"
                      :src="
                        !viModeEnabled
                          ? require('@/assets/img/social/twitter.svg')
                          : require('@/assets/img/social/twitter-black.svg')
                      "
                    />
                  </div>
                  <div class="social-icon" @click="share('telegram')">
                    <img
                      style="margin: auto;width: 15px; height: 15px;"
                      :src="
                        !viModeEnabled
                          ? require('@/assets/img/social/telegram.svg')
                          : require('@/assets/img/social/telegram-black.svg')
                      "
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section id="for_volunteers" class="about__container">
          <span class="about__container__title">{{
            $t("about.section8")
          }}</span>
          <div
            class="about__container__text"
            v-html="
              !viModeEnabled
                ? $t('about.section8Text')
                : $t('about.section8TextBlack')
            "
          />
        </section>
        <section id="realization" class="about__container realization">
          <span class="about__container__title">{{
            $t("about.section9")
          }}</span>
          <div class="about__container__realization">
            <img :src="require('@/assets/img/about/realization1.png')" />
            <img :src="require('@/assets/img/about/realization2.png')" />
            <img :src="require('@/assets/img/about/realization3.png')" />
          </div>
          <span class="about__container__span-text">
            {{ $t("about.section9Text") }}
            <nuxt-link :to="localePath({ name: 'contacts' })"
              ><span
                :style="
                  !viModeEnabled
                    ? 'color: #1534EC; font-weight: bold'
                    : 'font-weight: bold'
                "
                >{{ $t("mainMenu.contacts") }}</span
              ></nuxt-link
            ></span
          >
        </section>
        <section id="finance" class="about__container finance">
          <span class="about__container__title">{{
            $t("about.section10")
          }}</span>
          <div class="about__container__finance">
            <img :src="require('@/assets/img/about/finance1.png')" />
            <img :src="require('@/assets/img/about/finance2.png')" />
            <img :src="require('@/assets/img/about/finance3.png')" />
            <img :src="require('@/assets/img/about/finance4.png')" />
          </div>
          <span class="about__container__span-text">
            {{ $t("about.section10Text") }}
          </span>
        </section>
        <section id="support" class="about__container">
          <span class="about__container__title">{{
            $t("about.section11")
          }}</span>
          <div class="about__container__text">
            {{ $t("about.section11Text") }}
            <nuxt-link :to="localePath({ name: 'contacts' })"
              ><span
                :style="
                  !viModeEnabled
                    ? 'color: #1534EC; font-weight: bold'
                    : 'font-weight: bold'
                "
                >{{ $t("mainMenu.contacts") }}</span
              ></nuxt-link
            >
          </div>
        </section>
      </div>
      <scrollactive
        id="slide_scroll"
        active-class="about__sidebar__link-active"
        :duration="600"
        :highlight-first-item="true"
        :always-track="true"
        :scroll-offset="20"
        class="about__sidebar"
      >
        <a class="scrollactive-item" href="#what_it_is">{{
          $t("about.section1")
        }}</a>
        <a class="scrollactive-item" href="#for_whom">{{
          $t("about.section2")
        }}</a>
        <a class="scrollactive-item" href="#how_works">{{
          $t("about.section3")
        }}</a>
        <!--        <a class="scrollactive-item" href="#how_use_web">{{ $t('about.section4') }}</a>-->
        <!--        <a class="scrollactive-item" href="#how_use_ios">{{ $t('about.section5') }}</a>-->
        <!--        <a class="scrollactive-item" href="#how_use_android">{{ $t('about.section6') }}</a>-->
        <a class="scrollactive-item" href="#what_available_now">{{
          $t("about.section7")
        }}</a>
        <a class="scrollactive-item" href="#for_volunteers">{{
          $t("about.section8")
        }}</a>
        <a class="scrollactive-item" href="#realization">{{
          $t("about.section9")
        }}</a>
        <a class="scrollactive-item" href="#finance">{{
          $t("about.section10")
        }}</a>
        <a class="scrollactive-item" href="#support">{{
          $t("about.section11")
        }}</a>
      </scrollactive>
    </div>
    <MainFooter />
  </div>
</template>

<script>
import { get } from "vuex-pathify";
import MainHeader from "@/components/MainHeader";
import ViTop from "@/components/ViTop";
import MainFooter from "@/components/MainFooter";
import BackBtn from "@/components/BackBtn";

export default {
  components: { MainHeader, ViTop, MainFooter, BackBtn },
  head() {
    return {
      title: this.$t("mainMenu.about"),
    };
  },
  computed: {
    viModeEnabled: get("visualImpairedModeSettings/enabled"),
    shareLinks() {
      const path = encodeURIComponent(window.location.origin);

      return {
        fb: `https://www.facebook.com/sharer.php?u=${path}`,
        vk: `https://vk.com/share.php?url=${path}`,
        twitter: `https://twitter.com/intent/tweet?url=${path}`,
        telegram: `https://t.me/share/url?url=${path}`,
      };
    },
  },
  methods: {
    share(network) {
      window.open(this.shareLinks[network]);
    },
  },
};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";

.about {
  background: #fafafa;
  &-wrapper {
    max-width: 1200px;
    margin: auto;
    margin-top: 45px;
    margin-bottom: 50px;
    display: flex;
    @media screen and (max-width: 1023px) {
      display: grid;
      margin-top: unset;
      margin-bottom: unset;
    }

    .containers-wrap {
      flex: 1;
      display: grid;
      grid-row-gap: 45px;
      @media screen and (max-width: 1023px) {
        flex: unset;
        grid-row-gap: unset;
      }
    }

    .about__sidebar {
      background: #ffffff;
      box-shadow: 0px 24px 32px rgba(0, 0, 0, 0.04),
        0px 16px 24px rgba(0, 0, 0, 0.04), 0px 4px 8px rgba(0, 0, 0, 0.04),
        0px 0px 1px rgba(0, 0, 0, 0.04);
      border-radius: 10px;
      display: grid;
      width: 270px;
      margin-left: 30px;
      height: fit-content;
      padding: 25px;
      grid-row-gap: 15px;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 400;
      font-size: 18px;
      line-height: 17px;
      position: -webkit-sticky;
      position: sticky;
      top: 0;
      a {
        position: relative;
        @media screen and (max-width: 1023px) {
          white-space: nowrap;
          margin-right: 35px;
        }
      }
      .about__sidebar__link-active {
        color: #1534ec;
        &::before {
          content: "";
          background: #1534ec;
          width: 3px;
          height: 25px;
          position: absolute;
          top: 5px;
          left: -26px;
          @media screen and (max-width: 1023px) {
            bottom: -15px;
            top: unset;
            left: unset;
            height: 3px;
            width: 100%;
          }
        }
      }
      @media screen and (max-width: 1023px) {
        grid-row: 1;
        height: unset;
        display: flex;
        width: 100%;
        overflow: scroll;
        margin-left: unset;
        padding: 15px 25px;
        font-size: 16px;
        line-height: 13px;
        border-radius: unset;
      }
    }

    .about__container {
      background: #ffffff;
      box-shadow: 0px 24px 32px rgba(0, 0, 0, 0.04),
        0px 16px 24px rgba(0, 0, 0, 0.04), 0px 4px 8px rgba(0, 0, 0, 0.04),
        0px 0px 1px rgba(0, 0, 0, 0.04);
      border-radius: 10px;
      padding: 25px;
      &.realization,
      &.finance {
        .about__container__span-text {
          font-family: SFProDisplay;
          font-style: normal;
          font-weight: 400;
          font-size: 16px;
          line-height: 24px;
          color: #000000;
        }
      }
      .for-whom__img {
        @media screen and (max-width: 1023px) {
          display: none;
        }
      }
      @media screen and (max-width: 1023px) {
        border-radius: unset;
        padding: 15px;
      }

      &__title {
        font-family: SFProDisplay;
        font-style: normal;
        font-weight: bold;
        font-size: 28px;
        line-height: 33px;
        color: #000000;
        position: relative;
        @media screen and (max-width: 1023px) {
          font-size: 25px;
        }
        &::before {
          content: "";
          background: #2d9cdb;
          width: 3px;
          height: 25px;
          position: absolute;
          top: 5px;
          left: -26px;
          @media screen and (max-width: 1023px) {
            display: none;
          }
        }
      }

      &__text {
        font-family: SFProDisplay;
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 24px;
        color: #000000;
        margin-top: 25px;
      }

      &__how-works {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-column-gap: 20px;
        justify-content: space-between;
        margin-top: 25px;
        @media screen and (max-width: 1023px) {
          grid-template-columns: unset;
          grid-row-gap: 15px;
        }

        .item {
          box-sizing: border-box;
          box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
            0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
          border-radius: 10px;
          padding: 20px;
          display: grid;
          align-items: center;
          grid-row-gap: 10px;
          &.green {
            border: 2px solid #27ae60;
            .info {
              .icon {
                background: #27ae60;
              }
            }
          }
          &.yellow {
            border: 2px solid #f2994a;
            .info {
              .icon {
                background: #f2994a;
              }
            }
          }
          &.red {
            border: 2px solid #eb5757;
            .info {
              .icon {
                background: #eb5757;
              }
            }
          }
          .info {
            display: flex;
            align-items: center;
            font-family: SFProDisplay;
            font-style: normal;
            font-weight: 500;
            font-size: 18px;
            line-height: 21px;
            .icon {
              flex: none;
              border-radius: 10px;
              width: 30px;
              height: 30px;
              display: flex;
              align-items: center;
              justify-content: center;
              margin-right: 10px;
            }
            .title {
              &__green {
                color: #27ae60;
              }
              &__yellow {
                color: #f2994a;
              }
              &__red {
                color: #eb5757;
              }
            }
          }
          .desc {
            font-family: SFProDisplay;
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 20px;
            color: #000000;
          }
        }
      }
      &__steps {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-column-gap: 30px;
        margin-top: 40px;
        font-family: SFProDisplay;
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 24px;
        color: #000000;
        @media screen and (max-width: 1023px) {
          grid-template-columns: unset;
        }
        .step {
          display: grid;
          grid-template-rows: 192px auto;
          justify-items: center;
          .social-icon {
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
              0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
          }
        }
      }
      &__realization {
        margin-top: 33px;
        margin-bottom: 37px;
        img {
          margin-right: 60px;
        }
      }
      &__finance {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        margin-bottom: 30px;
        @media screen and (max-width: 1023px) {
          flex-wrap: wrap;
        }
      }
    }
  }
  .main-header__content {
    border: none;
  }
  &__link {
    &-b {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin: 110px 0 0;
      @media all and (max-width: 1023px) {
        margin: 60px 0 0;
        display: block;
        text-align: center;
      }
      .title {
        @media all and (max-width: 1023px) {
          font-size: 24px;
          line-height: 30px;
        }
      }
    }
    &-item {
      width: 500px;
      min-width: 500px;
      text-align: left;
      @media all and (max-width: 1023px) {
        margin: 60px auto 0;
        display: block;
        text-align: center;
      }
      @media all and (max-width: 1023px) {
        width: 100%;
        min-width: 100%;
      }
    }
    &-text {
      font-size: 16px;
      line-height: 30px;
      @media all and (max-width: 1023px) {
        font-size: 14px;
        line-height: 20px;
      }
      a {
        text-decoration: underline;
      }
    }
    &-logo {
      min-height: 131px;
      margin: 52px 0 30px;
      align-items: top;
      display: flex;
      @media all and (max-width: 1023px) {
        min-height: 0;
        margin: 24px 0 20px;
        justify-content: center;
      }
      @media all and (max-width: 1023px) {
        flex-wrap: wrap;
        margin: 0 0 20px;
      }
      a {
        margin: 0 0 0 80px;
        transition: opacity 0.3s;
        &:hover {
          opacity: 0.7;
        }
        &:first-child {
          margin: 0;
          @media all and (max-width: 1023px) {
            margin: 24px 30px 0;
          }
        }
        @media all and (max-width: 1023px) {
          margin: 24px 14px 0;
        }
      }
    }
  }
  &__title {
    font-weight: 600;
    font-size: 48px;
    line-height: 50px;
    text-align: center;
    margin: 0;
    @media all and (max-width: 1200px) {
      font-size: 32px;
      line-height: 36px;
    }
    @media all and (max-width: 1023px) {
      font-size: 20px;
      line-height: 28px;
    }
  }
  &__item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 80px 0 0;
    @media all and (max-width: 1023px) {
      align-items: flex-start;
    }
    @media all and (max-width: 1023px) {
      margin: 42px 0 0;
      display: block;
    }
    p {
      font-size: 16px;
      line-height: 30px;
      text-align: left;
      width: calc(100% - 290px);
      min-width: calc(100% - 290px);
      @media all and (max-width: 1023px) {
        width: calc(100% - 190px);
        min-width: calc(100% - 190px);
      }
      @media all and (max-width: 1023px) {
        text-align: center;
        width: 100%;
        margin: 18px 0 0;
        font-size: 14px;
        line-height: 20px;
      }
    }
    img {
      width: 260px;
      min-width: 260px;
      &.--right {
        order: 1;
        @media all and (max-width: 1023px) {
          order: 0;
        }
      }
      @media all and (max-width: 1023px) {
        width: 160px;
        min-width: 160px;
      }
      @media all and (max-width: 1023px) {
        margin: 0 auto;
        display: block;
      }
    }
    &-b {
      padding: 106px 0 60px;
      @media all and (max-width: 1023px) {
        padding: 53px 20px 50px;
      }
      @media all and (max-width: 1023px) {
        padding: 30px 0;
      }
    }
  }
  &__part {
    padding: 106px 0 196px;
    background: $light-gray;
    .about__title {
      position: relative;
      z-index: 1;
    }
    @media all and (max-width: 1200px) {
      padding: 53px 0 50px;
    }
    @media all and (max-width: 1023px) {
      padding: 53px 20px 50px;
    }
    @media all and (max-width: 1023px) {
      padding: 30px 0;
    }
    &-content {
      display: flex;
      margin: 64px 0 0;
      @media all and (max-width: 1023px) {
        display: block;
        margin: 0;
      }
    }
    &-img {
      width: 560px;
      min-width: 560px;
      margin: 7px 40px 0 0;
      position: relative;
      @media all and (max-width: 1200px) {
        width: 460px;
        min-width: 460px;
        margin: 7px 30px 0 0;
      }
      @media all and (max-width: 1023px) {
        width: 300px;
        min-width: 300px;
        margin: 37px auto 24px;
      }
      @media all and (max-width: 1023px) {
        width: 240px;
        min-width: 240px;
      }
      img {
        display: block;
        width: 100%;
        height: auto;
        position: relative;
        &:first-child {
          position: absolute;
          width: (552/560) * 100%;
          height: auto;
          left: -11.8%;
          top: -21%;
        }
      }
    }
    &-text {
      font-size: 16px;
      line-height: 30px;
      z-index: 1;
      position: relative;
      @media all and (max-width: 1023px) {
        font-size: 14px;
        line-height: 20px;
      }
    }
  }
  &__text {
    font-size: 16px;
    line-height: 30px;
    margin: 63px 0 0;
    @media all and (max-width: 1200px) {
      margin: 27px 0 0;
    }
    @media all and (max-width: 1023px) {
      font-size: 14px;
      line-height: 20px;
      margin: 14px 0 0;
    }
    &-b {
      background: #ffffff;
      padding: 66px 150px 11px;
      @media all and (max-width: 1200px) {
        padding: 53px 40px 11px;
      }
      @media all and (max-width: 1023px) {
        padding: 20px 20px 11px;
      }
      & + .about__top-text-b {
        margin: 20px 0 0;
      }
    }
  }
  &__top {
    &-bg {
      height: 405px;
      background-position: center;
      background-size: cover;
      display: grid;
      align-items: center;
      text-align: center;
      background-image: url("@/assets/img/about/header.png");

      &__back {
        width: 105px;
        margin-left: 15px;
        margin-top: 25px;
        @media all and (min-width: 1023px) {
          display: none !important;
        }
        .text {
          color: black !important;
        }
      }

      .wrapper {
        margin: auto;
        max-width: 1200px;
        display: grid;
        grid-row-gap: 20px;
        @media all and (max-width: 1023px) {
          padding: 0 15px;
        }
      }
      .title {
        font-family: Montserrat;
        font-style: normal;
        font-weight: 600;
        font-size: 30px;
        line-height: 36px;
        color: #ffffff;
      }
      .text {
        font-family: SFProDisplay;
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 24px;
        color: #ffffff;
      }
      @media all and (max-width: 1023px) {
        height: 547px;
        background-image: url("@/assets/img/about/header_mobile.png");
      }
    }
    &-list {
      display: flex;
      justify-content: space-between;
      margin: 20px 0 0;
      @media all and (max-width: 1023px) {
        display: block;
      }
    }
    &-item {
      padding: 40px 30px;
      background: #ffffff;
      box-shadow: 0 0 5px rgba(123, 149, 167, 0.15),
        0 4px 40px rgba(123, 149, 167, 0.25);
      width: calc((100% - 110px) / 3);
      @media all and (max-width: 1200px) {
        padding: 29px 20px 20px;
      }
      @media all and (max-width: 1023px) {
        width: calc((100% - 60px) / 3);
      }
      @media all and (max-width: 1023px) {
        width: 100%;
        margin: 24px 0 0;
      }
      &__img {
        display: block;
        width: 40px;
        @media all and (max-width: 1023px) {
          width: 30px;
        }
      }
      &__title {
        font-weight: bold;
        font-size: 16px;
        line-height: 20px;
        margin: 24px 0 5px;
        @media all and (max-width: 1023px) {
          font-size: 14px;
          line-height: 20px;
          margin: 15px 0 5px;
        }
      }
      &__text {
        font-size: 16px;
        line-height: 30px;
        margin: 5px 0 0;
        @media all and (max-width: 1023px) {
          font-size: 14px;
          line-height: 20px;
        }
      }
    }
  }
}
</style>
