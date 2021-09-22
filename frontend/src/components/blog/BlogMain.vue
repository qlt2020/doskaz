<template>
  <div class="blog__in">
    <div class="blog_mobile_top">
      <a class="filter_btn" @click="showFilter = !showFilter">
        <span class="icon"></span>
        <span class="text">
          {{ $t("blog.filter") }}
        </span>
      </a>
    </div>
    <h2 class="title">{{ $t("blog.title") }}</h2>
    <div
      style="border: none; font-size: 20px; display: flex; width: 50px; height: 60px; padding-bottom: 11px; justify-content: center; align-items: center; top: -60px; left: -25px; position:absolute;"
    >
      <svg
        width="50"
        height="50"
        viewBox="0 0 55 55"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        style="position: absolute; top: 0; left: 0; z-index: 0;"
      >
        <rect
          opacity="0.3"
          x="0.734863"
          y="0.211182"
          width="54"
          height="54"
          rx="13"
          fill="#DE1220"
        />
        <rect
          opacity="0.6"
          x="4.73486"
          y="4.21118"
          width="46"
          height="46"
          rx="10"
          fill="#DE1220"
        />
        <rect
          x="7.73486"
          y="7.21118"
          width="40"
          height="40"
          rx="10"
          fill="#DE1220"
        />
      </svg>
      <div
        style="background-color: #fff; mask: url('/static/icons/6/restoran.svg') no-repeat center; -webkit-mask: url('/static/icons/6/restoran.svg') no-repeat center; z-index: 1; width: 18px; height: 18px; "
      ></div>
    </div>
    <div class="blog__content --main">
      <div class="input__wrapper">
        <form class="input" @submit.prevent="search">
          <button alt="search">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M23.3397 20.1519L19.3617 16.1746C20.4101 14.5385 21.036 12.6053 21.036 10.5179C21.036 4.70874 16.3272 0 10.518 0C4.70944 0 0 4.70874 0 10.518C0 16.3273 4.70944 21.036 10.518 21.036C12.6053 21.036 14.5385 20.4101 16.1739 19.3617L20.1518 23.3397C21.0322 24.2201 22.4593 24.2201 23.3397 23.3397C24.2201 22.4593 24.2201 21.0323 23.3397 20.1519ZM10.518 18.0309C6.369 18.0309 3.00511 14.6677 3.00511 10.518C3.00511 6.36905 6.36905 3.00516 10.518 3.00516C14.6676 3.00516 18.0308 6.36905 18.0308 10.518C18.0308 14.6677 14.6676 18.0309 10.518 18.0309Z"
                fill="#5B6067"
              />
            </svg>
          </button>
          <input
            type="text"
            :placeholder="$t('index.searchSubmitButton')"
            :value="$route.query.search"
            ref="search"
          />
        </form>
        <button class="btn btn_blue blog__search__btn">
          <span class="text">
            {{ $t("index.searchSubmitButton") }}
          </span>
        </button>
      </div>
      <ul class="blog__list">
        <li class="blog__list-item" v-for="post in posts" :key="post.id">
          <div class="blog__item">
            <div
              class="blog__item-img"
              :style="`background: url(${post.image}) no-repeat center/cover`"
            >
              <img v-if="post.image" :src="post.image" :alt="post.title" />
              <div class="blog__item-img-category">
                {{ post.categoryTitle }}
              </div>
            </div>
            <div class="blog__item__content">
              <nuxt-link
                :to="
                  localePath({
                    name: 'blog-cat-slug',
                    params: { cat: post.categorySlug, slug: post.slug },
                  })
                "
                class="blog__item__content-title"
                >{{ post.title }}
              </nuxt-link>
              <formatted-date
                class="blog__item__content-link"
                :date="post.publishedAt"
                format="d.MM.y"
              />
              <p class="blog__item__content-text" v-html="post.annotation"></p>
              <div class="blog__item__content-bottom">
                <div>
                  <nuxt-link
                    :to="
                      localePath({
                        name: 'blog-cat-slug',
                        params: { cat: post.categorySlug, slug: post.slug },
                      })
                    "
                    class="blog__item__content-more"
                    >{{ $t("blog.postLinkTitle") }}
                  </nuxt-link>
                </div>
              </div>
            </div>
          </div>
        </li>
      </ul>
      <div class="blog__pagination">
        <Pagination :pages="pages" v-if="pages > 1" />
      </div>
    </div>
    <div :class="`blog__side${showFilter ? ` show` : ''}`">
      <div class="blog__side-main">
        <div class="blog__side-main__close" @click="showFilter = false"></div>
        <div class="blog__side-main__title">
          {{ $t("blog.filter") }}
        </div>
        <div class="blog__category">
          <span class="blog__category-title">{{ $t("blog.categories") }}</span>
          <nuxt-link
            :to="
              localePath({
                name: 'blog-category',
                query: {
                  period: $route.query.period,
                  post_date_from: $route.query.post_date_from,
                  post_date_to: $route.query.post_date_to,
                },
              })
            "
            class="blog__category-link"
            :class="{ isActive: !activeCategory }"
          >
            <span>{{ $t("blog.allCategories") }}</span>
          </nuxt-link>

          <nuxt-link
            :to="
              localePath({
                name: 'blog-category',
                params: { category: category.slug },
                query: { period: $route.query.period },
              })
            "
            class="blog__category-link"
            v-for="category in categories"
            :class="{ isActive: activeCategory === category.slug }"
            :key="category.slug"
          >
            <span>{{ category.title }}</span>
          </nuxt-link>
          <div class="blog__category__select">
            <!-- <select v-model="categoryId">
              <option :value="0" :key="0">
                {{ $t("blog.allCategories") }}
              </option>
              <option
                v-for="category in categories"
                :value="category.id"
                :key="category.id"
                >{{ category.title }}
              </option>
            </select> -->
            <DropdownBlock
              :options="[
                {
                  value: 0,
                  title: $t('blog.allCategories'),
                },
                ...categoriesForSelect,
              ]"
              v-model="categoryId"
            />
          </div>
        </div>
        <div class="blog__category">
          <span class="blog__category-title">{{ $t("blog.date") }}</span>
          <nuxt-link
            :to="{ ...$route, query: {} }"
            class="blog__category-link"
            @click="showDateInputs = false"
            :class="{
              isActive:
                !$route.query.period &&
                !$route.query.post_date_from &&
                !$route.query.post_date_to,
            }"
            ><span>{{ $t("blog.dateOverall") }}</span></nuxt-link
          >
          <nuxt-link
            v-for="period in periods"
            :id="`blog__date__${period.key}`"
            :key="period.key"
            :to="{ ...$route, query: { period: period.key } }"
            class="blog__category-link"
            :class="{ isActive: period.key === $route.query.period }"
          >
            <span>{{ $t(`blog.dateFilterPeriod.${period.key}`) }}</span>
          </nuxt-link>
          <div
            :id="`blog__date__period`"
            class="blog__category-link"
            @click="showDateInputs = true"
            :class="{
              isActive:
                'period' === $route.query.period ||
                ($route.query.post_date_from && $route.query.post_date_to),
            }"
          >
            <span>{{ $t(`blog.dateFilterPeriod.period`) }}</span>
          </div>
          <div class="blog__category__select">
            <!-- <select v-model="periodId">
              <option value="all">
                {{ $t("blog.dateOverall") }}
              </option>
              <option
                v-for="(period, index) in periods"
                :value="period.key"
                :key="index"
                >{{ $t(`blog.dateFilterPeriod.${period.key}`) }}
              </option>
            </select> -->
            <DropdownBlock
              :options="[
                {
                  value: 'all',
                  title: $t('blog.dateOverall'),
                },
                ...periodsForSelect,
              ]"
              v-model="periodId"
            />
          </div>
          <div
            class="blog__category__dates"
            v-if="
              showDateInputs ||
                periodId === 'period' ||
                ($route.query.post_date_from && $route.query.post_date_to)
            "
          >
            <div class="blog__category__dates__title">
              Поиск по дате
            </div>
            <div class="blog__category__dates__inputs">
              <vuejs-date-picker
                id="blog__period-from"
                :format="format"
                :disabledDates="disabledDatesFrom"
                :language="locale"
                v-model="periodFrom"
                :placeholder="$t('blog.from')"
              />
              <vuejs-date-picker
                id="blog__period-to"
                :format="format"
                :disabledDates="disabledDatesTo"
                :language="locale"
                v-model="periodTo"
                :disabled="periodFrom === ''"
                :placeholder="$t('blog.to')"
              />
            </div>
            <nuxt-link
              class="btn btn_blue blog__search__btn"
              :to="{
                ...$route,
                query: {
                  post_date_from: datesForFilter[0],
                  post_date_to: datesForFilter[1],
                },
              }"
            >
              <span class="text">
                {{ $t("index.searchSubmitButton") }}
              </span>
            </nuxt-link>
          </div>
        </div>
        <div class="blog__side__buttons">
          <nuxt-link
            :class="{ disabled: periodId === 'period' && periodTo === '' }"
            class="btn btn_green"
            :to="
              localePath({
                name: 'blog-category',
                params: params,
                query: query,
              })
            "
          >
            <span class="text">
              {{ $t("blog.accept") }}
            </span>
          </nuxt-link>
          <nuxt-link
            :class="{ disabled: periodId === 'period' && periodTo === '' }"
            class="btn btn_red"
            :to="
              localePath({
                name: 'blog-category',
                query: {},
              })
            "
          >
            <span class="text">
              {{ $t("blog.reset") }}
            </span>
          </nuxt-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from "~/components/Pagination";
