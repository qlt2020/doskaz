<template>
  <div class="user-notifications__item">
    <div class="user-notifications__info">
      <div class="user-notifications__title">
        <span class="name">{{ $t('newObjectAdded') }}</span>
        <!-- <span class="category">(категория, указанная в анкете)</span> -->
      </div>
      <div class="user-notifications__date">
        {{ $dateFns.format(new Date(item.created_at).toISOString(), "dd.MM.yyyy") }}
      </div>
    </div>
    <div class="user-notifications__description">
      <div class="user-notifications__category">
        <div class="cat-img">
          <img :src="`/${item.category_icon}`" style="margin: auto" />
        </div>
        <span>{{ item.category_name }}</span>
        <span>{{ item.sub_category_name }}</span>
      </div>
      <p class="user-notifications__text">{{ item.object_name }}</p>
    </div>
    <div class="user-notifications__body">
      <div class="user-notifications__body__item">
        <span class="user-notifications__body__label">{{ $t('complaint.city') }}</span>
        <div class="user-notifications__body__text">{{ item.city }}</div>
      </div>
      <div class="user-notifications__body__item">
        <span class="user-notifications__body__label">{{ $t('objectAdding.address') }}</span>
        <div class="user-notifications__body__text">{{ item.address }}</div>
      </div>
      <div class="user-notifications__body__item descrip">
        <span class="user-notifications__body__label">{{ $t('objects.tabTitles.description') }}</span>
        <div class="user-notifications__body__text descrip">
          {{ item.description }}
        </div>
      </div>
    </div>
    <nuxt-link class="user-comments__btn notifs" :to="localePath({ name: 'objects-id', params: { id: item.object_id }})">
      {{ $t('profile.objects.openObject') }}
    </nuxt-link>
  </div>
</template>

<script>
import ru from "date-fns/locale/ru";
import kz from "date-fns/locale/kk";

const locales = { ru, kz };

export default {
  props: {
    item: {
      type: Object
    }
  },
  computed: {
    categories() {
      return []
    }
  }
};
</script>
