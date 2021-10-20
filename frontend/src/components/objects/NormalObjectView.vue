<template>
  <div class="sidebar-wrapper" :class="{ opened: mobileOpened }">
    <div class="mob-menu">
      <nuxt-link :to="localePath({ name: 'index' })" class="main-filter__logo">
        <img src="@/assets/img/logo-new-black.svg" alt="logo" />
      </nuxt-link>
      <div
        v-if="!$route.name.includes('objects-id__')"
        class="burger-wrapper"
        @click="mainPageMobOpened()"
      >
        <span class="burger">
          <span class="burger-line"></span>
        </span>
      </div>
    </div>
    <div class="sidebar" :class="{ 'sidebar-show': detailObject }">
      <div
        @touchend="showDetail($event)"
        @touchstart="touchStart($event)"
        @click="showDetail($event)"
        v-if="!moreDetailsShow"
        class="object-side__close-mobile-wrapper"
      >
        <div class="object-side__close-mobile"></div>
      </div>
      <div class="object-side__close-wrapper" v-if="!moreDetailsShow">
        <nuxt-link
          :to="localePath({ name: 'index' })"
          class="object-side__close"
        >
        </nuxt-link>
      </div>
      <div class="object-side">
        <div
          v-if="object.photos[0]"
          class="object-side__top"
          :style="
            `background: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url(${object.photos[0].previewUrl}) no-repeat center`
          "
        >
          <div class="object-side__breadcrumb-b">
            <div class="object-side__breadcrumb-icon">
              <img
                :src="'/' + object.icon"
                alt="object_icon"
                class="object-side__breadcrumb-icon-img"
              />
            </div>
            <div class="object-side__breadcrumb-list">
              <a class="object-side__breadcrumb"
                ><span>&#8592;</span>
                <div class="object-side__breadcrumb-gray">
                  {{ object.category }}
                </div>
              </a>
              <a class="object-side__breadcrumb">{{ object.subCategory }}</a>
            </div>
          </div>
          <div class="object-side__heading">
            <h2 class="object-side__title">{{ object.title }}</h2>
            <div class="object-side__heading-wrap">
              <div class="object-side__address">
                {{ object.address }}
              </div>
              <div
                class="object-side__heading-availability object-side_availability availability__title"
                :class="overallAccessibility.class"
              >
                {{ $t(`accessibilityStatus.${object.overallScore}`) }}
              </div>
              <div class="object-side__verification">
                {{
                  $t(`objects.verificationStatus.${object.verificationStatus}`)
                }}
              </div>
            </div>
          </div>
        </div>
        <div class="object-side__wrapper">
          <div
            class="availability"
            :style="{ backgroundColor: overallAccessibility.backgroundColor }"
          >
            <div
              class="object-side__breadcrumb-b object-side__breadcrumb-b-mobile"
            >
              <div class="object-side__breadcrumb-icon">
                <img
                  :src="'/' + object.icon"
                  alt="object_icon"
                  class="object-side__breadcrumb-icon-img"
                />
              </div>
              <div class="object-side__breadcrumb-list">
                <a class="object-side__breadcrumb"
                  ><span>&#8592;</span>
                  <div class="object-side__breadcrumb-gray">
                    {{ object.category }}
                  </div>
                </a>
                <a class="object-side__breadcrumb">{{ object.subCategory }}</a>
              </div>
            </div>
            <div
              class="availability__title"
              :class="overallAccessibility.class"
            >
              {{ $t(`accessibilityStatus.${object.overallScore}`) }}
            </div>
            <div class="availability__list">
              <div
                class="availability__item"
                :class="zone.class"
                v-for="zone in zones"
                :key="zone.key"
              >
                {{ $t(`objects.zone.${zone.key}`) }}
              </div>
            </div>
          </div>
          <div class="object-side__content">
            <div class="object-side__tab-link-wrapper">
              <div class="object-side__tab-link-b">
                <nuxt-link
                  v-for="(tab, index) in tabs"
                  :to="tab.link"
                  :key="index"
                  class="object-side__tab-link"
                  :class="{ active: $route.query.tab === tab.link.query.tab }"
                >
                  {{ tab.title }}
                  <span class="object-side__tab-num" v-if="tab.counter >= 0">{{
                    tab.counter
                  }}</span>
                </nuxt-link>
              </div>
            </div>
            <div class="object-side__tab-content-b">
              <div
                class="object-side__tab-content"
                :class="{ active: !$route.query.tab }"
                id="tab-description"
              >
                <p class="text">{{ object.description }}</p>
                <div class="text__verification-b">
                  <span
                    class="text__verification-link"
                    v-on:click="moreDetailsShow = true"
                    >{{ $t("objects.detailedInfo") }}</span
                  >
                </div>
                <hr />
                <div class="object-side__button-b">
                  <nuxt-link
                    :to="
                      localePath({
                        name: 'complaint',
                        query: { objectId: $route.params.id },
                      })
                    "
                    class="object-side__button --complaint"
                  >
                    <img src="@/assets/icons/chat_plus.svg" alt="complaint" />
                    {{ $t("objects.makeComplaint") }}
                  </nuxt-link>
                  <nuxt-link
                    :to="
                      localePath({
                        name: 'objects-id-verify',
                        params: { id: $route.params.id },
                      })
                    "
                    class="object-side__button --check"
                  >
                    <img src="@/assets/icons/check.svg" alt="check" />
                    <div class="object-side__button-text">
                      {{ $t("objects.confirm") }}
                    </div>
                  </nuxt-link>
                </div>
              </div>
              <div
                class="object-side__tab-content"
                :class="{ active: $route.query.tab === 'photos' }"
                id="tab-photo"
              >
                <div
                  class="object-side__photo"
                  v-for="group in photosByYear"
                  :key="group.year"
                >
                  <div class="object-side__photo-list">
                    <a
                      :href="photo.viewUrl"
                      class="object-side__photo-link"
                      v-for="(photo, imageIndex) in group.photos"
                      :key="`${group.year}${imageIndex}`"
                      @click.prevent="viewPhoto(photo)"
                    >
                      <img :src="photo.previewUrl" alt="" />
                    </a>
                  </div>
                </div>
                <nuxt-link
                  class="object-side__review-add"
                  v-if="$route.query.tab === 'photos'"
                  :to="addPhotosRoute"
                >
                  Добавить фото
                </nuxt-link>
              </div>
              <div
                class="object-side__tab-content"
                :class="{ active: $route.query.tab === 'videos' }"
                id="tab-video"
              >
                <div class="object-side__photo">
                  <div class="object-side__photo-list --video">
                    <a
                      href="#"
                      class="object-side__photo-link-video"
                      v-for="(video, videoIndex) in object.videos"
                      :key="videoIndex"
                      @click="
                        imagesIndex = null;
                        videosIndex = videoIndex;
                      "
                    >
                      <img :src="video.thumbnail" />
                    </a>
                  </div>
                </div>
              </div>
              <div
                class="object-side__tab-content"
                :class="{ active: $route.query.tab === 'reviews' }"
                id="tab-reviews"
              >
                <ul class="object-side__review-list">
                  <li
                    class="object-side__review-item"
                    v-for="(review, index) in object.reviews"
                    :key="index"
                  >
                    <div class="object-side__review-top">
                      <div class="object-side__review-top-photo">
                        <img
                          src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pjxzdmcgdmlld0JveD0iMCAwIDMyIDMyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxkZWZzPjxzdHlsZT4uY2xzLTF7ZmlsbDpub25lO308L3N0eWxlPjwvZGVmcz48dGl0bGUvPjxnIGRhdGEtbmFtZT0iTGF5ZXIgMiIgaWQ9IkxheWVyXzIiPjxwYXRoIGQ9Ik0xNiwyOUExMywxMywwLDEsMSwyOSwxNiwxMywxMywwLDAsMSwxNiwyOVpNMTYsNUExMSwxMSwwLDEsMCwyNywxNiwxMSwxMSwwLDAsMCwxNiw1WiIvPjxwYXRoIGQ9Ik0xNiwxN2E1LDUsMCwxLDEsNS01QTUsNSwwLDAsMSwxNiwxN1ptMC04YTMsMywwLDEsMCwzLDNBMywzLDAsMCwwLDE2LDlaIi8+PHBhdGggZD0iTTI1LjU1LDI0YTEsMSwwLDAsMS0uNzQtLjMyQTExLjM1LDExLjM1LDAsMCwwLDE2LjQ2LDIwaC0uOTJhMTEuMjcsMTEuMjcsMCwwLDAtNy44NSwzLjE2LDEsMSwwLDAsMS0xLjM4LTEuNDRBMTMuMjQsMTMuMjQsMCwwLDEsMTUuNTQsMThoLjkyYTEzLjM5LDEzLjM5LDAsMCwxLDkuODIsNC4zMkExLDEsMCwwLDEsMjUuNTUsMjRaIi8+PC9nPjxnIGlkPSJmcmFtZSI+PHJlY3QgY2xhc3M9ImNscy0xIiBoZWlnaHQ9IjMyIiB3aWR0aD0iMzIiLz48L2c+PC9zdmc+"
                          alt=""
                        />
                      </div>
                      <div class="object-side__review-top-wrap">
                        <username
                          class="object-side__review-title"
                          :value="review.author"
                          tag="span"
                        />
                        <formatted-date
                          class="object-side__review-date"
                          :date="review.createdAt"
                          format="dd.MM.yyyy"
                        />
                      </div>
                    </div>
                    <p class="object-side__review-text">{{ review.text }}</p>
                  </li>
                </ul>
                <nuxt-link
                  v-if="$route.query.tab === 'reviews'"
                  :to="localePath(reviewsLink)"
                  class="object-side__review-add"
                  >{{ $t("objects.review.linkButtonTitle") }}
                </nuxt-link>
              </div>
              <div
                class="object-side__tab-content"
                :class="{ active: $route.query.tab === 'history' }"
              >
                <ul class="object-side__history-list">
                  <li
                    class="object-side__history-item object-side__review-top"
                    v-for="(item, index) in object.history"
                    :key="index"
                  >
                    <formatted-date
                      class="object-side__review-date"
                      :date="item.date"
                      format="dd.MM.yyyy"
                    />
                    <p class="object-side__history-text">
                      <username :value="item.name" />
                      <template v-if="item.data.type === 'review_created'">{{
                        $t("objects.history.reviewed")
                      }}</template>
                      <template
                        v-if="item.data.type === 'verification_confirmed'"
                        >{{ $t("objects.history.confirmed") }}</template
                      >
                      <template
                        v-if="item.data.type === 'verification_rejected'"
                        >{{ $t("objects.history.notConfirmed") }}</template
                      >
                      <template v-if="item.data.type === 'supplemented'">{{
                        $t("objects.history.supplemented")
                      }}</template>
                    </p>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="more-detail__wrapper" v-if="moreDetailsShow">
            <!-- <span class="more-detail__close" v-on:click="moreDetailsShow = false"></span> -->

            <div
              v-on:click="moreDetailsShow = false"
              class="more-detail__close object-side__close-wrapper"
            >
              <div class="object-side__close"></div>
            </div>
            <div class="more-detail__top">
              {{ $t("objects.detailedInfo") }}
              <a
                :href="`/api/objects/${$route.params.id}/pdf`"
                target="_blank"
                class="more-detail__download"
                download
              >
                {{ $t("objects.download") }}
              </a>
            </div>
            <div class="more-detail__content" id="more-detail__content">
              <div
                :id="zone.key"
                class="more-detail__item"
                v-for="zone in detailsZones"
                :key="zone.key"
              >
                <h3 class="more-detail__item-title">{{ zone.title }}</h3>
                <template v-for="(group, index) in attributesList[zone.group]">
                  <h4 class="more-detail__line-title" v-if="group.title" :key="index">
                    {{ group.title }}
                  </h4>
                  <template v-for="(sub, index2) in group.subGroups">
                    <h4 class="more-detail__line-title" v-if="sub.title" :key="index2">
                      {{ sub.title }}
                    </h4>
                    <div
                      class="more-detail__line"
                      v-for="attribute in sub.attributes"
                      :key="attribute"
                      :class="{
                        yes:
                          object.attributes.zones[zone.key][
                            `attribute${attribute.key}`
                          ] === 'yes',
                        no:
                          object.attributes.zones[zone.key][
                            `attribute${attribute.key}`
                          ] === 'no',
                        empty: !['yes', 'no'].includes(
                          object.attributes.zones[zone.key][
                            `attribute${attribute.key}`
                          ]
                        ),
                      }"
                    >
                      <span class="more-detail__line-text"
                        >{{ attribute.title }} {{ attribute.subTitle }}</span
                      >
                      <span
                        class="more-detail__line-status"
                        v-if="
                          object.attributes.zones[zone.key][
                            `attribute${attribute.key}`
                          ] === 'yes'
                        "
                        >{{ $t("objects.attribute.yes") }}</span
                      >
                      <span
                        class="more-detail__line-status"
                        v-else-if="
                          object.attributes.zones[zone.key][
                            `attribute${attribute.key}`
                          ] === 'no'
                        "
                        >{{ $t("objects.attribute.no") }}</span
                      >
                      <span class="more-detail__line-status" v-else
                        >&mdash;</span
                      >
                    </div>
                  </template>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <nuxt-child :objectName="object.title" />
    <client-only>
      <gallery
        id="blueimp-gallery"
        :images="images"
        :index="imagesIndex"
        :options="imagesOptions"
        @close="imagesIndex = null"
      ></gallery>
      <gallery
        id="blueimp-video"
        :images="videos"
        :index="videosIndex"
        :options="videosOptions"
        @close="videosIndex = null"
      ></gallery>
    </client-only>
    <post-submit-message />
  </div>
