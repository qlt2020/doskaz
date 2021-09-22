<template>
  <div class="complaint">
    <ViTop />
    <MainHeader />
    <div class="container">
      <div class="complaint__top">
        <h2 class="title">
          <img
            :src="require('@/assets/icons/back-arrow.svg')"
            @click="$router.back()"
            class="title-back_arrow"
          />
          {{ $t("complaint.pageTitle") }}
        </h2>
        <p class="complaint__pre-text">{{ $t("complaint.infoText") }}</p>
        <!--                <p class="complaint__pre-text &#45;&#45;required"><span>*</span>{{ $t('complaint.requiredFields') }}</p>-->
      </div>
    </div>
    <div class="complaint__wrapper">
      <client-only>
        <complaint-content
          :initial-data="initialData"
          :authorities="authorities"
          :cities="cities"
        />
      </client-only>
    </div>
    <MainFooter />
  </div>
</template>

<script>
import MainHeader from "~/components/MainHeader";
import ComplaintContent from "~/components/complaint/ComplaintContent";
import ViTop from "~/components/ViTop";
import MainFooter from "@/components/MainFooter";

export default {
  head() {
    return {
      title: this.$t("complaint.pageTitle")
    };
  },
  components: { ComplaintContent, MainHeader, ViTop, MainFooter },
  middleware: ["authenticated"],
  async asyncData({ $axios, query: { objectId } }) {
    const [
      { data: initialData },
      { data: authorities },
      { data: cities }
    ] = await Promise.all([
      $axios.get("/api/complaints/initialData", {
        params: {
          objectId
        }
      }),
      $axios.get("/api/complaints/authorities"),
      $axios.get("/api/cities")
    ]);

    return {
      initialData,
      authorities,
      cities
    };
  }
};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";

.complaint {
  &__wrapper {
    padding: 30px 0 60px;
    background: #fafafa;
    @media all and (max-width: 1023px) {
      padding: 20px 0;
      .container {
        padding: 0;
      }
    }
  }

  &__top {
    padding: 46px 0 23px;
    @media all and (max-width: 1023px) {
      padding: 28px 0 21px;
    }
    @media all and (max-width: 768px) {
      padding: 14px 0 15px;
      .title.--md {
        font-size: 18px;
      }
    }
    .title {
      font-size: 28px;
      font-weight: bold;
      font-family: SFProDisplay;
      font-style: normal;
      display: flex;
      align-items: center;
      img {
        margin-right: 20px;
        cursor: pointer;
      }
      @media screen and (max-width: 1023px) {
        margin-top: 70px;
      }
    }
  }

  &__pre-text {
    margin: 26px 0 0;
    font-size: 16px;
    line-height: 24px;
    font-family: SFProDisplay;
    font-weight: 500;
    font-style: normal;
    color: #3a3a3a;
    @media all and (max-width: 1023px) {
      margin-bottom: 17px;
    }
    @media all and (max-width: 768px) {
      font-size: 16px;
      line-height: 24px;
    }

    &.--required {
      margin: 19px 0 0;
      @media all and (max-width: 1023px) {
        margin: 10px 0 0;
      }
      @media all and (max-width: 768px) {
        margin: 5px 0 0;
      }

      span {
        color: #e0202e;
      }
    }
  }
}
</style>
