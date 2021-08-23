<template>
    <div class="popup__wrapper">
        <div class="popup__scroll">
            <div class="popup__in --sm">
                <span class="popup__close" @click="close"></span>
                <h5 class="popup__title">{{ $t('objects.review.popupTitle') }}</h5>
                <textarea
                        v-model.trim="reviewText"
                        class="popup__textarea textarea"
                        :placeholder="$t('objects.review.textareaPlaceholder')"
                        :disabled="reviewSubmitting"
                />
                <span class="popup__textarea-text">{{ $t('objects.review.textareaHelpText') }}</span>
                <div class="popup__buttons">
                    <div class="timeline__tab-link timeline__tab-link_user">
                        <user-avatar class="avatar" :value="avatar"/>
                        <username class="name" :value="name"/>
                    </div>
                    <button type="button" class="button" @click="createReview"
                            :disabled="reviewText.length < 20 || reviewSubmitting">{{
                        $t('objects.review.submitButtonTitle') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {get, call} from "vuex-pathify";
    import Username from "~/components/Username";
    import UserAvatar from "~/components/UserAvatar";

    export default {
        components: {UserAvatar, Username},
        middleware: ['authenticated'],
        data() {
            return {
                reviewSubmitting: false,
                reviewText: '',
            }
        },
        computed: {
            avatar: get('authentication/user.avatar'),
            name: get('authentication/name')
        },
        methods: {
            submitReview: call('object/submitReview'),
            async createReview() {
                this.reviewSubmitting = true;
                await this.submitReview(this.reviewText)
                this.reviewText = '';
                this.reviewSubmitting = false;
                this.$emit('review-submitted')
                this.close()
            },
            close() {
                this.$router.push(this.localePath({
                    name: 'objects-id', params: {
                        id: this.$route.params.id
                    },
                    query: {
                        tab: 'reviews'
                    }
                }))
            }
        }
    }
</script>

<style scoped>

</style>