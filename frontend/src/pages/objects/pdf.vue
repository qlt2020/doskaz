<template>
  <div class="pdf">
    <div class="pdf__top">
      <div class="pdf__fixed-logo">
        <img :src="require('@/assets/img/logo-new-black.svg')" />
      </div>
      <div class="pdf__qr">
        <span class="pdf__qr-text">
          <span class="pdf__qr-item">{{ object.title }}</span>
          <span class="pdf__qr-item">www.doskaz.kz</span>
        </span>
        <qrcode
          tag="img"
          :value="qrLink"
          :options="{ width: 500, margin: 0, type: 'svg' }"
        />
      </div>
    </div>
    <div class="pdf__info">
      <h2>Общая информация</h2>
      <div class="pdf__info-line">
        <div class="pdf__info-item">
          <span class="pdf__info-title">Наименование</span>
          <span class="pdf__info-text">{{ object.title }}</span>
        </div>
        <div class="pdf__info-item">
          <span class="pdf__info-title">Адрес</span>
          <span class="pdf__info-text">{{ object.address }}</span>
        </div>
      </div>
      <div class="pdf__info-line">
        <div class="pdf__info-item">
          <span class="pdf__info-title">Категория</span>
          <span class="pdf__info-text">{{ object.category }}</span>
        </div>
        <div class="pdf__info-item">
          <span class="pdf__info-title">Подкатегория</span>
          <span class="pdf__info-text">{{ object.subCategory }}</span>
        </div>
      </div>
    </div>
    <div class="pdf__item" v-for="(zone, index) in zones" :key="zone.key">
      <h2>{{ index + 1 }}. {{ zone.title }}</h2>

      <template v-for="group in formAttributes[zone.group]">
        <h3 v-if="group.title">{{ group.title }}</h3>

        <div
          v-for="subGroup in group.subGroups"
          :class="{ 'pdf__item-list': !!subGroup.title }"
        >
          <span v-if="subGroup.title" class="pdf__item-list__title">{{
            subGroup.title
          }}</span>
          <div class="pdf__item-line" v-for="attribute in subGroup.attributes">
            <img
              :src="require('@/assets/icons/pdf-check.svg')"
              v-if="
                object.attributes.zones[zone.key][
                  `attribute${attribute.key}`
                ] === 'yes'
              "
            />
            <img
              :src="require('@/assets/icons/pdf-not-prov.svg')"
              v-else-if="
                object.attributes.zones[zone.key][
                  `attribute${attribute.key}`
                ] === 'not_provided'
              "
            />
            <img :src="require('@/assets/icons/pdf-no-check.svg')" v-else />
            <span>{{ attribute.title || attribute.subTitle }}</span>
          </div>
        </div>
      </template>
    </div>
    <div class="pdf__fixed-left">
      <div class="pdf__fixed-item">
        <img :src="require('@/assets/icons/pdf-check.svg')" /><span>Да</span>
      </div>
      <div class="pdf__fixed-item">
        <img :src="require('@/assets/icons/pdf-no-check.svg')" /><span
          >Нет</span
        >
      </div>
      <div class="pdf__fixed-item">
        <img :src="require('@/assets/icons/pdf-not-prov.svg')" /><span
          >Не предусмотрено</span
        >
      </div>
      <div class="pdf__fixed-item">
        <img :src="require('@/assets/icons/pdf-unknown.svg')" /><span
          >Неизвестно</span
        >
      </div>
    </div>
  </div>
</template>

<script>
import VueQrcode from "@chenfengyuan/vue-qrcode";

