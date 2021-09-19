<template>
    <field-wrapper :error="error" :label="label" :required="required">
        <div class="row justify-content-start">
            <div v-for="(photo, index) in value" :key="index" class="col-sm-4 col-md-3 col-xl-2 mb-4">
                <a :href="photo" target="_blank"><img :src="photo" alt="" style="max-width: 100%"/></a>
                <b-btn variant="danger" title="Удалить" squared style="position: absolute; top: 0; right: 10px"
                       @click.prevent="deleteItem(index)"><i class="mdi mdi-close"/></b-btn>
            </div>

            <div v-for="(photo, index) in currentUploadingFiles" :key="`uploading${index}`"
                 class="col-xs-4 col-sm-3 col-lg-2 mb-4">
                <div style="display: block" class="vld-parent">
                    <loading :active="true" :is-full-page="false"/>
                    <img :src="photo.preview" style="max-width: 100%" alt=""/>
                </div>
                <!--<b-btn variant="danger" title="Отменить загрузку" squared @click="cancelItem(photo)"
                       style="position: absolute; top: 0; right: 10px; z-index: 12"><i class="mdi mdi-close"/></b-btn>-->
            </div>
        </div>

        <input type="file" class="hide" ref="fileInput" accept="image/*" multiple @change="filesSelected">

        <div class="form-group row no-gutters">
            <button class="btn btn-secondary" @click.prevent="upload">Загрузить</button>
        </div>
    </field-wrapper>
</template>

<script>
    import connectField from "../connectField";
    import FieldWrapper from "../FieldWrapper";
    import Loading from "vue-loading-overlay";
    import "vue-loading-overlay/dist/vue-loading.css";
    import queue from 'async/queue';

    export default {
        name: "ImagesCollection",
        components: {FieldWrapper, Loading},
        props: [
            'path',
            'label',
            'required',
            'disabled'
        ],
        data() {
            return {
                uploading: []
            }
        },
        created() {
            this.queue = queue((task, cb) => this.$axios.post("/api/storage/upload", task.file,{
                headers: {
                    'Content-Type': task.file.type
                }
            })
                .then(({data: {path}}) => cb(null, path))
                .catch(cb), 2);
        },
        methods: {
            filesSelected(event) {
                Array.from(event.target.files).forEach(file => {
                    const uploadingFile = {
                        file,
                        preview: null
                    }

                    const reader = new FileReader();

                    reader.onload = e => {
                        uploadingFile.preview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    this.uploading.push(uploadingFile)

                    console.log(file)

                    this.queue.push(uploadingFile, (e, res) => {
                        uploadingFile.file = null;
                        this.addItem(res)
                    });
                })
            },
            changeValue(index, event) {
                const val = [...this.value]
                val[index] = event.target.value

                this.value = val
            },
            addItem(item) {
                this.value = [...this.value, item]
            },
            deleteItem(index) {
                this.value = this.value.filter((v, i) => i !== index)
            },
            upload() {
                this.$refs.fileInput.click()
            }
        },
        computed: {
            ...connectField(),
            currentUploadingFiles() {
                return this.uploading.filter(f => f.file)
            }
        }
    }
</script>

<style scoped>

</style>
