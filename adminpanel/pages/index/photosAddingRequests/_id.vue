<template>
    <div>
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Запрос на добавление фото</h4>
            </div>
        </div>
        <b-card>
            <div class="form">
                <images-collection :disabled="false" label="Фото" v-model="item.photos"/>
                <button @click="approve" :disabled="item.status !== 'on_review' || isLoading || item.photos.length === 0" class="btn btn-primary">
                    Принять
                </button>
            </div>
        </b-card>
    </div>
</template>

<script>
import ImagesCollection from "@/components/ImagesCollection";

export default {
    components: {ImagesCollection},
    data() {
        return {
            isLoading: false
        }
    },
    async asyncData({$axios, params}) {
        const item = await $axios.$get(`/api/admin/photosAdding/${params.id}`)
        return {item}
    },
    methods: {
        async approve() {
            this.isLoading = true
            try {
                await this.$axios.$put(`/api/admin/photosAdding/${this.item.id}`, {
                    photos: this.item.photos
                })
                await this.$axios.$post(`/api/admin/photosAdding/${this.item.id}/approve`)
                await this.$nuxt.refresh()
                this.$bvToast.toast('Запрос одобрен', {
                    toaster: 'b-toaster-top-center',
                    variant: 'success',
                    noAutoHide: false,
                    solid: true,
                    appendToast: false
                })

            }
            catch (e) {
                this.$bvToast.toast('Ошибка', {
                    toaster: 'b-toaster-top-center',
                    variant: 'danger',
                    noAutoHide: false,
                    solid: true,
                    appendToast: false
                })
            }
            finally {
                this.isLoading = false
            }

        }
    }
}
</script>

<style scoped>

</style>
