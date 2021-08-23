<template>
    <div class="sidebar-wrapper" :class="{opened: mobileOpened}">
        <div class="mob-menu">
            <nuxt-link :to="localePath({name: 'index'})" class="main-filter__logo">
                <img :src="require(`@/assets/logo_${$i18n.locale}.svg`)" alt/>
            </nuxt-link>
            <div class="burger-wrapper" @click="mainPageMobOpened()">
                <span class="burger">
                  <span class="burger-line"></span>
                </span>
            </div>
        </div>
        <div class="sidebar">
            <div class="object-side">
                <div class="object-side__top">
                    <nuxt-link :to="localePath({name: 'index'})" class="object-side__close"></nuxt-link>
                    <div class="object-side__breadcrumb-b">
                        <div class="object-side__breadcrumb-icon">
                            <i class="fa" :class="object.icon"></i>
                        </div>
                        <div class="object-side__breadcrumb-list">
                            <a class="object-side__breadcrumb"><span>&#8592;</span>{{ object.category }}</a>
                            <a class="object-side__breadcrumb">{{ object.subCategory }}</a>
                        </div>
                    </div>
                    <h2 class="object-side__title">{{ object.title }}</h2>
                    <div class="object-side__address">
                        {{ object.address }}
                    </div>
                </div>
                <div class="availability"
                     :style="{backgroundColor: overallAccessibility.backgroundColor}">
                    <div class="availability__title" :class="overallAccessibility.class">{{ $t(`accessibilityStatus.${object.overallScore}`) }}
                    </div>
                    <div class="availability__list">
                        <div class="availability__item" :class="zone.class" v-for="zone in zones" :key="zone.key">
                            {{ $t(`objects.zone.${zone.key}`) }}
                        </div>
                    </div>
                </div>
                <div class="object-side__content">
                    <div class="object-side__tab-link-wrapper">
                        <div class="object-side__tab-link-b">
                            <nuxt-link v-for="(tab, index) in tabs" :to="tab.link" :key="index"
                                       class="object-side__tab-link"
                                       :class="{active: $route.query.tab === tab.link.query.tab}">
                                {{ tab.title }}
                                <span class="object-side__tab-num" v-if="tab.counter >= 0">{{ tab.counter }}</span>
                            </nuxt-link>
                        </div>
                    </div>
                    <div class="object-side__tab-content-b">
                        <div class="object-side__tab-content" :class="{active: !$route.query.tab}"
                             id="tab-description">
                            <p class="text">{{ object.description }}</p>
                            <div class="text__verification-b">
                                <span class="text__verification-link" v-on:click="moreDetailsShow = true">{{ $t('objects.detailedInfo') }}</span>
                                <p class="text__verification">{{ $t(`objects.verificationStatus.${object.verificationStatus}`) }}</p>
                            </div>
                            <div class="object-side__button-b">
                                <nuxt-link :to="localePath({name: 'complaint', query: {objectId: $route.params.id}})" class="object-side__button --complaint">
                                    {{ $t('objects.makeComplaint') }}</nuxt-link>
                                <nuxt-link :to="localePath({name: 'objects-id-verify', params: {id: $route.params.id}})"
                                           class="object-side__button --check">{{ $t('objects.confirm') }}
                                </nuxt-link>
                            </div>
                        </div>
                        <div class="object-side__tab-content" :class="{active: $route.query.tab === 'photos'}" id="tab-photo">
                            <div class="object-side__photo" v-for="group in photosByYear" :key="group.year">
                                <div class="object-side__photo-year">{{ group.year }}</div>
                                <div class="object-side__photo-list">
                                    <a :href="photo.viewUrl" class="object-side__photo-link"
                                       v-for="(photo, imageIndex) in group.photos"
                                       :key="`${group.year}${imageIndex}`"
                                       @click.prevent="viewPhoto(photo)">
                                        <img :src="photo.previewUrl" alt=""/>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="object-side__tab-content" :class="{active: $route.query.tab === 'videos'}"
                             id="tab-video">
                            <div class="object-side__photo">
                                <div class="object-side__photo-list --video">
                                    <a href="#" class="object-side__photo-link"
                                       v-for="(video, videoIndex) in object.videos"
                                       :key="videoIndex"
                                       @click="imagesIndex = null; videosIndex = videoIndex">
                                        <img :src="video.thumbnail"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="object-side__tab-content" :class="{active: $route.query.tab === 'reviews'}"
                             id="tab-reviews">
                            <ul class="object-side__review-list">
                                <li class="object-side__review-item" v-for="(review, index) in object.reviews"
                                    :key="index">
                                    <div class="object-side__review-top">
                                        <username class="object-side__review-title" :value="review.author" tag="span"/>
                                        <formatted-date class="object-side__review-date" :date="review.createdAt" format="d MMMM, HH:mm"/>
                                    </div>
                                    <p class="object-side__review-text">{{ review.text }}</p>
                                </li>
                            </ul>
                        </div>
                        <div class="object-side__tab-content" :class="{active: $route.query.tab === 'history'}">
                            <ul class="object-side__history-list">
                                <li class="object-side__history-item" v-for="(item, index) in object.history"
                                    :key="index">
                                    <formatted-date class="object-side__history-date" :date="item.date" format="d MMMM"/>
                                    <p class="object-side__history-text">
                                        <username :value="item.name" tag="b"/>
                                        <template v-if="item.data.type === 'review_created'">{{ $t('objects.history.reviewed') }}</template>
                                        <template v-if="item.data.type === 'verification_confirmed'">{{ $t('objects.history.confirmed') }}</template>
                                        <template v-if="item.data.type === 'verification_rejected'">{{ $t('objects.history.notConfirmed') }}</template>
                                        <template v-if="item.data.type === 'supplemented'">{{ $t('objects.history.supplemented') }}</template>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="more-detail__wrapper" v-if="moreDetailsShow">
                    <span class="more-detail__close" v-on:click="moreDetailsShow = false"></span>
                    <div class="more-detail__top">
                        <div class="more-detail__links-wrapper">
                            <div class="more-detail__links" v-for="(chunk, index) in zonesMenu" :key="index">
                                <a href="#detail_1" class="more-detail__link" v-for="item in chunk" :key="item.key"
                                   :class="{ active: isVisibleDetail(item.key) }"
                                   @click.prevent="setVisible(item.key)">{{ item.title }}</a>
                            </div>
                        </div>
                        <a :href="`/api/objects/${$route.params.id}/pdf`" target="_blank" class="more-detail__download" download>{{ $t('objects.download') }}</a>
                    </div>
                    <div class="more-detail__content" id="more-detail__content">
                        <div :id="zone.key" class="more-detail__item" v-for="zone in detailsZones" :key="zone.key">
                            <h3 class="more-detail__item-title">{{ zone.title }}</h3>
                            <template v-for="(group, index) in attributesList[zone.group]">
                                <h4 class="more-detail__line-title" v-if="group.title">{{ group.title }}</h4>
                                <template v-for="(sub, index2) in group.subGroups">
                                    <h4 class="more-detail__line-title" v-if="sub.title">{{ sub.title }}</h4>
                                    <div class="more-detail__line" v-for="attribute in sub.attributes"
                                         :class="{yes: object.attributes.zones[zone.key][`attribute${attribute.key}`] === 'yes', no: object.attributes.zones[zone.key][`attribute${attribute.key}`] === 'no', empty: !['yes', 'no'].includes(object.attributes.zones[zone.key][`attribute${attribute.key}`])}">
                                        <span class="more-detail__line-text">{{ attribute.title }} {{ attribute.subTitle }}</span>
                                        <span class="more-detail__line-status"
                                              v-if="object.attributes.zones[zone.key][`attribute${attribute.key}`] === 'yes'">{{ $t('objects.attribute.yes') }}</span>
                                        <span class="more-detail__line-status"
                                              v-else-if="object.attributes.zones[zone.key][`attribute${attribute.key}`] === 'no'">{{ $t('objects.attribute.no') }}</span>
                                        <span class="more-detail__line-status" v-else>&mdash;</span>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <nuxt-link
                    v-if="$route.query.tab === 'reviews'"
                    :to="localePath(reviewsLink)"
                    class="object-side__review-add">{{ $t('objects.review.linkButtonTitle') }}
            </nuxt-link>
            <nuxt-link class="object-side__review-add"
               v-if="$route.query.tab === 'photos'"
              :to="addPhotosRoute"
            >
              Добавить фото
            </nuxt-link>
        </div>

        <nuxt-child :objectName="object.title"/>
        <client-only>
            <gallery id="blueimp-gallery" :images="images" :index="imagesIndex" :options="imagesOptions"
                     @close="imagesIndex = null"></gallery>
            <gallery id="blueimp-video" :images="videos" :index="videosIndex" :options="videosOptions"
                     @close="videosIndex = null"></gallery>
        </client-only>
        <post-submit-message/>
    </div>
