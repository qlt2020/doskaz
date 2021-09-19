<template>
  <div class="add-object__content">
    <field :error="validationErrors.name">
      <div class="col">
        <label class="add-object__label" for="input_name">{{
          $t("objectAdding.objectName")
        }}</label>
      </div>
      <div class="col --long">
        <div class="input">
          <input
            id="input_name"
            v-model.trim="name"
            type="text"
            placeholder="Юбилейный"
          />
        </div>
      </div>
    </field>
    <field :error="validationErrors.otherNames">
      <div class="col">
        <label class="add-object__label">{{
          $t("objectAdding.objectOtherNames")
        }}</label>
      </div>
      <div class="col --long">
        <div class="input">
          <input
            type="text"
            placeholder="KAZKOM, КАЗКОМ"
            v-model.trim="otherNames"
          />
        </div>
      </div>
    </field>
    <field :error="validationErrors.point" class="direction-column">
      <div class="col">
        <label class="add-object__label --title">{{
          $t("objectAdding.mapPoint")
        }}</label>
      </div>
      <div class="col --long map">
        <client-only>
          <yandex-map
            @map-was-initialized="mapInitialized"
            @click="click"
            :style="{ width: '100%', height: '300px' }"
            :zoom="4"
            :coords.sync="mapCoords"
            :controls="['zoomControl']"
            class="yandex_map_style"
            :settings="{
              apiKey: 'c1050142-1c08-440e-b357-f2743155c1ec',
              lang: 'ru_RU',
              coordorder: 'latlong',
              version: '2.1',
            }"
          >
            <ymap-marker
              :coords="point"
              v-if="point"
              marker-id="point"
            ></ymap-marker>
          </yandex-map>
        </client-only>
      </div>
    </field>
    <field :error="validationErrors.address">
      <div class="col">
        <label class="add-object__label">{{
          $t("objectAdding.address")
        }}</label>
      </div>
      <div class="col --long">
        <div class="input">
          <input
            type="text"
            :placeholder="$t('objectAdding.addressPlaceholder')"
            v-model.trim="address"
          />
        </div>
      </div>
    </field>
    <div class="add-object__line --lrg">
      <div class="col">
        <label class="add-object__label">{{
          $t("objectAdding.category")
        }}</label>
      </div>
      <div class="col --long --info">
        <div class="select">
          <DropdownBlock
            :options="[
              {
                value: null,
                title: $t(`objectAdding.emptyCategoryPlaceholder`),
              },
              ...getCategoriesList,
            ]"
            v-model="selectedCategory"
          />
        </div>
      </div>
      <div
        class="add-object__info"
        :class="{ '--selected': selectedInfo === 'infoCategory' }"
      >
        <span
          class="add-object__info-icon"
          @click="toggleSelectInfo('infoCategory')"
        >
          <svg
            width="22"
            height="26"
            viewBox="0 0 22 26"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <rect y="2" width="22" height="22" rx="5" fill="#EDECEC" />
            <path
              d="M7.4834 9.71875C7.52441 8.68164 7.89062 7.83789 8.58203 7.1875C9.2793 6.53711 10.2432 6.21191 11.4736 6.21191C12.6221 6.21191 13.5566 6.51953 14.2773 7.13477C14.998 7.74414 15.3584 8.52344 15.3584 9.47266C15.3584 10.7441 14.7432 11.749 13.5127 12.4873C12.9502 12.8213 12.5664 13.1318 12.3613 13.4189C12.1562 13.7002 12.0537 14.0693 12.0537 14.5264V15.0625H10.2344L10.2256 14.3594C10.2021 13.75 10.3252 13.2314 10.5947 12.8037C10.8701 12.376 11.3125 11.9775 11.9219 11.6084C12.4609 11.2861 12.8359 10.9756 13.0469 10.6768C13.2578 10.3779 13.3633 10.0029 13.3633 9.55176C13.3633 9.04785 13.1816 8.63477 12.8184 8.3125C12.4551 7.99023 11.9746 7.8291 11.377 7.8291C10.7734 7.8291 10.2871 7.99902 9.91797 8.33887C9.54883 8.67871 9.34375 9.13867 9.30273 9.71875H7.4834ZM11.1572 19.1318C10.835 19.1318 10.5625 19.0234 10.3398 18.8066C10.1172 18.5898 10.0059 18.3203 10.0059 17.998C10.0059 17.6758 10.1172 17.4062 10.3398 17.1895C10.5625 16.9727 10.835 16.8643 11.1572 16.8643C11.4854 16.8643 11.7607 16.9727 11.9834 17.1895C12.2061 17.4062 12.3174 17.6758 12.3174 17.998C12.3174 18.3203 12.2061 18.5898 11.9834 18.8066C11.7607 19.0234 11.4854 19.1318 11.1572 19.1318Z"
              fill="#979797"
            />
          </svg>
        </span>
        <div class="add-object__info-text">
          {{ $t("objectAdding.categoryHelp1") }}<br />{{
            $t("objectAdding.categoryHelp2")
          }}
        </div>
      </div>
    </div>
    <field :error="validationErrors.categoryId">
      <div class="col">
        <label class="add-object__label">{{
          $t("objectAdding.subCategory")
        }}</label>
      </div>
      <div class="col --long --info">
        <div class="select">
          <!-- <select :disabled="!selectedCategory" v-model="categoryId">
            <option disabled :value="null">{{
              $t("objectAdding.emptySubCategoryPlaceholder")
            }}</option>
            <template v-if="selectedCategory">
              <option
                :value="category.id"
                v-for="category in selectedCategory.subCategories"
                :key="category.id"
                >{{ category.title }}
              </option>
            </template>
          </select> -->
          <DropdownBlock
            :options="[
              {
                value: null,
                title: $t(`objectAdding.emptySubCategoryPlaceholder`),
              },
              ...getSubcategoriesList,
            ]"
            v-model="categoryId"
            :active="!!selectedCategory"
          />
        </div>
      </div>
      <div
        class="add-object__info"
        :class="{ '--selected': selectedInfo === 'infoSubCategory' }"
      >
        <span
          class="add-object__info-icon"
          @click="toggleSelectInfo('infoSubCategory')"
        >
          <svg
            width="22"
            height="26"
            viewBox="0 0 22 26"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <rect y="2" width="22" height="22" rx="5" fill="#EDECEC" />
            <path
              d="M7.4834 9.71875C7.52441 8.68164 7.89062 7.83789 8.58203 7.1875C9.2793 6.53711 10.2432 6.21191 11.4736 6.21191C12.6221 6.21191 13.5566 6.51953 14.2773 7.13477C14.998 7.74414 15.3584 8.52344 15.3584 9.47266C15.3584 10.7441 14.7432 11.749 13.5127 12.4873C12.9502 12.8213 12.5664 13.1318 12.3613 13.4189C12.1562 13.7002 12.0537 14.0693 12.0537 14.5264V15.0625H10.2344L10.2256 14.3594C10.2021 13.75 10.3252 13.2314 10.5947 12.8037C10.8701 12.376 11.3125 11.9775 11.9219 11.6084C12.4609 11.2861 12.8359 10.9756 13.0469 10.6768C13.2578 10.3779 13.3633 10.0029 13.3633 9.55176C13.3633 9.04785 13.1816 8.63477 12.8184 8.3125C12.4551 7.99023 11.9746 7.8291 11.377 7.8291C10.7734 7.8291 10.2871 7.99902 9.91797 8.33887C9.54883 8.67871 9.34375 9.13867 9.30273 9.71875H7.4834ZM11.1572 19.1318C10.835 19.1318 10.5625 19.0234 10.3398 18.8066C10.1172 18.5898 10.0059 18.3203 10.0059 17.998C10.0059 17.6758 10.1172 17.4062 10.3398 17.1895C10.5625 16.9727 10.835 16.8643 11.1572 16.8643C11.4854 16.8643 11.7607 16.9727 11.9834 17.1895C12.2061 17.4062 12.3174 17.6758 12.3174 17.998C12.3174 18.3203 12.2061 18.5898 11.9834 18.8066C11.7607 19.0234 11.4854 19.1318 11.1572 19.1318Z"
              fill="#979797"
            />
          </svg>
        </span>
        <div class="add-object__info-text">
          {{ $t("objectAdding.subCategoryHelp1") }}<br />{{
            $t("objectAdding.subCategoryHelp2")
          }}
        </div>
      </div>
    </field>
    <field :error="validationErrors.description">
      <div class="col">
        <label class="add-object__label">{{
          $t("objectAdding.description")
        }}</label>
      </div>
      <div class="col --long add-object__textarea">
        <textarea
          class="textarea"
          :placeholder="$t('objects.tabTitles.description')"
          :value="description"
          @input="
            updateData({
              path: 'first.description',
              value: $event.target.value,
            })
          "
        ></textarea>
      </div>
    </field>
    <field class="flex-align-start">
      <div class="col">
        <label class="add-object__label">{{
          $t("objectAdding.videoLink")
        }}</label>
      </div>
      <div class="col --long">
        <div class="input" v-for="(photo, index) in videos" :key="index">
          <input
            type="text"
            placeholder="http://"
            :value="videos[index]"
            @input="
              updateData({
                path: `first.videos.${index}`,
                value: $event.target.value,
              })
            "
          />
        </div>
        <button
          type="button"
          class="add-link add-link_step"
          @click="videos = [...videos, '']"
        >
          {{ $t("objectAdding.addMoreVideos") }}
        </button>
      </div>
    </field>

    <field :error="validationErrors.photos" class="flex-align-start">
      <div class="col">
        <label class="add-object__label">{{
          $t("objectAdding.uploadPhotos")
        }}</label>
      </div>
      <div class="col --long --info">
        <photo-uploader
          v-model="photos"
          @is-uploading="$emit('is-photos-uploading', $event)"
        />
      </div>
      <div
        class="add-object__info"
        :class="{ '--selected': selectedInfo == 'infoPhoto' }"
      >
        <span
          class="add-object__info-icon"
          @click="toggleSelectInfo('infoPhoto')"
        >
          <svg
            width="22"
            height="26"
            viewBox="0 0 22 26"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <rect y="2" width="22" height="22" rx="5" fill="#EDECEC" />
            <path
              d="M7.4834 9.71875C7.52441 8.68164 7.89062 7.83789 8.58203 7.1875C9.2793 6.53711 10.2432 6.21191 11.4736 6.21191C12.6221 6.21191 13.5566 6.51953 14.2773 7.13477C14.998 7.74414 15.3584 8.52344 15.3584 9.47266C15.3584 10.7441 14.7432 11.749 13.5127 12.4873C12.9502 12.8213 12.5664 13.1318 12.3613 13.4189C12.1562 13.7002 12.0537 14.0693 12.0537 14.5264V15.0625H10.2344L10.2256 14.3594C10.2021 13.75 10.3252 13.2314 10.5947 12.8037C10.8701 12.376 11.3125 11.9775 11.9219 11.6084C12.4609 11.2861 12.8359 10.9756 13.0469 10.6768C13.2578 10.3779 13.3633 10.0029 13.3633 9.55176C13.3633 9.04785 13.1816 8.63477 12.8184 8.3125C12.4551 7.99023 11.9746 7.8291 11.377 7.8291C10.7734 7.8291 10.2871 7.99902 9.91797 8.33887C9.54883 8.67871 9.34375 9.13867 9.30273 9.71875H7.4834ZM11.1572 19.1318C10.835 19.1318 10.5625 19.0234 10.3398 18.8066C10.1172 18.5898 10.0059 18.3203 10.0059 17.998C10.0059 17.6758 10.1172 17.4062 10.3398 17.1895C10.5625 16.9727 10.835 16.8643 11.1572 16.8643C11.4854 16.8643 11.7607 16.9727 11.9834 17.1895C12.2061 17.4062 12.3174 17.6758 12.3174 17.998C12.3174 18.3203 12.2061 18.5898 11.9834 18.8066C11.7607 19.0234 11.4854 19.1318 11.1572 19.1318Z"
              fill="#979797"
            />
          </svg>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="40"
            height="40"
            viewBox="0 0 40 40"
            fill="none"
            class="black"
          >
            <path
              d="M20 40C31.0457 40 40 31.0457 40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40Z"
              fill="#000000"
            />
            <path
              d="M19.3871 15.4286C18.2074 15.4286 17.6175 15.7972 16.9908 16.6452L15 14.3594C15.8111 13.0323 17.47 12 19.6083 12C22.7419 12 24.5852 13.8433 24.5852 16.0922C24.5852 20.5161 20.3825 20.6636 20.6774 22.9493H17.5806C17.0645 19.1521 20.9355 19.4101 20.9355 16.8664C20.8986 16.0922 20.235 15.4286 19.3871 15.4286ZM19.2396 24.2396C20.3088 24.2396 21.0461 25.1613 21.0461 26.0461C21.0461 27.0415 20.3088 28 19.2396 28C18.023 28 17.2857 27.0783 17.2857 26.0461C17.3226 25.1982 18.023 24.2396 19.2396 24.2396Z"
              fill="#FFFFFF"
            />
          </svg>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="40"
            height="40"
            viewBox="0 0 40 40"
            fill="none"
            class="white"
          >
            <path
              d="M20 40C31.0457 40 40 31.0457 40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40Z"
              fill="#FFFFFF"
            />
            <path
              d="M19.3871 15.4286C18.2074 15.4286 17.6175 15.7972 16.9908 16.6452L15 14.3594C15.8111 13.0323 17.47 12 19.6083 12C22.7419 12 24.5852 13.8433 24.5852 16.0922C24.5852 20.5161 20.3825 20.6636 20.6774 22.9493H17.5806C17.0645 19.1521 20.9355 19.4101 20.9355 16.8664C20.8986 16.0922 20.235 15.4286 19.3871 15.4286ZM19.2396 24.2396C20.3088 24.2396 21.0461 25.1613 21.0461 26.0461C21.0461 27.0415 20.3088 28 19.2396 28C18.023 28 17.2857 27.0783 17.2857 26.0461C17.3226 25.1982 18.023 24.2396 19.2396 24.2396Z"
              fill="#000000"
            />
          </svg>
        </span>
        <div class="add-object__info-text">
          {{ $t("objectAdding.photosHelp1") }}<br />{{
            $t("objectAdding.photosHelp2")
          }}
        </div>
      </div>
    </field>
  </div>
