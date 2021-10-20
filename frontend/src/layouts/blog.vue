<template>
  <div class="blog">
    <ViTop />
    <div v-if="!viModeEnabled" class="blog__header">
      <MainHeader />
    </div>

    <div class="container">
      <nuxt />
    </div>
    <div class="blog__footer">
      <MainFooter />
    </div>
  </div>
</template>
<script>
import { get } from "vuex-pathify";
import MainHeader from "@/components/MainHeader";
import ViTop from "@/components/ViTop";
import MainFooter from "@/components/MainFooter";

export default {
  components: { MainHeader, ViTop, MainFooter },
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
  computed: {
    viModeEnabled: get("visualImpairedModeSettings/enabled"),
  },
};
</script>

<style lang="scss">
@import "../styles/mixins.scss";
.blog {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  & > .container {
    flex: 1;
  }
  .blog_ {
    &_header {
      position: fixed;
      z-index: 123;
      width: 100%;
      background: white;
    }
  }
}
</style>
