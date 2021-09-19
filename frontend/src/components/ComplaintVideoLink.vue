<template>
  <div class="complaint__line --sm">
    <div class="input">
      <input type="text" id="value" placeholder="http://" />
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      values: []
    };
  },
  props: ["value"]
};
</script>
