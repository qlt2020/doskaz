<template>
  <span :style="{ 'background-image': 'url(' + avatar + ')' }"></span>
</template>

<script>
export default {
  name: "UserAvatar",
  props: {
    value: [String, null]
  },
  computed: {
    avatar() {
      return this.value || "/static/ava.png";
    }
  }
};
</script>

<style scoped></style>
