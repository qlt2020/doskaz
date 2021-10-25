<template>
  <div class="add-object">
    <ViTop />
    <MainHeader v-if="!viModeEnabled" />
    <div class="container">
      <div class="complaint__top">
        <div class="title-back">
          <img
            :src="require('@/assets/icons/back-arrow.svg')"
            @click="$router.back()"
            class="title-back_arrow"
          />
          <h2 class="title-back_title">{{ $t("objectAdding.pageHeader") }}</h2>
        </div>
        <div class="add-object__link-b">
          <span
            class="add-object__link"
            v-for="form in forms"
            :key="form.key"
            @click="changeForm(form.key)"
            :class="{ active: form.key === selectedForm }"
          >
            {{ $t(`objectAdding.formType.${form.key}`) }}
          </span>
        </div>
      </div>
    </div>
    <div class="complaint__wrapper">
      <client-only>
        <ObjectAddContent />
      </client-only>
      
    </div>
    <MainFooter />
  </div>
</template>

<script>
import MainHeader from "@/components/MainHeader";
import ObjectAddContent from "@/components/object_add/ObjectAddContent.vue";
import { get, call } from "vuex-pathify";
import ViTop from "@/components/ViTop";
import MainFooter from "@/components/MainFooter";

export default {
  head() {
    return {
      title: this.$t("objectAdding.pageHeader"),
    };
  },
  middleware: ["authenticated"],
  layout: "complaint",
  components: {
    MainHeader,
    ObjectAddContent,
    ViTop,
    MainFooter,
  },
  async fetch({ store }) {
    return store.dispatch("objectAdding/init");
  },
  mounted() {
    this.$store.dispatch("objectAdding/init");
  },
  methods: {
    ...call("objectAdding", ["changeForm"]),
  },
  computed: {
    viModeEnabled: get("visualImpairedModeSettings/enabled"),
    forms: get("objectAdding/forms"),
    selectedForm: get("objectAdding/data@form"),
  },
};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";
</style>
