<template>
  <div class="user-comments__item">
    <div class="user-comments__info">
      <div class="user-comments__title">
        {{ item.title }}
      </div>
      <div class="user-comments__date">
        {{ $dateFns.format(new Date(item.date), "dd.MM.yyyy") }}
      </div>
    </div>
    <div
      class="user-comments__image"
      v-bind:style="{ 'background-image': 'url(' + item.image + ')' }"
    ></div>
    <div class="user-comments__description">
      <p class="user-comments__text">{{ item.text }}</p>
    </div>
    <div @click="setClickedObj(item.objectId)">
      <nuxt-link
        class="user-comments__btn" 
        :to="localePath({
          name: 'objects-id',
          params: { id: item.objectId },
          query: { zoom: 19 }
        })"
      >
        {{ $t("profile.view") }}
      </nuxt-link>
    </div>
  </div>
</template>

<script>
import ru from "date-fns/locale/ru";
import kz from "date-fns/locale/kk";
import { formatRelative } from "date-fns";
import capitalize from "lodash/capitalize";

const locales = { ru, kz };

export default {
  props: ["item"],
  methods: {
    openLink() {
      this.$router.push(this.localePath(this.link));
    },
  },
  computed: {
    link() {
      return this.item.type === "post"
        ? {
            name: "blog-cat-slug",
            params: { cat: this.item.categorySlug, slug: this.item.slug },
          }
        : { name: "objects-id", params: { id: this.item.objectId } };
    },
  },
  methods: {
    setClickedObj(id) {
      this.$store.commit("map/SET_CLICKED_OBJECT_ID", id);
    }
  }
};
</script>
