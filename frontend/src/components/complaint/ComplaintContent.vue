<template>
  <div class="container">
    <loading :is-full-page="true" :active="isLoading" />
    <div>
      <div class="complaint__item">
        <h4 class="title --md">{{ $t("complaint.personalInfoTitle") }}</h4>
        <div class="complaint__line complaint__row">
          <div
            class="complaint__col required"
            :class="{ error: violations['complainant.lastName'] }"
          >
            <label for="11" class="label">{{ $t("complaint.lastName") }}</label>
            <div class="input">
              <input
                id="11"
                type="text"
                v-model="complaint.complainant.lastName"
                @input="checValue('lastName')"
              />
            </div>
            <span class="violations_error" v-if="focus.lastName">
              {{ $t("complaint.validation.emptyField") }}
            </span>
          </div>
          <div
            class="complaint__col required"
            :class="{ error: violations['complainant.firstName'] }"
          >
            <label for="12" class="label">{{
              $t("complaint.firstName")
            }}</label>
            <div class="input">
              <input
                id="12"
                type="text"
                v-model="complaint.complainant.firstName"
                @input="checValue('firstName')"
              />
            </div>
            <span class="violations_error" v-if="focus.firstName">{{
              $t("complaint.validation.emptyField")
            }}</span>
          </div>
        </div>
        <div class="complaint__line complaint__row">
          <div
            class="complaint__col required"
            :class="{ error: violations['complainant.middleName'] }"
          >
            <label for="21" class="label">{{
              $t("complaint.middleName")
            }}</label>
            <div class="input">
              <input
                id="21"
                type="text"
                v-model="complaint.complainant.middleName"
                @input="checValue('middleName')"
              />
            </div>
            <span class="violations_error" v-if="focus.middleName">{{
              $t("complaint.validation.emptyField")
            }}</span>
          </div>
          <div
            class="complaint__col required"
            :class="{ error: violations['complainant.iin'] }"
          >
            <label for="22" class="label">{{ $t("complaint.iin") }}</label>
            <div class="input">
              <input
                id="22"
                type="text"
                v-model="complaint.complainant.iin"
                @input="checValue('iin')"
              />
            </div>
            <span class="violations_error" v-if="focus.iin">{{
              $t("complaint.validation.iin")
            }}</span>
          </div>
        </div>
        <div class="complaint__line complaint__row">
          <div
            class="complaint__col required"
            :class="{ error: violations['complainant.user_cityId'] }"
          >
            <label for="u21" class="label">{{ $t("complaint.city") }}</label>
            <div id="u21" class="select">
              <!-- <select id="u21" v-model="complaint.complainant.cityId">
                <option v-for="city in cities" :value="city.id" :key="city.id"
                  >{{ city.name }}
                </option>
              </select> -->
              <DropdownBlock :options="citiesForSelect" v-model="complaint.complainant.cityId" />
            </div>
            <span class="violations_error" v-if="user_focus.cityId">{{
              $t("complaint.validation.emptyField")
            }}</span>
          </div>
          <div
            class="complaint__col required"
            :class="{ error: violations['complainant.street'] }"
          >
            <label for="u22" class="label">{{ $t("complaint.street") }}</label>
            <div class="input">
              <input
                type="text"
                id="u22"
                v-model="complaint.complainant.street"
                @input="checValue('street', 'complainant', 'user')"
              />
            </div>
            <span class="violations_error" v-if="user_focus.street">{{
              $t("complaint.validation.emptyField")
            }}</span>
          </div>
        </div>
        <div class="complaint__line complaint__row">
          <div class="complaint__col">
            <div
              class="complaint__line"
              :class="{ '--sm --mob': isMobile, complaint__row: !isMobile }"
            >
              <div
                class="required"
                :class="{
                  error: violations['complainant.building'],
                  'complaint__line --sm complaint__row': isMobile,
                  complaint__col: !isMobile,
                }"
              >
                <label for="31" class="label">{{
                  $t("complaint.building")
                }}</label>
                <div class="input">
                  <input
                    id="31"
                    type="text"
                    v-model="complaint.complainant.building"
                    @input="checValue('building')"
                  />
                </div>
                <span class="violations_error" v-if="focus.building">{{
                  $t("complaint.validation.emptyField")
                }}</span>
              </div>
              <div
                :class="{
                  error: violations['complainant.apartment'],
                  'complaint__line --sm complaint__row': isMobile,
                  complaint__col: !isMobile,
                }"
              >
                <label for="312" class="label">{{
                  $t("complaint.apartment")
                }}</label>
                <div class="input">
                  <input
                    id="312"
                    type="text"
                    v-model="complaint.complainant.apartment"
                    @input="checValue('apartment')"
                  />
                </div>
                <span class="violations_error" v-if="focus.apartment">{{
                  $t("complaint.validation.emptyField")
                }}</span>
              </div>
            </div>
          </div>
          <div
            class="complaint__col required"
            :class="{ error: violations['complainant.phone'] }"
          >
            <label for="32" class="label">{{ $t("complaint.phone") }}</label>
            <div class="input">
              <masked-input
                mask="\+\7(111)111-11-11"
                id="32"
                type="text"
                v-model="complaint.complainant.phone"
                @input="checValue('phone')"
              />
            </div>
            <span class="violations_error" v-if="focus.phone">{{
              violations["complainant.phone"]
            }}</span>
          </div>
        </div>
        <div class="complaint__line complaint__row">
          <div
            class="complaint__col --lg-2 required"
            :class="{ error: violations['authorityId'] }"
          >
            <label for="41" class="label">{{
              $t("complaint.authority")
            }}</label>
            <div class="select">
              <!-- <select v-model="complaint.authorityId" id="41">
                <option
                  v-for="authority in authorities"
                  :key="authority.id"
                  :value="authority.id"
                  >{{ authority.name }}
                </option>
              </select> -->
              <DropdownBlock
                :options="authoritiesForSelect"
                v-model="complaint.authorityId"
              />
            </div>
          </div>
          <div class="complaint__col"></div>
        </div>
        <!--                <div class="complaint__line complaint__row">-->
        <!--                    <div class="checkbox">-->
        <!--                        <input-->
        <!--                                type="checkbox"-->
        <!--                                checked-->
        <!--                                id="r1"-->
        <!--                                v-model="complaint.rememberPersonalData"-->
        <!--                        />-->
        <!--                        <label for="r1">{{ $t('complaint.rememberPersonalData') }}</label>-->
        <!--                    </div>-->
        <!--                </div>-->
      </div>
      <div class="complaint__item">
        <h4 class="title --md">{{ $t("complaint.complaintBlockTitle") }}</h4>
        <div
          class="complaint__line --sm complaint__row required"
          :class="{ error: violations['content.type'] }"
        >
          <div class="complaint__col --text --vertical">
            <label for="s2" class="label --vertical">{{
              $t("complaint.type")
            }}</label>
          </div>
          <div class="complaint__col --lg --vertical">
            <div class="select">
              <!-- <select id="s2" v-model="complaint.content.type">
                <option
                  v-for="type in types"
                  :value="type.type"
                  :key="type.type"
                  >{{ type.name }}
                </option>
              </select> -->
              <DropdownBlock
                :options="typesForSelect"
                v-model="complaint.content.type"
              />
            </div>
          </div>
        </div>
        <div
          class="complaint__line --sm complaint__row required"
          :class="{ error: violations['content.visitedAt'] }"
        >
          <div class="complaint__col --text --vertical">
            <label class="label --vertical">{{
              $t("complaint.visitDate")
            }}</label>
          </div>
          <div class="complaint__col --sm --vertical">
            <div class="input --date">
              <vuejs-date-picker
                id="x3"
                :format="format"
                :disabledDates="disabledDates"
                :language="locale"
                v-model="complaint.content.visitedAt"
                @change="checValue('visitedAt')"
              />
            </div>
            <span class="violations_error" v-if="focus.visitedAt">{{
              $t("complaint.validation.emptyField")
            }}</span>
          </div>
        </div>
        <div
          class="complaint__line --sm complaint__row required"
          :class="{ error: violations['content.objectName'] }"
        >
          <div class="complaint__col --text --vertical">
            <label for="i2" class="label --vertical">{{
              $t("complaint.objectName")
            }}</label>
          </div>
          <div class="complaint__col --lg --vertical">
            <div class="input">
              <input
                id="i2"
                type="text"
                v-model="complaint.content.objectName"
                @input="checValue('objectName', 'content')"
              />
            </div>
            <span class="violations_error" v-if="focus.objectName">{{
              $t("complaint.validation.emptyField")
            }}</span>
          </div>
        </div>
        <div
          class="complaint__line --sm complaint__row required"
          :class="{ error: violations['content.cityId'] }"
        >
          <div class="complaint__col --text --vertical">
            <label for="s3" class="label --vertical">{{
              $t("complaint.city")
            }}</label>
          </div>
          <div class="complaint__col --lg --vertical">
            <!-- <div class="select">
                    <select id="s3" v-model="complaint.content.cityId">
                        <option v-for="city in cities" :value="city.id" :key="city.id"
                        >{{ city.name }}
                        </option>
                    </select>
                </div> -->
            <DropdownBlock
              :options="citiesForSelect"
              v-model="complaint.content.cityId"
            />
          </div>
        </div>
        <div
          class="complaint__line --sm complaint__row required"
          :class="{ error: violations['content.street'] }"
        >
          <div class="complaint__col --text --vertical">
            <label for="i3" class="label --vertical">{{
              $t("complaint.street")
            }}</label>
          </div>
          <div class="complaint__col --lg --vertical">
            <div class="input">
              <input
                type="text"
                id="i3"
                v-model="complaint.content.street"
                @input="checValue('street', 'content')"
              />
            </div>
            <span class="violations_error" v-if="focus.street">{{
              $t("complaint.validation.emptyField")
            }}</span>
          </div>
        </div>
        <div class="complaint__line --sm --mob">
          <div
            class="complaint__line --sm complaint__row required"
            :class="{ error: violations['content.building'] }"
          >
            <div class="complaint__col --text --vertical">
              <label for="i4" class="label --vertical">{{
                $t("complaint.objectBuilding")
              }}</label>
            </div>
            <div class="complaint__col --sm --vertical">
              <div class="input">
                <input
                  type="text"
                  id="i4"
                  v-model="complaint.content.building"
                  @input="checValue('building', 'content')"
                />
              </div>
              <span class="violations_error" v-if="focus.building">{{
                $t("complaint.validation.emptyField")
              }}</span>
            </div>
          </div>
          <div class="complaint__line --sm complaint__row">
            <div class="complaint__col --text --vertical">
              <label for="i5" class="label --vertical">{{
                $t("complaint.office")
              }}</label>
            </div>
            <div class="complaint__col --sm --vertical">
              <div class="input">
                <input
                  type="text"
                  id="i5"
                  v-model="complaint.content.office"
                  @input="checValue('office', 'content')"
                />
              </div>
              <span class="violations_error" v-if="focus.office">{{
                $t("complaint.validation.emptyField")
              }}</span>
            </div>
          </div>
        </div>
        <div
          class="complaint__line --sm complaint__row required"
          :class="{ error: violations['content.visitPurpose'] }"
        >
          <div class="complaint__col --text --vertical">
            <label for="i6" class="label --vertical">{{
              $t("complaint.visitPurpose")
            }}</label>
          </div>
          <div class="complaint__col --lg --vertical">
            <div class="input">
              <input
                type="text"
                id="i6"
                v-model="complaint.content.visitPurpose"
                @input="checValue('visitPurpose', 'content')"
              />
            </div>
            <span class="violations_error" v-if="focus.visitPurpose">{{
              $t("complaint.validation.emptyField")
            }}</span>
          </div>
        </div>
        <div
          class="complaint__accordion"
          v-if="complaint.content.type === 'complaint2'"
        >
          <h3 class="title --small">{{ $t("complaint.attributes") }}</h3>
          <div class="complaint__accordion-list">
            <div
              class="complaint__accordion-item"
              v-for="field in fields"
              :key="field.key"
            >
              <div class="complaint__accordion-item__row">
                <div
                  class="complaint__accordion-head complaint__col --text"
                  :class="{ isActive: openedGroups.includes(field.key) }"
                  v-on:click="toggleGroup(field.key)"
                >
                  {{ $t(`objects.zone.${field.key}`) }}
                </div>
                <div
                  class="complaint__accordion-content complaint__col --lg --checklist"
                >
                  <div
                    class="checkbox complaint__line --sm"
                    v-for="option in field.options"
                    :key="option.key"
                  >
                    <input
                      type="checkbox"
                      :id="`option-${field.key}-${option.key}`"
                      v-model="complaintType2Attributes[option.key]"
                    />
                    <label :for="`option-${field.key}-${option.key}`">
                      {{ $t(`complaint.attribute.${option.key}`) }}
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div
          class="complaint__line"
          v-if="complaint.content.type === 'complaint2'"
        >
          <div class="complaint__col --text">
            <label
              for="t1"
              class="label"
              style="font-size: 20px; color: #0B132A"
              >{{ $t("complaint.attributesOther") }}</label
            >
          </div>
          <div
            class="complaint__col"
            style="padding: unset"
            :class="{ 'error required': violations['content.comment'] }"
          >
            <textarea
              :placeholder="$t('complaint.commentPlaceholder')"
              class="textarea"
              id="t1"
              v-model="complaint.content.comment"
            />
            <span
              class="violations_error"
              v-if="violations['content.comment']"
              >{{ violations["content.comment"] }}</span
            >
          </div>
        </div>
        <div
          class="complaint__line complaint__row"
          v-if="complaint.content.type === 'complaint2'"
        >
          <div class="checkbox">
            <input
              type="checkbox"
              id="d1"
              v-model="complaint.content.threatToLife"
            />
            <label for="d1">{{ $t("complaint.hasThreatToLife") }}</label>
          </div>
        </div>
      </div>
      <div class="complaint__item">
        <div class="complaint__line complaint__row">
          <div class="complaint__col --text --vertical">
            <label class="label">{{ $t("complaint.videoLink") }}</label>
            <!--                        <span class="label-text">{{ $t('complaint.videoLinkLabel') }}</span>-->
          </div>
          <div class="complaint__col --lg --vertical">
            <div
              class="complaint__line --sm"
              v-for="(input, index) in complaint.content.videos"
              v-bind:key="index"
            >
              <div class="input">
                <input
                  type="text"
                  :id="`video${index}`"
                  v-model="complaint.content.videos[index]"
                  placeholder="http://"
                />
              </div>
            </div>

            <button
              type="button"
              @click.prevent="addVideoLink"
              class="add-link"
            >
              {{ $t("complaint.addMoreVideos") }}
            </button>
          </div>
        </div>
        <div class="complaint__line complaint__row" id="file_required_class">
          <div class="complaint__col --text --vertical">
            <label class="label">{{ $t("complaint.uploadPhoto") }}</label>
          </div>
          <div class="complaint__col --lg --vertical">
            <photo-uploader
              v-model="photos"
              @is-uploading="$emit('is-photos-uploading', $event)"
            />
            <div class="violations_error" style="text-align: end;">
              {{ violations["content.photos"] }}
            </div>
            <div class="complaint__line">
              <button class="button" @click="submit">
                {{ $t("complaint.submit") }}
              </button>
              <p class="complaint__hide-text" v-if="hasViolations">
                {{ $t("complaint.validation.fillAllRequiredFields") }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import get from "lodash/get";
import transform from "lodash/transform";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import ru from "vuejs-datepicker/dist/locale/translations/ru";
import complaintTypes from "~/complaintTypes";
import PhotoUploader from "@/components/object_add/PhotoUploader";
import DropdownBlock from "@/components/DropdownBlock";

const fields = [
  {
    key: "parking",
    title: "Парковка",
    options: [
      {
        key: "e7dc7624-c80c-4ae8-b7bd-2c69f35e06e3",
        label: "Отсутствие специального парковочного места",
      },
      {
        key: "8ab4229b-c978-4493-b4c4-a346d57ec03b",
        label: "Специальное парковочное место находится далеко от входа",
      },
      {
        key: "7752cb1a-365f-4d99-b536-01e3eeddeb3f",
        label:
          "Отсутствие на асфальте специальной разметки со знаком «Инвалид»",
      },
      {
        key: "a5a91cab-807e-4366-b9ec-420fca40a849",
        label:
          "Отсутствие специального знака «Инвалид» рядом с парковочным местом»",
      },
      {
        key: "052d6769-dd4f-49eb-a759-349596733089",
        label: "Отсутствие бордюрного пандуса",
      },
      {
        key: "cbcba0f9-f5f9-4ee5-95fd-db9f857efdc8",
        label:
          "Наличие высокого бордюра в месте съезда с парковки на тротуар/ бордюрного пандуса",
      },
    ],
  },
  {
    key: "entrance",
    title: "Входная группа",
    options: [
      {
        key: "e91a378c-eaaf-45d8-aaf0-711def5e395b",
        label: "Крутой уклон пандуса",
      },
      { key: "154ed3ee-ed9c-45b9-a93e-f1d66151c262", label: "Узкий пандус" },
      {
        key: "202e4587-b204-4148-a478-a403011da336",
        label: "Скользкое покрытие пандуса",
      },
      {
        key: "7946cc13-d816-4e1e-b095-21a7d4c17d41",
        label:
          "Вместо пандуса/подъемника установлен швеллер/ колейная аппарель",
      },
      {
        key: "9b6eea82-3aab-43f6-a5c8-f9537e1da5fd",
        label: "Отсутствие поручней у пандуса с 2-х сторон",
      },
      {
        key: "347a8414-ce3d-4869-933b-73eefbf44dc4",
        label: "Отсутствие бортиков у пандуса",
      },
      {
        key: "b7985e98-a703-41c7-8cb8-2018f9297228",
        label: "Подъемник в неисправном состоянии",
      },
      {
        key: "0e4218b0-0e96-4ea6-816e-cc87c528fac2",
        label:
          "Отсутствие тактильной полосы на нижней и верхней ступенях лестницы",
      },
      {
        key: "6c675929-1b9f-4b16-83c7-5959f6933da6",
        label: "Узкая разворотная площадка (начало, середина, конец пандуса)",
      },
      {
        key: "65661b0c-04dd-47d0-b1d0-7e8410268b8f",
        label: "Узкая площадка перед входной дверью",
      },
      {
        key: "8b024e9b-8363-401d-8284-284c23c94df5",
        label: "Узкая входная дверь",
      },
      {
        key: "5fdab5c7-2142-4805-bd81-290f43b84126",
        label: "Наличие высокого порога у входной двери",
      },
      {
        key: "fb9cb374-1148-4d8e-8fc9-71c8122fb1af",
        label: "Входная дверь без фиксации",
      },
      {
        key: "135ef242-5e6d-47c7-89d3-9c74ab2bd6d0",
        label: "Отсутствие контрастной маркировки на входной стеклянной двери",
      },
      { key: "68cbf35c-3cc4-4f40-93c7-12754fe93e65", label: "Узкий тамбур" },
      {
        key: "a3847f00-f6c1-4db7-9c3f-6419512de951",
        label: "Наличие ступени (-ней) в тамбуре",
      },
    ],
  },
  {
    key: "movement",
    title: "Пути движения по объекту",
    options: [
      {
        key: "a815cdab-07a0-4294-9453-0a2265f8c2d8",
        label: "Отсутствие лифта/скаломобиля",
      },
      { key: "e2cbadbd-cd70-4015-a1be-838beab71c39", label: "Узкий лифт" },
      {
        key: "f2d6378e-79c5-49da-abe8-6f143ad7e9c9",
        label: "Отсутствие внутренних пандусов/подъемников",
      },
      {
        key: "74e8697d-6d85-409d-8867-c3bfef30370b",
        label: "Крутой уклон внутреннего пандуса",
      },
      {
        key: "f0808216-223e-413a-bb05-aa52d9e9581d",
        label: "Узкий внутренний пандус",
      },
      {
        key: "78110447-d877-412d-abb3-786a6b3a7bbb",
        label: "Скользкое покрытие внутреннего пандуса",
      },
      {
        key: "bb43be62-8457-4ab9-be12-27b0d1e6e092",
        label:
          "Вместо внутреннего пандуса/подъемника установлен швеллер/ колейная аппарель",
      },
      {
        key: "b57ca558-dfc9-4e17-b39b-cb289aa9822c",
        label: "Отсутствие поручней у пандуса с 2-х сторон",
      },
      {
        key: "dcf1a252-d145-42a8-bae7-6749cacd323a",
        label: "Отсутствие бортиков у пандуса",
      },
      {
        key: "3dbbc686-7340-46fb-8b59-e40ee6462b67",
        label: "Подъемник в неисправном состоянии",
      },
      {
        key: "84eb75f9-0ea3-469e-adee-931a6ae00028",
        label: "Узкий или заставленный мебелью коридор",
      },
      {
        key: "b8b216e8-3691-4560-b468-2da1fbdb3330",
        label: "Скользкое покрытие пола",
      },
      {
        key: "98a7430e-9f5c-4c7d-8346-08aee38f6539",
        label: "Отсутствие тактильных дорожек",
      },
      {
        key: "7ed5fdbf-98c8-427d-8ff4-6fd2f3c57a56",
        label: "Узкая ширина дверных проемов",
      },
      {
        key: "fb558668-03f4-44ff-a15f-e08c7eb70e34",
        label: "Высокие пороги в дверных проемах",
      },
    ],
  },
  {
    key: "service",
    title: "Зона оказания услуг",
    options: [
      {
        key: "d45aa08a-f924-4eeb-8319-6a836842daf2",
        label: "Слишком низкая/высокая рабочая поверхности стола, стойки",
      },
      {
        key: "a642c676-a789-4ce7-ba17-3d9ffb5332e8",
        label: "Отсутствие свободного пространства около мебели",
      },
      {
        key: "ec7393f7-272a-42ed-b0e0-81dc56bfdc23",
        label: "Отсутствие доступа к кассовым аппаратам, банкоматам и т.д.",
      },
      {
        key: "56590b0a-d6b2-4117-9b4a-22220b441b45",
        label:
          "Отсутствие службы сопровождения (только для вокзалов, аэропортов, поликлиник)",
      },
      {
        key: "a38aeb4f-1c3a-48db-b9e7-9afe24077e3f",
        label:
          "Отсутствие специальных мест в концертных и спортивных залах, на смотровых площадках",
      },
    ],
  },
  {
    key: "toilet",
    title: "Туалет",
    options: [
      {
        key: "c1bdc1fd-8be2-4d14-98c0-dd83959c86fb",
        label: "Отсутствие специально оборудованного туалета",
      },
      {
        key: "5ec0ae09-2aa0-4caf-aa2b-dcdd60ee24bd",
        label: "Отсутствие поручней на внутренней стороне двери",
      },
      {
        key: "4716729e-1d01-46f6-b59c-8bf4586a20ad",
        label: "Наличие высокого порога у двери",
      },
      {
        key: "a3f69839-36a2-40a6-bb46-54898910ab69",
        label: "Узкие размеры туалетной кабины",
      },
      {
        key: "4e570daa-d80e-49cf-bc4f-60239c7f440b",
        label: "Отсутствие свободного пространства около раковины",
      },
      {
        key: "7cb29da0-ea16-468e-94d4-c681dcde194d",
        label: "Отсутствие поручней около унитаза",
      },
      {
        key: "1750cc2c-9810-4ab7-92d0-59438bd30e87",
        label: "Отсутствие свободного пространства рядом с унитазом",
      },
      {
        key: "fb55c2c6-2369-4b0f-8658-2f5d905f275e",
        label: "Отсутствие крючков",
      },
      {
        key: "11c27055-fc69-4e6f-a873-7e29fc5fff8c",
        label: "Отсутствие кнопки экстренного вызова персонала",
      },
    ],
  },
  {
    key: "navigation",
    title: "Навигация",
    options: [
      {
        key: "81bc70ac-5f34-40c1-8ae3-4f9ee740aab4",
        label: "Отсутствие электронных табло",
      },
      {
        key: "4cd729da-ac47-4ae5-8014-a4105bcdc07e",
        label:
          "Отсутствие указателей, в том числе тактильных и/или с использованием шрифта Брайля",
      },
      {
        key: "b117f3d1-e42f-4211-849b-66c9a50d6e2b",
        label:
          "Отсутствие режима работы, расписания, номеров кабинетов и т.д., в том числе тактильных и/или с использованием шрифта Брайля",
      },
      {
        key: "1adac3a8-ddae-4550-bbbd-78800c90b2b8",
        label: "Отсутствие путей экстренной эвакуации",
      },
      {
        key: "743e4ca9-4105-49d4-b23b-36eb58e6ae07",
        label: "Отсутствие номеров этажей ",
      },
      {
        key: "8daf9379-b0e4-4585-a06d-a8b1464a9654",
        label: "Отсутствие тактильных путей движения",
      },
      {
        key: "6621b726-df16-4339-89d5-9a02675e2d9c",
        label: "Отсутствие международных символов доступности",
      },
      {
        key: "9a64ac67-1180-48fb-9fb9-9f6b98ebaa86",
        label: "Отсутствие звукового сопровождения визуальной информации",
      },
      {
        key: "e480a9ae-3b5d-4a61-ac08-40201f60e06f",
        label: "Отсутствие мнемосхемы",
      },
    ],
  },
];

export default {
  name: "ComplaintContent",
  props: ["initialData", "authorities", "cities"],
  data() {
    return {
      complaintType2Attributes: {},
      openedGroups: [fields[0].key],
      user_focus: {
        street: false,
        building: false,
        cityId: 0,
      },
      focus: {
        lastName: false,
        firstName: false,
        middleName: false,
        iin: false,
        address: false,
        phone: false,
        visitedAt: false,
        objectName: false,
        street: false,
        building: false,
        office: false,
        visitPurpose: false,
      },
      isLoading: false,
      photosRaw: [{ preview: null }],
      photosPreview: [],
      complaint: {
        complainant: {
          firstName: "",
          lastName: "",
          middleName: "",
          iin: "",
          cityId: 1,
          street: "",
          building: "",
          address: "",
          phone: "",
        },
        authorityId: null,
        rememberPersonalData: true,
        content: {
          cityId: 1,
          type: complaintTypes[0],
          objectName: "",
          videos: [""],
          photos: [""],
          options: [],
        },
      },
      violations: {},
      form: {
        name: "",
        surname: "",
        patronymic: "",
        iin: "",
        address: "",
        phone: "",
        agency: "",
        type: "",
        date: "",
        objectName: "",
        city: "",
        street: "",
        houseNumber: "",
        purpose: "",
      },
      imageFile: "",
      format: "dd.MM.yyyy",
      disabledDates: {
        from: new Date(Date.now() - 8640000),
      },
      isOpen: true,
      locale: ru,
      inputs: [],
      photos: [
        "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxNCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEzIDFMMSAxMyIgc3Ryb2tlPSIjN0I5NUE3IiBzdHJva2Utd2lkdGg9IjIiLz4KPHBhdGggZD0iTTEgMUwxMyAxMyIgc3Ryb2tlPSIjN0I5NUE3IiBzdHJva2Utd2lkdGg9IjIiLz4KPC9zdmc+Cg==",
      ],
      url: null,
    };
  },
  components: { PhotoUploader, Loading, DropdownBlock },
  mounted() {
    this.cities.unshift({
      id: 1,
      name: this.$t("index.selectCity"),
    });
    this.complaint = this.initialData;
    this.complaint.complainant.cityId = 1;
  },
  methods: {
    async checValue(param, content, user) {
      var url = {};
      var validate_param = {};
      let refreshInterval = await setInterval(() => {
        if (content == "content") {
          url = {
            content: {
              type: this.complaint.content.type,
              [param]: this.complaint.content[param],
            },
          };
        } else {
          url = {
            complainant: {
              type: this.complaint.complainant.type,
              [param]: this.complaint.complainant[param],
            },
          };
          validate_param[param] = this.complaint.complainant[param];
        }
        this.$axios.post("/api/complaints/validate", url).catch((error) => {
          if (content == "content") {
            if (
              error.response.data.errors.violations.find(
                (item) => item.propertyPath == `content.${param}`
              ) !== undefined
            ) {
              this.focus[param] = true;
            } else {
              this.focus[param] = false;
            }
          } else {
            if (
              error.response.data.errors.violations.find(
                (item) => item.propertyPath == `complainant.${param}`
              ) !== undefined
            ) {
              if (user === "user") {
                this.user_focus[param] = true;
              } else {
                this.focus[param] = true;
              }
            } else {
              if (user === "user") {
                this.user_focus[param] = false;
              } else {
                this.focus[param] = false;
              }
            }
          }
        });
        clearInterval(refreshInterval);
      }, 1000);
    },
    async submit() {
      this.isLoading = true;
      this.violations = {};
      try {
        this.complaint.content.options = transform(
          this.complaintType2Attributes,
          (r, v, k) => {
            if (v) {
              r.push(k);
            }
          },
          []
        );
        this.complaint.content.photos = this.photos;
        const { data } = await this.$axios.post(
          "/api/complaints",
          this.complaint
        );
        return this.redirect(data.id, this.complaint.objectId);
      } catch (e) {
        this.violations = get(e, "response.data.errors", []).reduce(
          (violations, violation) => {
            console.log(violations);
            console.log(violation);
            if (violations["complainant.lastName"]) {
              document.getElementById("11").focus();
              this.focus.lastName = true;
            }
            if (violations["complainant.firstName"]) {
              document.getElementById("12").focus();
            }
            if (violations["complainant.middleName"]) {
              document.getElementById("21").focus();
            }
            if (violations["complainant.iin"]) {
              document.getElementById("22").focus();
            }
            if (violations["complainant.address"]) {
              document.getElementById("31").focus();
            }
            if (violations["complainant.phone"]) {
              document.getElementById("32").focus();
            }
            if (violations["complainant.authorityId"]) {
              document.getElementById("41").focus();
            }
            if (violations["complainant.complaint.rememberPersonalData"]) {
              document.getElementById("f1").focus();
            }
            if (violations["content.type"]) {
              document.getElementById("s2").focus();
            }
            if (violations["content.visitedAt"]) {
              document.getElementById("x3").focus();
            }
            if (violations["content.objectName"]) {
              document.getElementById("i2").focus();
            }
            if (violations["content.street"]) {
              document.getElementById("i3").focus();
            }
            if (violations["content.building"]) {
              document.getElementById("i4").focus();
            }
            if (violations["content.office"]) {
              document.getElementById("i5").focus();
            }
            if (violations["content.visitPurpose"]) {
              document.getElementById("i6").focus();
            }
            violations[violation.property] = violation.message;
            return violations;
          },
          {}
        );
        console.log(this.violations);
      } finally {
        this.isLoading = false;
      }
    },
    toggleGroup: function(key) {
      if (this.openedGroups.includes(key)) {
        return this.openedGroups.splice(this.openedGroups.indexOf(key), 1);
      }
      this.openedGroups.push(key);
    },
    addVideoLink() {
      this.complaint.content.videos.push("");
    },
    addPhotoInput() {
      this.photosRaw.push({ preview: null });
    },
    imageFileChanged(event, index) {
      var p = this.photosRaw[index];

      var input = event.target;
      var file = input.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = (e) => {
          p.preview = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        p.file = file;
      }
    },
    redirect(complaintId, objectId) {
      this.$cookies.set(
        "complaintPostSubmitMessage",
        {
          complaintId,
        },
        {
          maxAge: 60,
        }
      );

      return this.$router.push(
        this.localePath(
          objectId
            ? {
                name: "objects-id",
                params: { id: objectId },
              }
            : { name: "index" }
        )
      );
    },
  },
  computed: {
    typesForSelect() {
      var typesList = this.types.map((c) => {
        return {
          value: c.type,
          title: c.name,
        };
      });
      return typesList;
    },
    authoritiesForSelect() {
      var authoritiesList = this.authorities.map((c) => {
        return {
          value: c.id,
          title: c.name,
        };
      });
      return authoritiesList;
    },
    citiesForSelect() {
      var citiesList = this.cities.map((c) => {
        return {
          value: c.id,
          title: c.name,
        };
      });
      return citiesList;
    },
    types() {
      return complaintTypes.map((type) => ({
        type,
        name: this.$t(`complaintTypes.${type}`),
      }));
    },
    hasViolations() {
      return Object.values(this.violations).length > 0;
    },
    fields() {
      return fields;
    },
    isMobile() {
      let res = false;
      (function(a) {
        if (
          /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
            a
          ) ||
          /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
            a.substr(0, 4)
          )
        )
          res = true;
      })(navigator.userAgent || navigator.vendor || window.opera);
      return res;
    },
  },
};
</script>

