<template>
  <div>
    <popup-dialog
      v-if="!showPostRejectMessage"
      :object-name="objectName"
      @closed="close"
      @rejected="reject"
      @confirmed="confirm"
    />

    <post-rejection-dialog
      v-else
      @closed="close"
      @want-to-help="
        $router.push(
          localePath({
            name: 'objects-id-review',
            params: { id: $route.params.id }
          })
        )
      "
    />
  </div>
</template>

<script>
import PopupDialog from "~/components/objects/verification/PopupDialog";
import PostRejectionDialog from "~/components/objects/verification/PostRejectionDialog";
import { call } from "vuex-pathify";

export default {
  components: { PostRejectionDialog, PopupDialog },
  middleware: ["authenticated"],
  props: ["objectName"],
  data() {
    return {
      showPostRejectMessage: false
    };
  },
  methods: {
    close() {
      this.$router.push(
        this.localePath({
          name: "objects-id",
          params: { id: this.$route.params.id }
        })
      );
    },
    submitVerification: call("object/submitVerification"),
    async confirm() {
      await this.submitVerification("confirm");
      return this.$router.push(
        this.localePath({
          name: "objects-id",
          params: { id: this.$route.params.id }
        })
      );
    },
    async reject() {
      await this.submitVerification("reject");
      this.showPostRejectMessage = true;
    }
  }
};
</script>

<style scoped></style>
