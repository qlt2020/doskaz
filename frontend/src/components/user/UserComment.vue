<template>
    <div class="user-comments__item">
        <div class="user-comments__image" v-bind:style="{'background-image': 'url(' + item.image + ')'}"></div>
        <div class="user-comments__description">
            <p class="user-comments__text">{{item.text}}</p>
            <div class="user-comments__info">{{relativeDate}}&nbsp;{{objectType}}&nbsp;<nuxt-link :to="localePath(link)"><strong>{{item.title}}</strong></nuxt-link></div>
        </div>
    </div>
</template>

<script>
    import ru from 'date-fns/locale/ru'
    import kz from 'date-fns/locale/kk'
    import {formatRelative} from 'date-fns'
    import capitalize from 'lodash/capitalize'

    const locales = {ru, kz}

    export default {
        props: [
            'item'
        ],
        methods: {
          openLink() {
              this.$router.push(this.localePath(this.link))
          }
        },
        computed: {
            objectType() {
                return this.item.type === 'post' ? this.$t('profile.comments.forPost') : this.$t('profile.comments.forObject')
            },
            relativeDate() {
                return capitalize(formatRelative( new Date(this.item.date), new Date(), {
                    locale: locales[this.$i18n.locale]
                }))
            },
            link() {
                return this.item.type === 'post' ? {name: 'blog-cat-slug', params: {cat: this.item.categorySlug, slug: this.item.slug}} : {name: 'objects-id', params: {id: this.item.objectId}}
            }
        }
    };
</script>