export default {
  components: {
    [VueQrcode.name]: VueQrcode,
  },
  async asyncData({ $axios, query, req }) {
    const [{ data: object }, { data: attributes }] = await Promise.all([
      $axios.get(`/api/objects/${query.id}`),
      $axios.get(`/api/objects/attributes`),
    ]);
    return {
      object,
      attributes,
      host: "doskaz.kz",
    };
  },
  computed: {
    zones() {
      return [
        {
          key: "parking",
          title: "Парковка",
          group: "parking",
        },
        {
          key: "entrance1",
          title: "Входная группа",
          group: "entrance",
        },
        {
          key: "entrance2",
          title: "Входная группа",
          group: "entrance",
        },
        {
          key: "entrance3",
          title: "Входная группа",
          group: "entrance",
        },
        {
          key: "movement",
          title: "Пути движения по объекту",
          group: "movement",
        },
        {
          key: "service",
          title: "Зона оказания услуги",
          group: "service",
        },
        {
          key: "toilet",
          title: "Туалет",
          group: "toilet",
        },
        {
          key: "navigation",
          title: "Навигация",
          group: "navigation",
        },
        {
          key: "serviceAccessibility",
          title: "Доступность услуги",
          group: "serviceAccessibility",
        },
        {
          key: "kidsAccessibility",
          title: "Доступность и безопасность услуг для детей до 7 лет",
          group: "kidsAccessibility",
        },
      ].filter((z) => !!this.object.attributes.zones[z.key]);
    },
    qrLink() {
      return `https://${this.host}/objects/${this.$route.query.id}`;
    },
    formAttributes() {
      return this.attributes[this.object.attributes.form];
    },
  },
};
</script>

<style lang="scss">
@import "@/styles/mixins.scss";

.pdf__fixed {
  &-logo {
    img {
      width: 100px;
      height: auto;
      filter: grayscale(1);
    }
  }

  &-left {
    position: fixed;
    left: 50%;
    margin: 0 0 0 -275px;
    bottom: 22px;
    z-index: 10;
    display: flex;
    transform: rotate(-90deg);
    transform-origin: 0 0 0;
  }

  &-item {
    margin: 0;
    display: flex;

    img {
      width: 10px;
      height: 10px;
      display: block;
    }

    span {
      font-size: 8px;
      line-height: 10px;
      display: block;
      margin: 0 0 0 5px;
    }

    & + .pdf__fixed-item {
      margin: 0 0 0 14px;
    }
  }
}

.pdf {
  width: 450px;
  color: #000000;
  margin: 0 auto;

  h2,
  h3 {
    page-break-after: avoid;
    page-break-before: avoid;
    page-break-inside: avoid;
  }

  h2 {
    margin: 0;
    font-size: 14px;
    line-height: 20px;
  }

  &__top {
    padding: 25px 0 11px;
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #000000;
    align-items: top;
  }

  &__logo {
    padding: 3px 0 0;

    img {
      width: 100px;
      height: auto;
      display: block;
    }
  }

  &__qr {
    display: flex;

    &-text {
      text-align: right;
      padding: 2px 0 0;
    }

    &-item {
      font-size: 8px;
      line-height: 10px;
      display: block;

      & + .pdf__qr-item {
        margin: 5px 0 0;
      }
    }

    img {
      width: 30px;
      margin: 0 0 0 20px;
      display: block;
    }
  }

  &__item {
    margin: 0;

    h2 {
      margin: 0 0 10px;
    }

    h3 {
      font-size: 12px;
      line-height: 15px;
      margin: 22px 0 12px;
    }

    & + .pdf__item {
      margin: 34px 0;
    }

    &-list {
      padding: 0 0 0 25px;
      margin: 11px 0 14px;

      &__title {
        font-size: 10px;
        line-height: 15px;
        margin: 0 0 11px;
        display: block;
      }
    }

    &-line {
      margin: 0;
      position: relative;
      display: flex;

      img {
        display: block;
        width: 15px;
        height: 15px;
        margin: 0 10px 0 0;
      }

      span {
        font-size: 10px;
        line-height: 15px;
        padding: 0 0 6px;
        flex: 1 1 auto;
        border-bottom: 1px solid #000000;
      }

      & + .pdf__item-line {
        margin: 14px 0 0;
      }
    }
  }

  &__info {
    margin: 24px 0 20px;

    &-title {
      font-size: 8px;
      line-height: 10px;
      opacity: 0.5;
      margin: 0 0 2px;
      display: block;
    }

    &-text {
      font-size: 10px;
      line-height: 15px;
      display: block;
    }

    &-item {
      width: 210px;
      border-bottom: 1px solid #000000;
      padding: 0 0 6px;
    }

    &-line {
      display: flex;
      justify-content: space-between;
      margin: 12px 0 0;

      & + .pdf__info-line {
        margin: 16px 0 0;
      }
    }
  }
}

@media print {
  @page {
    margin: 30px 0;
  }
  .pdf {
    h2,
    h3 {
      page-break-after: avoid;
      page-break-before: avoid;
      page-break-inside: avoid;
    }

    &__item {
      &-line {
        page-break-inside: avoid;
      }
    }
  }
}
</style>
