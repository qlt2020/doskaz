<template>
  <div>
    <normal-index-page v-if="!viModeEnabled" />
    <vi-index-page v-if="viModeEnabled" />
  </div>
</template>

<script>
import { get } from "vuex-pathify";
import NormalIndexPage from "~/components/NormalIndexPage";
import ViIndexPage from "~/components/ViIndexPage";

export default {
  layout({ store }) {
    return store.get("visualImpairedModeSettings/enabled") ? "default" : "main";
  },
  components: {
    ViIndexPage,
    NormalIndexPage
  },
  async fetch({ store }) {
    return store.dispatch("objectCategories/getCategories");
  },
  computed: {
    viModeEnabled: get("visualImpairedModeSettings/enabled")
  }
};
</script>

<style scoped></style>
