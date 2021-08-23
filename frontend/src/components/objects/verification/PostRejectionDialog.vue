<template>
    <div class="popup__wrapper">
        <div class="popup__scroll">
            <div class="popup__in --md">
                <a class="popup__close" @click="$emit('closed')"></a>
                <h5 class="popup__title">Нашли ошибку?</h5>
                <p class="popup__text">Пожалуйста, оставьте свой отзыв. Это не займёт много времени, но поможет сделайть наш сайт лучше.</p>
                <div class="popup__buttons" style="justify-content: center">
                    <button type="button" class="popup__button --yes" style="text-align: center; padding: 14px 0 16px 14px">
                        <span style="margin: 0" @click="$emit('want-to-help')">Я хочу помочь</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PostRejectionDialog",
    }
</script>

<style scoped>

</style>