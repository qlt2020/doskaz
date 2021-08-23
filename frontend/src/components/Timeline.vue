<template>
    <div class="timeline">
        <div class="timeline__tab-links">
            <div
                    class="timeline__tab-link timeline__tab-link_blog"
                    :class="{'isActive': activeTab===0}"
                    @click="setActiveTab(0)">
        <span>{{ $t('blogTabTitle') }}
            <!--<span class="new">2</span>-->
        </span>
            </div>
            <div class="timeline__tab-link" :class="{'isActive': activeTab===1}" @click="setActiveTab(1)"
                 v-if="isAuthenticated">
                <span>{{ $t('eventsTabTitle') }}</span>
            </div>
            <div class="spacer"></div>
            <nuxt-link
                    :to="localePath({name: 'profile'})"
                    v-if="isAuthenticated"
                    class="timeline__tab-link timeline__tab-link_user"
                    :class="{'isActive': activeTab===2}"
            >
        <user-avatar
                class="avatar"
                :value="user.avatar"
        ></user-avatar>
                <username class="name" :value="name"/>
            </nuxt-link>
            <div
                    v-else
                    class="timeline__tab-link timeline__tab-link_user"
                    @click="showLoginForm"
            >
                <span class="name">{{ $t('loginLinkTitle') }}</span>
            </div>
        </div>
        <div class="timeline__tabs">
            <div class="timeline__tab timeline__tab_blog" :class="{'isActive': activeTab===0}">
                <nuxt-link :to="localePath(`/blog/${post.categorySlug}/${post.slug}`)" class="item" v-for="post in postsShow"
                           :key="post.id">
                    <div
                            class="item__img"
                            v-bind:style="{'background-image': 'url(' +post.previewImage +')'}"
                    ></div>
                    <div class="item__info">
                        <p class="item__date">
                            <span>{{ post.categoryTitle }}</span>
                            <formatted-date element="span" :date="post.publishedAt" format="d MMMM"/>
                        </p>
                        <h3 class="item__title">{{ post.title }}</h3>
                        <p
                                class="item__text"
                                v-html="post.annotation"></p>
                    </div>
                </nuxt-link>
                <div class="item item_link">
                    <nuxt-link :to="localePath({name: 'blog-category'})">
                        <span>{{ $t('blogLink') }}</span>
                    </nuxt-link>
                </div>
            </div>
            <div class="timeline__tab timeline__tab_events" :class="{'isActive': activeTab===1}">
                <div class="scroll">
                    <div class="item" v-for="(event, index) in events" :key="index">
                        <div class="item__date">
                            <formatted-date :date="event.date" format="d MMMM"/>
                        </div>
                        <div class="item__text">
                            <template v-if="event.type === 'object_added'">
                                <span v-if="userId === event.userId">
                                   {{ $t('events.objectAdded.yourself') }} <nuxt-link :to="localePath({name: 'objects-id', params: {id: event.data.id}})"><b>{{ event.data.title }}</b></nuxt-link>
                                </span>
                                <span v-else>
                                    <username tag="b" :value="event.username"/>
                                    {{ $t('events.objectAdded.others') }} <nuxt-link :to="localePath({name: 'objects-id', params: {id: event.data.id}})"><b>{{ event.data.title }}</b></nuxt-link>
                                </span>
                            </template>
                            <template v-if="event.type === 'object_reviewed'">
                                <span v-if="userId === event.userId">
                                    <username tag="b" :value="event.data.username"/>
                                    {{ $t('events.objectReviewed.yourObject') }}
                                    <nuxt-link :to="localePath({name: 'objects-id', params: {id: event.data.id}})"><b>{{ event.data.title }}</b></nuxt-link>
                                </span>
                                <span v-else-if="event.data.userId === userId">
                                    {{ $t('events.objectReviewed.yourself') }} <nuxt-link :to="localePath({name: 'objects-id', params: {id: event.data.id}})"><b>{{ event.data.title }}</b></nuxt-link>
                                </span>
                                <span v-else>
                                    <username tag="b" :value="event.data.username"/>
                                    {{ $t('events.objectReviewed.others') }} <nuxt-link :to="localePath({name: 'objects-id', params: {id: event.data.id}})"><b>{{ event.data.title }}</b></nuxt-link>
                                </span>
                            </template>
                            <template v-if="event.type === 'level_reached'">
                                <span v-if="userId === event.userId">
                                     {{ $t('events.levelReached.yourself', {level: event.data.level }) }}
                                </span>
                                <span v-else>
                                    <username tag="b" :value="event.username"/>
                                    {{ $t('events.levelReached.others', {level: event.data.level}) }}
                                </span>
                            </template>
                            <template v-if="event.type === 'blog_comment_replied'">
                                <span v-if="userId === event.data.userId">
                                     {{ $t('events.blogCommentReplied.yourself') }} <nuxt-link :to="localePath({name: 'blog-cat-slug', params: {
                                         cat: event.data.categorySlug,
                                         slug: event.data.slug,
                                     }})"><b>{{ event.data.title }}</b></nuxt-link>
                                </span>
                                <span v-else>
                                     <username tag="b" :value="event.data.username"/>
                                      {{ $t('events.blogCommentReplied.others') }} <nuxt-link :to="localePath({name: 'blog-cat-slug', params: {
                                         cat: event.data.categorySlug,
                                         slug: event.data.slug,
                                     }})"><b>{{ event.data.title }}</b></nuxt-link>
                                </span>
                            </template>
                            <template v-if="event.type === 'award_issued'">
                                 <span v-if="userId === event.userId">
                                     {{ $t('events.awardIssued.yourself') }} <b>"{{ event.data.title }}"</b>
                                </span>
                                <span v-else>
                                     <username tag="b" :value="event.username"/>
                                      {{ $t('events.awardIssued.others') }} <b>"{{ event.data.title }}"</b>
                                </span>
                            </template>
                            <template v-if="event.type === 'object_supplemented'">
                                  <span v-if="userId === event.userId">
                                      <username tag="b" :value="event.data.username"/>
                                      {{ $t('events.objectSupplemented.yourObject') }}
                                      <nuxt-link :to="localePath({name: 'objects-id', params: {id: event.data.id}})"><b>{{ event.data.title }}</b></nuxt-link>
                                  </span>
                              <span v-else-if="event.data.userId === userId">
                                      {{ $t('events.objectSupplemented.yourself') }} <nuxt-link :to="localePath({name: 'objects-id', params: {id: event.data.id}})"><b>{{ event.data.title }}</b></nuxt-link>
                                  </span>
                              <span v-else>
                                      <username tag="b" :value="event.data.username"/>
                                      {{ $t('events.objectSupplemented.others') }} <nuxt-link :to="localePath({name: 'objects-id', params: {id: event.data.id}})"><b>{{ event.data.title }}</b></nuxt-link>
                                  </span>
                            </template>
                        </div>
                    </div>
                  <!--  <div class="item">
                        <div class="item__date">
                            <span>12 августа</span>
                        </div>
                        <div class="item__text">
              <span>
                <b>Арай Молдахметова</b> прокомментировала ваш объект
                <b>Суши-бар Saya Sushi</b>
              </span>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item__date">
                            <span>12 августа</span>
                        </div>
                        <div class="item__text">
              <span>
                <b>Арай Молдахметова</b> прокомментировала ваш объект
                <b>Суши-бар Saya Sushi</b>
              </span>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item__date">
                            <span>12 августа</span>
                        </div>
                        <div class="item__text">
              <span>
                <b>Арай Молдахметова</b> прокомментировала ваш объект
                <b>Суши-бар Saya Sushi</b>
              </span>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item__date">
                            <span>12 августа</span>
                        </div>
                        <div class="item__text">
              <span>
                <b>Арай Молдахметова</b> прокомментировала ваш объект
                <b>Суши-бар Saya Sushi</b>
              </span>
                        </div>
                    </div>
                    <div class="item">
                        <div class="item__date">
                            <span>12 августа</span>
                        </div>
                        <div class="item__text">
              <span>
                <b>Арай Молдахметова</b> прокомментировала ваш объект
                <b>Суши-бар Saya Sushi</b>
              </span>
                        </div>
                    </div>-->
                </div>
                <div class="link">
                    <nuxt-link :to="localePath({name: 'profile'})">
                        <span>{{ $t('events.profileLinkTitle') }}</span>
                    </nuxt-link>
                </div>
            </div>
            <div class="timeline__tab timeline__tab_user" :class="{'isActive': activeTab===2}">
                <UserTabs></UserTabs>
            </div>
        </div>
    </div>