</template>

<script>
import { sync, get } from "vuex-pathify";
import groupBy from "lodash/groupBy";
import map from "lodash/map";
import chunk from "lodash/chunk";
import Username from "~/components/Username";
import PostSubmitMessage from "~/components/complaint/PostSubmitMessage";
import LangSelect from "~/components/LangSelect";
import FormattedDate from "~/components/FormattedDate";
import PhotoUploader from "~/components/object_add/PhotoUploader";

const accessibilityValues = {
  full_accessible: {
    class: "--available",
    backgroundColor: "rgba(61,186,59,0.15)",
  },
  partial_accessible: {
    class: "--partially",
    backgroundColor: "rgba(248,172,26,0.15)",
  },
  not_accessible: {
    class: "--not-available",
    backgroundColor: "rgba(222,18,32,0.1)",
  },
  not_provided: {
    class: "--not-provided",
    backgroundColor: "rgba(123,149,167,0.15)",
  },
  unknown: {
    class: "--not-provided",
    backgroundColor: "rgba(123,149,167,0.15)",
  },
};

const accessibilityValuesList = {
  full_accessible: {
    class: "--availableList",
    backgroundColor: "rgba(61,186,59,0.15)",
  },
  partial_accessible: {
    class: "--partiallyList",
    backgroundColor: "rgba(248,172,26,0.15)",
  },
  not_accessible: {
    class: "--not-availableList",
    backgroundColor: "rgba(222,18,32,0.1)",
  },
  not_provided: {
    class: "--not-providedList",
    backgroundColor: "rgba(123,149,167,0.15)",
  },
  unknown: {
    class: "--not-providedList",
    backgroundColor: "rgba(123,149,167,0.15)",
  },
};

const zones = [
  { key: "parking", label: "Парковка" },
  { key: "entrance", label: "Входная группа" },
  { key: "movement", label: "Пути движения по объекту" },
  { key: "service", label: "Зона оказания услуги" },
  { key: "toilet", label: "Туалет" },
  { key: "navigation", label: "Навигация" },
  { key: "serviceAccessibility", label: "Доступность услуги" },
  { key: "kidsAccessibility", label: "" },
];