</template>

<script>
import Field from "./Field";
import PhotoUploader from "./PhotoUploader";
import { sync, get, call } from "vuex-pathify";
import DropdownBlock from "@/components/DropdownBlock";

export default {
  name: "FirstStep",
  components: { PhotoUploader, Field, DropdownBlock },
  props: ["value"],
  data() {
    return {
      coords: null,
      selectedCategory: null,
      mapCoords: [47.74887674893552, 67.04712168264118],
      zoom: null,
      selectedInfo: false,
    };
  },
  methods: {
    mapInitialized(map) {
      this.map = map;
      this.map.setBounds(this.selectedCity.bounds);
    },
    click(e) {
      this.point = e.get("coords");
      this.mapCoords = this.point;
      if (this.map && !this.zoom) {
        this.zoom = 12;
        this.map.setCenter(this.point, 12);
      }
      ymaps.geocode(this.point).then((res) => {
        const result = res.geoObjects.get(0);
        if (result.getThoroughfare()) {
          this.address = [result.getThoroughfare(), result.getPremiseNumber()]
            .filter((item) => !!item)
            .join(", ");
        } else {
          this.address = "";
        }
      });
    },
    toggleSelectInfo(infoCat) {
      this.selectedInfo = this.selectedInfo === infoCat ? false : infoCat;
    },
    ...call("objectAdding", ["updateData"]),
  },
  watch: {
    selectedCategory() {
      this.categoryId = null;
    },
  },
  computed: {
    ...sync("objectAdding/data@first", [
      "name",
      "address",
      "point",
      "categoryId",
      "videos",
      "photos",
      "otherNames",
      "description",
    ]),
    ...get("objectAdding", ["categories"]),
    selectedCity: get("cities/selectedCity"),
    errors: get("objectAdding/validationErrors"),
    validationErrors() {
      return this.errors.first || {};
    },
    getCategoriesList() {
      var categoriesList = this.categories.map((c) => {
        return {
          value: c,
          title: c.title,
        };
      });
      return categoriesList;
    },
    getSubcategoriesList() {
      if (this.selectedCategory) {
        var subcategoriesList = this.selectedCategory.subCategories.map((c) => {
          return {
            value: c.id,
            title: c.title,
            subCategories: c.subCategories,
            id: c.id,
          };
        });
        return subcategoriesList;
      } else return [];
    },
  },
};
</script>

<style lang="scss">
.add-object__form {
  .input:not(:first-of-type) {
    margin-top: 20px;
  }
}

ymaps {
  border-radius: 10px;
}

.select:after {
  display: none;
}
</style>
