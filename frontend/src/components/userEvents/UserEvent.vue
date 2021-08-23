<template>
    <div class="list__item">
        <div class="list__date">
            <formatted-date :date="event.date" format="d MMMM"/>
        </div>
        <template v-if="event.type === 'object_reviewed'">
            <div class="list__icon"></div>
            <div class="list__text">
                <a><username :value="event.data.username"/></a> {{ $t('profile.achievements.event.objectReviewed') }}
                <nuxt-link :to="localePath({name: 'objects-id', params: {id: event.data.id}})">{{ event.data.title }}</nuxt-link>
            </div>
        </template>
      <template v-if="event.type === 'object_supplemented'">
        <div class="list__icon"></div>
        <div class="list__text">
          <a><username :value="event.data.username"/></a> {{ $t('profile.achievements.event.objectSupplemented') }}
          <nuxt-link :to="localePath({name: 'objects-id', params: {id: event.data.id}})">{{ event.data.title }}</nuxt-link>
        </div>
      </template>
        <template v-if="event.type === 'blog_comment_replied'">
            <div class="list__icon"></div>
            <div class="list__text">
                <a>
                    <username :value="event.data.username"/>
                </a> {{ $t('profile.achievements.event.blogCommentReplied') }}
                <nuxt-link
                        :to="localePath({name: 'blog-cat-slug', params: {cat: event.data.categorySlug, slug: event.data.slug}})">
                    {{ event.data.title }}
                </nuxt-link>
            </div>
        </template>
        <template v-if="event.type === 'level_reached'">
            <div class="list__icon list__icon_level">
                <span>{{ event.data.level }}</span>
            </div>
            <div class="list__text">
                {{ $t('profile.achievements.event.levelReached', {level: event.data.level}) }}
                <template v-if="event.data.unlockedAbility">{{ $t(`profile.achievements.ability.${event.data.unlockedAbility}`) }}</template>
                <template v-if="event.data.pointsUntilNextLevel > 0">
                    {{ $t('profile.achievements.event.pointsUntilNextLevel', {nextLevel: event.data.level + 1, points: event.data.pointsUntilNextLevel}) }}
                </template>
            </div>
        </template>
        <template v-if="event.type === 'award_issued'">
            <div class="list__icon list__icon_achievment"></div>
            <div class="list__text">{{ $t('profile.achievements.event.awardIssued', {award: event.data.title}) }}</div>
        </template>
        <template v-if="event.type === 'object_added'">
            <div class="list__icon"></div>
            <div class="list__text">
                {{ $t('profile.achievements.event.objectAdded') }}
                <nuxt-link :to="this.localePath({name: 'objects-id', params: {id: event.data.id}})">{{ event.data.title }}</nuxt-link>, {{ event.data.categoryTitle }}
            </div>
        </template>
    </div>

    <!--
<div class="list__item">
    <div class="list__date">
        <span>10 августа</span>
    </div>
    <div class="list__icon"></div>
    <div class="list__text">
        <a href="#">Илья Давыдов</a> дополнил ваш объект
        <a href="#">Суши-бар Saya Sushi</a>
    </div>
</div>

<div class="list__item">
    <div class="list__date">
        <span>6 августа</span>
    </div>
    <div class="list__icon"></div>
    <div class="list__text">
        <a href="#">Ирина Ахметова</a> ответила на ваш комментарий к объекту
        <a href="#">Ветеринарная клиника «Мурзик»</a>
    </div>
</div>

<div class="list__item">
    <div class="list__date">
        <span>1 августа</span>
    </div>
    <div class="list__icon"></div>
    <div class="list__text">
        Ваш объект
        <a href="#">Суши-бар Saya Sushi</a> проверен и верифицирован модератором
        <a href="#">Volkorn</a>
    </div>
</div>

<div class="list__item">
    <div class="list__date">
        <span>26 июля</span>
    </div>
    <div class="list__icon list__icon_level">
        <span>7</span>
    </div>
    <div
            class="list__text"
    >Поздравляем, вы достигли 7 уровня! Теперь вы можете сменить аватар. До 8 уровня вам нужно набрать 60 баллов.</div>
</div>

<div class="list__item">
    <div class="list__date">
        <span>23 июля</span>
    </div>
    <div class="list__icon list__icon_achievment"></div>
    <div class="list__text">Вам выдана награда за активное участие в развитии портала</div>
</div>

<div class="list__item">
    <div class="list__date">
        <span>10 июля</span>
    </div>
    <div class="list__icon"></div>
    <div class="list__text">
        Ваш объект
        <a href="#">Аптека №234</a> проверен и верифицирован модератором
        <a href="#">Валерия Осинская</a>
    </div>
</div>-->

</template>

<script>
    import Username from "../Username";
    import FormattedDate from "~/components/FormattedDate";

    export default {
        name: "UserEvent",
        components: {FormattedDate, Username},
        props: [
            'event'
        ]
    }
</script>

<style scoped>

</style>