export default {
  components: {
    FormattedDate,
    PostSubmitMessage,
    Username,
    LangSelect,
    PhotoUploader,
  },
  name: "NormalObjectView",
  props: ["mobileOpened"],
  head() {
    return {
      title: this.object.title,
    };
  },
  data() {
    return {
      isPartially: false,
      isNotAvailable: false,
      isAvailable: true,
      activeItem: "tab-description",
      visibleDetail: "detail_1",
      moreDetailsShow: false,
      addPhotosPopup: false,
      videosIndex: null,
      videosOptions: {
        container: "#blueimp-video",
        continuous: false,
        youTubeVideoIdProperty: "youtube",
        youTubePlayerVars: undefined,
        youTubeClickToPlay: true,
      },
      imagesIndex: null,
      imagesOptions: {
        continuous: false,
        onslide: function(index, slide) {
          var indicator = document.getElementsByClassName("indicator");
          indicator[0].innerHTML =
            index + 1 + " / " + document.getElementsByClassName("slide").length;
        },
      },
      detailObject: false,
      touchStartData: null,
    };
  },
  mounted() {
    this.visibleDetail = this.detailsZones[0].key;
  },
  computed: {
    ...sync("map", ["coordinates", "zoom", "coordinatesAndZoom"]),
    addPhotosRoute() {
      return this.localePath({
        name: "objects-id-add-photos",
        query: { tab: "photos" },
        params: { id: this.$route.params.id },
      });
    },
    overallAccessibility() {
      return accessibilityValues[this.object.overallScore];
    },
    zones() {
      return zones.map((zone) => ({
        ...zone,
        class:
          accessibilityValuesList[this.object.scoreByZones[zone.key]].class,
      }));
    },
    reviewsLink() {
      return {
        name: "objects-id-review",
        params: {
          id: this.$route.params.id,
        },
      };
    },
    photosByYear() {
      return map(
        groupBy(this.object.photos, (photo) =>
          new Date(photo.date).getFullYear()
        ),
        (v, k) => ({
          year: Number(k),
          photos: v,
        })
      ).sort((a, b) => b.year - a.year);
    },
    tabs() {
      return [
        {
          title: this.$t("objects.tabTitles.description"),
          link: { ...this.$route, query: { tab: undefined } },
        },
        {
          title: this.$t("objects.tabTitles.photos"),
          link: { ...this.$route, query: { tab: "photos" } },
          counter: this.object.photos.length,
        },
        {
          title: this.$t("objects.tabTitles.videos"),
          link: { ...this.$route, query: { tab: "videos" } },
          counter: this.object.videos.length,
        },
        {
          title: this.$t("objects.tabTitles.reviews"),
          link: { ...this.$route, query: { tab: "reviews" } },
          counter: this.object.reviews.length,
        },
        {
          title: this.$t("objects.tabTitles.history"),
          link: { ...this.$route, query: { tab: "history" } },
        },
      ];
    },
    attributesList: get("objectAdding/attributesList[:form]"),
    form() {
      return this.object.attributes.form;
    },
    detailsZones() {
      const zones = [
        {
          key: "parking",
          title: this.$t("objects.zone.parking"),
          group: "parking",
        },
        {
          key: "entrance1",
          title: this.$t("objects.zone.entrance") + " 1",
          group: "entrance",
        },
        {
          key: "entrance2",
          title: this.$t("objects.zone.entrance") + " 2",
          group: "entrance",
        },
        {
          key: "entrance3",
          title: this.$t("objects.zone.entrance") + " 3",
          group: "entrance",
        },
        {
          key: "movement",
          title: this.$t("objects.zone.movement"),
          group: "movement",
        },
        {
          key: "service",
          title: this.$t("objects.zone.service"),
          group: "service",
        },
        {
          key: "toilet",
          title: this.$t("objects.zone.toilet"),
          group: "toilet",
        },
        {
          key: "navigation",
          title: this.$t("objects.zone.navigation"),
          group: "navigation",
        },
        {
          key: "serviceAccessibility",
          title: this.$t("objects.zone.serviceAccessibility"),
          group: "serviceAccessibility",
        },
        {
          key: "kidsAccessibility",
          title: this.$t("objects.zone.kidsAccessibility"),
          group: "kidsAccessibility",
        },
      ];

      return zones.filter((z) => this.object.attributes.zones[z.key]);
    },
    zonesMenu() {
      return chunk(this.detailsZones, Math.round(this.detailsZones.length / 2));
    },
    images() {
      return this.object.photos.map((photo) => photo.viewUrl);
    },
    videos: get("object/videos"),
    object: get("object/item"),
    formAttributesByZone: get("objectAdding/formAttributesByZone"),
  },
  methods: {
    mainPageMobOpened() {
      this.$nuxt.$emit("mainPageMobOpened");
    },
    isActive(tabItem) {
      return this.activeItem === tabItem;
    },
    isVisibleDetail(detail) {
      return this.visibleDetail === detail;
    },
    setVisible(detail) {
      this.visibleDetail = detail;
      document.getElementById("" + detail + "").scrollIntoView();
    },
    setActive(tabItem) {
      this.activeItem = tabItem;
    },
    viewPhoto(photo) {
      console.log(photo);
      this.videosIndex = null;
      this.imagesIndex = this.object.photos.indexOf(photo);
    },
    touchStart(event) {
      if (event.touches) {
        this.touchStartData = event.touches[0].clientY;
      }
    },
    showDetail(event) {
      if (event.changedTouches) {
        if (event.changedTouches[0].clientY <= this.touchStartData) {
          this.detailObject = true;
        } else if (
          event.changedTouches[0].clientY >= this.touchStartData &&
          this.touchStartData <= window.innerHeight / 2
        ) {
          this.detailObject = false;
        } else if (
          this.touchStartData >= window.innerHeight / 2 &&
          event.changedTouches[0].clientY >= this.touchStartData
        ) {
          this.$router.replace({ path: "/" });
        }
      } else {
        this.detailObject = !this.detailObject;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import "~/styles/mixins.scss";

.mob-menu {
  display: none;
  left: 0;
  top: 0;
  right: 0;
  height: 50px;
  z-index: 10;
  background: #ffffff;
  justify-content: space-between;
  align-items: center;
  position: fixed;
  padding: 0 20px;
  .main-filter__logo {
    img {
      width: 100px;
    }
  }
  @media all and (max-width: 1023px) {
    display: flex;
  }
}

#blueimp-gallery {
  left: 600px;
  width: auto;
  margin: 30px;
  box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06), 0px 2px 6px rgba(0, 0, 0, 0.04),
    0px 0px 1px rgba(0, 0, 0, 0.04);
  border-radius: 10px;
  @media all and (max-width: 1366px) {
    left: 560px;
  }
  > {
    .close {
      width: 24px;
      height: 24px;
      right: 14px;
      top: 14px;
      margin: 0;
      font-size: 0;
      background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xNi42NjY3IDQuMDA4ZS0wNkwyMCAzLjMzMzM0TDEzLjMzMzMgMTBMMjAgMTYuNjY2N0wxNi42NjY3IDIwTDEwIDEzLjMzMzNMMy4zMzMzMyAyMEwwIDE2LjY2NjdMNi42NjY2NyAxMEwwIDMuMzMzMzNMMy4zMzMzMyAwTDEwIDYuNjY2NjdMMTYuNjY2NyA0LjAwOGUtMDZaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4K")
        center no-repeat;
    }

    .slides {
      > .slide {
        height: 85vh;
        top: 70px;
        > .slide-content {
          padding: 0 60px;
          height: 100%;
          width: 100%;
          object-fit: contain;
        }
      }
    }

    .next,
    .prev {
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
      background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNjIiIHZpZXdCb3g9IjAgMCAzMiA2MiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMxIDFMMSAzMUwzMSA2MSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg==")
        center no-repeat;
    }

    .next {
      background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNjIiIHZpZXdCb3g9IjAgMCAzMiA2MiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEgNjFMMzEgMzFMMSAxIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K")
        center no-repeat;
    }

    .title {
      top: 110px;
      left: 50%;
      transform: translateX(-50%);
    }
    .description {
      top: 140px;
      left: 50%;
      transform: translateX(-50%);
    }

    .indicator {
      color: #ffffff;
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
      background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xNi42NjY3IDQuMDA4ZS0wNkwyMCAzLjMzMzM0TDEzLjMzMzMgMTBMMjAgMTYuNjY2N0wxNi42NjY3IDIwTDEwIDEzLjMzMzNMMy4zMzMzMyAyMEwwIDE2LjY2NjdMNi42NjY2NyAxMEwwIDMuMzMzMzNMMy4zMzMzMyAwTDEwIDYuNjY2NjdMMTYuNjY2NyA0LjAwOGUtMDZaIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4K")
        center no-repeat;
    }

    .slides > .slide > .slide-content {
      max-height: 508px;
      max-width: 900px;
    }

    .next,
    .prev {
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
      background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNjIiIHZpZXdCb3g9IjAgMCAzMiA2MiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMxIDFMMSAzMUwzMSA2MSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg==")
        center no-repeat;
    }

    .next {
      background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIiIGhlaWdodD0iNjIiIHZpZXdCb3g9IjAgMCAzMiA2MiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEgNjFMMzEgMzFMMSAxIiBzdHJva2U9IndoaXRlIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K")
        center no-repeat;
    }

    .slides > .slide > .video-content > iframe {
      position: absolute;
      left: 0;
      top: 0;
    }

    .slides > .slide > .video-content > a {
      background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjUiIGN5PSIyNSIgcj0iMjUiIGZpbGw9ImJsYWNrIiBmaWxsLW9wYWNpdHk9IjAuNSIvPgo8cGF0aCBkPSJNMTkgMTZMMzQgMjUuNUwxOSAzNVYxNloiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=")
        center no-repeat;
      background-size: 128px;
    }

    .slides > .slide > .video-content:not(.video-loading) > a {
      background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjUiIGN5PSIyNSIgcj0iMjUiIGZpbGw9ImJsYWNrIiBmaWxsLW9wYWNpdHk9IjAuNSIvPgo8cGF0aCBkPSJNMTkgMTZMMzQgMjUuNUwxOSAzNVYxNloiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=")
        center no-repeat;
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

  &.blueimp-gallery-left > .prev,
  &.blueimp-gallery-right > .next {
    display: none;
  }
}

.sidebar {
  position: fixed;
  top: 100px;
  left: 25px;
  bottom: 0;
  width: 498px;
  height: 85%;
  background: $tr;
  transform: translateX(0);
  transition: transform 0.3s;
  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;
  z-index: 5;
  @media all and (max-width: 1024px) {
    width: 450px;
    padding-left: 0;
    top: 50px;
  }
  @media all and (max-width: 768px) {
    z-index: 11;
  }
  &-wrapper {
    .sidebar {
      @media all and (max-width: 1023px) {
        position: absolute;
        height: 100%;
        top: auto;
        bottom: calc(-100% + 152px);
        width: 100%;
        padding-left: 0;
        padding-right: 0;
        left: 0;
        transition: bottom 1s;
      }
      @media screen and (min-width: 1900px) {
        width: 550px;
      }
      &-show {
        @media (max-width: 1023px) {
          transition: bottom 1s;
          bottom: -50px;
        }
      }
    }
  }
}

.--availableList {
  background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHZpZXdCb3g9IjAgMCAxOCAxOCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMuNzUgOUw3LjI1MDk4IDEyLjc1TDE0LjI1IDUuMjUiIHN0cm9rZT0iIzI3QUU2MCIgc3Ryb2tlLXdpZHRoPSI0IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg==);
}

.--partiallyList {
  background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iNCIgdmlld0JveD0iMCAwIDE2IDQiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0yIDJIMTQiIHN0cm9rZT0iI0YyOTk0QSIgc3Ryb2tlLXdpZHRoPSI0IiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4KPC9zdmc+Cg==);
}
.--not-availableList {
  background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTMiIGhlaWdodD0iMTMiIHZpZXdCb3g9IjAgMCAxMyAxMyIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIuNzk5MDcgMi4wOTcxN0wxMC45NTkxIDEwLjI1NzIiIHN0cm9rZT0iI0VCNTc1NyIgc3Ryb2tlLXdpZHRoPSI0IiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4KPHBhdGggZD0iTTExIDIuMDk2MTNMMi44NCAxMC4yNTYxIiBzdHJva2U9IiNFQjU3NTciIHN0cm9rZS13aWR0aD0iNCIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIi8+Cjwvc3ZnPgo=);
}

.--not-providedList {
  background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwKSI+CjxwYXRoIGQ9Ik0xMy4xNzgzIDQuNTUwMDRWNC41MDczMUMxMy4xNzgzIDEuNzE3MDcgMTAuOTY3MyAwIDcuODEyNTEgMEM1Ljk5MjY5IDAgNC41NDYwMiAwLjUxMzA1NyAzLjM4NzE4IDEuMzA3MzlDMi43MTM1MSAxLjc2OTA3IDIuNjUyODcgMi43ODEyNSAzLjE0NjQxIDMuNDMyNjFMMy41MDYzMSAzLjkwNzYxQzMuOTk5ODUgNC41NTg2OSA0Ljg4MzYyIDQuNjIwNTggNS41OTkwNSA0LjIyNTQxQzYuMjUxMDMgMy44NjUyMiA2LjkxMjMgMy42NzAyNSA3LjUzMzM5IDMuNjcwMjVDOC41NjM1NCAzLjY3MDI1IDkuMTQzMTEgNC4xMjA3OCA5LjE0MzExIDQuODUwNzdWNC44NzIxMUM5LjE0MzExIDUuNzk1MTkgOC4yNjMwNCA3LjExNDMgNS43NzMzMSA3LjMyODc4TDUuNjg3NTcgNy40MjUzM0M1LjY0MDI0IDcuNDc4NTggNS42OTk5NyA4LjE3Njk5IDUuODIwNjQgOC45ODQ5Mkw1LjkxOTY5IDkuNjQzNzRDNi4wNDAzNiAxMC40NTE3IDYuNzE5ODkgMTEuMTA2NCA3LjQzNjkgMTEuMTA2NEM4LjE1MzkyIDExLjEwNjQgOC43OTMxNiAxMC43OTg5IDguODYzNzcgMTAuNDE5OEM4LjkzNTIyIDEwLjA0MDUgOC45OTI4IDkuNzMyODMgOC45OTI4IDkuNzMyODNDMTEuMzc0OCA5LjE1Mjk5IDEzLjE3ODMgNy4yNTQ1NCAxMy4xNzgzIDQuNTUwMDRaIiBmaWxsPSIjNDg0ODQ4Ii8+CjxwYXRoIGQ9Ik02LjczNzIzIDExLjk5NTRDNS45MjAzMSAxMS45OTU0IDUuMjU4NDIgMTIuNjU3NiA1LjI1ODQyIDEzLjQ3NDVWMTQuMTY1MkM1LjI1ODQyIDE0Ljk4MjEgNS45MjA2NSAxNS42NDQzIDYuNzM3MjMgMTUuNjQ0M0g4LjAwNzE5QzguODI0MTEgMTUuNjQ0MyA5LjQ4NjMzIDE0Ljk4MjEgOS40ODYzMyAxNC4xNjUyVjEzLjQ3NDVDOS40ODYzMyAxMi42NTc2IDguODIzODIgMTEuOTk1NCA4LjAwNzE5IDExLjk5NTRINi43MzcyM1oiIGZpbGw9IiM0ODQ4NDgiLz4KPC9nPgo8ZGVmcz4KPGNsaXBQYXRoIGlkPSJjbGlwMCI+CjxyZWN0IHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0id2hpdGUiLz4KPC9jbGlwUGF0aD4KPC9kZWZzPgo8L3N2Zz4KCg==);
}

