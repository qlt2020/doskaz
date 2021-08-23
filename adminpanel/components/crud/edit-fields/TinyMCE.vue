<template>
    <editor :value="value" @input="$emit('input', $event)" :init="tinymceConfig"
            plugins="link image imagetools table"
            toolbar="blocks image blockquote link table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | forecolor backcolor"/>

</template>

<script>
    import Editor from '@tinymce/tinymce-vue';

    export default {
        name: "TinyMCE",
        props: [
            'value'
        ],
        components: {
            Editor
        },
        computed: {
            tinymceConfig() {
                return {
                    language: 'ru',
                    plugins: '',
                    toolbar: '',
                    height: 500,
                    language_url: '/admin/tinymce/langs/ru.js',
                    images_upload_handler: (blobInfo, success, failure) => {
                        this.$axios.post('/api/storage/upload', blobInfo.blob())
                            .then(({data: {path}}) => success(path))
                            .catch(e => failure(e));
                    }
                }
            },
        }
    }
</script>

<style>
    .tox-notifications-container {
        display: none !important;
    }
</style>
