<template>
    <div>
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Изображение" v-model="fileName" :disabled="!value"/>
            <div class="input-group-append">
                <button class="btn btn-info" type="button" @click.prevent="$refs.image.click" :disabled="isUploading"
                        v-if="!value">
                    <span v-if="!isUploading">Выбрать</span>
                    <span v-else>Загрузка ({{uploadProgress}}%)</span>
                </button>
                <b-button class="btn btn-success" type="button" v-if="value"
                          @click.prevent="showCropModal">
                    <i class="mdi mdi-crop"/>
                </b-button>
                <button class="btn btn-danger" type="button" @click.prevent="removeImage" v-if="value">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <input type="file" class="hide" ref="image" accept="image/*" @change="imageSelected">
        </div>

        <div ref="previewBlock" class="preview-block">
            <img ref="previewImage"
                 class="preview-image"
                 :src="previewLink"
                 v-if="previewLink"
                 alt="preview">
        </div>

        <b-modal ref="cropModal" size="lg" centered title="BootstrapVue" no-close-on-backdrop>
            <div class="img-container">
                <img ref="cropImage" :src="uploadedImage" v-if="uploadedImage" alt="Picture">
                <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">

                    <label class="btn btn-primary"
                           v-for="aspectRatioButton in aspectRatioButtons"
                           :key="aspectRatioButton.value"
                           v-bind:class="{active:cropOptions.aspectRatio===aspectRatioButton.value}"
                           @click="changeAspectRation(aspectRatioButton.value)">
                        <span class="docs-tooltip">
                            {{aspectRatioButton.title}}
                        </span>
                    </label>
                </div>
            </div>
            <div slot="modal-footer" class="w-100">
                <b-button size="sm" class="float-right" variant="primary" @click="show=finishCrop()">
                    Принять
                </b-button>
                <b-button size="sm" class="float-right" variant="danger" @click="$refs.cropModal.hide()">
                    Отмена
                </b-button>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import Cropper from 'cropperjs';
    import 'cropperjs/dist/cropper.css';

    export default {
        name: "ImageUpload",
        props: [
            'value'
        ],
        data() {
            return {
                isUploading: false,
                uploadProgress: 0,
                cropper: null,
                defaultAspectRatio: 16 / 9,
                cropOptions: {
                    autoCropArea: 0.5,
                    viewMode: 3,
                    aspectRatio: null,
                    data: null
                },
                croppedImageUrl: '',
                aspectRatioButtons: [
                    {
                        value: 16 / 9,
                        title: '16:9'
                    },
                    {
                        value: 4 / 3,
                        title: '4:3'
                    },
                    {
                        value: 1,
                        title: '1:1'
                    },
                    {
                        value: 2 / 3,
                        title: '2:3'
                    },
                ]
            }
        },
        mounted() {
        },
        methods: {
            async imageSelected(e) {
                const [file] = e.target.files;
                const {data} = await this.uploadImage(file);

                this.updateValue({
                    image: data.path,
                    name: file.name,
                })
            },
            updateValue(newValue) {
                this.$emit('input', newValue);
            },
            async uploadImage(image) {
                this.isUploading = true;
                try {
                    return await this.$axios.post('/api/storage/upload', image, {
                        onUploadProgress: ({total, loaded}) => {
                            this.uploadProgress = Math.round(loaded / total * 100)
                        },
                    });
                } finally {
                    this.isUploading = false
                }
            },
            removeImage() {
                this.updateValue(null);
                this.croppedImageUrl = '';
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropOptions = {
                        autoCropArea: 0.5,
                        aspectRatio: 16 / 9,
                        data: null
                    };
                }
            },
            showCropModal() {
                this.$refs.cropModal.show();
                this.$nextTick(() => this.initCropper())
            },
            initCropper() {
                if (this.cropper) {
                    this.cropper.destroy();
                }

                if (!this.cropOptions.aspectRatio) {
                    this.cropOptions.aspectRatio = this.value.cropData ? this.value.cropData.width / this.value.cropData.height : this.defaultAspectRatio;
                }
                this.cropper = new Cropper(this.$refs.cropImage, this.cropOptions);
            },
            finishCrop() {
                this.$refs.cropModal.hide();
                this.cropOptions.data = this.cropper.getData();
                this.croppedImageUrl = this.cropper.getCroppedCanvas().toDataURL();
                this.updateValue({
                    image: this.value.image,
                    name: this.value.name,
                    cropData: this.cropOptions.data
                });
            },
            changeAspectRation(value) {
                this.cropOptions.aspectRatio = value;
                this.initCropper();
            },
        },
        computed: {
            previewLink() {
                if (!this.uploadedImage) {
                    return null;
                }
                if (this.value.cropData && this.value.cropData.y) {
                    return `/api/storage/preview/${encodeURIComponent(this.uploadedImage)}?y=${this.value.cropData.y}&x=${this.value.cropData.x}&width=${this.value.cropData.width}&height=${this.value.cropData.height}`
                }
                return this.uploadedImage
            },
            fileName: {
                get() {
                    return this.value ? this.value.name : '';
                },
                set(name) {
                    if (!this.value) {
                        return;
                    }
                    this.updateValue({...this.value, name})
                }
            },
            uploadedImage() {
                if (!this.value) {
                    return ''
                }
                return this.value.image;
            },
        }
    }
</script>

<style scoped>
    .img-container img {
        max-width: 100%;
    }

    img {
        max-width: 100%;
    }

    .preview-block {
        margin-top: 10px;
        overflow: hidden;
        width: 25%;
    }
</style>