.--partially {
  background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTEiIGhlaWdodD0iOTEiIHZpZXdCb3g9IjAgMCA5MSA5MSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgZmlsdGVyPSJ1cmwoI2ZpbHRlcjBfZGRkKSI+CjxwYXRoIGQ9Ik0yNC4zNTI5IDE4QzI0LjM1MjkgMTIuNDc3MiAyOC44MzAxIDggMzQuMzUyOSA4SDU2LjcwNThDNjIuMjI4NyA4IDY2LjcwNTggMTIuNDc3MiA2Ni43MDU4IDE4VjQwLjM1MjlDNjYuNzA1OCA0NS44NzU4IDYyLjIyODcgNTAuMzUyOSA1Ni43MDU4IDUwLjM1MjlIMzQuMzUyOUMyOC44MzAxIDUwLjM1MjkgMjQuMzUyOSA0NS44NzU4IDI0LjM1MjkgNDAuMzUyOVYxOFoiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0yNC44NTI5IDE4QzI0Ljg1MjkgMTIuNzUzMyAyOS4xMDYyIDguNSAzNC4zNTI5IDguNUg1Ni43MDU4QzYxLjk1MjUgOC41IDY2LjIwNTggMTIuNzUzMyA2Ni4yMDU4IDE4VjQwLjM1MjlDNjYuMjA1OCA0NS41OTk2IDYxLjk1MjUgNDkuODUyOSA1Ni43MDU4IDQ5Ljg1MjlIMzQuMzUyOUMyOS4xMDYyIDQ5Ljg1MjkgMjQuODUyOSA0NS41OTk2IDI0Ljg1MjkgNDAuMzUyOVYxOFoiIHN0cm9rZT0iI0YyOTk0QSIvPgo8L2c+CjxwYXRoIGQ9Ik00MC40NDcgMjkuMTc2NUg1MC42MTE3IiBzdHJva2U9IiNGMjk5NEEiIHN0cm9rZS13aWR0aD0iNCIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIi8+CjxkZWZzPgo8ZmlsdGVyIGlkPSJmaWx0ZXIwX2RkZCIgeD0iMC4zNTI5MDUiIHk9IjAiIHdpZHRoPSI5MC4zNTI5IiBoZWlnaHQ9IjkwLjM1MjkiIGZpbHRlclVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgY29sb3ItaW50ZXJwb2xhdGlvbi1maWx0ZXJzPSJzUkdCIj4KPGZlRmxvb2QgZmxvb2Qtb3BhY2l0eT0iMCIgcmVzdWx0PSJCYWNrZ3JvdW5kSW1hZ2VGaXgiLz4KPGZlQ29sb3JNYXRyaXggaW49IlNvdXJjZUFscGhhIiB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMTI3IDAiIHJlc3VsdD0iaGFyZEFscGhhIi8+CjxmZU9mZnNldC8+CjxmZUdhdXNzaWFuQmx1ciBzdGREZXZpYXRpb249IjAuNSIvPgo8ZmVDb2xvck1hdHJpeCB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMC4wNCAwIi8+CjxmZUJsZW5kIG1vZGU9Im5vcm1hbCIgaW4yPSJCYWNrZ3JvdW5kSW1hZ2VGaXgiIHJlc3VsdD0iZWZmZWN0MV9kcm9wU2hhZG93Ii8+CjxmZUNvbG9yTWF0cml4IGluPSJTb3VyY2VBbHBoYSIgdHlwZT0ibWF0cml4IiB2YWx1ZXM9IjAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDEyNyAwIiByZXN1bHQ9ImhhcmRBbHBoYSIvPgo8ZmVPZmZzZXQgZHk9IjIiLz4KPGZlR2F1c3NpYW5CbHVyIHN0ZERldmlhdGlvbj0iMyIvPgo8ZmVDb2xvck1hdHJpeCB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMC4wNCAwIi8+CjxmZUJsZW5kIG1vZGU9Im5vcm1hbCIgaW4yPSJlZmZlY3QxX2Ryb3BTaGFkb3ciIHJlc3VsdD0iZWZmZWN0Ml9kcm9wU2hhZG93Ii8+CjxmZUNvbG9yTWF0cml4IGluPSJTb3VyY2VBbHBoYSIgdHlwZT0ibWF0cml4IiB2YWx1ZXM9IjAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDEyNyAwIiByZXN1bHQ9ImhhcmRBbHBoYSIvPgo8ZmVPZmZzZXQgZHk9IjE2Ii8+CjxmZUdhdXNzaWFuQmx1ciBzdGREZXZpYXRpb249IjEyIi8+CjxmZUNvbG9yTWF0cml4IHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwLjA2IDAiLz4KPGZlQmxlbmQgbW9kZT0ibm9ybWFsIiBpbjI9ImVmZmVjdDJfZHJvcFNoYWRvdyIgcmVzdWx0PSJlZmZlY3QzX2Ryb3BTaGFkb3ciLz4KPGZlQmxlbmQgbW9kZT0ibm9ybWFsIiBpbj0iU291cmNlR3JhcGhpYyIgaW4yPSJlZmZlY3QzX2Ryb3BTaGFkb3ciIHJlc3VsdD0ic2hhcGUiLz4KPC9maWx0ZXI+CjwvZGVmcz4KPC9zdmc+Cg==);
  color: #f2994a;
}

