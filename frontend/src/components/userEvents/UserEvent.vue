<template>
  <div class="list__item">
    <div class="list__date">
      <formatted-date :date="event.date" format="dd.MM.yyyy" />
    </div>
    <template v-if="event.type === 'object_reviewed'">
      <div class="list__item-wrap">
        <div class="user-tasks__item-status list__icon --new"></div>
        <div class="list__text">
          <a><username :value="event.data.username"/></a>
          {{ $t("profile.achievements.event.objectReviewed") }}
          <div @click="setClickedObj(event.data.id)">
            <nuxt-link
              :to="
                localePath({ name: 'objects-id', params: { id: event.data.id }, query: { zoom: 19 } })
              "
              >{{ event.data.title }}</nuxt-link
            >
          </div>
        </div>
      </div>
    </template>
    <template v-if="event.type === 'object_supplemented'">
      <div class="list__item-wrap">
        <div class="user-tasks__item-status list__icon --new"></div>
        <div class="list__text">
          <span><username :value="event.data.username"/></span>
          {{ $t("profile.achievements.event.objectSupplemented") }}
          <div @click="setClickedObj(event.data.id)">
            <nuxt-link
              :to="
                localePath({ name: 'objects-id', params: { id: event.data.id }, query: { zoom: 19 } })
              "
              >{{ event.data.title }} </nuxt-link
            >
          </div>
        </div>
      </div>
    </template>
    <template v-if="event.type === 'blog_comment_replied'">
        <div class="list__item-wrap">
          <div class="user-tasks__item-status list__icon --new"></div>
          <div class="list__text">
            <a>
              <username :value="event.data.username" />
            </a>
            {{ $t("profile.achievements.event.blogCommentReplied") }}
            <nuxt-link
              :to="
                localePath({
                  name: 'blog-cat-slug',
                  params: { cat: event.data.categorySlug, slug: event.data.slug }
                })
              "
            >
              {{ event.data.title }}
            </nuxt-link>
          </div>
        </div>
    </template>
    <div v-if="event.type === 'level_reached'" class="list__item-wrap">
      <div class="list__icon list__icon_level">
        <span>{{ event.data.level }}</span>
      </div>
      <div class="list__text">
          <i18n tag="div" path="profile.achievements.event.levelReached">
            <span place="level">{{ event.data.level }}</span>
          </i18n>
        <template v-if="event.data.unlockedAbility">{{
          $t(`profile.achievements.ability.${event.data.unlockedAbility}`)
        }}</template>
        <template v-if="event.data.pointsUntilNextLevel > 0">
          <i18n tag="div" path="profile.achievements.event.pointsUntilNextLevel">
            <span place="nextLevel">{{ event.data.level + 1 }}</span>
            <span place="points">{{ event.data.pointsUntilNextLevel }}</span>
          </i18n>
        </template>
      </div>
    </div>
    <template v-if="event.type === 'award_issued'">
        <div class="list__item-wrap">
          <div class="user-tasks__item-status list__icon --new"></div>
          <div class="list__text">
              <i18n tag="div" path="profile.achievements.event.awardIssued">
                <span place="award">{{ event.data.title }}</span>
              </i18n>
          </div>
        </div>
    </template>
    <template v-if="event.type === 'object_added'">
        <div class="list__item-wrap">
          <div class="user-tasks__item-status list__icon --new"></div>
          <div class="list__text">
            {{ $t("profile.achievements.event.objectAdded") }}
            <div @click="setClickedObj(event.data.id)">
              <nuxt-link
                :to="
                  this.localePath({
                    name: 'objects-id',
                    params: { id: event.data[0].id },
                    query: { zoom: 19 }
                  })
                "
                >{{ event.data[0].title}},
              </nuxt-link> {{ event.data[0].categoryTitle }}
            </div> 
          </div>
        </div>

    </template>
  </div>
</template>

<script>
import Username from "../Username";
import FormattedDate from "~/components/FormattedDate";

export default {
  name: "UserEvent",
  components: { FormattedDate, Username },
  props: ["event"],
  methods: {
    setClickedObj(id) {
      this.$store.commit("map/SET_CLICKED_OBJECT_ID", id);
    },
  }
};
</script>

<style scoped></style>
