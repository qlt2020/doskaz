<template>
    <div>
        <!-- <h4>Мета данные</h4>
         <hr/>
 -->
        <fields :fields="fields" :value="value" @input="$emit('input', $event)" :error="error"/>
        <!-- <text-field :property="`${property}.title`" label="Meta-заголовок"/>
         <text-field :property="`${property}.description`" label="Meta-описание"/>
         <text-field :property="`${property}.keywords`" label="Meta-ключевые слова"/>
         <text-field :property="`${property}.ogTitle`" label="OG-заголовок"/>
         <text-field :property="`${property}.ogDescription`" label="OG-описание"/>
         <image-field :property="`${property}.ogImage`" label="OG-изображение"/>-->
    </div>
</template>

<script>
    import Fields from "../Fields";
    import ImageUpload from "./ImageUpload";

    export default {
        name: "MetaData",
        components: {Fields},
        props: {
            value: {
                type: Object,
                default() {
                    return {}
                }
            },
            error: {
                default() {
                    return {}
                }
            }
        },
        computed: {
            fields() {
                return [
                    {key: 'title', label: 'Meta-заголовок'},
                    {key: 'description', label: 'Meta-описание'},
                    {key: 'keywords', label: 'Meta-ключевые слова'},
                    {key: 'ogTitle', label: 'OG-заголовок'},
                    {key: 'ogDescription', label: 'OG-описание'},
                    {key: 'ogImage', label: 'OG-изображение', type: ImageUpload},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
