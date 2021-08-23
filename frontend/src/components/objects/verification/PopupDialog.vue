<template>
    <div class="popup__wrapper">
        <div class="popup__scroll">
            <div class="popup__in --md">
                <a class="popup__close" @click="$emit('closed')"></a>
                <h5 class="popup__title">{{ $t('objects.verificationPopupTitle') }}</h5>
                <p class="popup__text">{{ $t('objects.verificationPopupQuestion', {objectName}) }}</p>
                <div class="popup__buttons">
                    <button type="button" class="popup__button --no" @click.prevent="$emit('rejected')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M1.28 1.28H0V9.6H0.38976C1.33248 9.6 2.1984 10.1178 2.64448 10.9478L4.15936 13.7658L4.48 16H5.12C7.04 16 7.04 14.2406 7.04 12.8C7.04 12.3302 6.4 10.24 6.4 10.24H12.48C13.3637 10.24 14.08 9.52366 14.08 8.64C14.08 8.11661 13.8287 7.65191 13.4402 7.36C13.8287 7.06809 14.08 6.60339 14.08 6.08C14.08 5.39275 13.6467 4.80671 13.0384 4.58014C13.2883 4.29786 13.44 3.92665 13.44 3.52C13.44 2.74593 12.8903 2.10025 12.16 1.95201V1.28C12.16 0.573076 11.5869 0 10.88 0H4.36992C3.21088 0 2.09984 0.46016 1.28 1.28Z"
                                  fill="white"/>
                        </svg>
                        <span>{{ $t('no') }}</span>
                    </button>
                    <button type="button" class="popup__button --yes" @click.prevent="$emit('confirmed')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M1.28 14.72H0V6.4H0.38976C1.33248 6.4 2.1984 5.88224 2.64448 5.05216L4.15936 2.23424L4.48 0H5.12C7.04 0 7.04 1.75936 7.04 3.2C7.04 3.66976 6.4 5.76 6.4 5.76H12.48C13.3637 5.76 14.08 6.47634 14.08 7.36C14.08 7.88339 13.8287 8.34809 13.4402 8.64C13.8287 8.93191 14.08 9.39661 14.08 9.92C14.08 10.6072 13.6467 11.1933 13.0384 11.4199C13.2883 11.7021 13.44 12.0734 13.44 12.48C13.44 13.2541 12.8903 13.8997 12.16 14.048V14.72C12.16 15.4269 11.5869 16 10.88 16H4.36992C3.21088 16 2.09984 15.5398 1.28 14.72Z"
                                  fill="white"/>
                        </svg>
                        <span>{{ $t('yes') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PopupDialog",
        props: [
            'objectName'
        ]
    }
</script>

<style scoped>

</style>