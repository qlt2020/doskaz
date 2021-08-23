<template>
  <div class="popup__wrapper">
    <div class="popup__scroll">
      <div class="popup__in --md">
        <loading :active="isLoading" :is-full-page="false" :style="{'z-index': 10}"/>
        <span class="popup__close" @click="close"></span>
        <h5 class="popup__title --s-m">Добавить фото к объекту</h5>
        <p class="popup__text">{{ object.title }}, {{ object.address }}</p>
        <div class="popup__photos">
          <object-add-photo-uploader v-model="photos" @is-uploading="isUploading = $event"/>
        </div>
        <div class="popup__buttons">
          <a @click.prevent="close" class="popup__button --no --text"><span>Отмена</span></a>
          <a @click.prevent="submit" class="popup__button --yes --text"><span>Загрузить</span></a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {get} from "vuex-pathify";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

export default {
  middleware: ['authenticated'],
  data() {
    return {
      isLoading: false,
      isUploading: false,
      photos: []
    }
  },
  components: {
    Loading
  },
  computed: {
    object: get('object/item'),
  },
  methods: {
    async submit() {
      this.isLoading = true;
      try {
        await this.$axios.post(`/api/objects/${this.$route.params.id}/addPhotos`, {
          photos: this.photos
        })
        this.isLoading = false
        this.close()
      } finally {
        this.isLoading = false
      }
    },
    close() {
      if (this.isLoading) {
        return;
      }
      this.$router.push(this.localePath({
        name: 'objects-id', params: {
          id: this.$route.params.id
        },
        query: {
          tab: 'photos'
        }
      }))
    }
  }
}
</script>

<style scoped>

</style>