import FormattedDate from "~/components/FormattedDate";
import Dropdown from "../Dropdown";
import ru from "vuejs-datepicker/dist/locale/translations/ru";

export default {
  props: ["posts", "categories", "pages"],
  components: {
    FormattedDate,
    Pagination,
    Dropdown,
  },
  data() {
    return {
      showFilter: false,
      categoryId: 0,
      periodId: "all",
      format: "dd.MM.yyyy",
      disabledDatesFrom: {
        from: new Date(Date.now() + 8640000),
      },
      locale: ru,
      periodFrom: "",
      periodTo: "",
      showDateInputs: false,
    };
  },
  mounted() {
    if (this.$route.query.post_date_from && this.$route.query.post_date_to) {
      this.periodFrom = new Date(this.$route.query.post_date_from);
      this.periodTo = new Date(this.$route.query.post_date_to);
    }
    if (this.$route.params.category) {
      var currentCategory = this.categories.find(
        (c) => c.slug === this.$route.params.category
      );
      this.categoryId = currentCategory.id;
    }
    if (this.$route.query.period) {
      var currentPeriod = this.periods.find(
        (c) => c.key === this.$route.query.period
      );
      this.periodId = currentPeriod.key;
    }
  },
  methods: {
    async getNewList() {
      var newPosts = await this.$axios.get(
        `api/blog/posts?post_date_from=${this.datesForFilter[0]}&post_date_to=${this.datesForFilter[1]}`
      );
      this.posts = newPosts.data.items;
    },
    search() {
      this.$router.push({
        ...(this.$route + "/"),
        query: {
          ...this.$route.query,
          page: undefined,
          search: this.$refs.search.value || undefined,
        },
      });
    },
  },
  watch: {
    periodId(val) {
      if (val !== "period") {
        this.periodFrom = "";
        this.periodTo = "";
      }
    },
    $route(to, from) {
      this.showFilter = false;
      if (!to.query.post_date_from && !to.query.post_date_to) {
        this.showDateInputs = false;
      }
    },
  },
  computed: {
    categoriesForSelect() {
      var categoriesList = this.categories.map((c) => {
        return {
          value: c.id,
          title: c.title,
        };
      });
      return categoriesList;
    },
    periodsForSelect() {
      var periodsList = [...this.periods, { key: "period" }];
      var list = periodsList.map((c) => {
        return {
          value: c.key,
          title: this.$t(`blog.dateFilterPeriod.${c.key}`),
        };
      });
      return list;
    },
    datesForFilter() {
      var dates = [this.periodFrom, this.periodTo].map((el) => {
        var date = new Date(el);
        var month =
          date.getMonth().toString().length === 1
            ? `0${date.getMonth() + 1}`
            : date.getMonth() + 1;
        var day =
          date.getDate().toString().length === 1
            ? `0${date.getDate()}`
            : date.getDate();
        var newDate = `${date.getFullYear()}-${month}-${day}`;
        return newDate;
      });
      return dates;
    },
    options() {
      return [
        { value: "date desc", title: this.$t("profile.sortNewestFirst") },
        {
          value: "popularity desc",
          title: this.$t("profile.comments.sortByPopularity"),
        },
      ];
    },
    activeCategory() {
      return this.$route.params.category;
    },
    periods() {
      return [{ key: "year" }, { key: "month" }];
    },
    params() {
      var obj;
      var selectedCategory = this.categories.find(
        (c) => c.id == this.categoryId
      );
      if (selectedCategory) {
        obj = {
          category: selectedCategory.slug,
        };
      } else {
        obj = {};
      }
      return obj;
    },
    query() {
      var obj;
      if (this.periodId) {
        if (this.periodId === "all") {
          obj = {};
        } else if (this.periodId === "period") {
          obj = {
            post_date_from: this.datesForFilter[0],
            post_date_to: this.datesForFilter[1],
          };
        } else {
          obj = {
            period: this.periodId,
          };
        }
      } else {
        obj = {
          period: this.$route.query.period,
        };
      }
      return obj;
    },
    disabledDatesTo() {
      return {
        to: this.periodFrom,
        from: new Date(Date.now() + 8640000),
      };
    },
  },
};
</script>

