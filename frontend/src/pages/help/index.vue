<template>
  <div class="blog">
    <ViTop />
    <MainHeader />

    <div class="container">
      <div class="title">
        {{ $t("mainMenu.help") }}
      </div>
      <div class="content">
        <nuxt-link
          :to="
            localePath({
              name: 'help-id',
              params: { id: article.id },
            })
          "
          class="item"
          v-for="article in articles.items"
          :key="article.id"
        >
          <div class="item-img">
            <img :src="article.image" alt="" />
            <div class="item-category">{{ article.category_name }}</div>
          </div>

          <div class="item-content">
            <span class="item-title">{{ article.title }}</span>
            <!-- <span class="date">{{ article.date }}</span> -->
            <span class="text" v-html="article.description"> </span>
            <nuxt-link
              class="detail"
              :to="
                localePath({
                  name: 'help-id',
                  params: { id: article.id },
                })
              "
            >
              {{ $t("blog.postLinkTitle") }}
            </nuxt-link>
          </div>
        </nuxt-link>
      </div>
      <!-- <Pagination :pages="999"></Pagination> -->
    </div>
    <MainFooter />
  </div>
</template>
<script>
import MainHeader from "@/components/MainHeader";
import ViTop from "@/components/ViTop";
import MainFooter from "@/components/MainFooter";
import Pagination from "@/components/Pagination";

export default {
  name: "Help",
  data() {
    return {};
  },
  async asyncData({ $axios }) {
    const articles = await $axios.$get("api/help");
    return { articles };
  },

  components: { MainHeader, ViTop, MainFooter, Pagination },
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

<style lang="scss" scoped>
.container {
  margin-bottom: 100px;
  .title {
    margin-top: 100px;
  }
  .content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    justify-items: center;
    gap: 30px;
    margin-bottom: 40px;
    @media all and (max-width: 991px) {
      grid-template-columns: 1fr;
    }
    .item {
      display: flex;
      width: 100%;
      align-items: flex-start;
      flex-direction: column;
      background: white;
      cursor: pointer;
      border-radius: 10px;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
      .item-img {
        position: relative;
        width: 100%;
        .item-category {
          position: absolute;
          top: 20px;
          right: 20px;
          background: rgba(255, 255, 255, 0.5);
          border-radius: 10px;
          padding: 10px 20px;
          font-family: "SFProDisplay";
          font-style: normal;
          font-weight: 500;
          font-size: 18px;
        }
        img {
          position: relative;
          width: 100%;
          border-radius: 10px 10px 0px 0px;
          height: 400px;
        }
      }
      .item-content {
        display: flex;
        flex-direction: column;
        font-family: "SFProDisplay";
        font-style: normal;
        padding: 20px;

        .item-title {
          font-weight: bold;
          font-size: 22px;
          margin-bottom: 5px;
        }
        .date {
          font-weight: 500;
          font-size: 14px;
          color: #2d9cdb;
          margin-bottom: 10px;
        }
        .text {
          font-weight: 300;
          font-size: 18px;
          line-height: 24px;
          margin-bottom: 10px;
          overflow: hidden;
          display: -webkit-box;
          -webkit-line-clamp: 5;
          -webkit-box-orient: vertical;
          overflow: hidden;
        }
        .detail {
          font-weight: 500;
          font-size: 18px;
        }
      }
    }
  }
}
</style>