</template>

<script>
    import {sync, get} from "vuex-pathify";
    import groupBy from 'lodash/groupBy'
    import map from 'lodash/map'
    import chunk from 'lodash/chunk'
    import Username from "~/components/Username";
    import PostSubmitMessage from "~/components/complaint/PostSubmitMessage";
    import LangSelect from "~/components/LangSelect";
    import FormattedDate from "~/components/FormattedDate";
    import PhotoUploader from "~/components/object_add/PhotoUploader";

    const accessibilityValues = {
        full_accessible: {
            class: '--available',
            backgroundColor: 'rgba(61,186,59,0.15)'
        },
        partial_accessible: {
            class: '--partially',
            backgroundColor: 'rgba(248,172,26,0.15)'
        },
        not_accessible: {
            class: '--not-available',
            backgroundColor: 'rgba(222,18,32,0.1)'
        },
        not_provided: {
            class: '--not-provided',
            backgroundColor: 'rgba(123,149,167,0.15)'
        },
        unknown: {
            class: '--not-provided',
            backgroundColor: 'rgba(123,149,167,0.15)'
        }
    };

    const zones = [
        {key: 'parking', label: 'Парковка'},
        {key: 'entrance', label: 'Входная группа'},
        {key: 'movement', label: 'Пути движения по объекту'},
        {key: 'service', label: 'Зона оказания услуги'},
        {key: 'toilet', label: 'Туалет'},
        {key: 'navigation', label: 'Навигация'},
        {key: 'serviceAccessibility', label: 'Доступность услуги'},
        {key: 'kidsAccessibility', label: ''},
    ]

    export default {
        components: {FormattedDate, PostSubmitMessage, Username, LangSelect, PhotoUploader},
        name: 'NormalObjectView',
        props: [
            'mobileOpened',
        ],
        head() {
            return {
                title: this.object.title
            }
        },
        data() {
            return {
                isPartially: false,
                isNotAvailable: false,
                isAvailable: true,
                activeItem: 'tab-description',
                visibleDetail: 'detail_1',
                moreDetailsShow: false,
                addPhotosPopup: false,
                videosIndex: null,
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
                    onslide: function (index, slide) {
                        var indicator = document.getElementsByClassName('indicator');
                        indicator[0].innerHTML = (index + 1) + ' / ' + document.getElementsByClassName('slide').length;
                    }
                }
            }
        },
        mounted() {
            this.visibleDetail = this.detailsZones[0].key
        },
        computed: {
            ...sync('map', [
                'coordinates',
                'zoom',
                'coordinatesAndZoom'
            ]),
            addPhotosRoute() {
              return this.localePath({name: 'objects-id-add-photos', query: {tab: 'photos'}, params: {id: this.$route.params.id}})
            },
            overallAccessibility() {
                return accessibilityValues[this.object.overallScore]
            },
            zones() {
                return zones.map(zone => ({
                    ...zone,
                    class: accessibilityValues[this.object.scoreByZones[zone.key]].class
                }));
            },
            reviewsLink() {
                return {
                    name: 'objects-id-review',
                    params: {
                        id: this.$route.params.id
                    }
                }
            },
            photosByYear() {
                return map(groupBy(this.object.photos, photo => (new Date(photo.date).getFullYear())), (v, k) => ({
                    year: Number(k),
                    photos: v
                })).sort((a, b) => b.year - a.year)
            },
            tabs() {
                return [
                    {title: this.$t('objects.tabTitles.description'), link: {...this.$route, query: {tab: undefined}}},
                    {title: this.$t('objects.tabTitles.photos'), link: {...this.$route, query: {tab: 'photos'}}, counter: this.object.photos.length},
                    {
                        title: this.$t('objects.tabTitles.videos'),
                        link: {...this.$route, query: {tab: 'videos'}},
                        counter: this.object.videos.length
                    },
                    {
                        title: this.$t('objects.tabTitles.reviews'),
                        link: {...this.$route, query: {tab: 'reviews'}},
                        counter: this.object.reviews.length
                    },
                    {title: this.$t('objects.tabTitles.history'), link: {...this.$route, query: {tab: 'history'}}},
                ]
            },
            attributesList: get('objectAdding/attributesList[:form]'),
            form() {
                return this.object.attributes.form
            },
            detailsZones() {
                const zones = [
                    {key: 'parking', title: this.$t('objects.zone.parking'), group: 'parking'},
                    {key: 'entrance1', title: this.$t('objects.zone.entrance') + ' 1', group: 'entrance'},
                    {key: 'entrance2', title: this.$t('objects.zone.entrance') + ' 2', group: 'entrance'},
                    {key: 'entrance3', title: this.$t('objects.zone.entrance') + ' 3', group: 'entrance'},
                    {key: 'movement', title: this.$t('objects.zone.movement'), group: 'movement'},
                    {key: 'service', title: this.$t('objects.zone.service'), group: 'service'},
                    {key: 'toilet', title: this.$t('objects.zone.toilet'), group: 'toilet'},
                    {key: 'navigation', title: this.$t('objects.zone.navigation'), group: 'navigation'},
                    {key: 'serviceAccessibility', title: this.$t('objects.zone.serviceAccessibility'), group: 'serviceAccessibility'},
                    {key: 'kidsAccessibility', title: this.$t('objects.zone.kidsAccessibility'), group: 'kidsAccessibility'},
                ]

                return zones.filter(z => this.object.attributes.zones[z.key])
            },
            zonesMenu() {
                return chunk(this.detailsZones, Math.round(this.detailsZones.length / 2))
            },
            images() {
                return this.object.photos.map(photo => photo.viewUrl)
            },
            videos: get('object/videos'),
            object: get('object/item'),
            formAttributesByZone: get('objectAdding/formAttributesByZone')
        },
        methods: {
            mainPageMobOpened() {
                this.$nuxt.$emit('mainPageMobOpened');
            },
            isActive(tabItem) {
                return this.activeItem === tabItem
            },
            isVisibleDetail(detail) {
                return this.visibleDetail === detail
            },
            setVisible(detail) {
                this.visibleDetail = detail;
                document.getElementById('' + detail + '').scrollIntoView();
            },
            setActive(tabItem) {
                this.activeItem = tabItem
            },
            viewPhoto(photo) {
                this.videosIndex = null;
                this.imagesIndex = this.object.photos.indexOf(photo)
            },
        }
    };
