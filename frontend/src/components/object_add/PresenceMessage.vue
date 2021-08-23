<template>
    <div class="popup__wrapper">
        <div class="popup__scroll">
            <div class="popup__in --md">
                <a class="popup__close" @click="close"></a>
                <h5 class="popup__title">{{ $t('objectAdding.presenceMessageHeader') }}</h5>
                <p class="popup__text">
                    {{ $t('objectAdding.presenceMessage', {name}) }}
                </p>
                <div class="popup__buttons" style="justify-content: center">
                    <button class="popup__button --yes" style="text-align: center; padding: 14px 0 16px 14px"  @click="close">
                        <span style="margin: 0">
                            {{ $t('ok') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PresenceMessage",
        props: {
            name: String
        },
        methods: {
            close() {
                this.$emit('closed')
            }
        }
    }
</script>

<style scoped>

</style>