<style lang="scss" scoped>
select {
  background-color: white;
}

.input {
  margin-bottom: 10px;
}

.violations_error {
  color: red;

  @media all and (max-width: 1023px) {
    font-size: 12px;
    line-height: 1.25;
  }
}

.required {
  label {
    &:after {
      content: "*";
      color: #e0202e;
      margin: 0 0 0 5px;
    }
  }

  &.error {
    .input,
    textarea,
    .select select,
    &.photo-input {
      border: 1px solid #e0202e;
    }
  }
}

.complaint {
  &__hide {
    &-text {
      display: inline-block;
      margin: 0 0 0 20px;
      @media all and (max-width: 768px) {
        margin: 16px 0 0;
        font-size: 12px;
        line-height: 16px;
        text-align: center;
        display: block;
      }
    }
  }

  &__row {
    display: flex;
    @media all and (max-width: 768px) {
      display: block;
    }
  }

  &__col {
    flex-basis: 0;
    flex-grow: 1;
    max-width: 100%;
    padding: 0 20px;
    @media all and (max-width: 1023px) {
      padding: 0 15px;
    }
    @media all and (max-width: 768px) {
      margin: 22px 0 0;
      width: 100%;
      max-width: 100%;
    }

    &.--vertical {
      @media all and (max-width: 768px) {
        margin: 0;
      }
    }

    &.--sm {
      flex: 0 0 (170/1080) * 100%;
      max-width: (170/1080) * 100%;
      @media all and (max-width: 1023px) {
        flex: 0 0 (190/1080) * 100%;
        max-width: (190/1080) * 100%;
      }
      @media all and (max-width: 768px) {
        width: 100%;
        max-width: 100%;
      }
    }

    &.--text {
      flex: 0 0 (240/1080) * 100%;
      max-width: (240/1080) * 100%;
      @media all and (max-width: 768px) {
        width: 100%;
        max-width: 100%;
      }
    }

    &.--lg {
      flex: 0 0 (840/1080) * 100%;
      max-width: (840/1080) * 100%;
      @media all and (max-width: 768px) {
        width: 100%;
        max-width: 100%;
      }
    }

    &.--lg-2 {
      flex: 0 0 (690/1080) * 100%;
      max-width: (690/1080) * 100%;
      @media all and (max-width: 768px) {
        width: 100%;
        max-width: 100%;
      }
    }

    &:first-child {
      padding: 0 20px 0 0;
      @media all and (max-width: 1023px) {
        padding: 0 15px 0 0;
      }
      @media all and (max-width: 768px) {
        padding: 0;
      }
    }

    &:last-child {
      padding: 0 0 0 20px;
      @media all and (max-width: 1023px) {
        padding: 0 0 0 15px;
      }
      @media all and (max-width: 768px) {
        padding: 0;
      }
    }
  }

  &__line {
    margin: 38px 0 0;
    @media all and (max-width: 1023px) {
      margin: 22px 0 0;
    }

    .button {
      @media all and (max-width: 768px) {
        width: 100%;
      }
    }

    &.--sm {
      margin: 20px 0 0;
    }

    &.--mob {
      @media all and (max-width: 768px) {
        display: flex !important;
        .complaint__line {
          width: 50%;
          max-width: 50%;
          min-width: 50%;
          padding: 0 10px;
          margin: 0;

          &:first-child {
            padding: 0 10px 0 0;
          }

          &:last-child {
            padding: 0 0 0 10px;
          }
        }
      }
    }

    &:first-child {
      margin: 0;
    }

    .checkbox {
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: normal;
      font-size: 16px;
      line-height: 38px;
      color: #000000;
    }

    textarea {
      height: 131px;
      @media screen and (max-width: 768px) {
        height: 247px;
      }
    }
  }

  &__item {
    background: #ffffff;
    margin: 20px 0;
    padding: 30px 40px 40px;
    box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
      0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
    border-radius: 10px;
    .title {
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 600;
      font-size: 24px;
      line-height: 34px;
      color: #202020;
      position: relative;
      &::before {
        content: "";
        background: #2d9cdb;
        width: 3px;
        height: 25px;
        position: absolute;
        top: 4px;
        left: -40px;
        @media screen and (max-width: 768px) {
          left: -16px;
        }
      }
    }
    @media all and (max-width: 1023px) {
      padding: 25px 16px;
      margin: 0 15px 25px;
    }

    .input {
      @media all and (max-width: 1023px) {
        height: 50px;
      }
      @media all and (max-width: 768px) {
        input {
          font-size: 18px;
        }
        height: 60px;
      }
    }

    .select {
      height: auto;
      &:after {
        display: none;
      }
      @media all and (max-width: 768px) {
        height: auto;
        &:after {
          top: 27px;
          right: 14px;
        }
        select {
          font-size: 14px;
          height: 60px;
          padding: 0 30px 0 10px;
        }
      }
    }

    .label {
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 500;
      font-size: 16px;
      line-height: 19px;
      color: #3a3a3a;
      margin-bottom: 15px;
      @media all and (max-width: 768px) {
        font-size: 16px;
        line-height: 19px;
      }

      &-text {
        font-size: 12px;
        line-height: 20px;
        color: #5b6067;
        display: block;
        @media all and (max-width: 768px) {
          display: inline-block;
        }
      }

      &.--vertical {
        padding: 15px 0 0;
        font-family: SFProDisplay;
        font-style: normal;
        font-weight: 500;
        font-size: 16px;
        line-height: 19px;
        color: #3a3a3a;

        @media all and (max-width: 768px) {
          padding: 0;
          font-size: 16px;
          line-height: 19px;
        }
      }
    }

    .add-link {
      margin-top: 15px;
      display: inline-block;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 500;
      font-size: 16px;
      line-height: 19px;
      color: #2d9cdb;
      border-bottom: 1px solid #2d9cdb;
      @media all and (max-width: 768px) {
        margin: 6px 0 0;
      }
    }

    .photo-input {
      width: 80px;
      height: 80px;
      border: 1px solid #7b95a7;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
      position: relative;
      background: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjUiIGhlaWdodD0iMjUiIHZpZXdCb3g9IjAgMCAyNSAyNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTI0LjU1NTcgMTAuMDQ5N0gxNS4wNjE1VjAuNTU1NTQySDEwLjA0OThWMTAuMDQ5N0gwLjU1NTY2NFYxNS4wNjE0SDEwLjA0OThWMjQuNTU1NUgxNS4wNjE1VjE1LjA2MTRIMjQuNTU1N1YxMC4wNDk3WiIgZmlsbD0iIzdCOTVBNyIvPgo8L3N2Zz4K")
        center no-repeat;
      background-size: 24px;
      margin: 0 30px 30px 0;
      display: inline-block;
      @media all and (max-width: 768px) {
        margin: 0 0 0 10px;
        &:first-child {
          margin: 0;
        }
      }

      &__wrapper {
        font-size: 0;
        margin: 0 0 -30px;
        @media all and (max-width: 768px) {
          overflow-x: auto;
          white-space: nowrap;
          margin: 0;
        }
      }

      span {
        display: block;
        width: 100%;
        height: 100%;
        position: relative;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin: 0 auto;
        z-index: 1;
      }

      input {
        cursor: pointer;
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
        @media all and (max-width: 1023px) {
          width: 100%;
          height: 100%;
        }
      }
    }

    &:first-child {
      margin: 0;
      @media all and (max-width: 1023px) {
        margin: 0 15px 25px;
      }
    }
  }

  &__accordion {
    margin: 60px 0 0;

    .title {
      margin: 0 0 10px;
      &::before {
        background: unset;
      }
    }

    &-list {
      display: grid;
      grid-row-gap: 20px;
    }

    &-item {
      border: 1px solid #2d9cdb;
      border-radius: 10px;
      padding: 20px 28px;
      box-shadow: 0px 16px 24px rgba(0, 0, 0, 0.06),
        0px 2px 6px rgba(0, 0, 0, 0.04), 0px 0px 1px rgba(0, 0, 0, 0.04);
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: normal;
      line-height: 38px;

      @media screen and (max-width: 768px) {
        padding: 20px;
      }

      &__row {
        display: grid;
      }
    }

    &-content {
      display: none;

      .complaint__line:first-child {
        margin: 4px 0 0;
      }

      &.--checklist {
        padding: unset;
        margin-top: 15px;
      }
    }

    &-head {
      cursor: pointer;
      flex: 0 0 100% !important;
      max-width: 100% !important;
      position: relative;
      height: 30px;
      font-family: SFProDisplay;
      font-style: normal;
      font-weight: 500;
      font-size: 20px;
      line-height: 30px;
      color: #0b132a;

      &.complaint__col {
        @media screen and (max-width: 768px) {
          margin: unset;
        }
      }

      &:after {
        content: "";
        position: absolute;
        background: url("@/assets/icons/select_arrow.png") no-repeat center;
        width: 10px;
        height: 10px;
        top: 50%;
        right: 5px;
      }

      &.isActive {
        flex: 0 0 (240/1080) * 100% !important;
        max-width: (240/1080) * 100% !important;

        @media screen and (max-width: 768px) {
          max-width: fit-content !important;
        }

        &:after {
          margin: -4px 0 0 10px;
          right: auto;
        }

        & + .complaint__accordion-content {
          display: block;
        }
      }
    }
  }
}

.button {
  border-radius: 10px;
  border: none;
  background: #27ae60;
  cursor: pointer;
  font-family: SFProDisplay;
  font-style: normal;
  font-weight: bold;
  font-size: 18px;
  line-height: 19px;
  color: #ffffff;
  width: 290px;
  height: 60px;
}
</style>