<style lang="scss">
.blog {
  .title {
    width: 100%;
    margin-bottom: 30px;
  }
  &__in {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    padding: 50px 0 30px;
    position: relative;
    .input__wrapper {
      margin-bottom: 20px;
      display: flex;
      .input {
        background: #fff;
        border: 1px solid #c2c2c2;
        height: 60px;
        border-radius: 10px;
        padding: 0 23px 0 0;
        input {
          font-size: 16px;
          background: #fff;
        }
        button {
          display: flex;
          align-items: center;
          justify-content: center;
          height: 100%;
          padding: 0 15px 0 23px;
          svg {
            display: inline-block;
            width: 16px;
            height: 16px;
          }
        }
      }
      .btn {
        cursor: pointer;
        height: 60px;
        width: 170px;
        margin-left: 15px;
      }
    }
  }

  .blog__search__btn {
    .text {
      font-size: 18px;
      font-weight: 700;
      font-family: "SFProDisplay";
    }
    &:disabled {
      opacity: 0.8;
    }
  }

  &__main {
    display: flex;
    padding-top: 30px;
  }

  &__content {
    flex: 1;
    width: 770px;
    &.--main {
    }
  }

  &__side {
    width: 370px;
    margin-left: 30px;
    &-main {
      background: #f4f4f4;
      padding: 23px 29px;
      border-radius: 10px;
      &__close,
      &__title {
        display: none;
      }
    }
    &-subscribe {
      margin-top: 20px;
      .subscribe-link {
        background: #2d9cdb;
        height: 43px;
        border-radius: 10px;
        padding: 0;
        span.icon {
          width: 16px;
          height: 16px;
        }
      }
    }
    &__buttons {
      display: none;
    }
  }

  &__list {
    list-style: none;
    padding: 0;
    margin: 0;

    &-item {
      padding-bottom: 40px;
      &:first-child {
        padding: 0 0 35px;
      }
    }
  }

  &__item {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0px 16px 24px 0px #0000000f;
    overflow: hidden;

    &-img {
      height: 392px;
      overflow: hidden;
      position: relative;
      &-category {
        position: absolute;
        top: 23px;
        right: 23px;
        min-width: 126px;
        height: 40px;
        padding: 0 20px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 10px;
        color: #000;
        font-size: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: "SFProDisplay", sans-serif;
      }
      & > img {
        display: block;
        opacity: 0;
        max-width: 100%;
      }
    }

    &__content {
      padding: 28px;
      &-title,
      & > h3 {
        font-family: "SFProDisplay", sans-serif;
        font-weight: bold;
        font-size: 22px;
        margin: 0 0 32px;
        line-height: 26px;
      }
      &-text,
      & > p {
        font-family: "SFProDisplay", sans-serif;
        font-weight: 300;
        font-size: 18px;
        line-height: 24.5px;
        margin: 10px 0;
      }

      &-bottom {
        display: flex;
        justify-content: space-between;
        font-size: 0;
      }

      &-link {
        display: block;
        font-size: 14px;
        line-height: 20px;
        color: #2d9cdb;
        margin-top: 5px;
        font-family: "SFProDisplay", sans-serif;
        font-weight: 500;
        & + .blog__item-link {
          margin: 0 0 0 50px;
        }
      }
      &-more {
        font-family: "SFProDisplay", sans-serif;
        display: block;
        color: #000;
        font-weight: 500;
        font-size: 18px;
      }
    }
  }

  &__pagination {
    padding: 40px 0 30px;
  }

  &__category {
    margin-top: 30px;
    &__select {
      display: none;
    }
    &:first-child {
      margin-top: 0;
    }

    &-link {
      display: block;
      line-height: 36px;
      color: #333333;
      position: relative;
      cursor: pointer;
      span {
        font-family: "SFProDisplay", sans-serif;
        font-weight: 300;
        font-size: 16px;
      }

      &.isActive {
        span {
          font-weight: 700;
          color: #2d9cdb;
        }
      }
    }

    &-title {
      font-size: 22px;
      margin: 0 0 10px;
      font-family: "SFProDisplay";
      font-weight: 600;
      display: block;
      color: #000;
    }

    .social {
      margin: 15px 0 0;
    }

    &__dates {
      margin: 30px -6px 0;
      &__title {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 17px;
        font-family: "SFProDisplay";
      }
      &__inputs {
        & > div {
          position: relative;
          background: #fff;
          border-radius: 6px;
          flex: 1;
          &:first-child {
            margin-right: 15px;
          }
          .vdp-datepicker__calendar {
            left: auto;
            right: 0;
          }
        }
        display: flex;
        input {
          height: 60px;
          border: none;
          width: 100%;
          max-width: auto;
          padding: 17px 40px 17px 17px;
          font-size: 18px;
          font-weight: 500;
          color: #696969;
          font-family: "SFProDisplay";
          background: url("@/assets/icons/calendar.svg") no-repeat
            calc(100% - 14px) center;
          &:disabled {
            opacity: 0.5;
          }
        }
      }
      .btn {
        cursor: pointer;
        margin-top: 17px;
        height: 60px;
      }
    }
  }
}

