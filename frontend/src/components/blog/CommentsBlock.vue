<template>
    <div class="blog__inside-comments">
        <h4>{{ $tc('blog.commentsCount', comments.count) }}</h4>
        <span class="custom-dropdown">
            <select v-model="comments_sort" @change="sortedComment()">
                <option v-for="option in sortOptions" :key="option" :value="option">{{ $t(`blog.commentsSort.${option}`) }}</option>
            </select>
          </span>

        <Comments
                @formFocus="formFocus"
                v-for="comment in comments.items"
                :key="comment.id"
                :comment="comment"
                :comments="comments.items"
        />
        <form class="comment-form" v-if="$store.state.authentication.user">

              <textarea
                      ref="comment"
                      :placeholder="$t('blog.commentInputPlaceholder')"
                      name="comment"
                      rows="2"
                      v-model="commentText"
                      @input="resizeHeight"
              />
            <div class="form-actions">
                <div>
                    <button
                            type="button"
                            class="send-button"
                            @click="sendComment()"
                    >
                        <img src="@/assets/icons/send.svg" alt=""/>
                    </button>
                    <button
                            type="button"
                            @click="clearComment()"
                            class="clear-button"
                            v-if="commentText.length > 0"
                    >
                        <img src="@/assets/icons/close.svg" alt=""/>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import Comments from "../Comments";

    export default {
        name: "CommentsBlock",
        props: ['id'],
        components: {Comments},
        data() {
            return {
                comments_sort: 'newest',
                commentText: "",
                comments: {
                    count: 0,
                    items: []
                },
                commentId: Number
            };
        },
        mounted() {
            this.$axios.$get(`/api/blog/posts/${this.id}/comments`).then(res => {
                this.comments = res;
            }).catch(e => console.log(e.response));
        },
        methods: {
            async sortedComment() {
                const sortVariants = {
                    newest: {sortOrder: "desc"},
                    oldest: {sortOrder: "asc"},
                    popular: {sortBy: "popularity"},
                }

                this.comments = await this.$axios.$get(`/api/blog/posts/${this.id}/comments`, {
                    params: sortVariants[this.comments_sort]
                })
            },
            formFocus(id) {
                this.$nextTick(() => {
                    this.$refs.comment.focus();
                });
                this.commentId = id;
            },
            sendComment() {
                this.$axios
                    .post(`/api/blog/posts/${this.id}/comments`, {
                        text: this.commentText,
                        parentId: this.commentId
                    })
                    .then(res => {
                        console.log(res);
                        this.$axios.get(`/api/blog/posts/${this.id}/comments`).then(res => {
                            this.comments = res.data;
                        });
                        this.commentId = null;
                        this.commentText = "";
                        //   this.$store.commit("setId", null);
                    });
                // this.$store.commit("setId", null);
            },
            clearComment() {
                this.commentText = "";
            },
            resizeHeight(e) {
                e.target.style.height = "70px";
                e.target.style.height = e.target.scrollHeight + "px";
            },
        },
        computed: {
            sortOptions() {
                return ['newest', 'oldest', 'popular']
            }
        }
    }
</script>

<style scoped>

</style>