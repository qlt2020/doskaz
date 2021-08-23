<template>
    <div class="popup__wrapper" v-if="show">
        <div class="popup__scroll">
            <div class="popup__in --md">
                <a class="popup__close" @click="show = false"></a>
                <h5 class="popup__title">Готово!</h5>
                <p class="popup__text">
                    Сообщение успешно отправлено. Объект появиться на карте в течение суток после проверки модератором
                </p>
                <div class="popup__buttons" style="justify-content: center">
                    <button class="popup__button --yes" style="text-align: center; padding: 14px 0 16px 14px"  @click="show = false">
                        <span style="margin: 0">
                            Ок
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PostAdditionMessage",
        data() {
            return {show: false}
        },
        mounted() {
            this.show = !!this.$cookies.get('objectAdditionSubmitted')
            this.$cookies.remove('objectAdditionSubmitted')
        }
    }
</script>

<style scoped>

</style>