</template>

<script>
    import UserTabs from "~/components/UserTabs";
    import {get} from 'vuex-pathify'
    import Username from "./Username";
    import UserAvatar from "./UserAvatar";
    import FormattedDate from "~/components/FormattedDate";

    export default {
        props: ['posts', 'events'],
        data() {
            return {
                activeTab: 0
            };
        },
        components: {
            FormattedDate,
            UserAvatar,
            Username,
            UserTabs
        },
        methods: {
            setActiveTab(tab) {
                this.activeTab = tab;
            },
            showLoginForm() {
                this.$router.push(this.localePath({name: 'login'}))
            }
        },
        computed: {
            isAuthenticated() {
                return !!this.user
            },
            user() {
                return this.$store.state.authentication.user
            },
            name: get('authentication/name'),
            postsShow() {
                return this.posts.slice(0, 3)
            },
            userId() {
                return this.user ? this.user.id : null
            }
        }
    };
</script>

<style lang="scss">
    @import "./../styles/mixins.scss";

    .timeline {
        .item__title {
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .item__text {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        background: $white;
        width: 100%;
        flex: 1 0 auto;
        max-height: 400px;
        padding: 30px 0px 34px 40px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        @media all and (max-width: 1366px){
            max-height: 254px;
            padding: 20px 0 20px 30px;
        }
        @media all and (max-width: 768px){
            display: none;
        }

        &__tab-links {
            width: calc(100% - 40px);
            margin-right: 40px;
            border-bottom: 1px solid #7b95a7;
            display: flex;
            flex-direction: row;
            align-items: flex-end;
            justify-content: flex-start;
        }

        &__tab-link {
            font-size: 16px;
            line-height: 20px;
            color: $black;
            display: block;
            position: relative;
            margin-left: 25px;
            padding-bottom: 10px;
            cursor: pointer;
            transition: opacity 0.3s;

            &:first-child {
                margin-left: 0;
            }

            &:after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                border-bottom: 3px solid #0f6bf5;
                opacity: 0;
                transition: opacity 0.3s;
            }

            &:hover {
                opacity: 0.7;
            }

            &.isActive {
                font-weight: 700;

                &:after {
                    opacity: 1;
                }
            }

            &.timeline__tab-link_blog {
                .new {
                    position: relative;
                    top: -8px;
                    font-size: 10px;
                    line-height: 20px;
                    color: $black;
                    font-weight: 400;
                }
            }

            &.timeline__tab-link_user {
                display: flex;
                flex-direction: row;
                justify-content: flex-end;
                align-items: center;

                .avatar {
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    display: block;
                    margin-right: 10px;
                    background-position: center;
                    background-size: cover;
                    background-repeat: no-repeat;
                }
            }
        }

        &__tabs {
            display: block;
            margin-top: 30px;
            flex: 1 0 auto;
            max-height: calc(100% - 41px);
            width: 100%;
            @media all and (max-width: 1366px){
                margin-top: 20px;
            }
        }

        &__tab {
            display: none;

            &.isActive {
                display: block;
            }

            &.timeline__tab_blog {
                position: relative;
                overflow-x: hidden;
                overflow-y: auto;
                height: 100%;
                padding-right: 30px;

                &::-webkit-scrollbar {
                    width: 10px;
                }

                &::-webkit-scrollbar-track {
                    background: $tr;
                }

                &::-webkit-scrollbar-thumb {
                    background: transparentize(#c4c4c4, 0.5);
                }

                .item {
                    display: flex;
                    flex-direction: row;
                    justify-content: flex-start;
                    align-items: flex-start;
                    margin-top: 30px;
                    transition: opacity 0.3s;
                    @media all and (max-width: 1366px){
                        display: none;
                    }

                    &:first-child {
                        margin-top: 0;
                        @media all and (max-width: 1366px){
                            display: flex;
                        }
                    }

                    &:nth-last-child(1) {
                        margin-bottom: 30px;
                    }

                    &:hover {
                        opacity: 0.7;
                    }

                    &.item_link {
                        position: relative;
                        bottom: 0;
                        left: 0;
                        right: 0;
                        height: 30px;
                        background: $white;

                        &:hover {
                            opacity: 1;
                        }

                        a {
                            margin-left: 180px;
                            transition: opacity 0.3s;

                            &:hover {
                                opacity: 0.7;
                            }

                            span {
                                font-size: 16px;
                                line-height: 20px;
                                color: $black;
                                text-decoration: underline;
                            }
                        }
                    }

                    &__img {
                        width: 150px;
                        height: 110px;
                        background-position: center;
                        background-repeat: no-repeat;
                        background-size: cover;
                        margin-right: 30px;
                        @media all and (max-width: 1366px){
                            width: 140px;
                            height: 90px;
                            margin-right: 20px;
                        }
                    }

                    &__info {
                        display: block;
                        flex: 1 0 auto;
                        max-width: calc(100% - 180px);
                        position: relative;
                        top: -8px;
                        @media all and (max-width: 1366px){
                            max-width: calc(100% - 160px);
                        }
                    }

                    &__title {
                        margin-bottom: 2px;
                        @media all and (max-width: 1366px){
                            font-size: 16px;
                        }
                    }

                    &__text {
                        margin-bottom: 0;
                        @media all and (max-width: 1366px){
                            font-size: 14px;
                            line-height: 20px;
                        }
                    }

                    &__date {
                        font-size: 14px;
                        color: #5b6067;
                        margin-bottom: 0px;

                        span {
                            margin-left: 14px;

                            &:first-child {
                                margin-left: 0;
                            }
                        }
                    }
                }
            }

            &.timeline__tab_events {
                position: relative;
                height: 100%;

                .scroll {
                    position: relative;
                    overflow-x: hidden;
                    overflow-y: auto;
                    height: calc(100% - 90px);
                    padding-right: 30px;

                    &::-webkit-scrollbar {
                        width: 10px;
                    }

                    &::-webkit-scrollbar-track {
                        background: $tr;
                    }

                    &::-webkit-scrollbar-thumb {
                        background: transparentize(#c4c4c4, 0.5);
                    }
                }

                .item {
                    display: flex;
                    flex-direction: row;
                    justify-content: flex-start;
                    align-items: flex-start;
                    margin-top: 30px;

                    &:first-child {
                        margin-top: 0;
                    }

                    &__date {
                        width: 110px;
                        font-size: 14px;
                        line-height: 20px;
                        color: #5b6067;
                    }

                    &__text {
                        flex: 1 0 auto;
                        max-width: calc(100% - 110px);
                        font-size: 16px;
                        line-height: 20px;
                        color: #333333;
                    }
                }

                .link {
                    position: absolute;
                    bottom: 42px;
                    margin-left: 110px;

                    a {
                        text-decoration: underline;
                        font-size: 16px;
                        line-height: 20px;
                        color: $black;
                        transition: opacity 0.3s;

                        &:hover {
                            opacity: 0.7;
                        }
                    }
                }
            }

            &.timeline__tab_user {
                position: relative;
                height: 100%;

                .user-tabs {
                    height: 100%;

                    &__links {
                        display: flex;
                        justify-content: flex-start;
                        align-items: flex-start;
                    }

                    &__link {
                        display: block;
                        margin-left: 20px;
                        background: $tr;
                        padding: 10px;
                        font-size: 16px;
                        line-height: 20px;
                        color: #333333;
                        position: relative;
                        cursor: pointer;

                        &.isActive {
                            background: #f1f8fc;
                            font-weight: 700;
                        }

                        &.isUpdated {
                            &:after {
                                content: "";
                                position: absolute;
                                top: 10px;
                                right: 0;
                                width: 7px;
                                height: 7px;
                                border-radius: 50%;
                                background: #3dba3b;
                            }
                        }

                        &:first-child {
                            margin-left: 0;
                        }
                    }

                    &__tabs {
                        padding-top: 30px;
                        height: 100%;
                    }

                    &__tab {
                        height: 100%;
                        width: 100%;
                        display: none;

                        &.isActive {
                            display: block;
                        }

                        .scroll {
                            position: relative;
                            overflow-x: hidden;
                            overflow-y: auto;
                            height: 100%;
                            padding-right: 30px;
                            padding-bottom: 30px;

                            &::-webkit-scrollbar {
                                width: 10px;
                            }

                            &::-webkit-scrollbar-track {
                                background: $tr;
                            }

                            &::-webkit-scrollbar-thumb {
                                background: transparentize(#c4c4c4, 0.5);
                            }
                        }

                        .item {
                            display: flex;
                            width: 100%;
                            justify-content: flex-start;
                            align-items: flex-start;
                            margin-bottom: 40px;

                            &__icon {
                                width: 20px;
                                height: 20px;
                                display: flex;
                                flex-direction: row;
                                justify-content: center;
                                align-items: center;

                                svg {
                                    max-width: 100%;
                                    max-height: 100%;
                                }
                            }

                            &__date {
                                margin-left: 30px;
                                font-size: 14px;
                                line-height: 20px;
                                color: #5b6067;
                                width: 100px;
                            }

                            &__text {
                                flex: 1 0 auto;
                                max-width: calc(100% - 150px);
                                font-size: 16px;
                                line-height: 20px;
                                color: #333333;
                            }
                        }
                    }
                }
            }
        }
    }
</style>