.social {
  font-size: 0;
  display: flex;
  align-items: center;

  &__mobile {
    display: none;
  }

  &__link {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 21px;
    height: 21px;
    margin: 0 0 0 5px;
    line-height: 50px;
    text-align: center;
    transition: opacity 0.3s;
    border-radius: 5px;
    box-shadow: 0px 2px 10px 0px rgba(0, 0, 0, 0.1);
    cursor: pointer;

    &:hover {
      opacity: 0.7;
    }

    img {
      vertical-align: middle;
      display: inline-block;
    }

    &:first-child {
      margin: 0;
    }
  }
}

a.blog__item-link {
  transition: opacity 0.4s;

  &:hover {
    opacity: 0.7;
  }
}

.blog_mobile_top {
  display: flex;
  align-items: center;
  margin-bottom: 23px;
  width: 100%;
  display: none;
  .filter_btn {
    margin-left: auto;
    box-shadow: 0px 16px 24px 0px #0000000f;
    background: #fff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 42px;
    padding: 0 13px;
    cursor: pointer;
    transition: 0.3s ease;
    .icon {
      width: 24px;
      height: 24px;
      background: url("@/assets/icons/filter_icon.svg") no-repeat center/contain;
      margin-right: 10px;
    }
    .text {
      font-weight: 500;
      font-size: 16px;
    }
    &:hover {
      box-shadow: 0px 5px 5px 0px #0000000f;
    }
  }
}

