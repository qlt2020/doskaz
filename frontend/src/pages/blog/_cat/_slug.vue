<template>
  <BlogInside :post="post" :similarPosts="similar" />
</template>
<script>
import BlogInside from "~/components/blog/BlogInside";
import get from "lodash/get";

export default {
  components: { BlogInside },
  layout: "blog",
  head() {
    return {
      title: get(this.post, "meta.title"),
      meta: [
        { name: "keywords", content: get(this.post, "meta.keywords") },
        { name: "description", content: get(this.post, "meta.description") },
        { property: "og:title", content: get(this.post, "meta.ogTitle") },
        {
          property: "og:description",
          content: get(this.post, "meta.ogDescription")
        },
        { property: "og:image", content: get(this.post, "meta.ogImage") }
      ].filter(({ content }) => !!content)
    };
  },
  async asyncData({ $axios, params, query, error }) {
    try {
      const {
        data: { post, similar }
      } = await $axios.get(`/api/blog/posts/bySlug/${params.slug}`);
      return { post, similar };
    } catch (e) {
      if (e.response.status) {
        return error({ statusCode: e.response.status });
      }
      return error({ statusCode: 500 });
    }
  }
};
</script>
