<template>
  <div class="modal" v-if="complaintPostSubmitMessage">
    <div class="modal__content">
      <div class="modal__head">
        <div class="modal__title">
          {{ $t("profile.tickets.postSubmitMessage") }}
        </div>
        <div class="close-button" @click="complaintPostSubmitMessage = null">
          <img src="@/assets/icons/close_h.svg" alt="" />
        </div>
      </div>
      <p class="message">
        <nuxt-link to="/profile">{{ $t("profile.tickets.tabTitle") }}</nuxt-link>
      </p>
      <div class="btn btn_green">
        <img src="@/assets/icons/download_icon.svg" alt="" />
        <a
          :href="
            `/api/complaints/${complaintPostSubmitMessage.complaintId}/doc`
          "
          target="_blank"
        >
          {{ $t("profile.tickets.downloadComplaintButton") }}
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "PostSubmitMessage",
  data() {
    return { complaintPostSubmitMessage: null };
  },
  mounted() {
    this.complaintPostSubmitMessage = this.$cookies.get(
      "complaintPostSubmitMessage"
    );
    this.$cookies.remove("complaintPostSubmitMessage");

    if (this.$route.query.test_message) {
      this.complaintPostSubmitMessage = { complaintId: 42 };
    }
  },
};
</script>

<style scoped lang="scss">
.modal__title {
  font-style: normal;
  font-weight: bold;
  font-size: 22px;
  margin-bottom: 10px;
  width: min-content;
  @media all and (max-width: 991px) {
    font-size: 16px;
  }
}
.message {
  font-family: "SFProDisplay";
  font-style: normal;
  font-weight: bold;
  font-size: 22px;
  line-height: inherit;
  width: 400px;
  a {
    color: #2d9cdb;
  }

  @media all and (max-width: 991px) {
    width: 300px;
  }
}
.btn_green {
  margin-top: 30px;
  background: #27ae60;
  img {
    margin-right: 10px;
  }
  a {
    color: #fff;
    font-family: "SFProDisplay";
    font-style: normal;
    font-weight: bold;
    font-size: 18px;
  }
}
</style>
