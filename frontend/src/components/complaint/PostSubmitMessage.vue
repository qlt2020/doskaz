<template>
    <div class="popup__wrapper" v-if="complaintPostSubmitMessage">
        <div class="popup__scroll">
            <div class="popup__in --md">
                <a class="popup__close" @click="complaintPostSubmitMessage = null"></a>
                <p class="popup__text">
                    Мы сформировали жалобу в .docx формате, его можно скачать тут или в вашем профиле во вкладке <nuxt-link to="/profile">Мои тикеты</nuxt-link>.
                </p>
                <div class="popup__buttons" style="justify-content: center">
                    <a :href="`/api/complaints/${complaintPostSubmitMessage.complaintId}/doc`" target="_blank" class="popup__button --yes" style="text-align: center; padding: 14px 0 16px 14px">
                    <span style="margin: 0" @click="$emit('want-to-help')">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="-2 4 24 24" width="20" height="20">
                            <style type="text/css">
                                .st0{fill:none;stroke:#FFFFFF;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
                            </style>
                            <path class="st0" d="M21,19.7v4.9c0,1.4-1.1,2.4-2.4,2.4H1.4C0.1,27-1,25.9-1,24.6v-4.9"/>
                            <polyline class="st0" points="3.9,13.6 10,19.7 16.1,13.6 "/>
                            <line class="st0" x1="10" y1="19.7" x2="10" y2="5"/>
                        </svg>
                        Скачать
                    </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PostSubmitMessage",
        data() {
            return {complaintPostSubmitMessage: null}
        },
        mounted() {
            this.complaintPostSubmitMessage = this.$cookies.get('complaintPostSubmitMessage');
            this.$cookies.remove('complaintPostSubmitMessage')

            if(this.$route.query.test_message) {
                this.complaintPostSubmitMessage = {complaintId: 42}
            }
        }
    }
</script>

<style scoped>

</style>