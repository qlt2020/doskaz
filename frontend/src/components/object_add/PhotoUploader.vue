<template>
  <div>
    <div class="photo-input__wrapper">
      <div
        class="photo-input required"
        v-for="(slot, index) in slots"
        :key="index"
      >
        <loading
          :active="slot.isLoading"
          :is-full-page="false"
          :style="{ 'z-index': 10 }"
        />
        <input
          type="file"
          accept="image/*"
          @change="onFileSelected($event, slot)"
          multiple="multiple"
        />
        <span
          class="photo-input__bg"
          v-if="slot.preview"
          :style="{
            'background-image': `url(${slot.preview})`,
            'background-size': 'cover'
          }"
        ></span>
        <span
          class="photo-input__remove"
          @click.prevent="clearSlot(slot)"
          v-if="!slot.isLoading && slot.preview"
        ></span>
      </div>
    </div>
    <button
      type="button"
      class="add-link add-link_step"
      @click.prevent="addSlot"
    >
      {{ $t("complaint.addMorePhotos") }}
    </button>
  </div>
</template>

<script>
import queue from "async/queue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  name: "PhotoUploader",
  components: {
    Loading
  },
  model: {
    prop: "files",
    event: "change"
  },
  props: ["files"],
  data() {
    return {
      isLoading: false,
      slots: []
    };
  },
  mounted() {
    let isMobile = false;
    (function(a) {
      if (
        /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
          a
        ) ||
        /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
          a.substr(0, 4)
        )
      )
        isMobile = true;
    })(navigator.userAgent || navigator.vendor || window.opera);
    let numSlots = isMobile ? 4 : 6;
    for (let i = 1; i < numSlots; i++) {
      this.addSlot();
    }
  },
  created() {
    this.queue = queue(
      (task, cb) =>
        this.$axios
          .post("/api/storage/upload", task.file)
          .then(({ data: { path } }) => cb(null, path))
          .catch(cb),
      2
    );
    this.queue.drain(() =>
      this.$nextTick(() => this.$emit("is-uploading", false))
    );
  },
  destroyed() {
    this.queue.kill();
  },
  methods: {
    addSlot() {
      const slot = {
        preview: null,
        file: null,
        uploadedFile: null,
        isLoading: false
      };
      this.slots.push(slot);
      return slot;
    },
    uploadToSlot(file, slot) {
      const reader = new FileReader();
      reader.onload = e => {
        slot.preview = e.target.result;
      };
      reader.readAsDataURL(file);
      slot.file = file;
      slot.isLoading = true;
      this.$emit("is-uploading", true);
      this.queue.push(slot, (e, res) => {
        slot.isLoading = false;
        slot.file = null;
        slot.uploadedFile = res;
      });
    },
    clearSlot(slot) {
      slot.preview = null;
      slot.file = null;
      slot.uploadedFile = null;
    },
    onFileSelected(e, slot) {
      if (e.target.files.length === 1) {
        return this.uploadToSlot(e.target.files[0], slot);
      }

      const emptySlotsCount = this.emptySlots.length;

      const addCount = e.target.files.length - emptySlotsCount;
      if (addCount > 0) {
        for (let i = 0; i < addCount; i++) {
          this.addSlot();
        }
      }

      for (const file of e.target.files) {
        this.uploadToSlot(file, this.emptySlots[0]);
      }
    }
  },
  computed: {
    emptySlots() {
      return this.slots.filter(
        slot => !slot.file && !slot.isLoading && !slot.uploadedFile
      );
    },
    uploaded() {
      return this.slots
        .filter(slot => slot.uploadedFile)
        .map(slot => slot.uploadedFile);
    }
  },
  watch: {
    uploaded(v) {
      this.$emit("change", v);
    }
  }
};
</script>

<style scoped lang="scss">
.add-link {
  margin-top: 15px;
  display: inline-block;
  font-family: SFProDisplay;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 19px;
  color: #2d9cdb;
  border-bottom: 1px solid #2d9cdb;
  @media all and (max-width: 768px) {
    margin: 17px 0 0;
  }
}
</style>
