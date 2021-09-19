<template>
  <client-only>
    <LoginForm />
  </client-only>
</template>

<script>
import LoginForm from "~/components/LoginForm";

export default {
  layout: "main",
  components: { LoginForm },
  head() {
    return {
      title: this.$t("login.pageTitle")
    };
  }
};
</script>

<style scoped></style>
