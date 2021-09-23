<template>
  <div class="error-page">
    <ViTop />
    <MainHeader />
    <div
      class="error-page-wrapper"
      :class="visualImpairedModeSettings.enabled ? 'vis' : ''"
    >
      <div
        class="error-page-bg"
        :class="visualImpairedModeSettings.enabled ? 'vis' : ''"
      >
        <div class="container">
          <div class="error-page__content" v-if="error.statusCode == '404'">
            <h2 class="error-page__title">
              {{ $t("error.title", { code: error.statusCode }) }}
            </h2>
            <p class="error-page__text">{{ $t("error.error404.message") }}</p>
            <p
              class="error-page__text"
              v-html="$t('error.error404.searchAdvice')"
            ></p>
            <div class="error-page__search">
              <form
                class="input grey-bg"
                :style="
                  visualImpairedModeSettings.enabled ? 'border: 1px solid;' : ''
                "
              >
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
                    stroke="#696969"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <path
                    d="M12.5137 13.8638L15.1567 16.5"
                    stroke="#696969"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
                <input
                  class="search-input"
                  type="text"
                  :placeholder="$t('index.searchSubmitButton')"
                />
              </form>
              <button class="button">
                {{ $t("index.searchSubmitButton") }}
              </button>
            </div>
          </div>
          <div class="error-page__content" v-if="error.statusCode == '500'">
            <h2 class="error-page__title">
              {{ $t("error.title", { code: error.statusCode }) }}
            </h2>
            <p class="error-page__text">{{ $t("error.error500.message") }}</p>
            <p class="error-page__text">{{ $t("error.error500.advice") }}</p>
            <div class="error-page__link-b">
              <a href="/" class="error-page__link">{{
                $t("error.error500.linkToMainPageTitle")
              }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import MainHeader from "~/components/MainHeader";
import { get } from "vuex-pathify";
export default {
  props: ["error"],
  components: {
    MainHeader,
  },
  computed: {
    visualImpairedModeSettings: get("visualImpairedModeSettings"),
  },
};
</script>

<style lang="scss">
.error-page {
  background-size: 100%;
  background-position: center bottom;
  min-height: 100vh;
  background-repeat: no-repeat;
  &-wrapper {
    position: relative;
    background: linear-gradient(180deg, #85cbf3, #fff);
    height: calc(100vh - 77px);
    @media all and (max-width: 768px) {
      height: calc(100vh - 62px);
    }
  }
  .vis {
    background: none;
  }
  &-bg {
    position: absolute;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    background-image: url("@/assets/img/page-error-bg.png");
    background-repeat: no-repeat;
    background-size: cover;
  }
  &__content {
    padding: 170px 0 30px;
    @media all and (max-width: 575px) {
      padding: 60px 0;
    }
  }
  &__title {
    font-weight: 600;
    font-size: 48px;
    line-height: 50px;
    margin: 0 0 30px;
    color: #3a3a3a;
    @media all and (max-width: 768px) {
      text-align: center;
    }
    @media all and (max-width: 575px) {
      font-size: 30px;
    }
  }
  &__text {
    font-size: 18px;
    max-width: 700px;
    line-height: 30px;
    margin: 20px 0;
    font-family: SFProDisplay;
    font-weight: 500;
    @media all and (max-width: 1024px) {
      max-width: 500px;
    }
    @media all and (max-width: 768px) {
      margin: auto;
    }
    @media all and (max-width: 575px) {
      font-size: 16px;
      line-height: 20px;
    }
  }

  &__search {
    max-width: 700px;
    display: flex;
    margin-top: 45px;
    .input {
      height: 60px;
      input {
        @media all and (max-width: 768px) {
          font-size: 16px;
        }
      }
    }
    @media all and (max-width: 768px) {
      max-width: 500px;
      margin: 45px auto;
    }
    @media all and (max-width: 768px) {
      margin: 30px auto;
    }
    &-button {
      cursor: pointer;
      padding: 13px 26px;
      border: 1px solid #2d9cdb;
      border-radius: 10px;
      font-size: 18px;
      font-weight: 700;
      font-family: "SFProDisplay";
      color: #fff;
      background: #2d9cdb;
      width: 170px;
      margin-left: 15px;
      @media all and (max-width: 768px) {
        display: none;
      }
    }
  }
  &__link {
    font-size: 18px;
    line-height: 20px;
    display: inline-block;
    border-bottom: 1px solid;
    transition: opacity 0.4s;
    &:hover {
      opacity: 0.7;
    }
    &-b {
      margin: 44px 0 0;
    }
  }
}
</style>
