<template>
  <BlogMain :posts="posts" :categories="categories" :pages="pages" />
</template>

<script>
import MainHeader from "~/components/MainHeader";
import BlogMain from "~/components/blog/BlogMain";

export default {
  layout: "blog",
  components: {
    MainHeader,
    BlogMain,
  },
  name: "category",
  head() {
    return {
      title: this.$t("mainMenu.blog"),
      meta: [
          { property: "og:title", content: this.$t("mainMenu.blog") }
      ],
    };
  },
  watchQuery: ["page", "search", "period", "post_date_from", "post_date_to"],
  async asyncData({ $axios, params, query, error }) {
    try {
      const [categories, { items: posts, pages }] = await Promise.all([
        $axios.$get("/api/blog/categories"),
        $axios.$get("/api/blog/posts", {
          params: {
            page: query.page || 1,
            category: params.category,
            search: query.search,
            period: query.period,
            post_date_from: query.post_date_from,
            post_date_to: query.post_date_to,
          },
        }),
      ]);

      return { posts, categories: categories.reverse(), pages };
    } catch (e) {
      console.log(e);
      if (e.response.status) {
        return error({ statusCode: e.response.status });
      }
      return error({ statusCode: 500 });
    }
  },
};
</script>
