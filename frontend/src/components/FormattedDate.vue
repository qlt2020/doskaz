<template>
  <component :is="element">{{ formattedDate }}</component>
</template>

<script>
import format from "date-fns/format";
import ru from "date-fns/locale/ru";
import kz from "date-fns/locale/kk";

const locales = { ru, kz };

export default {
  name: "FormattedDate",
  props: {
    element: {
      type: String,
      default: "span"
    },
    date: {
      type: String
    },
    format: {
      type: String,
      default: "yyyy-MM-dd'T'HH:mm:ss.SSSxxx"
    }
  },
  computed: {
    formattedDate() {
      return format(new Date(this.date), this.format, {
        locale: locales[this.$i18n.locale]
      });
    }
  }
};
</script>

<style scoped></style>