@media screen and (max-width: 1200px) {
  .blog .container {
    max-width: 960px;
  }
  .blog {
    &__side {
      width: 270px;
      &-main {
        padding: 15px 20px;
      }
    }
    &__item {
      &-img {
        height: 320px;
        &-category {
          height: 30px;
          min-width: 100px;
          border-radius: 7px;
          font-size: 16px;
        }
      }
    }
    &__category__dates__inputs {
      display: block;
      & > div:first-child {
        margin-right: 0;
        margin-bottom: 10px;
      }
    }
  }
}

@media screen and (max-width: 1023px) {
  .blog {
    &__search__btn {
      display: none;
    }
    &__category__dates {
      margin: 30px 0 0;
      &__inputs {
        display: flex;
        & > div {
          box-shadow: -10px 16px 24px 0px #0000000f;
          &:first-child {
            margin-bottom: 0;
            margin-right: 10px;
          }
        }
      }
    }
  }
  .blog .container {
    max-width: 720px;
  }
  .blog_mobile_top {
    display: flex;
  }
  .blog__side {
    position: fixed;
    top: 70px;
    right: -20%;
    left: 100%;
    bottom: 0;
    width: auto;
    margin: 0;
    transition: 0.2s ease;
    &.show {
      left: 50%;
      right: 0;
      box-shadow: -10px 16px 24px 0px #0000000f;
    }
    &-main {
      position: relative;
      background: #fafafa;
      padding-top: 52px;
      height: 100%;
      border-radius: 0;
      &__close,
      &__title {
        display: block;
      }
      &__title {
        font-weight: 500;
        font-size: 18px;
      }
      &__close {
        position: absolute;
        top: 56px;
        right: 24px;
        width: 14px;
        height: 14px;
        cursor: pointer;
        background: url("@/assets/icons/cross.png") no-repeat center/cover;
      }
    }
    &__buttons {
      display: flex;
      position: absolute;
      bottom: 50px;
      left: 15px;
      right: 15px;
      .btn {
        flex: 1;
        &:first-child {
          margin-right: 15px;
        }
        &.disabled {
          opacity: 0.5;
          cursor: auto;
        }
      }
    }
  }
  .blog {
    &__category {
      &-title {
        font-weight: 500;
        font-size: 16px;
        line-height: 19px;
      }
      &-link {
        display: none;
      }
      &__select {
        display: block;
      }
    }
    &__item {
      &-img {
        height: 350px;
      }
      &__content {
        &-title {
          font-size: 18px;
          line-height: 21.5px;
        }
      }
    }
  }
}

@media screen and (max-width: 1023px) {
  .blog__side {
    &.show {
      left: 0;
      right: 0;
    }
  }
  .blog .title {
    font-size: 28px;
    line-height: 33.5px;
    margin-bottom: 20px;
  }
  .blog__category__dates__inputs > div:first-child .vdp-datepicker__calendar {
    right: auto;
    left: 0;
  }
}

@media screen and (max-width: 1023px) {
  .blog__item-img {
    height: 176px;
  }
  .social {
    display: none;
    &__mobile {
      display: flex;
      margin-left: 14px;
    }
  }
  .blog__content-top__text {
    display: flex;
    flex-wrap: wrap;
    h3 {
      width: 100%;
      margin-bottom: 10px;
    }
  }
}
</style>