.--available {
  background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTEiIGhlaWdodD0iOTEiIHZpZXdCb3g9IjAgMCA5MSA5MSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgZmlsdGVyPSJ1cmwoI2ZpbHRlcjBfZGRkKSI+CjxwYXRoIGQ9Ik0yNCAxOEMyNCAxMi40NzcyIDI4LjQ3NzIgOCAzNCA4SDU2LjM1MjlDNjEuODc1OCA4IDY2LjM1MjkgMTIuNDc3MiA2Ni4zNTI5IDE4VjQwLjM1MjlDNjYuMzUyOSA0NS44NzU4IDYxLjg3NTggNTAuMzUyOSA1Ni4zNTI5IDUwLjM1MjlIMzRDMjguNDc3MiA1MC4zNTI5IDI0IDQ1Ljg3NTggMjQgNDAuMzUyOVYxOFoiIGZpbGw9IiMyN0FFNjAiLz4KPC9nPgo8cGF0aCBkPSJNMzkuMjQ3MSAyOS4xNzY1TDQzLjIwMTEgMzMuNDExOEw1MS4xMDU5IDI0Ljk0MTIiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iNCIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+CjxkZWZzPgo8ZmlsdGVyIGlkPSJmaWx0ZXIwX2RkZCIgeD0iMCIgeT0iMCIgd2lkdGg9IjkwLjM1MjkiIGhlaWdodD0iOTAuMzUyOSIgZmlsdGVyVW5pdHM9InVzZXJTcGFjZU9uVXNlIiBjb2xvci1pbnRlcnBvbGF0aW9uLWZpbHRlcnM9InNSR0IiPgo8ZmVGbG9vZCBmbG9vZC1vcGFjaXR5PSIwIiByZXN1bHQ9IkJhY2tncm91bmRJbWFnZUZpeCIvPgo8ZmVDb2xvck1hdHJpeCBpbj0iU291cmNlQWxwaGEiIHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAxMjcgMCIgcmVzdWx0PSJoYXJkQWxwaGEiLz4KPGZlT2Zmc2V0Lz4KPGZlR2F1c3NpYW5CbHVyIHN0ZERldmlhdGlvbj0iMC41Ii8+CjxmZUNvbG9yTWF0cml4IHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwLjA0IDAiLz4KPGZlQmxlbmQgbW9kZT0ibm9ybWFsIiBpbjI9IkJhY2tncm91bmRJbWFnZUZpeCIgcmVzdWx0PSJlZmZlY3QxX2Ryb3BTaGFkb3ciLz4KPGZlQ29sb3JNYXRyaXggaW49IlNvdXJjZUFscGhhIiB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMTI3IDAiIHJlc3VsdD0iaGFyZEFscGhhIi8+CjxmZU9mZnNldCBkeT0iMiIvPgo8ZmVHYXVzc2lhbkJsdXIgc3RkRGV2aWF0aW9uPSIzIi8+CjxmZUNvbG9yTWF0cml4IHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwLjA0IDAiLz4KPGZlQmxlbmQgbW9kZT0ibm9ybWFsIiBpbjI9ImVmZmVjdDFfZHJvcFNoYWRvdyIgcmVzdWx0PSJlZmZlY3QyX2Ryb3BTaGFkb3ciLz4KPGZlQ29sb3JNYXRyaXggaW49IlNvdXJjZUFscGhhIiB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMTI3IDAiIHJlc3VsdD0iaGFyZEFscGhhIi8+CjxmZU9mZnNldCBkeT0iMTYiLz4KPGZlR2F1c3NpYW5CbHVyIHN0ZERldmlhdGlvbj0iMTIiLz4KPGZlQ29sb3JNYXRyaXggdHlwZT0ibWF0cml4IiB2YWx1ZXM9IjAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAuMDYgMCIvPgo8ZmVCbGVuZCBtb2RlPSJub3JtYWwiIGluMj0iZWZmZWN0Ml9kcm9wU2hhZG93IiByZXN1bHQ9ImVmZmVjdDNfZHJvcFNoYWRvdyIvPgo8ZmVCbGVuZCBtb2RlPSJub3JtYWwiIGluPSJTb3VyY2VHcmFwaGljIiBpbjI9ImVmZmVjdDNfZHJvcFNoYWRvdyIgcmVzdWx0PSJzaGFwZSIvPgo8L2ZpbHRlcj4KPC9kZWZzPgo8L3N2Zz4K);
  color: #27ae60;
}

.--not-available {
  background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTIiIGhlaWdodD0iOTEiIHZpZXdCb3g9IjAgMCA5MiA5MSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgZmlsdGVyPSJ1cmwoI2ZpbHRlcjBfZGRkKSI+CjxwYXRoIGQ9Ik0yNC43MDU4IDE4QzI0LjcwNTggMTIuNDc3MiAyOS4xODMgOCAzNC43MDU4IDhINTcuMDU4N0M2Mi41ODE2IDggNjcuMDU4OCAxMi40NzcyIDY3LjA1ODggMThWNDAuMzUyOUM2Ny4wNTg4IDQ1Ljg3NTggNjIuNTgxNiA1MC4zNTI5IDU3LjA1ODggNTAuMzUyOUgzNC43MDU4QzI5LjE4MyA1MC4zNTI5IDI0LjcwNTggNDUuODc1OCAyNC43MDU4IDQwLjM1MjlWMThaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMjUuMjA1OCAxOEMyNS4yMDU4IDEyLjc1MzMgMjkuNDU5MSA4LjUgMzQuNzA1OCA4LjVINTcuMDU4N0M2Mi4zMDU0IDguNSA2Ni41NTg4IDEyLjc1MzMgNjYuNTU4OCAxOFY0MC4zNTI5QzY2LjU1ODggNDUuNTk5NiA2Mi4zMDU1IDQ5Ljg1MjkgNTcuMDU4OCA0OS44NTI5SDM0LjcwNThDMjkuNDU5MSA0OS44NTI5IDI1LjIwNTggNDUuNTk5NiAyNS4yMDU4IDQwLjM1MjlWMThaIiBzdHJva2U9IiNFQjU3NTciLz4KPC9nPgo8cGF0aCBkPSJNNDEuNzA3MiAyNC45OTIzTDUwLjA3NTYgMzMuMzYwNyIgc3Ryb2tlPSIjRUI1NzU3IiBzdHJva2Utd2lkdGg9IjQiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIvPgo8cGF0aCBkPSJNNTAuMTE3NiAyNC45OTIzTDQxLjc0OTIgMzMuMzYwNyIgc3Ryb2tlPSIjRUI1NzU3IiBzdHJva2Utd2lkdGg9IjQiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIvPgo8ZGVmcz4KPGZpbHRlciBpZD0iZmlsdGVyMF9kZGQiIHg9IjAuNzA1ODExIiB5PSIwIiB3aWR0aD0iOTAuMzUyOSIgaGVpZ2h0PSI5MC4zNTI5IiBmaWx0ZXJVbml0cz0idXNlclNwYWNlT25Vc2UiIGNvbG9yLWludGVycG9sYXRpb24tZmlsdGVycz0ic1JHQiI+CjxmZUZsb29kIGZsb29kLW9wYWNpdHk9IjAiIHJlc3VsdD0iQmFja2dyb3VuZEltYWdlRml4Ii8+CjxmZUNvbG9yTWF0cml4IGluPSJTb3VyY2VBbHBoYSIgdHlwZT0ibWF0cml4IiB2YWx1ZXM9IjAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDEyNyAwIiByZXN1bHQ9ImhhcmRBbHBoYSIvPgo8ZmVPZmZzZXQvPgo8ZmVHYXVzc2lhbkJsdXIgc3RkRGV2aWF0aW9uPSIwLjUiLz4KPGZlQ29sb3JNYXRyaXggdHlwZT0ibWF0cml4IiB2YWx1ZXM9IjAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAuMDQgMCIvPgo8ZmVCbGVuZCBtb2RlPSJub3JtYWwiIGluMj0iQmFja2dyb3VuZEltYWdlRml4IiByZXN1bHQ9ImVmZmVjdDFfZHJvcFNoYWRvdyIvPgo8ZmVDb2xvck1hdHJpeCBpbj0iU291cmNlQWxwaGEiIHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAxMjcgMCIgcmVzdWx0PSJoYXJkQWxwaGEiLz4KPGZlT2Zmc2V0IGR5PSIyIi8+CjxmZUdhdXNzaWFuQmx1ciBzdGREZXZpYXRpb249IjMiLz4KPGZlQ29sb3JNYXRyaXggdHlwZT0ibWF0cml4IiB2YWx1ZXM9IjAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAuMDQgMCIvPgo8ZmVCbGVuZCBtb2RlPSJub3JtYWwiIGluMj0iZWZmZWN0MV9kcm9wU2hhZG93IiByZXN1bHQ9ImVmZmVjdDJfZHJvcFNoYWRvdyIvPgo8ZmVDb2xvck1hdHJpeCBpbj0iU291cmNlQWxwaGEiIHR5cGU9Im1hdHJpeCIgdmFsdWVzPSIwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAxMjcgMCIgcmVzdWx0PSJoYXJkQWxwaGEiLz4KPGZlT2Zmc2V0IGR5PSIxNiIvPgo8ZmVHYXVzc2lhbkJsdXIgc3RkRGV2aWF0aW9uPSIxMiIvPgo8ZmVDb2xvck1hdHJpeCB0eXBlPSJtYXRyaXgiIHZhbHVlcz0iMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMC4wNiAwIi8+CjxmZUJsZW5kIG1vZGU9Im5vcm1hbCIgaW4yPSJlZmZlY3QyX2Ryb3BTaGFkb3ciIHJlc3VsdD0iZWZmZWN0M19kcm9wU2hhZG93Ii8+CjxmZUJsZW5kIG1vZGU9Im5vcm1hbCIgaW49IlNvdXJjZUdyYXBoaWMiIGluMj0iZWZmZWN0M19kcm9wU2hhZG93IiByZXN1bHQ9InNoYXBlIi8+CjwvZmlsdGVyPgo8L2RlZnM+Cjwvc3ZnPgo=);
  color: #eb5757;
}

