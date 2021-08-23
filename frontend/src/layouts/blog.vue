<template>
  <div class="blog">
    <ViTop/>
    <MainHeader/>

    <div class="container">
      <nuxt/>
    </div>
  </div>

</template>
<script>
import MainHeader from "@/components/MainHeader";
import ViTop from "@/components/ViTop";

export default {
  components: {MainHeader, ViTop},
  head() {
    return {
      title: this.$t('meta.title'),
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.$t('meta.description')
        },
        {
          hid: 'keywords',
          name: 'keywords',
          content: this.$t('meta.keywords')
        }
      ]
    }
  }
}
</script>

<style lang="scss">
@import "../styles/mixins.scss";
</style>