<template>
    <div class="vi" :class="viSettingsClasses">
        <div class="vi-container">
            <ViHeader/>
            <div class="vi-object" v-if="objectInfoShow === false">
                <nuxt-link :to="localePath({name: 'index', query: {subCategoryId: object.subCategoryId, categoryId: object.categoryId}})" class="vi-object__top-link">{{ $t('objects.viBreadcrumbsTitle', {category: object.category, subCategory: object.subCategory}) }}</nuxt-link>
                <h2 class="vi-object__title">{{ object.title }}</h2>
                <div class="vi-object__edit">
                    <span class="vi-object__address">{{ object.address }}</span>
                 <!--   <a href="" class="vi-object__edit-link">Редактировать</a>-->
                </div>
                <div class="vi-object__tab-link-b">
                    <span class="vi-object__tab-link" :class="{'--active': activeTab === 0}" @click="setActiveTab(0)">{{ $t('objects.tabTitles.description') }}</span>
                    <span class="vi-object__tab-link" :class="{'--active': activeTab === 1}" @click="setActiveTab(1)">{{ $t('objects.tabTitles.photos') }}</span>
                    <span class="vi-object__tab-link" :class="{'--active': activeTab === 2}" @click="setActiveTab(2)">{{ $t('objects.tabTitles.videos')}}</span>
                    <span class="vi-object__tab-link" :class="{'--active': activeTab === 3}" @click="setActiveTab(3)">{{ $t('objects.tabTitles.reviews') }}</span>
                    <span class="vi-object__tab-link" :class="{'--active': activeTab === 4}" @click="setActiveTab(4)">{{ $t('objects.tabTitles.history')}}</span>
                </div>
                <div class="vi-object__tab-content-b">
                    <div class="vi-object__tab-content" v-if="activeTab === 0">
                        <div class="vi-object__available-top">
                            <h4 class="vi-object__available-top__title">{{ $t('objects.viSummaryScore', {score: $t(`objects.viAccessibilityCategory.${object.overallScore}`)}) }}</h4>
                            <div class="vi-object__available-top__item" v-for="zone in zones" :key="zone">{{ $t(`objects.zone.${zone}`) }} — {{ $t(`objects.viAccessibilityCategory.${object.scoreByZones[zone]}`) }}</div>
                        </div>
                        <p class="vi-object__text">{{ object.description }}</p>
                        <div class="vi-object__verification-b">
                            <button type="button" class="vi__button" @click="objectInfoShow = true">{{ $t('objects.detailedInfo') }}</button>
                            <span class="vi-object__verification">{{ $t(`objects.verificationStatus.${object.verificationStatus}`) }}</span>
                        </div>
                    </div>
                    <div class="vi-object__tab-content" v-if="activeTab === 1">
                        <div class="vi-object__photo">
                            <a href="#"
                               v-for="(image, imageIndex) in object.photos"
                               :key="imageIndex"
                               @click="videosIndex = null; imagesIndex = imageIndex">
                                <img :src="image.previewUrl" alt=""/>
                            </a>
                        </div>
                    </div>
                    <div class="vi-object__tab-content" v-if="activeTab === 2">
                        <div class="vi-object__photo">
                            <a href="#"
                               v-for="(video, videoIndex) in videos"
                               :key="videoIndex"
                               @click="imagesIndex = null; videosIndex = videoIndex">
                                <img :src="video.poster"/>
                            </a>
                        </div>
                    </div>
                    <div class="vi-object__tab-content" v-if="activeTab === 3">
                        <ul class="vi-object__review-list">
                            <li class="vi-object__review" v-for="review in object.reviews">
                                <div class="vi-object__review-top">
                                    <username class="vi-object__review-author" :value="review.author"/>
                                    <formatted-date class="vi-object__review-date" :date="review.createdAt" format="dd.MM.yyyy"/>
                                </div>
                                <p class="vi-object__review-text">{{ review.text }}</p>
                            </li>
                        </ul>
                        <div class="vi-object__review-add">
                            <button type="button" class="vi__button" @click="showReviewForm">{{ $t('objects.review.linkButtonTitle')}}</button>
                        </div>
                        <div class="vi-object__review-new" v-if="reviewNew">
                            <textarea v-model="reviewText" :placeholder="$t('objects.review.textareaPlaceholder')"/>
                            <button type="button" class="vi__button" @click="submitReview()">{{ $t('objects.review.submitButtonTitle') }}</button>
                        </div>
                    </div>
                    <div class="vi-object__tab-content" v-if="activeTab === 4">
                        <ul class="vi-object__history">
                            <li class="vi-object__history-item"  v-for="(item, index) in object.history" :key="index">
                                <formatted-date class="vi-object__history-date" :date="item.date" format="d MMMM"/>
                                <p class="vi-object__history-text"><username :value="item.name" tag="b"/>
                                    <template v-if="item.data.type === 'review_created'">{{ $t('objects.history.reviewed') }}</template>
                                    <template v-if="item.data.type === 'verification_confirmed'">{{ $t('objects.history.confirmed') }}</template>
                                    <template v-if="item.data.type === 'verification_rejected'">{{ $t('objects.history.notConfirmed') }}</template>
                                </p>
                            </li>
                        </ul>
                    </div>
                    <div class="vi-object__content-complaint">
                        <nuxt-link :to="localePath({name: 'complaint', query: {objectId: $route.params.id}})" class="vi__button">{{ $t('objects.makeComplaint') }}</nuxt-link>
                        <button type="button" class="vi__button" @click="showVerificationPopup = true">{{ $t('objects.confirm') }}</button>
                    </div>
                </div>
            </div>
            <div class="vi-object__available" v-if="objectInfoShow === true">
                <span class="vi-object__top-link" @click="objectInfoShow = false">{{ $t('objects.viReturnToObject', {object: object.title}) }}</span>
                <h2 class="vi-object__title">{{ $t('objects.detailedInfo') }}</h2>
                <div class="vi-object__anchor-b">
                    <div class="vi-object__anchor-list" v-for="(item, index) in zonesMenu" :key="index">
                        <div class="vi-object__anchor-item" v-for="zone in item" :key="zone.key">
                            <span class="vi-object__anchor" @click="setVisible(zone.key)" :class="{ '--active': visibleDetail === zone.key }">{{ $t(`objects.zone.${zone.group}`) }}</span>
                        </div>
                    </div>
                    <a :href="exportLink" download class="vi__button">{{ $t('objects.download') }}</a>
                </div>
                <div class="vi-object__available-content">
                    <div class="vi-object__available-item" v-for="(zone, index) in attributes" :key="index" :id="`detail_${zone.key}`">
                        <h4 class="vi-object__available-title">{{ $t(`objects.zone.${zone.group}`) }}</h4>
                        <template v-for="group in zone.groups">
                            <template v-for="subGroup in group.subGroups">
                                <div class="vi-object__available-line" v-for="attribute in subGroup.attributes" :key="`${zone.key}-${attribute.key}`">
                                    <div class="vi-object__available-text">
                                        {{ attribute.title }}
                                    </div>
                                    <div class="vi-object__available-status">
                                        {{ $t(`objects.attribute.${attribute.value}`) }}
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
            <ViFooter/>
        </div>
        <client-only>
            <gallery id="blueimp-gallery" :images="images" :index="imagesIndex" :options="imagesOptions" @close="imagesIndex = null"></gallery>
            <gallery id="blueimp-video" class="blueimp-gallery-controls" :images="videos" :index="videosIndex" :options="videosOptions" @close="videosIndex = null"></gallery>
        </client-only>
      <div class="vi-popup__wrapper" v-if="showVerificationPopup">
        <div class="vi-popup-b">
          <div class="vi-popup">
            <div class="vi-popup__close" @click="showVerificationPopup = false">
              <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                <path d="M50 10L9.99999 50" stroke="white" stroke-width="2" stroke-linecap="round"/>
                <path d="M10 10L50 50" stroke="white" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <h4 class="vi-popup__title">{{ $t('objects.verificationPopupTitle') }}</h4>
            <p class="vi-popup__text">{{ $t('objects.verificationPopupQuestion', {objectName: object.title}) }}</p>
            <div class="vi-popup__button">
              <button class="vi__button --half" type="button" @click="submitVerification('confirm')">{{ $t('yes') }}</button><button type="button" @click="submitVerification('reject')" class="vi__button --half">{{ $t('no') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    import ViHeader from "~/components/ViHeader";
    import ViFooter from "~/components/ViFooter";
    import Username from "~/components/Username";
    import FormattedDate from "~/components/FormattedDate";
    import {get, call} from 'vuex-pathify'
    import chunk from "lodash/chunk";

    export default {
        name: 'ViObjectView',
        props: [
            'zones'
        ],
        data() {
            return {
                showVerificationPopup: false,
                activeTab: 0,
                reviewNew: false,
                objectInfoShow: false,
                visibleDetail: 'detail_1',
                videosIndex: null,
                reviewText: '',
                videosOptions: {
                    container: '#blueimp-video',
                    continuous: false,
                    youTubeVideoIdProperty: 'youtube',
                    youTubePlayerVars: undefined,
                    youTubeClickToPlay: true
                },
                imagesIndex: null,
                imagesOptions: {
                    continuous: false,
                    onslide: function(index, slide) {
                        var indicator = document.getElementsByClassName('indicator');
                        indicator[0].innerHTML = (index + 1) + ' / ' + document.getElementsByClassName('slide').length;
                    }
                }
            }
        },
        components: {
            FormattedDate,
            Username,
            ViHeader,
            ViFooter
        },
        computed: {
            images() {
                return this.object.photos.map(photo => photo.viewUrl)
            },
            videos: get('object/videos'),
            object: get('object/item'),
            attributes: get('object/attributes'),
            exportLink: get('object/exportLink'),
            user: get('authentication/user'),
            visualImpairedModeSettings: get('visualImpairedModeSettings'),
            zonesMenu() {
                return chunk(this.attributes, Math.round(this.attributes.length / 2))
            },
            viSettingsClasses() {
                return {
                    '--noto': this.visualImpairedModeSettings.fontFamily === 'Noto',
                    '--black': this.visualImpairedModeSettings.colorTheme === 'black',
                    '--sm': this.visualImpairedModeSettings.fontSize === 'sm',
                    '--md': this.visualImpairedModeSettings.fontSize === 'md',
                    '--lrg':this.visualImpairedModeSettings.fontSize === 'lrg'
                }
            }
        },
        methods: {
            setVisible(detail) {
                this.visibleDetail = detail;console.log(detail);
                document.getElementById('detail_' + detail).scrollIntoView();
            },
            setActiveTab(n){
                this.activeTab = n;
            },
            showReviewForm() {
                if(!user) {
                    this.$router.push(this.localePath({name: 'login'}))
                }
                this.reviewNew = true
            },
            async submitReview() {
                await this._submitReview(this.reviewText)
                this.reviewText = ''
                this.reviewNew = false
            },
            async submitVerification(status) {
              this.showVerificationPopup = false
              await this._submitVerification(status)
            },
            _submitReview: call('object/submitReview'),
            _submitVerification: call('object/submitVerification'),
        }
    }
</script>

<style lang="scss">

    #blueimp-gallery {
        background: #FFFFFF;
        > {
            .close {
                width: 60px;
                height: 60px;
                left: 50%;
                top: 130px;
                margin: 0 0 0 580px;
                font-size: 0;
                opacity: 1;
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTUwIDEwTDkuOTk5OTkgNTAiIHN0cm9rZT0iYmxhY2siIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIi8+CjxwYXRoIGQ9Ik0xMCAxMEw1MCA1MCIgc3Ryb2tlPSJibGFjayIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4KPC9zdmc+Cg==') center no-repeat;
                &:hover {
                    opacity: 0.7;
                }
            }

            .prev {
                opacity: 1;
                border: none;
                margin: 0 580px 0 0;
                right: 50%;
                font-size: 0;
                width: 60px;
                height: 100px;
                left: auto;
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgNjAgMTAwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNNDUgMjBMMTUgNTBMNDUgODAiIHN0cm9rZT0iYmxhY2siIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=') center no-repeat;
                &:hover {
                    opacity: 0.7;
                }
            }

            .next {
                opacity: 1;
                border: none;
                margin: 0 0 0 580px;
                left: 50%;
                font-size: 0;
                width: 60px;
                height: 100px;
                right: auto;
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgNjAgMTAwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTUgODBMNDUgNTBMMTUgMjAiIHN0cm9rZT0iYmxhY2siIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=') center no-repeat;
                &:hover {
                    opacity: 0.7;
                }
            }

            .slides {
                > .slide {
                    > .slide-content {
                        max-width: 800px;
                        max-height: 600px;
                    }
                }
            }

            .indicator {
                bottom: 80px;
                line-height: 40px;
                margin: 0;
            }
        }
    }
    #blueimp-video {
        background: #FFFFFF;
        &.blueimp-gallery-left > .prev, &.blueimp-gallery-right > .next {
            display: none;
        }
        > {
            .close {
                width: 60px;
                height: 60px;
                left: 50%;
                top: 130px;
                margin: 0 0 0 580px;
                font-size: 0;
                opacity: 1;
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTUwIDEwTDkuOTk5OTkgNTAiIHN0cm9rZT0iYmxhY2siIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIi8+CjxwYXRoIGQ9Ik0xMCAxMEw1MCA1MCIgc3Ryb2tlPSJibGFjayIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4KPC9zdmc+Cg==') center no-repeat;
                &:hover {
                    opacity: 0.7;
                }
            }

            .prev {
                opacity: 1;
                border: none;
                margin: 0 580px 0 0;
                right: 50%;
                font-size: 0;
                width: 60px;
                height: 100px;
                left: auto;
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgNjAgMTAwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNNDUgMjBMMTUgNTBMNDUgODAiIHN0cm9rZT0iYmxhY2siIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=') center no-repeat;
                &:hover {
                    opacity: 0.7;
                }
            }

            .next {
                opacity: 1;
                border: none;
                margin: 0 0 0 580px;
                left: 50%;
                font-size: 0;
                width: 60px;
                height: 100px;
                right: auto;
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgNjAgMTAwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNMTUgODBMNDUgNTBMMTUgMjAiIHN0cm9rZT0iYmxhY2siIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=') center no-repeat;
                &:hover {
                    opacity: 0.7;
                }
            }

            .title {
                position: absolute;
                left: 0;
                right: 0;
                bottom: 40px;
                color: #000000;
                top: auto;
                margin: 0;
                padding: 0;
                font-weight: 600;
                text-shadow: none;
                text-align: center;
            }

            .slides {
                > .slide {
                    > .video-content {
                        max-width: 800px;
                        max-height: 600px;
                    }
                }
            }

            .play-pause {
                display: block;
            }


            .slides > .slide > .video-content > iframe {
                position: absolute;
                left: 0;
                top: 0;
            }

            .slides > .slide > .video-content > a {
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjUiIGN5PSIyNSIgcj0iMjUiIGZpbGw9ImJsYWNrIiBmaWxsLW9wYWNpdHk9IjAuNSIvPgo8cGF0aCBkPSJNMTkgMTZMMzQgMjUuNUwxOSAzNVYxNloiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=') center no-repeat;
                background-size: 128px;
            }

            .slides > .slide > .video-content:not(.video-loading) > a {
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjUiIGN5PSIyNSIgcj0iMjUiIGZpbGw9ImJsYWNrIiBmaWxsLW9wYWNpdHk9IjAuNSIvPgo8cGF0aCBkPSJNMTkgMTZMMzQgMjUuNUwxOSAzNVYxNloiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=') center no-repeat;
                background-size: 128px;
            }
        }
    }

    .vi {
        &-object {
            padding: 40px 0;
            &__photo {
                display: flex;
                justify-content: flex-start;
                align-items: flex-start;
                flex-wrap: wrap;
                img {
                    height: 210px;
                    width: auto;
                    margin: 0 20px 20px 0;
                }
            }
            &__history {
                list-style: none;
                padding: 20px 0;
                margin: 0;
                &-item {
                    margin: 36px 0 0;
                    display: flex;
                    &:first-child {
                        margin: 0;
                    }
                }
                &-date {
                    font-size: 22px !important;
                    line-height: 24px;
                    width: 150px;
                }
                &-text {
                    width: calc(100% - 150px);
                    line-height: 24px;
                }
            }
            &__review {
                margin: 56px 0 0;
                &:first-child {
                    margin: 0;
                }
                &-list {
                    padding: 20px 0 0;
                    list-style: none;
                    margin: 0;
                }
                &-author {
                    line-height: 50px;
                    font-weight: 700;
                }
                &-date {
                    line-height: 50px;
                    margin: 0 0 0 20px;
                }
                &-text {
                    line-height: 50px;
                }
                &-add {
                    margin: 50px 0 0;
                }
                &-new {
                    margin: 60px 0 0;
                    textarea {
                        width: 100%;
                        min-height: 200px;
                        padding: 16px 30px 20px;
                        border: 1px solid;
                        margin: 0 0 30px;
                    }
                }
            }
            &__top {
                &-link {
                    display: block;
                    line-height: 24px;
                    margin: 0 0 32px;
                }
            }
            &__title {
                font-size: 40px !important;
                line-height: 44px;
                margin: 0 0 30px;
            }
            &__edit {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin: 0 0 40px;
                &-link {
                    line-height: 24px;
                }
            }
            &__address {
                line-height: 24px;
            }
            &__content {
                &-complaint {
                    margin: 52px 0 0;
                    display: flex;
                    .vi__button + .vi__button {
                        margin: 0 0 0 20px;
                    }
                }
            }
            &__tab {
                &-content {
                    padding: 40px 0;
                }
            }
            &__available {
                padding: 40px 0;
                &-title {
                    font-size: 40px !important;
                    line-height: 40px;
                    margin: 54px 0 30px;
                }
                &-top {
                    border-bottom: 1px solid;
                    padding: 0 0 40px;
                    margin: 0 0 46px;
                    &__title {
                        font-size: 32px;
                        line-height: 36px;
                        margin: 0;
                    }
                    &__item {
                        line-height: 24px;
                        margin: 32px 0 0;
                    }
                }
                &-line {
                    display: flex;
                    border-bottom: 1px solid;
                    padding: 25px 0;
                }
                &-text {
                    width: calc(100% - 270px);
                    line-height: 30px;
                    &__title {
                        margin: 36px 0 10px;
                    }
                }
                &-status {
                    width: 260px;
                    text-align: left;
                    line-height: 30px;
                }
            }
            &__anchor {
                line-height: 44px;
                height: 50px;
                padding: 0 20px;
                border: 3px solid transparent;
                display: inline-block;
                position: relative;
                cursor: pointer;
                &-list {
                    max-width: 33.33%;
                    min-width: 33.33%;
                    &:first-child {
                        margin: 0 0 0 -20px;
                    }
                }
                &-item {
                    height: 60px;
                    padding: 5px 0;
                }
                &.--active {
                    font-weight: 700;
                    border-color: #000;
                    &:before {
                        content: '';
                        position: absolute;
                        border-top: 6px solid #000000;
                        border-left: 7px solid transparent;
                        border-right: 7px solid transparent;
                        left: 50%;
                        margin: 0 0 0 -7px;
                        bottom: -9px;
                    }
                    &:after {
                        content: '';
                        position: absolute;
                        border-top: 5px solid #FFFFFF;
                        border-left: 6px solid transparent;
                        border-right: 6px solid transparent;
                        left: 50%;
                        margin: 0 0 0 -6px;
                        bottom: -5px;
                        z-index: 1;
                    }
                }
                &-b {
                    display: flex;
                    align-items: flex-end;
                    border-bottom: 2px solid;
                    padding: 0 0 42px;
                }
            }
            &__text {
                line-height: 1.5;
            }
            &__verification {
                line-height: 24px;
                &-b {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin: 40px 0 0;
                }
            }
        }
    }

</style>