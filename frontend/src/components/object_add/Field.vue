<template>
  <div class="add-object__line --lrg" :class="{ error: error }">
    <slot></slot>
    <div class="add-object__error">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  name: "Field",
  props: ["error"]
};
</script>

<style scoped></style>
