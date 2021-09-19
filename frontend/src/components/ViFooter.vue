<template>
  <div class="vi__footer --bcolor">
    <a href="" class="vi__footer-link">{{ $t("mainMenu.help") }}</a>
    <nuxt-link :to="localePath({ name: 'about' })" class="vi__footer-link">{{
      $t("mainMenu.about")
    }}</nuxt-link>
    <nuxt-link
      :to="localePath({ name: 'blog-cat-slug' })"
      class="vi__footer-link"
      >{{ $t("mainMenu.blog") }}</nuxt-link
    >
    <nuxt-link :to="localePath({ name: 'contacts' })" class="vi__footer-link">{{
      $t("mainMenu.contacts")
    }}</nuxt-link>
  </div>
</template>
<style lang="scss">
.vi {
  &__footer {
    border-top: 3px solid;
    padding: 40px 0;
    display: flex;
    &-link {
      line-height: 24px;
      margin: 0 0 0 60px;
      &:first-child {
        margin: 0;
      }
    }
  }
}
</style>