</script>

<style lang="scss">
    @import "~/styles/mixins.scss";

    .mob-menu {
        display: none;
        left: 0;
        top: 0;
        right: 0;
        height: 50px;
        z-index: 10;
        background: #FFFFFF;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        padding: 0 20px;
        .main-filter__logo {
            img {
                width: 100px;
            }
        }
        @media all and (max-width: 768px) {
            display: flex;
        }
    }

    #blueimp-gallery {
        left: 680px;
        width: auto;

        > {
            .close {
                width: 24px;
                height: 24px;
                right: 14px;
                top: 14px;
                margin: 0;
                font-size: 0;
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xNi42NjY3IDQuMDA4ZS0wNkwyMCAzLjMzMzM0TDEzLjMzMzMgMTBMMjAgMTYuNjY2N0wxNi42NjY3IDIwTDEwIDEzLjMzMzNMMy4zMzMzMyAyMEwwIDE2LjY2NjdMNi42NjY2NyAxMEwwIDMuMzMzMzNMMy4zMzMzMyAwTDEwIDYuNjY2NjdMMTYuNjY2NyA0LjAwOGUtMDZaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4K') center no-repeat;
            }

            .slides {
                > .slide {
                    > .slide-content {
                        max-width: 800px;
                        max-height: 600px;
                    }
                }
            }

            .next, .prev {
                width: 60px;
                height: 100px;
                font-size: 0;
                border: none;
                border-radius: 0;
                opacity: 0.8;
                margin: -50px 0 0;

                &:hover {
                    opacity: 1;
                }
            }

            .prev {
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNjIiIHZpZXdCb3g9IjAgMCAzMiA2MiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMxIDFMMSAzMUwzMSA2MSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg==') center no-repeat;
            }

            .next {
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNjIiIHZpZXdCb3g9IjAgMCAzMiA2MiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEgNjFMMzEgMzFMMSAxIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K') center no-repeat;
            }

            .indicator {
                color: #FFFFFF;
                bottom: 60px;
                font-size: 14px;
                line-height: 20px;
            }
        }
    }

    #blueimp-video {
        left: 680px;
        width: auto;

        > {
            .close {
                display: block;
                width: 24px;
                height: 24px;
                right: 14px;
                top: 14px;
                margin: 0;
                font-size: 0;
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xNi42NjY3IDQuMDA4ZS0wNkwyMCAzLjMzMzM0TDEzLjMzMzMgMTBMMjAgMTYuNjY2N0wxNi42NjY3IDIwTDEwIDEzLjMzMzNMMy4zMzMzMyAyMEwwIDE2LjY2NjdMNi42NjY2NyAxMEwwIDMuMzMzMzNMMy4zMzMzMyAwTDEwIDYuNjY2NjdMMTYuNjY2NyA0LjAwOGUtMDZaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4K') center no-repeat;
            }

            .slides > .slide > .slide-content {
                max-height: 508px;
                max-width: 900px;
            }

            .next, .prev {
                width: 60px;
                height: 100px;
                font-size: 0;
                border: none;
                border-radius: 0;
                opacity: 0.8;
                display: block;
                margin: -50px 0 0;

                &:hover {
                    opacity: 1;
                }
            }

            .prev {
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNjIiIHZpZXdCb3g9IjAgMCAzMiA2MiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMxIDFMMSAzMUwzMSA2MSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg==') center no-repeat;
            }

            .next {
                background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNjIiIHZpZXdCb3g9IjAgMCAzMiA2MiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEgNjFMMzEgMzFMMSAxIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K') center no-repeat;
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

            .title {
                position: absolute;
                left: 40px;
                right: 40px;
                bottom: 40px;
                top: auto;
                font-weight: 400;
                font-size: 16px;
                text-align: center;
                opacity: 1;
            }
        }

        &.blueimp-gallery-left > .prev, &.blueimp-gallery-right > .next {
            display: none;
        }
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 710px;
        background: $tr;
        transform: translateX(0);
        transition: transform 0.3s;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        justify-content: flex-start;
        padding-left: 40px;
        padding-right: 30px;
        z-index: 5;
        &-wrapper {
            .sidebar {
                @media all and (max-width: 768px) {
                    height: 100%;
                    bottom: 0;
                    padding: 80px 0 0;
                }
            }
        }
    }

    .--partially {
        background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE2IDBIMFYxNkgxNlYwWiIgZmlsbD0iI0Y4QUMxQSIvPgo8cGF0aCBkPSJNMTIuNzk3MiA2LjgwMDY2SDMuMjAyODJWOS4xOTkyNEgxMi43OTcyVjYuODAwNjZaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4K);
    }

    .--available {
        background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE2IDBIMFYxNkgxNlYwWiIgZmlsbD0iIzNEQkEzQiIvPgo8cGF0aCBkPSJNNC41OTk1MyA0Ljg4MTg0TDMuMTg4NiA2LjMwNjg4TDcuOTk5ODkgMTEuMTA0MUwxMi44MTEyIDYuMzA2ODhMMTEuMzg2MSA0Ljg4MTg0TDcuOTk5ODkgOC4yODIxOUw0LjU5OTUzIDQuODgxODRaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4K);
    }

    .--not-available {
        background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAiIGhlaWdodD0iMzAiIHZpZXdCb3g9IjAgMCAzMCAzMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjMwIiBoZWlnaHQ9IjMwIiBmaWxsPSIjREUxMjIwIi8+CjxwYXRoIGQ9Ik0xIDI5VjFIMjlWMjlIMVoiIHN0cm9rZT0iI0RFMTIyMCIgc3Ryb2tlLXdpZHRoPSIyIi8+CjxwYXRoIGQ9Ik0xNi45NDA0IDE1LjcwODlMMjEuMjgyNSAyMC4wMjgzTDIwLjAyODUgMjEuMjgyM0wxNS43MDkxIDE2Ljk0MDJMMTUuMDAwMiAxNi4yMjc1TDE0LjI5MTIgMTYuOTQwMkw5Ljk3MTg2IDIxLjI4MjNMOC43MTc4MyAyMC4wMjgzTDEzLjA1OTkgMTUuNzA4OUwxMy43NzI2IDE1TDEzLjA1OTkgMTQuMjkxTDguNzE3ODMgOS45NzE2N0w5Ljk3MTg2IDguNzE3NjVMMTQuMjkxMiAxMy4wNTk3TDE1LjAwMDIgMTMuNzcyNEwxNS43MDkxIDEzLjA1OTdMMjAuMDI4NSA4LjcxNzY1TDIxLjI4MjUgOS45NzE2N0wxNi45NDA0IDE0LjI5MUwxNi4yMjc3IDE1TDE2Ljk0MDQgMTUuNzA4OVoiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMiIvPgo8L3N2Zz4K);
    }

    .--not-provided {
        background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAzMCAzMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzAgMzA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiM3QTk1QTc7fQ0KCS5zdDF7ZmlsbDpub25lO3N0cm9rZTojRkZGRkZGO3N0cm9rZS13aWR0aDoyO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDt9DQo8L3N0eWxlPg0KPHJlY3QgaWQ9IlhNTElEXzFfIiBjbGFzcz0ic3QwIiB3aWR0aD0iMzAiIGhlaWdodD0iMzAiLz4NCjxwYXRoIGlkPSJYTUxJRF8zXyIgY2xhc3M9InN0MSIgZD0iTTEwLDEwLjhjMC40LTEuMSwxLjItMi4xLDIuMi0yLjZzMi4zLTAuOCwzLjUtMC42YzEuMiwwLjIsMi4zLDAuOCwzLjEsMS43DQoJYzAuOCwwLjksMS4yLDIsMS4yLDMuMmMwLDMuMy01LjEsNS01LjEsNSIvPg0KPHBhdGggaWQ9IlhNTElEXzJfIiBjbGFzcz0ic3QxIiBkPSJNMTQuOSwyMi41aC0wLjQiLz4NCjwvc3ZnPg0K);
    }

    .object-side {
        width: 100%;
        height: 100%;
        background: #ffffff;
        position: relative;
        overflow-x: hidden;
        overflow-y: auto;
        @media all and (max-width: 768px) {
            border-radius: 25px 25px 0 0;
            box-shadow: 0px -4px 4px rgba(0, 0, 0, 0.1), 0px 0px 2px rgba(0, 0, 0, 0.2);
        }

        &::-webkit-scrollbar {
            width: 10px;
            @media all and (max-width: 768px) {
                width: 4px;
            }
        }

        &::-webkit-scrollbar-track {
            background: $tr;
        }

        &::-webkit-scrollbar-thumb {
            background: transparentize(#c4c4c4, 0.5);
        }

        .more-detail {
            &__wrapper {
                position: fixed;
                z-index: 10;
                left: 120px;
                top: 0;
                bottom: 0;
                width: 790px;
                background: #FFFFFF;
                box-sizing: border-box;
                display: flex;
                flex-direction: column;
                @media all and (max-width: 1023px){
                    left: 0;
                    width: 700px;
                }
                @media all and (max-width: 768px){
                    left: 0;
                    top: 50px;
                    width: 100%;
                }
            }

            &__content {
                flex: 1 1 auto;
                overflow-x: hidden;
                overflow-y: auto;
                padding: 2px 30px 34px 40px;
                @media all and (max-width: 768px){
                    padding: 0 20px 20px 20px;
                }

                &::-webkit-scrollbar {
                    width: 10px;
                }

                &::-webkit-scrollbar-track {
                    background: rgba(123, 149, 167, 0.1);
                }

                &::-webkit-scrollbar-thumb {
                    background: rgba(123, 149, 167, 0.5);
                }
            }

            &__close {
                display: block;
                width: 20px;
                height: 20px;
                position: absolute;
                cursor: pointer;
                right: 20px;
                top: 20px;
                background-size: 20px;
                background: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0yMCAzLjMzMzM0TDEzLjMzMzMgMTBMMjAgMTYuNjY2N0wxNi42NjY3IDIwTDEwIDEzLjMzMzNMMy4zMzMzMyAyMEwwIDE2LjY2NjdMNi42NjY2NyAxMEwwIDMuMzMzMzNMMy4zMzMzMyAwTDEwIDYuNjY2NjdMMTYuNjY2NyAzLjk0NjI3ZS0wNkwyMCAzLjMzMzM0WiIgZmlsbD0iIzdCOTVBNyIvPgo8L3N2Zz4K) no-repeat;
                z-index: 1;
            }

            &__top {
                background: $light-gray;
                padding: 32px 40px;
                display: block;
                position: relative;
                @media all and (max-width: 768px){
                    padding: 30px 46px 30px 0;;
                    height: 40px;
                    display: block;
                }
            }

            &__links {
                width: 300px;
                padding: 0 40px 0 0;
                &-wrapper {
                    display: flex;
                    position: relative;
                    @media all and (max-width: 768px){
                        display: none;
                    }
                }
                @media all and (max-width: 768px){
                    width: 100%;
                    padding: 0;
                }
            }

            &__link {
                font-size: 16px;
                line-height: 20px;
                color: $black;
                margin: 20px 0 0;
                display: block;

                &.active, &:hover {
                    font-weight: 700;
                }
                @media all and (max-width: 768px){
                    font-size: 14px;
                    line-height: 18px;
                    margin: 8px 0 0;
                }
                &:first-child {
                    margin: 0;
                }
            }

            &__download {
                position: absolute;
                bottom: 40px;
                right: 40px;
                color: #FFFFFF;
                font-size: 16px;
                line-height: 20px;
                display: block;
                padding: 9px 16px 11px 36px;
                -webkit-transition: opacity 0.4s;
                -moz-transition: opacity 0.4s;
                -ms-transition: opacity 0.4s;
                -o-transition: opacity 0.4s;
                transition: opacity 0.4s;
                background-color: $blue;
                font-weight: 400;
                background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE0IDEwVjEyLjY2NjdDMTQgMTMuMDIwMyAxMy44NTk1IDEzLjM1OTQgMTMuNjA5NSAxMy42MDk1QzEzLjM1OTQgMTMuODU5NSAxMy4wMjAzIDE0IDEyLjY2NjcgMTRIMy4zMzMzM0MyLjk3OTcxIDE0IDIuNjQwNTcgMTMuODU5NSAyLjM5MDUyIDEzLjYwOTVDMi4xNDA0OCAxMy4zNTk0IDIgMTMuMDIwMyAyIDEyLjY2NjdWMTAiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPHBhdGggZD0iTTQuNjY2NSA2LjY2NjY5TDcuOTk5ODQgMTBMMTEuMzMzMiA2LjY2NjY5IiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjEuNSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxwYXRoIGQ9Ik04IDEwVjIiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg==);
                background-position: left 15px center;
                background-repeat: no-repeat;
                @media all and (max-width: 768px){
                    padding: 8px 16px 8px 40px;
                    bottom: auto;
                    right: auto;
                    top: 12px;
                    left: 20px;
                    font-size: 14px;
                }
                &:hover {
                    opacity: 0.7;
                }
            }

            &__item {
                padding: 30px 0 0;
                @media all and (max-width: 768px){
                    padding: 20px 0 0;
                }

                &-title {
                    font-size: 34px;
                    line-height: 40px;
                    margin: 0 0 24px;
                    @media all and (max-width: 768px){
                        font-size: 20px;
                        line-height: 24px;
                        margin: 0 0 12px;
                    }
                }
            }

            &__line {
                padding: 15px 20px 15px 0;
                border-bottom: 1px solid rgba(123, 149, 167, 0.3);
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                @media all and (max-width: 768px){
                    padding: 10px 0px 10px 0;
                    align-items: center;
                }

                &-title {
                    font-size: 16px;
                    line-height: 20px;
                    margin: 24px 0 16px;
                    @media all and (max-width: 768px){
                        font-size: 14px;
                        line-height: 18px;
                        margin: 18px 0 12px;
                    }
                }

                &-text {
                    font-size: 16px;
                    line-height: 20px;
                    width: 540px;
                    display: block;
                    @media all and (max-width: 768px){
                        font-size: 14px;
                        line-height: 18px;
                        width: calc(100% - 45px);
                    }
                }

                &-status {
                    width: 90px;
                    font-size: 16px;
                    line-height: 20px;
                    padding: 0 0 0 30px;
                    @media all and (max-width: 768px){
                        font-size: 14px;
                        line-height: 0;
                        width: 45px;
                        padding: 9px 33px 9px 12px;
                    }
                }

                &.yes {
                    .more-detail__line-status {
                        background: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE2LjY2NjggNUw3LjUwMDE2IDE0LjE2NjdMMy4zMzM1IDEwIiBzdHJva2U9IiMzREJBM0IiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=) left center no-repeat;
                        @media all and (max-width: 768px){
                            background-position: center;
                        }
                    }
                }

                &.no {
                    .more-detail__line-status {
                        background: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGxpbmUgeDE9IjQiIHkxPSIxMCIgeDI9IjE2IiB5Mj0iMTAiIHN0cm9rZT0iI0RFMTIyMCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4KPC9zdmc+Cg==) left center no-repeat;
                        @media all and (max-width: 768px){
                            background-position: center;
                        }
                    }
                }

                &.empty {
                    .more-detail__line-status {
                        color: $text;
                    }
                }
            }
        }

        .availability {
            padding: 30px 40px 37px;
            background: #FEF3DD;
            @media all and (max-width: 768px) {
                padding: 30px 20px 27px;
            }

            &__title {
                font-weight: 700;
                font-size: 16px;
                line-height: 30px;
                padding: 0 0 0 38px;
                margin: 0 0 4px;
                background-size: 30px 30px;
                background-repeat: no-repeat;
                background-position: left top;
                @media all and (max-width: 768px) {
                    font-size: 14px;
                    padding: 0 0 0 50px;
                    margin: 0;
                }
            }

            &__list {
                display: flex;
                flex-wrap: wrap;
            }

            &__item {
                font-size: 16px;
                line-height: 20px;
                height: 20px;
                margin: 30px 0 0;
                width: 50%;
                padding: 0 0 0 26px;
                background-position: left center;
                background-size: 16px 16px;
                background-repeat: no-repeat;
                @media all and (max-width: 768px) {
                    width: 100%;
                    padding: 0 0 0 50px;
                    font-size: 14px;
                    line-height: 16px;
                    margin: 24px 0 0;
                    background-position: left 7px center;
                    height: auto;
                }
            }
        }

        &__button {
            font-size: 16px;
            line-height: 20px;
            padding: 14px 43px 16px 50px;
            display: inline-block;
            color: #ffffff;
            -webkit-transition: opacity 0.3s;
            -moz-transition: opacity 0.3s;
            -ms-transition: opacity 0.3s;
            -o-transition: opacity 0.3s;
            transition: opacity 0.3s;
            background-position: center left 30px;
            background-repeat: no-repeat;
            @media all and (max-width: 768px) {
                display: block;
                font-size: 14px;
                padding: 10px 0;
                text-align: center;
            }

            &:hover {
                opacity: 0.7;
            }

            & + .object-side__button {
                margin: 0 0 0 20px;
                @media all and (max-width: 768px) {
                    margin: 20px 0 0;
                }
            }

            &.--check {
                background-color: $blue;
                background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIiIGhlaWdodD0iMTAiIHZpZXdCb3g9IjAgMCAxMiAxMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwKSI+CjxwYXRoIGQ9Ik0xMC4yMTc2IDBMNC40NDM1MSA1Ljk0MzQ3TDEuNzgyNDMgMy4xOTEzOUwwIDUuMDI2MTFMNC40NDM1MSA5LjZMMTIgMS44MzQ3MkwxMC4yMTc2IDBaIiBmaWxsPSJ3aGl0ZSIvPgo8L2c+CjxkZWZzPgo8Y2xpcFBhdGggaWQ9ImNsaXAwIj4KPHJlY3Qgd2lkdGg9IjEyIiBoZWlnaHQ9IjkuNiIgZmlsbD0id2hpdGUiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4K);
            }

            &.--complaint {
                background-color: $red;
                background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEzLjM3MzYgNC4zMDc3QzEzLjg4MzQgNC4zMDc3IDE0LjI5NjcgMy44OTQ0MyAxNC4yOTY3IDMuMzg0NjJDMTQuMjk2NyAyLjg3NDgyIDEzLjg4MzQgMi40NjE1NSAxMy4zNzM2IDIuNDYxNTVDMTIuODYzOCAyLjQ2MTU1IDEyLjQ1MDUgMi44NzQ4MiAxMi40NTA1IDMuMzg0NjJDMTIuNDUwNSAzLjg5NDQzIDEyLjg2MzggNC4zMDc3IDEzLjM3MzYgNC4zMDc3WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTEwLjkxMiAyLjQ2MTUxQzExLjQyMTggMi40NjE1MSAxMS44MzUxIDIuMDQ4MjMgMTEuODM1MSAxLjUzODQzQzExLjgzNTEgMS4wMjg2MyAxMS40MjE4IDAuNjE1MzU2IDEwLjkxMiAwLjYxNTM1NkMxMC40MDIyIDAuNjE1MzU2IDkuOTg4OTUgMS4wMjg2MyA5Ljk4ODk1IDEuNTM4NDNDOS45ODg5NSAyLjA0ODIzIDEwLjQwMjIgMi40NjE1MSAxMC45MTIgMi40NjE1MVoiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik04LjQ1MDQ4IDEuODQ2MTVDOC45NjAyOCAxLjg0NjE1IDkuMzczNTYgMS40MzI4OCA5LjM3MzU2IDAuOTIzMDc3QzkuMzczNTYgMC40MTMyNzYgOC45NjAyOCAwIDguNDUwNDggMEM3Ljk0MDY4IDAgNy41Mjc0IDAuNDEzMjc2IDcuNTI3NCAwLjkyMzA3N0M3LjUyNzQgMS40MzI4OCA3Ljk0MDY4IDEuODQ2MTUgOC40NTA0OCAxLjg0NjE1WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTUuOTg4OTMgMy4wNzY4N0M2LjQ5ODc0IDMuMDc2ODcgNi45MTIwMSAyLjY2MzU5IDYuOTEyMDEgMi4xNTM3OUM2LjkxMjAxIDEuNjQzOTkgNi40OTg3NCAxLjIzMDcxIDUuOTg4OTMgMS4yMzA3MUM1LjQ3OTEzIDEuMjMwNzEgNS4wNjU4NiAxLjY0Mzk5IDUuMDY1ODYgMi4xNTM3OUM1LjA2NTg2IDIuNjYzNTkgNS40NzkxMyAzLjA3Njg3IDUuOTg4OTMgMy4wNzY4N1oiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0xMi40NTA1IDMuMzg0NjNWNy4zODQ2M0MxMi40NTA1IDcuNTU0NDggMTIuMzEyNiA3LjY5MjMzIDEyLjE0MjggNy42OTIzM0MxMS45NzI5IDcuNjkyMzMgMTEuODM1MSA3LjU1NDQ4IDExLjgzNTEgNy4zODQ2M1YxLjUzODQ4SDkuOTg4OTNWNi43NjkyNUM5Ljk4ODkzIDYuOTM5MSA5Ljg1MTA5IDcuMDc2OTQgOS42ODEyNCA3LjA3Njk0QzkuNTExMzkgNy4wNzY5NCA5LjM3MzU1IDYuOTM5MSA5LjM3MzU1IDYuNzY5MjVWMC45MjMwOTZINy41Mjc0VjYuNzY5MjVDNy41Mjc0IDYuOTM5MSA3LjM4OTU1IDcuMDc2OTQgNy4yMTk3IDcuMDc2OTRDNy4wNDk4NiA3LjA3Njk0IDYuOTEyMDEgNi45MzkxIDYuOTEyMDEgNi43NjkyNVYyLjE1Mzg2SDUuMDY1ODZWOS44NDYxN0wzLjgxOTcgOC4xNDA5NEMzLjQ1MDQ3IDcuNTcxNzEgMi43Mjk4NiA3LjM4MDk0IDIuMjAxODYgNy43MDcxQzEuNjc1NyA4LjA0MDYzIDEuNTQ1MjQgOC43NjgwMiAxLjkwOTU1IDkuMzM1NEMxLjkwOTU1IDkuMzM1NCAzLjkxOTQgMTIuMzc3MiA0Ljc3NjAxIDEzLjY3OTRDNS42MzI2MyAxNC45ODE2IDcuMDIwMzIgMTYgOS42MTUzOSAxNkMxMy45MTIgMTYgMTQuMjk2NiAxMi42ODE5IDE0LjI5NjYgMTEuNjkyM0MxNC4yOTY2IDEwLjcwMjggMTQuMjk2NiAzLjM4NDYzIDE0LjI5NjYgMy4zODQ2M0gxMi40NTA1WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==);
            }

            &-b {
                padding: 150px 0 0;
                font-size: 0;
                @media all and (max-width: 768px) {
                    padding: 30px 0 0;
                }
            }
        }

        &__content {
            padding: 33px 40px 20px;
            @media all and (max-width: 768px) {
                padding: 0;
            }
        }

        &__close {
            width: 20px;
            height: 20px;
            position: absolute;
            top: 20px;
            right: 20px;
            background: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjEiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMSAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0yMC45NzA2IDMuMzMzMzRMMTQuMzAzOSAxMEwyMC45NzA2IDE2LjY2NjdMMTcuNjM3MiAyMEwxMC45NzA2IDEzLjMzMzNMNC4zMDM5MSAyMEwwLjk3MDU4MSAxNi42NjY3TDcuNjM3MjUgMTBMMC45NzA1ODEgMy4zMzMzM0w0LjMwMzkxIDBMMTAuOTcwNiA2LjY2NjY3TDE3LjYzNzIgMy45NDYyN2UtMDZMMjAuOTcwNiAzLjMzMzM0WiIgZmlsbD0iIzdCOTVBNyIvPgo8L3N2Zz4K) center no-repeat;
            -webkit-transition: opacity 0.3s;
            -moz-transition: opacity 0.3s;
            -ms-transition: opacity 0.3s;
            -o-transition: opacity 0.3s;
            transition: opacity 0.3s;
            @media all and (max-width: 768px) {
                left: 50%;
                top: 14px;
                width: 30px;
                height: 2px;
                background: $stroke;
                opacity: 0.5;
                margin: 0 0 0 -15px;
            }

            &:hover {
                opacity: 0.7;
            }
        }

        &__address {
            margin: 16px 0 0;
            line-height: 20px;
            @media all and (max-width: 768px) {
                font-size: 14px;
                margin: 14px 0 0;
            }

            &-link {
                display: inline-block;
                margin: 0 0 0 20px;
                color: #5B6067;
                -webkit-transition: opacity 0.3s;
                -moz-transition: opacity 0.3s;
                -ms-transition: opacity 0.3s;
                -o-transition: opacity 0.3s;
                transition: opacity 0.3s;

                &:hover {
                    opacity: 0.7;
                }
            }
        }

        &__title {
            font-size: 32px;
            line-height: 40px;
            margin: 28px 0 16px;
            @media all and (max-width: 768px) {
                font-size: 18px;
                line-height: 20px;
                margin: 8px 0 14px;
            }
        }

        &__top {
            position: relative;
            padding: 30px 40px 26px;
            @media all and (max-width: 768px) {
                padding: 34px 20px 27px;
            }
        }

        &__tab {
            &-link {
                display: inline-block;
                vertical-align: top;
                font-size: 16px;
                line-height: 20px;
                padding: 0 0 16px;
                position: relative;
                color: #333;
                margin: 0 0 0 40px;
                @media all and (max-width: 768px) {
                    color: $stroke;
                    padding: 16px;
                    font-size: 14px;
                    line-height: 16px;
                    margin: 0;
                    .object-side__tab-num {
                        display: inline-block;
                        left: auto;
                        top: -10px;
                        position: relative;
                    }
                }
                &-wrapper {
                    @media all and (max-width: 768px) {
                        overflow-x: auto;
                        white-space: nowrap;
                        position: relative;
                        &::-webkit-scrollbar {
                            height: 2px;
                        }

                        &::-webkit-scrollbar-track {
                            background: rgba(123, 149, 167, 0.1);
                        }

                        &::-webkit-scrollbar-thumb {
                            background: rgba(123, 149, 167, 0.5);
                        }
                        &:after {
                            background: rgba(123, 149, 167, 0.2);
                            position: absolute;
                            display: block;
                            content: '';
                            height: 1px;
                            left: 0;
                            right: 0;
                            bottom: 0;
                        }
                    }
                }
                &-b {
                    position: relative;
                    font-size: 0;
                    @media all and (max-width: 768px) {
                        overflow-x: auto;
                    }

                    &:after {
                        position: absolute;
                        display: block;
                        content: '';
                        height: 1px;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background: #7B95A7;
                        @media all and (max-width: 768px){
                            display: none;
                        }
                    }
                }

                &:after {
                    content: '';
                    background: transparent;
                    height: 3px;
                    position: absolute;
                    display: block;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    z-index: 1;
                    -webkit-transition: background 0.3s;
                    -moz-transition: background 0.3s;
                    -ms-transition: background 0.3s;
                    -o-transition: background 0.3s;
                    transition: background 0.3s;
                }

                &:first-child {
                    margin: 0;
                }

                &.active, &:hover {
                    font-weight: 700;
                    @media all and (max-width: 768px){
                        color: $blue;
                    }

                    &:after {
                        background: $blue;
                    }
                }
            }

            &-content {
                display: none;
                position: relative;

                &-b {
                    padding: 20px 0 0;
                    @media all and (max-width: 768px) {
                        padding: 24px 20px 30px;
                    }
                }

                &.active {
                    display: block;
                }

                .text {
                    font-size: 16px;
                    line-height: 30px;
                    @media all and (max-width: 768px) {
                        font-size: 14px;
                        line-height: 20px;
                    }

                    &__verification {
                        font-size: 14px;
                        line-height: 20px;
                        color: #5B6067;
                        @media all and (max-width: 768px) {
                            font-size: 12px;
                            margin: 14px 0 0;
                        }

                        &-b {
                            margin: 14px 0 0;
                            display: flex;
                            justify-content: space-between;
                            @media all and (max-width: 768px) {
                                display: block;
                            }
                        }

                        &-link {
                            display: inline-block;
                            cursor: pointer;
                            font-size: 16px;
                            line-height: 20px;
                            -webkit-transition: opacity 0.4s;
                            -moz-transition: opacity 0.4s;
                            -ms-transition: opacity 0.4s;
                            -o-transition: opacity 0.4s;
                            transition: opacity 0.4s;
                            color: $black;
                            position: relative;
                            @media all and (max-width: 768px) {
                                font-size: 14px;
                                line-height: 20px;
                            }

                            &:after {
                                content: '';
                                position: absolute;
                                left: 0;
                                bottom: 2px;
                                width: 100%;
                                height: 1px;
                                background: $black;
                            }

                            &:hover {
                                opacity: 0.7;
                            }
                        }
                    }
                }
            }

            &-num {
                display: block;
                padding: 0 0 0 2px;
                font-size: 10px;
                line-height: 10px;
                position: absolute;
                left: 100%;
                top: 0;
                color: #333;
            }
        }

        &__breadcrumb {
            display: inline-block;
            font-size: 16px;
            line-height: 20px;
            vertical-align: middle;
            -webkit-transition: opacity 0.3s;
            -moz-transition: opacity 0.3s;
            -ms-transition: opacity 0.3s;
            -o-transition: opacity 0.3s;
            transition: opacity 0.3s;
            color: #5B6067;
            span {
                display: none;
                @media all and (max-width: 768px){
                    display: inline-block;
                    font-size: 12px;
                    color: $stroke;
                    padding: 0 4px 0 0;
                }
            }
            @media all and (max-width: 768px){
                display: none;
                font-size: 12px;
                line-height: 20px;
                color: $stroke;
                &:first-child {
                    display: block;
                }
            }

            &:hover {
                opacity: 0.7;
            }

            &:before {
                content: '>';
                display: inline-block;
                vertical-align: middle;
                margin: 0 9px;
            }

            &:first-child {
                &:before {
                    display: none;
                }
            }

            &-list {
                padding: 0 0 0 9px;
                @media all and (max-width: 768px){
                    padding: 0;
                }
            }

            &-b {
                display: flex;
                align-items: center;
                margin: 0 0 28px;
                @media all and (max-width: 768px) {
                    margin: 0 0 8px;
                }
            }

            &-icon {
                width: 30px;
                height: 30px;
                text-align: center;
                line-height: 30px;
                background-color: #FF5F5F;
                background-position: center;
                background-repeat: no-repeat;

                @media all and (max-width: 768px){
                    display: none;
                }

                .fa {
                    color: #FFFFFF;
                }

                &.--drink {
                    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTUuMzAxOTIgOC45MjkzOEM1LjA1Mzk3IDguOTI5MzggNC44NTI5NyA5LjEzMDM4IDQuODUyOTcgOS4zNzgzNFYxMi45MDI0QzQuODUyOTcgMTMuMTUwNCA1LjA1Mzk3IDEzLjM1MTQgNS4zMDE5MiAxMy4zNTE0QzUuNTQ5ODggMTMuMzUxNCA1Ljc1MDg4IDEzLjE1MDQgNS43NTA4OCAxMi45MDI0VjkuMzc4MzRDNS43NTA4OCA5LjEzMDM4IDUuNTQ5ODggOC45MjkzOCA1LjMwMTkyIDguOTI5MzhaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNOC42OTg2NSA4LjkyOTM4QzguNDUwNjkgOC45MjkzOCA4LjI0OTY5IDkuMTMwMzggOC4yNDk2OSA5LjM3ODM0VjEyLjkwMjRDOC4yNDk2OSAxMy4xNTA0IDguNDUwNjkgMTMuMzUxNCA4LjY5ODY1IDEzLjM1MTRDOC45NDY2MiAxMy4zNTE0IDkuMTQ3NjEgMTMuMTUwNCA5LjE0NzYxIDEyLjkwMjRWOS4zNzgzNEM5LjE0NzYxIDkuMTMwMzggOC45NDY2MiA4LjkyOTM4IDguNjk4NjUgOC45MjkzOFoiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0xMi41OTMgNS41NjExNEgxMi4wNzMyVjUuMDUwMTNDMTIuMjcyNyA0LjcyMDU1IDEyLjM4NzcgNC4zMzQ0IDEyLjM4NzcgMy45MjE4QzEyLjM4NzcgMi44MDk4MiAxMS41NTI5IDEuODg5MTggMTAuNDc3MSAxLjc1MzY0QzEwLjI3NjEgMC43NTQ2NTcgOS4zOTE4MiAwIDguMzM0NjEgMEM3LjcyMDEyIDAgNy4xNDQ2NCAwLjI1NTE0NCA2LjczNDc4IDAuNjk2Njk3QzYuNTI0ODEgMC42MzEzNzMgNi4zMDQ5IDAuNTk3NTIxIDYuMDg0NjkgMC41OTc1MjFDNS4yNjcyMiAwLjU5NzUyMSA0LjUyNDA2IDEuMDYwODUgNC4xNTEzOCAxLjc2NTA0QzQuMDM1MSAxLjc0NjAxIDMuOTE3MTEgMS43MzY0NCAzLjc5ODA1IDEuNzM2NDRDMi41OTMwNCAxLjczNjQ0IDEuNjEyNzMgMi43MTY3OSAxLjYxMjczIDMuOTIxOEMxLjYxMjczIDQuMzM0NCAxLjcyNzc2IDQuNzIwNTUgMS45MjcyNyA1LjA1MDEzVjEzLjMwNjJDMS45MjcyNyAxNC43OTE2IDMuMTM1NjkgMTYgNC42MjEwMyAxNkg5LjM3OTQ4QzEwLjc1MDkgMTYgMTEuODg1NCAxNC45Njk2IDEyLjA1MTUgMTMuNjQyNEgxMi41OTNDMTMuNTgyMyAxMy42NDI0IDE0LjM4NzEgMTIuODM3NiAxNC4zODcxIDExLjg0ODJWNy4zNTUzNkMxNC4zODcyIDYuMzY2MDMgMTMuNTgyMyA1LjU2MTE0IDEyLjU5MyA1LjU2MTE0Wk0zLjc5ODA1IDIuNjM0MzZDMy45NTU2MyAyLjYzNDM2IDQuMTEwMDMgMi42NjI3OCA0LjI1Njk3IDIuNzE4ODZDNC4zNzQyNCAyLjc2MzYyIDQuNTA0ODkgMi43NTcyIDQuNjE3MjYgMi43MDEyMUM0LjcyOTU5IDIuNjQ1MjMgNC44MTM0MSAyLjU0NDc1IDQuODQ4MyAyLjQyNDIxQzUuMDA2NTEgMS44NzczNyA1LjUxNDk2IDEuNDk1NDQgNi4wODQ2OSAxLjQ5NTQ0QzYuMjkxNzUgMS40OTU0NCA2LjQ4OTc5IDEuNTQzNDQgNi42NzMzNyAxLjYzODA4QzYuODc4MTQgMS43NDM1OCA3LjEyOTU2IDEuNjc3MDkgNy4yNTUyNiAxLjQ4NDA0QzcuNDk0MzQgMS4xMTcwMSA3Ljg5NzgyIDAuODk3OTIgOC4zMzQ2MSAwLjg5NzkyQzkuMDQyODkgMC44OTc5MiA5LjYxOTQgMS40NzI5IDkuNjIyIDIuMTgwNkM5LjYyMTkxIDIuMTg0MjggOS42MjE4NyAyLjE4ODAxIDkuNjIxODcgMi4xOTA4OEM5LjYyMTg3IDIuMzE1ODMgOS42NzM5NSAyLjQzNTEyIDkuNzY1NTggMi41MjAxQzkuODU3MjEgMi42MDUwNSA5Ljk3OTk2IDIuNjQ4MDEgMTAuMTA0NyAyLjYzODU4QzEwLjE0MjEgMi42MzU3NiAxMC4xNzQgMi42MzQzNiAxMC4yMDI0IDIuNjM0MzZDMTAuOTEyMyAyLjYzNDM2IDExLjQ4OTggMy4yMTE5MSAxMS40ODk4IDMuOTIxOEMxMS40ODk4IDQuNjMxNyAxMC45MTIzIDUuMjA5MjQgMTAuMjAyNCA1LjIwOTI0SDYuMzkwNDhDNi4xNDI1MiA1LjIwOTI0IDUuOTQxNTIgNS40MTAyNCA1Ljk0MTUyIDUuNjU4MlY3LjMwNTIxQzUuOTQxNTIgNy41NTE2NSA1Ljc0MTA2IDcuNzUyMTEgNS40OTQ2NyA3Ljc1MjExQzUuMjQ4MjMgNy43NTIxMSA1LjA0NzczIDcuNTUxNjUgNS4wNDc3MyA3LjMwNTIxVjUuNjU4MkM1LjA0NzczIDUuNDEwMjQgNC44NDY3MyA1LjIwOTI0IDQuNTk4NzcgNS4yMDkyNEgzLjc5ODA1QzMuMDg4MTUgNS4yMDkyNCAyLjUxMDY1IDQuNjMxNyAyLjUxMDY1IDMuOTIxOEMyLjUxMDY1IDMuMjExOTEgMy4wODgxNSAyLjYzNDM2IDMuNzk4MDUgMi42MzQzNlpNMTEuMTc1MyAxMy4zMDYyQzExLjE3NTMgMTQuMjk2NSAxMC4zNjk3IDE1LjEwMjEgOS4zNzk0OCAxNS4xMDIxSDQuNjIwOTlDMy42MzA3NiAxNS4xMDIxIDIuODI1MTUgMTQuMjk2NSAyLjgyNTE1IDEzLjMwNjJWNS44Nzc4M0MzLjExODM3IDYuMDI0MjggMy40NDg2MiA2LjEwNzEyIDMuNzk4IDYuMTA3MTJINC4xNDk4MVY3LjMwNTE3QzQuMTQ5ODEgOC4wNDY3MSA0Ljc1MzEyIDguNjQ5OTggNS40OTQ2NyA4LjY0OTk4QzYuMjM2MTcgOC42NDk5OCA2LjgzOTQ0IDguMDQ2NjcgNi44Mzk0NCA3LjMwNTE3VjYuMTA3MTJIMTAuMjAyNEMxMC41NTE4IDYuMTA3MTIgMTAuODgyMSA2LjAyNDI0IDExLjE3NTMgNS44Nzc4M1YxMy4zMDYySDExLjE3NTNaTTEzLjQ4OTMgMTEuODQ4MkMxMy40ODkzIDEyLjM0MjUgMTMuMDg3MiAxMi43NDQ1IDEyLjU5MyAxMi43NDQ1SDEyLjA3MzJWNi40NTkwNkgxMi41OTNDMTMuMDg3MiA2LjQ1OTA2IDEzLjQ4OTMgNi44NjExNSAxMy40ODkzIDcuMzU1MzZWMTEuODQ4MloiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=);
                }
            }
        }

        &__review {
            &-list {
                padding: 0 0 20px;
                list-style: none;
                @media all and (max-width: 768px){
                    margin: 0;
                    padding: 0 0 40px;
                }
            }

            &-item {
                margin: 34px 0 0;
                @media all and (max-width: 768px){
                    margin: 23px 0 0;
                }

                &:first-child {
                    margin: 14px 0 0;
                    @media all and (max-width: 768px){
                        margin: 0;
                    }
                }
            }

            &-top {
                margin: 0 0 9px;
                font-size: 0;
            }

            &-title {
                font-size: 16px;
                line-height: 20px;
                font-weight: 700;
                display: inline-block;
                vertical-align: top;
                @media all and (max-width: 768px){
                    font-size: 14px;
                    line-height: 24px;
                    display: block;
                }
            }

            &-date {
                color: #5B6067;
                display: inline-block;
                vertical-align: top;
                font-size: 14px;
                line-height: 20px;
                margin: 0 0 0 9px;
                @media all and (max-width: 768px){
                    font-size: 12px;
                    line-height: 20px;
                    display: block;
                    margin: 0;
                }
            }

            &-text {
                font-size: 16px;
                line-height: 30px;
                margin: 9px 0 0;
                @media all and (max-width: 768px){
                    font-size: 14px;
                    line-height: 20px;
                    margin: 10px 0 0;
                }
            }

            &-add {
                cursor: pointer;
                position: absolute;
                bottom: 0;
                left: 40px;
                right: 30px;
                line-height: 50px;
                font-size: 16px;
                color: #FFFFFF;
                background: $blue;
                text-align: center;
                -webkit-transition: opacity 0.4s;
                -moz-transition: opacity 0.4s;
                -ms-transition: opacity 0.4s;
                -o-transition: opacity 0.4s;
                transition: opacity 0.4s;
                @media all and (max-width: 768px){
                    left: 20px;
                    right: 20px;
                    line-height: 40px;
                    font-size: 14px;
                }

                &:hover {
                    opacity: 0.9
                }
            }
        }

        &__history {
            &-list {
                padding: 0;
                list-style: none;
                margin: 0;
            }

            &-item {
                margin: 30px 0 0;
                padding: 0 0 0 100px;
                position: relative;
                @media all and (max-width: 768px) {
                    padding: 0;
                }
                &:first-child {
                    margin: 4px 0 0;
                    @media all and (max-width: 768px) {
                        margin: 0;
                    }
                }
            }

            &-text {
                font-size: 16px;
                line-height: 20px;
                @media all and (max-width: 768px) {
                    font-size: 14px;
                    margin: 10px 0 0;
                }
            }

            &-date {
                width: 100px;
                display: block;
                font-size: 14px;
                line-height: 20px;
                color: #5B6067;
                position: absolute;
                left: 0;
                top: 0;
                @media all and (max-width: 768px) {
                    position: relative;
                    font-size: 12px;
                }
            }
        }

        &__photo {
            margin: 17px 0 0;


            &:first-child {
                margin: 4px 0 0;
            }

            &-year {
                font-size: 16px;
                line-height: 20px;
                margin: 0 0 16px;
            }

            &-list {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-start;
                align-items: top;

                &.--video {
                    justify-content: space-between;

                    .object-side__photo-link {
                        width: calc(50% - 5px);
                        margin: 0 0 10px;
                        position: relative;

                        &:after {
                            content: '';
                            position: absolute;
                            width: 50px;
                            height: 50px;
                            left: 50%;
                            top: 50%;
                            margin: -25px 0 0 -25px;
                            background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjUiIGN5PSIyNSIgcj0iMjUiIGZpbGw9ImJsYWNrIiBmaWxsLW9wYWNpdHk9IjAuNSIvPgo8cGF0aCBkPSJNMTkgMTZMMzQgMjUuNUwxOSAzNVYxNloiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=') center no-repeat;
                        }
                    }

                    img {
                        width: 100%;
                        height: auto;
                    }
                }
            }

            &-link {
                display: block;
                margin: 0 10px 10px 0;

                img {
                    display: block;
                    height: 150px;
                    width: auto;
                }
            }
        }
    }
</style>