.--not-provided {
  background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAzMCAzMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzAgMzA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiM3QTk1QTc7fQ0KCS5zdDF7ZmlsbDpub25lO3N0cm9rZTojRkZGRkZGO3N0cm9rZS13aWR0aDoyO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDt9DQo8L3N0eWxlPg0KPHJlY3QgaWQ9IlhNTElEXzFfIiBjbGFzcz0ic3QwIiB3aWR0aD0iMzAiIGhlaWdodD0iMzAiLz4NCjxwYXRoIGlkPSJYTUxJRF8zXyIgY2xhc3M9InN0MSIgZD0iTTEwLDEwLjhjMC40LTEuMSwxLjItMi4xLDIuMi0yLjZzMi4zLTAuOCwzLjUtMC42YzEuMiwwLjIsMi4zLDAuOCwzLjEsMS43DQoJYzAuOCwwLjksMS4yLDIsMS4yLDMuMmMwLDMuMy01LjEsNS01LjEsNSIvPg0KPHBhdGggaWQ9IlhNTElEXzJfIiBjbGFzcz0ic3QxIiBkPSJNMTQuOSwyMi41aC0wLjQiLz4NCjwvc3ZnPg0K);
  color: #7a95a7;
}

.object-side {
  width: 100%;
  height: 100%;
  border-radius: 10px;
  box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06), 0px 2px 6px rgba(0, 0, 0, 0.04),
    0px 0px 1px rgba(0, 0, 0, 0.04);
  background: #ffffff;
  position: relative;
  @media all and (max-width: 1023px) {
    border-radius: 10px 10px 0 0;
    box-shadow: none;
  }
  &__wrapper {
    height: calc(100% - 200px);
    width: 100%;
    overflow: auto;
    background: $white;
    /* box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.08), 0px 2px 6px rgba(0, 0, 0, 0.20), 0px 0px 1px rgba(0, 0, 0, 0.09); */
    border-radius: 0 0 10px 10px;
    &::-webkit-scrollbar {
      width: 8px;
    }

    &::-webkit-scrollbar-track {
      background: #efefef;
    }

    &::-webkit-scrollbar-thumb {
      background: #d1e0ea;
    }
  }
  .more-detail {
    &__wrapper {
      position: fixed;
      z-index: 10;
      left: 530px;
      top: 0;
      bottom: 0;
      width: 460px;
      border-radius: 10px;
      background: #ffffff;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
      @media all and (max-width: 1899px) {
        left: 490px;
      }
      @media all and (max-width: 1024px) {
        left: 435px;
      }
      @media all and (max-width: 960px) {
        width: 280px;
      }
      @media all and (max-width: 1023px) {
        width: auto;
        left: 0;
        top: -50px;
        right: 0;
        bottom: 50px;
        z-index: 11;
      }
    }

    &__content {
      flex: 1 1 auto;
      overflow-x: hidden;
      overflow-y: auto;
      padding: 2px 30px 34px 40px;
      @media all and (max-width: 960px) {
        padding: 2px 20px 34px 20px;
      }
      @media all and (max-width: 575px) {
        padding: 2px 15px 34px 15px;
      }

      &::-webkit-scrollbar {
        width: 8px;
      }

      &::-webkit-scrollbar-track {
        background: #efefef;
      }

      &::-webkit-scrollbar-thumb {
        background: #d1e0ea;
      }
    }

    &__close {
      position: absolute;
      top: 13px;
      right: -35px;
      cursor: pointer;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
      @media all and (max-width: 1023px) {
        box-shadow: none;
        top: 0;
        right: 0;
        z-index: 99;
      }
    }

    &__top {
      padding: 32px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 18px;
      font-family: "Montserrat";
      font-weight: 500;
      height: 68px;
      position: relative;
      border-radius: 10px 10px 0 0;
      box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);
      @media all and (max-width: 960px) {
        flex-direction: column;
        height: 130px;
        align-items: flex-start;
        padding: 20px;
      }
      @media all and (max-width: 575px) {
        height: 115px;
        padding: 15px;
        font-size: 17px;
      }
    }

    &__link {
      font-size: 16px;
      line-height: 20px;
      color: $black;
      margin: 20px 0 0;
      display: block;

      &.active,
      &:hover {
        font-weight: 700;
      }
      &:first-child {
        margin: 0;
      }
    }

    &__download {
      color: #ffffff;
      font-size: 14px;
      font-family: "Montserrat";
      padding: 13px 20px 13px 40px;
      -webkit-transition: opacity 0.4s;
      -moz-transition: opacity 0.4s;
      -ms-transition: opacity 0.4s;
      -o-transition: opacity 0.4s;
      transition: opacity 0.4s;
      background-color: #2d9cdb;
      border-radius: 10px;
      font-weight: 600;
      background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTUiIHZpZXdCb3g9IjAgMCAxNiAxNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik04LjAwMDczIDEwLjQwNThDNy42OTAyMyAxMC40MDU4IDcuNDM4MjMgMTAuMTUzOCA3LjQzODIzIDkuODQzMjVWMC44MTI1QzcuNDM4MjMgMC41MDIgNy42OTAyMyAwLjI1IDguMDAwNzMgMC4yNUM4LjMxMTIzIDAuMjUgOC41NjMyMyAwLjUwMiA4LjU2MzIzIDAuODEyNVY5Ljg0MzI1QzguNTYzMjMgMTAuMTUzOCA4LjMxMTIzIDEwLjQwNTggOC4wMDA3MyAxMC40MDU4WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik04LjAwMDc0IDEwLjQwNThDNy44NTE0OSAxMC40MDU4IDcuNzA3NDkgMTAuMzQ2NSA3LjYwMjQ5IDEwLjI0TDUuNDE1NDkgOC4wNDQ3N0M1LjE5NjQ5IDcuODI0MjcgNS4xOTcyNCA3LjQ2ODAyIDUuNDE2OTkgNy4yNDkwMkM1LjYzNzQ5IDcuMDMwMDIgNS45OTI5OSA3LjAzMDAyIDYuMjExOTkgNy4yNTA1Mkw4LjAwMDc0IDkuMDQ2NzdMOS43ODk0OSA3LjI1MDUyQzEwLjAwODUgNy4wMzAwMiAxMC4zNjQgNy4wMzAwMiAxMC41ODQ1IDcuMjQ5MDJDMTAuODA0MiA3LjQ2ODAyIDEwLjgwNSA3LjgyNDI3IDEwLjU4NiA4LjA0NDc3TDguMzk4OTkgMTAuMjRDOC4yOTM5OSAxMC4zNDY1IDguMTQ5OTkgMTAuNDA1OCA4LjAwMDc0IDEwLjQwNThaIiBmaWxsPSJ3aGl0ZSIvPgo8bWFzayBpZD0ibWFzazAiIG1hc2stdHlwZT0iYWxwaGEiIG1hc2tVbml0cz0idXNlclNwYWNlT25Vc2UiIHg9IjAiIHk9IjMiIHdpZHRoPSIxNiIgaGVpZ2h0PSIxMiI+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMC41MDAxMjIgMy43OTkzMkgxNS41VjE0LjEwNzNIMC41MDAxMjJWMy43OTkzMloiIGZpbGw9IndoaXRlIi8+CjwvbWFzaz4KPGcgbWFzaz0idXJsKCNtYXNrMCkiPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTEyLjE4MTQgMTQuMTA3M0gzLjgyNjM3QzEuOTkyNjIgMTQuMTA3MyAwLjUwMDEyMiAxMi42MTU2IDAuNTAwMTIyIDEwLjc4MTFWNy4xMTczMkMwLjUwMDEyMiA1LjI4NzMyIDEuOTg4ODcgMy43OTkzMiAzLjgxOTYyIDMuNzk5MzJINC41MjUzN0M0LjgzNTg3IDMuNzk5MzIgNS4wODc4NyA0LjA1MTMyIDUuMDg3ODcgNC4zNjE4MkM1LjA4Nzg3IDQuNjcyMzIgNC44MzU4NyA0LjkyNDMyIDQuNTI1MzcgNC45MjQzMkgzLjgxOTYyQzIuNjA5MTIgNC45MjQzMiAxLjYyNTEyIDUuOTA3NTcgMS42MjUxMiA3LjExNzMyVjEwLjc4MTFDMS42MjUxMiAxMS45OTUzIDIuNjEyMTIgMTIuOTgyMyAzLjgyNjM3IDEyLjk4MjNIMTIuMTgxNEMxMy4zOTA0IDEyLjk4MjMgMTQuMzc1MSAxMS45OTc2IDE0LjM3NTEgMTAuNzg4NlY3LjEyNTU3QzE0LjM3NTEgNS45MTEzMiAxMy4zODc0IDQuOTI0MzIgMTIuMTc0NiA0LjkyNDMySDExLjQ3NTZDMTEuMTY1MSA0LjkyNDMyIDEwLjkxMzEgNC42NzIzMiAxMC45MTMxIDQuMzYxODJDMTAuOTEzMSA0LjA1MTMyIDExLjE2NTEgMy43OTkzMiAxMS40NzU2IDMuNzk5MzJIMTIuMTc0NkMxNC4wMDg0IDMuNzk5MzIgMTUuNTAwMSA1LjI5MTgyIDE1LjUwMDEgNy4xMjU1N1YxMC43ODg2QzE1LjUwMDEgMTIuNjE4NiAxNC4wMTA2IDE0LjEwNzMgMTIuMTgxNCAxNC4xMDczWiIgZmlsbD0id2hpdGUiLz4KPC9nPgo8L3N2Zz4K);
      background-position: left 15px center;
      background-repeat: no-repeat;
      @media all and (max-width: 960px) {
        margin-top: 20px;
        width: 100%;
        text-align: center;
      }
      &:hover {
        opacity: 0.7;
      }
    }

    &__item {
      &:first-child {
        padding: 25px 0 0;
      }
      padding: 40px 0 0;
      @media all and (max-width: 768px) {
        padding: 20px 0 0;
      }

      &-title {
        font-size: 20px;
        margin: 0 0 10px;
        @media all and (max-width: 575px) {
          font-size: 18px;
        }
      }
    }

    &__line {
      padding: 10px 20px 10px 0;
      border-bottom: 1px solid rgba(123, 149, 167, 0.3);
      display: flex;
      align-items: flex-start;
      justify-content: space-between;

      &-title {
        font-size: 16px;
        line-height: 20px;
        margin: 24px 0 16px;
      }

      &-text {
        font-size: 16px;
        line-height: 20px;
        font-weight: 300;
        font-family: "SFProDisplay";
        margin-right: 15px;
        display: block;
      }

      &-status {
        width: 43px;
        font-size: 16px;
        line-height: 20px;
        padding: 0 0 0 30px;
      }

      &.yes {
        .more-detail__line-status {
          background: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE2LjY2NjggNUw3LjUwMDE2IDE0LjE2NjdMMy4zMzM1IDEwIiBzdHJva2U9IiMzREJBM0IiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+Cjwvc3ZnPgo=)
            left center no-repeat;
        }
      }

      &.no {
        .more-detail__line-status {
          background: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGxpbmUgeDE9IjQiIHkxPSIxMCIgeDI9IjE2IiB5Mj0iMTAiIHN0cm9rZT0iI0RFMTIyMCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiLz4KPC9zdmc+Cg==)
            left center no-repeat;
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
    padding: 25px;
    background: #f2994a;
    @media all and (max-width: 768px) {
      padding: 15px 15px 25px;
    }

    &__title {
      font-weight: 600;
      font-family: "Montserrat";
      font-size: 16px;
      line-height: 50px;
      padding: 0 0 0 55px;
      margin: 0 0 4px;
      background-size: 80px;
      background-repeat: no-repeat;
      background-position: -20px top;
      @media all and (max-width: 768px) {
        font-size: 16px;
        padding: 0 0 0 50px;
        margin: 0;
      }
    }

    &__list {
      display: flex;
      flex-wrap: wrap;
    }
    &__item {
      font-size: 14px;
      font-weight: 500;
      line-height: 20px;
      height: 20px;
      margin: 20px 0 0;
      width: 50%;
      height: fit-content;
      padding: 0 0 0 26px;
      background-position: left center;
      background-repeat: no-repeat;
      @media all and (max-width: 374px) {
        margin: 15px 0 0;
        background-position: left top;
      }
    }
  }

  &__button {
    font-size: 14px;
    font-weight: 600;
    font-family: "Montserrat";
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    -webkit-transition: opacity 0.3s;
    -moz-transition: opacity 0.3s;
    -ms-transition: opacity 0.3s;
    -o-transition: opacity 0.3s;
    transition: opacity 0.3s;
    background-position: center left 10px;
    background-repeat: no-repeat;
    width: 50%;
    height: 43px;
    border-radius: 10px;
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
      0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    img {
      margin-right: 10px;
      @media all and (max-width: 1366px) {
        margin-right: 5px;
      }
    }
    @media all and (max-width: 1024px) {
      padding: 14px 0 14px 10px;
    }
    @media all and (max-width: 768px) {
      font-size: 16px;
      font-weight: 500;
      font-family: SFProdisplay;
    }
    @media all and (max-width: 475px) {
      margin: 0;
      width: 100%;
    }

    &:hover {
      opacity: 0.7;
    }

    & + .object-side__button {
      margin: 0 0 0 10px;
      @media all and (max-width: 475px) {
        margin: 10px 0 0 0;
      }
    }

    &.--check {
      background-color: #2d9cdb;
    }
    &.--complaint {
      background-color: #eb5757;
    }
    &-text {
      color: $white;
      @media (max-width: 1024px) {
        width: 70%;
      }
      @media (max-width: 1023px) {
        width: fit-content;
      }
    }

    &-b {
      font-size: 0;
      display: flex;
      @media all and (max-width: 1023px) {
        margin-top: 25px;
        align-items: center;
      }
      @media all and (max-width: 475px) {
        align-items: flex-start;
        flex-direction: column;
      }
    }
  }

  &__content {
    padding: 20px 30px;
    background: $white;
    @media all and (max-width: 768px) {
      padding: 0 15px;
    }
  }
  &__close {
    width: 35px;
    height: 55px;
    position: absolute;
    background: white;
    background: url("@/assets/icons/close_main.svg") center no-repeat;

    -webkit-transition: opacity 0.3s;
    -moz-transition: opacity 0.3s;
    -ms-transition: opacity 0.3s;
    -o-transition: opacity 0.3s;
    transition: opacity 0.3s;
    @media all and (max-width: 575px) {
      height: 50px;
    }
    &-wrapper {
      position: absolute;
      border-radius: 0px 12px 12px 0px;
      right: -5px;
      top: 30px;
      width: 35px;
      height: 55px;
      background-color: $white;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.1),
        0px 2px 6px rgba(0, 0, 0, 0.1), 0px 0px 1px rgba(0, 0, 0, 0.25);
      @media all and (max-width: 575px) {
        height: 35px;
      }
    }
    &:hover {
      opacity: 0.7;
    }
  }
  @media all and (max-width: 1023px) {
    &__close-mobile {
      position: relative;
      display: block;
      margin: auto;
      width: 40px;
      height: 4px;
      background: $white;
      border-radius: 35px;
      &-wrapper {
        position: absolute;
        border-radius: 0px 12px 12px 0px;
        right: -5px;
        top: 15px;
        width: 35px;
        height: 55px;
        z-index: 9;
        top: 0;
        padding: 10px;
        right: 50%;
        width: 50%;
        height: 65px;
        transform: translateX(50%);
        &:hover {
          opacity: 0.7;
        }
      }
      &:hover {
        opacity: 0.7;
      }
    }
  }
  &__address {
    margin: 0 20px 0 0;
    color: #bebebe;
    font-weight: 500;
    font-family: "Montserrat";

    &-link {
      display: inline-block;
      color: #5b6067;
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

  &__heading {
    display: flex;
    flex-direction: column;
    font-size: 14px;
    font-weight: 500;
    font-family: "Montserrat";
    color: #bebebe;
    &-wrap {
      display: flex;
      justify-content: space-between;
      font-weight: 500;
      margin-top: 6px;
      @media all and (max-width: 1023px) {
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
      }
    }
    &-availability {
      &.object-side_availability {
        display: none;
        color: $white;
        &.--not-available {
          background-color: rgba(235, 87, 87, 0.2);
          border: 1px solid #eb5757;
        }
        &.--not-provided {
          background-color: rgba(122, 149, 167, 0.2);
          border: 1px solid #7a95a7;
        }
        &.--available {
          background-color: rgba(39, 174, 96, 0.2);
          border: 1px solid #27ae60;
        }
        &.--partially {
          background-color: rgba(242, 153, 74, 0.2);
          border: 1px solid #f2994a;
        }
        &::before {
          content: "";
          display: block;
          background-color: $white;
          width: 7px;
          height: 2px;
          border-radius: 10px;
          margin-right: 10px;
        }
        @media all and (max-width: 1023px) {
          display: flex;
          align-items: center;
          padding: 7px 9px;
          font-size: 12px;
          font-weight: 500;
          background-image: none;
          line-height: 1;
          box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
            0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
          border-radius: 6px;
          opacity: 2;
          margin: 9px 0 12px;
        }
      }
    }
  }

  &__title {
    font-size: 20px;
    font-weight: 700;
    font-family: "Montserrat";
    color: $white;
    margin: 0;
  }

  &__verification {
    color: #bebebe;
    font-weight: 500;
    @media (max-width: 768px) {
      font-size: 16px;
      font-weight: 300;
      font-family: SFProdisplay;
      color: $white;
    }
  }
  &__top {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    padding: 15px 25px;
    height: 200px;
    border-radius: 10px 10px 0 0px;
    background-size: cover !important;
    background: linear-gradient(
      360deg,
      rgba(0, 0, 0, 0.62) 5.88%,
      rgba(218, 218, 218, 0.0904167) 99.99%,
      rgba(255, 255, 255, 0) 100%
    );
    @media all and (max-width: 1023px) {
      height: fit-content;
      min-height: 148px;
      padding: 25px 15px 15px;
    }
  }

  &__tab {
    &-link {
      display: inline-block;
      justify-content: space-between;
      flex-direction: row;
      vertical-align: top;
      font-size: 16px;
      font-weight: 400;
      font-family: "SFProDisplay";
      line-height: 20px;
      padding: 0 0 16px;
      position: relative;
      color: $black;
      margin: 0 0 0 20px;
      @media all and (max-width: 1024px) {
        margin: 0 0 0 18px;
      }
      @media all and (max-width: 1023px) {
        padding: 16px 3px;
        line-height: 16px;
        .object-side__tab-num {
          display: inline-block;
          left: -3px;
          top: -10px;
          position: relative;
        }
      }
      @media all and (max-width: 475px) {
        margin: 0 0 0 7px;
        padding: 16px 0;
      }
      @media all and (max-width: 374px) {
        font-size: 13px;
      }
      &-wrapper {
        @media all and (max-width: 1023px) {
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
            background: #c9c9c9;
            position: absolute;
            display: block;
            content: "";
            height: 1px;
            left: 0;
            right: 0;
            bottom: 0;
          }
        }
      }
      &-b {
        position: relative;
        display: flex;
        justify-content: flex-start;
        font-size: 0;
        overflow-x: hidden;
        &:after {
          position: absolute;
          display: block;
          content: "";
          height: 1px;
          left: 0;
          right: 0;
          bottom: 0;
          background: #7b95a7;
          @media all and (max-width: 1023px) {
            display: none;
          }
        }
      }

      &:after {
        content: "";
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

      &.active,
      &:hover {
        color: #2d9cdb;
        @media all and (max-width: 768px) {
          color: #2d9cdb;
        }

        &:after {
          background: #2d9cdb;
        }
      }
    }

    &-content {
      display: none;
      position: relative;
      &-b {
        padding: 20px 0 0;
        @media all and (max-width: 768px) {
          padding: 20px 0;
        }
        hr {
          color: #d6dadc;
          border-top: none;
          border-width: 0.5px;
          margin: 26px 0 29px;
          @media (max-width: 1023px) {
            display: none;
          }
        }
      }

      &.active {
        display: block;
      }

      .text {
        font-size: 16px;
        font-family: "SFProDisplay";
        font-weight: 300;
        line-height: 20px;

        &__verification {
          font-size: 14px;
          line-height: 20px;
          color: #5b6067;
          @media all and (max-width: 768px) {
            font-size: 12px;
            margin: 14px 0 0;
          }

          &-b {
            margin: 20px 0 0;
            display: flex;
            padding: 8px 14px;
            justify-content: space-between;
            box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
              0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
            border-radius: 12px;
            width: fit-content;
            @media all and (max-width: 1023px) {
              display: block;
            }
          }

          &-link {
            display: inline-block;
            cursor: pointer;
            font-size: 16px;
            font-weight: 400;
            font-family: "SFProDisplay";
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
      color: #9c9c9c;
      @media (max-width: 768px) {
        padding: 0;
      }
    }
  }

  &__breadcrumb {
    display: inline-block;
    font-weight: 500;
    vertical-align: middle;
    -webkit-transition: opacity 0.3s;
    -moz-transition: opacity 0.3s;
    -ms-transition: opacity 0.3s;
    -o-transition: opacity 0.3s;
    transition: opacity 0.3s;
    color: #000000;
    &-gray {
      color: #717171;
    }
    span {
      display: none;
    }

    &:hover {
      opacity: 0.7;
    }

    &:before {
      content: "/";
      display: inline-block;
      vertical-align: middle;
      margin: 0 9px;
      color: #000000;
      @media (max-width: 475px) {
        margin: 0 3px;
      }
    }

    &:first-child {
      &:before {
        display: none;
      }
    }

    &-list {
      padding: 0 0 0 9px;
    }

    &-b {
      display: flex;
      align-items: center;
      background: $white;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
      font-size: 14px;
      font-family: "Montserrat";
      font-weight: 500;
      border-radius: 10px;
      width: fit-content;
      padding: 11px;
      @media all and (max-width: 1023px) {
        display: none;
      }
      &-mobile {
        display: none;
        @media all and (max-width: 1023px) {
          display: flex !important;
          margin-bottom: 15px;
          font-size: 13px;
        }
      }
    }

    &-icon {
      width: 20px;
      height: 20px;
      text-align: center;
      line-height: 20px;
      background-color: #2d9cdb;
      border-radius: 6px;
      background-position: center;
      background-repeat: no-repeat;
      display: flex;
      align-items: center;
      justify-content: center;
      &-img {
        width: 12px;
        height: 12px;
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
      @media all and (max-width: 768px) {
        margin: 0;
        padding: 0 0 5px;
      }
    }

    &-item {
      margin: 34px 0 0;
      @media all and (max-width: 768px) {
        margin: 23px 0 0;
      }
      padding-bottom: 20px;
      border-bottom: 1px solid #eae7e7;
      &:last-child {
        border-bottom: none;
      }

      &:first-child {
        margin: 14px 0 0;
        @media all and (max-width: 768px) {
          margin: 0;
        }
      }
    }

    &-top {
      display: flex;
      align-items: flex-start;
      margin: 0 0 9px;
      font-size: 0;
      &-photo {
        width: 41px;
        height: 41px;
        border-radius: 50%;
        margin-right: 10px;
        img {
          width: 41px;
          height: 41px;
        }
      }
      &-wrap {
        display: flex;
        flex-direction: column;
      }
    }

    &-title {
      font-size: 18px;
      line-height: 20px;
      font-weight: 500;
      font-family: "Montserrat";
      font-style: normal;
      margin-bottom: 5px;
    }

    &-date {
      color: #2d9cdb;
      font-size: 14px;
      font-weight: 500;
      font-family: "SFProDisplay";
    }

    &-text {
      font-size: 16px;
      font-family: "SFProDisplay";
      font-weight: 300;
      margin: 10px 0 0;
      line-height: 18px;
    }

    &-add {
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 43px;
      width: 100%;
      font-size: 14px;
      font-weight: 600;
      font-family: "Montserrat";
      color: $white;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
      border-radius: 10px;
      background: #2d9cdb;
      text-align: center;
      -webkit-transition: opacity 0.4s;
      -moz-transition: opacity 0.4s;
      -ms-transition: opacity 0.4s;
      -o-transition: opacity 0.4s;
      transition: opacity 0.4s;
      @media all and (max-width: 768px) {
        font-size: 14px;
      }

      &:hover {
        opacity: 0.9;
      }
    }
  }

  &__history {
    &-list {
      padding: 0;
      list-style: none;
      margin: 0;
      .object-side__review-date {
        margin-right: 10px;
      }
    }
    &-item {
      margin: 34px 0 0;
      padding-bottom: 20px;
      border-bottom: 1px solid #eae7e7;
      @media all and (max-width: 768px) {
        padding: 12px 0;
        margin: 0;
      }
      &:first-child {
        margin: 4px 0 0;
        @media all and (max-width: 768px) {
          margin: 0;
          padding: 0 0 12px;
        }
      }
    }

    &-text {
      font-size: 16px;
      font-weight: 300;
      font-family: "SFProDisplay";
      line-height: 18px;
      @media (max-width: 768px) {
        line-height: 16px;
      }
      & span {
        font-size: 16px;
        font-weight: 400;
        font-family: "SFProDisplay";
      }
    }
  }

  &__photo {
    padding: 15px 0;
    @media (max-width: 768px) {
      padding: 0 0 25px;
    }
    &-year {
      font-size: 16px;
      line-height: 20px;
      margin: 0 0 16px;
    }

    &-list {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      -ms-grid-columns: 1fr 1fr 1fr;
      grid-gap: 10px;
      &.--video {
        grid-template-columns: 1fr 1fr;
        .object-side__photo-link {
          width: calc(50% - 5px);
          margin: 0 0 10px;
          position: relative;
          &-video {
            width: 190px;
            height: 125px;
            position: relative;
            &:after {
              content: "";
              position: absolute;
              width: 50px;
              height: 50px;
              left: 50%;
              top: 50%;
              margin: -25px 0 0 -25px;
              background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjUiIGN5PSIyNSIgcj0iMjUiIGZpbGw9ImJsYWNrIiBmaWxsLW9wYWNpdHk9IjAuNSIvPgo8cGF0aCBkPSJNMTkgMTZMMzQgMjUuNUwxOSAzNVYxNloiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=")
                center no-repeat;
            }
            @media (max-width: 1024px) {
              width: 170px;
              height: 110px;
            }
            @media (max-width: 1023px) {
              width: 100%;
              height: 150px;
            }
            @media (max-width: 1023px) {
              height: 110px;
            }
          }
        }

        img {
          width: 100%;
          height: 100%;
          object-fit: cover;
        }
      }
      @media all and (max-width: 374px) {
        grid-template-columns: 1fr 1fr;
      }
    }

    &-link {
      display: block;
      width: 125px;
      height: 125px;
      @media (max-width: 1024px) {
        width: 110px;
        height: 110px;
        margin: auto;
      }
      @media (max-width: 1023px) {
        width: 200px;
        height: 200px;
      }
      @media (max-width: 650px) {
        width: 150px;
        height: 150px;
      }
      @media (max-width: 650px) {
        width: 100px;
        height: 100px;
      }
      img {
        display: block;
        height: 125px;
        width: 125px;
        border-radius: 5px;
        object-fit: cover;
        @media (max-width: 1024px) {
          width: 110px;
          height: 110px;
        }
        @media (max-width: 1023px) {
          width: 100%;
          height: 100%;
        }
      }
    }
  }
}
</style>
