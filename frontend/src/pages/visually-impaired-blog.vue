<template>
    <div class="vi" :class="{'--noto': fontFamily == 'Noto', '--black': colorTheme == 'black', '--sm': fontSize == 'sm', '--md': fontSize == 'md','--lrg': fontSize == 'lrg'}">
        <div class="container">
            <ViHeader></ViHeader>
            <div class="vi__title-flex">
                <h3 class="vi__title --fcolor">Блог</h3>
                <a href="" class="vi__title-link">Подписаться на рассылку</a>
            </div>
            <div class="vi__line">
                <div class="col --padding">
                    <label class="vi__label --fcolor">Поиск по блогу</label>
                    <div class="input --bcolor">
                        <input type="text">
                    </div>
                </div>
                <div class="col --padding --search">
                    <button type="button" class="vi-search-button --bcolor">Поиск</button>
                </div>
            </div>
            <div class="vi__line">
                <div class="col">
                    <label class="vi__label --fcolor">Категория объекта</label>
                    <div class="select">
                        <select class="--bcolor">
                            <option>Питание</option>
                            <option>Правильное питание</option>
                            <option>Здравоохранение</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label class="vi__label --fcolor">подкатегория объекта</label>
                    <div class="select">
                        <select class="--bcolor">
                            <option>Подкатегория</option>
                            <option>Подкатегория1</option>
                            <option>Подкатегория2</option>
                            <option>Подкатегория3</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="vi-blog__list">
                <div class="vi-blog__list-item">
                    <a href="" class="vi-blog__list-title">Жалоба на отделение банка</a>
                    <p class="vi-blog__list-text">Каждый гражданин может повлиять на то, чтобы его окружала городская безбарьерная среда. Взывание к совести хозяев объектов, споры, объяснения и скандалы обычно ни к чему не приводят. Самый действенный способ – подача жалобы на недоступный объект в уполномоченный государственный орган. В Казахстане жалобы принимают областные Департаменты Комитета труда, социальной защиты и миграции населения. Данные государственные органы наделены полномочиями выдавать предписание на адаптацию объекта или накладывать административные штрафы за невыполнение предписаний.</p>
                    <div class="vi-blog__list-flex">
                        <div><span class="vi-blog__list-link">19 марта 2019</span><span class="vi-blog__list-link">Новости</span></div>
                        <div><a href="#" class="vi-blog__list-link">Подробнее</a></div>
                    </div>
                </div>
                <div class="vi-blog__list-item">
                    <a href="" class="vi-blog__list-title">«Народный контроль» в Павлодаре: эпизоды 3 и 4</a>
                    <p class="vi-blog__list-text">Сотрудничество между проектом «Доступный Казахстан» и телеканалом Хабар продолжается. В программе «Народный контроль» вышли 2 очередных сюжета про павлодарские объекты.</p>
                    <div class="vi-blog__list-flex">
                        <div><span class="vi-blog__list-link">19 марта 2019</span><span class="vi-blog__list-link">Новости</span></div>
                        <div><a href="#" class="vi-blog__list-link">Подробнее</a></div>
                    </div>
                </div>
                <div class="vi-blog__list-item">
                    <a href="" class="vi-blog__list-title">«Народный контроль» в Павлодаре: эпизоды 1 и 2</a>
                    <p class="vi-blog__list-text">С недавнего времени между проектом «Доступный Казахстан» и телеканалом Хабар началось сотрудничество по формированию контента для программы «Народный контроль». Съемки начались с Павлодара, ведущим определен герой проекта «Доступный Павлодар» Ерлан Курмангазин. В начале февраля съемочная группа проверила 10 социально значимых объектов на доступность для людей с инвалидностью.</p>
                    <div class="vi-blog__list-flex">
                        <div><span class="vi-blog__list-link">19 марта 2019</span><span class="vi-blog__list-link">Новости</span></div>
                        <div><a href="#" class="vi-blog__list-link">Подробнее</a></div>
                    </div>
                </div>
            </div>
            <div class="vi-pag__wrapper">
                <div class="vi-pag">
                    <a href="" class="vi-pag__prev vi-pag__item disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="16" viewBox="0 0 32 16" fill="none">
                            <path d="M31 8H0.999889" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 1L1 8L8 15" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="" class="vi-pag__item vi-set__link --active">1</a>
                    <a href="" class="vi-pag__item vi-set__link">2</a>
                    <a href="" class="vi-pag__item vi-set__link">3</a>
                    <a href="" class="vi-pag__item vi-set__link">...</a>
                    <a href="" class="vi-pag__item vi-set__link">102</a>
                    <a href="" class="vi-pag__next vi-pag__item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="16" viewBox="0 0 32 16" fill="none">
                            <path d="M1 8H31.0001" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M24 1L31 8L24 15" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
            <ViFooter></ViFooter>
        </div>
    </div>
</template>
<script>
    import ViHeader from "./../components/ViHeader";
    import ViFooter from "./../components/ViFooter";
    export default {
        data() {
            return{
                fontSize: 'lrg',
                colorTheme: 'white',
                fontFamily: 'Lato'
            }
        },
        created() {
            this.$nuxt.$on('setFontSize', this.setFontSize);
            this.$nuxt.$on('setColorTheme', this.setColorTheme);
            this.$nuxt.$on('setFontFamily', this.setFontFamily);
        },
      beforeDestroy() {
        this.$nuxt.$off('setFontSize', this.setFontSize);
        this.$nuxt.$off('setColorTheme', this.setColorTheme);
        this.$nuxt.$off('setFontFamily', this.setFontFamily);
      },
      components: {
            ViHeader,
            ViFooter
        },
        methods: {
            setFontSize(fsize) {
                this.fontSize = '' + fsize + '';
            },
            setColorTheme(ctheme) {
                this.colorTheme = '' + ctheme + '';
            },
            setFontFamily(ff) {
                this.fontFamily = '' + ff + '';
            }
        }
    }
</script>

<style lang="scss">
    .vi {
        &-blog {
            &__list {
                margin: 50px 0;
                border-top: 2px solid;
                &-title {
                    font-size: 32px !important;
                    margin: 0;
                    line-height: 40px;
                    font-weight: 700;
                }
                &-link {
                    display: block;
                    line-height: 40px;
                    font-size: 22px !important;
                    & + .vi-blog__list-link {
                        margin: 0 0 0 60px;
                    }
                }
                &-flex {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    > div {
                        display: flex;
                    }
                }
                &-text {
                    line-height: 40px;
                    margin: 20px 0;
                }
                &-item {
                    border-bottom: 2px solid;
                    padding: 50px 0 30px;
                }
            }
        }
    }
</style>