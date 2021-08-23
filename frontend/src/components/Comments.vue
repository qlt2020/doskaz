<template>
    <div class="comments-block">
        <div class="comments-list">
            <div class="person-comment">
                <div>
                    <img
                            :src="comment.userAvatar"
                            class="avatar"
                            v-if="!!comment.userAvatar"
                    />
                    <div
                            v-else
                            :style="`background:${colorGenerator()}`"
                            class="no-avatar"
                    ></div>
                </div>
                <div class="person-info">
                    <div>
                        <username class="person-name" :value="comment.userName"/>
                        <span v-if="parentItem">
              <small class="small" style="margin:0 10px;">{{ $t('blog.commentReplied') }}</small>
                            <username tag="small" class="small" :value="parentItem.userName"/>
            </span>
                        <p class="text-comment">{{ comment.text }}</p>
                        <span class="date-comment small">{{formatDate(comment.createdAt)}}</span>
                        <span class="small res" v-if="$store.state.authentication.user"
                              @click="requestComment(comment.id)"
                        >{{ $t('blog.commentReply') }}</span
                        >
                    </div>
                    <transition-group class="slide-group" name="slide" mode="out-in">
                        <Comments
                                v-show="showAllComments"
                                v-for="(replie, i) in comment.replies"
                                :comment="replie"
                                :index="i"
                                :key="replie.id"
                                :parentItem="comment"
                        >
                        </Comments>
                    </transition-group>
                    <Comments
                            v-if="comment.replies.length > 0"
                            @formFocus="formFocus"
                            v-show="!showAllComments"
                            :comment="comment.replies[0]"
                            :key="comment.replies.id"
                            :parentItem="comment"
                    >
                    </Comments>

                    <p
                            class="check-comments"
                            v-if="showAllComments === false && comment.replies.length > 1"
                            @click="showAllComments = true"
                    >
                        {{ $t('blog.showComments') }} &#11206;
                    </p>
                    <p
                            class="check-comments"
                            v-if="showAllComments === true && comment.replies.length > 1"
                            @click="showAllComments = false"
                    >
                        {{ $t('blog.hideComments') }} &#11205;
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Comments from "~/components/Comments";
    import {formatDistanceToNow} from "date-fns";
    import ru from 'date-fns/locale/ru';
    import kz from 'date-fns/locale/kk';
    import Username from "~/components/Username";
    const locales = {ru, kz}

    export default {
        name: "Comments",
        components: {Username, Comments},
        props: ["comment", "comments", "parentItem"],
        data: () => ({
            commentText: "",
            postId: "",
            commentId: undefined,
            showAllComments: false
        }),
        methods: {
            firstComment(arr) {
                return arr[0];
            },
            formatDate(date) {
                return formatDistanceToNow(new Date(date), {
                    addSuffix: true,
                    locale: locales[this.$i18n.locale]
                });
            },
            formFocus(id) {
                this.$emit("formFocus", id);
            },
            requestComment(id) {
                this.$emit("formFocus", id);
            },
        }
    };
</script>

<style lang="scss" scoped>
    .res {
        cursor: pointer;
    }

    .slide-enter-active,
    .slide-leave-active {
        transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
    }

    .slide-enter,
    .slide-leave-to {
        opacity: 0;
        transform: translateX(-20px);
    }

    .check-comments {
        margin-top: 30px;
        font-size: 14px;
        color: #5b6067;
    }

    .comments-block {
        h4 {
            font-size: 22px;
            line-height: 30px;
            margin: 27px 0 17px 0;
        }

        .replie-comment {
            margin-top: 29px;
        }

        .person-comment,
        .replie-comment {
            display: flex;

            .person-info {
                margin-left: 10px;

                .person-name {
                    font-weight: bold;
                    font-size: 16px;
                    line-height: 20px;
                    @media all and (max-width: 1023px) {
                        font-size: 14px;
                    }
                    @media all and (max-width: 768px) {
                        font-size: 12px;
                    }
                }

                .text-comment {
                    font-size: 16px;
                    line-height: 20px;
                    @media all and (max-width: 1023px) {
                        font-size: 14px;
                    }
                    @media all and (max-width: 768px) {
                        font-size: 12px;
                    }
                }

                .date-comment {
                    margin-right: 40px;
                }

                .small {
                    color: #5b6067;
                    font-size: 14px;
                }

                .comments-block {
                    padding: 18px 0 0;
                }
            }

            .avatar {
                height: 30px;
                width: 30px;
                border-radius: 50%;
            }

            .no-avatar {
                height: 30px;
                width: 30px;
                border-radius: 50%;
            }
        }
    }
</style>
