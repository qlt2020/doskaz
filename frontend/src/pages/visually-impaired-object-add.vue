<template>
    <div class="vi" :class="{'--noto': fontFamily == 'Noto', '--black': colorTheme == 'black', '--sm': fontSize == 'sm', '--md': fontSize == 'md','--lrg': fontSize == 'lrg'}">
        <div class="vi-container">
            <ViHeader></ViHeader>
            <div class="vi-add-object">
                <h3 class="vi__title --fcolor">{{ $t('index.addObjectLink')}}</h3>
                <div class="vi-object__tab-link-b">
                    <span class="vi-object__tab-link" :class="{ '--active': activeForm == 1 }" @click="setActiveForm(1)">Простая форма</span>
                    <span class="vi-object__tab-link" :class="{ '--active': activeForm == 2 }" @click="setActiveForm(2)">Средняя форма</span>
                    <span class="vi-object__tab-link" :class="{ '--active': activeForm == 3 }" @click="setActiveForm(3)">Сложная форма</span>
                </div>
                <div class="vi-add-object__link-wrapper">
                    <div class="vi-add-object__link-list">
                        <div class="vi-add-object__link-item">
                            <span class="vi-add-object__link" :class="{ '--active': addObjectStep == 1 }">Общая информация</span>
                        </div>
                        <div class="vi-add-object__link-item">
                            <span class="vi-add-object__link" :class="{ '--active': addObjectStep == 2 }">Парковка</span>
                        </div>
                        <div class="vi-add-object__link-item">
                            <span class="vi-add-object__link" :class="{ '--active': addObjectStep == 3 }">Входная группа</span>
                        </div>
                        <div class="vi-add-object__link-item">
                            <span class="vi-add-object__link" :class="{ '--active': addObjectStep == 4 }">Пути движения по объекту</span>
                        </div>
                    </div>
                    <div class="vi-add-object__link-list">
                        <div class="vi-add-object__link-item">
                            <span class="vi-add-object__link" :class="{ '--active': addObjectStep == 5 }">Зона оказания услуги</span>
                        </div>
                        <div class="vi-add-object__link-item">
                            <span class="vi-add-object__link" :class="{ '--active': addObjectStep == 6 }">Туалет</span>
                        </div>
                        <div class="vi-add-object__link-item">
                            <span class="vi-add-object__link" :class="{ '--active': addObjectStep == 7 }">Навигация</span>
                        </div>
                        <div class="vi-add-object__link-item">
                            <span class="vi-add-object__link" :class="{ '--active': addObjectStep == 8 }">Доступность услуги</span>
                        </div>
                    </div>
                </div>
                <div class="vi-add-object__form">
                    <h4 class="vi-add-object__title">Шаг {{ addObjectStep }} из 8. {{ addObjectTitle[addObjectStep-1] }}</h4>
                    <div class="vi-add-object__form-content" v-if="addObjectStep == 1">
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Наименование</label>
                            </div>
                            <div class="col --long">
                                <div class="input col">
                                    <input type="text" placeholder="Например, суши-бар Saya Sushi">
                                </div>
                                <span class="vi-add-object__info-icon" @click="showInfo">?</span>
                                <div class="vi-add-object__info">
                                    Имеется ли специальное парковочное место для людей с ограниченными возможностями? Выберите статус «Доступно», если на парковке есть специальное парковочное место; «Частично доступно» — если парковка есть, но нет специального парковочного места. Если парковка отсутствует, выберите статус «Не предусмотрено».
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Адрес</label>
                            </div>
                            <div class="col --long">
                                <div class="input col">
                                    <input type="text" placeholder="">
                                </div>
                                <span class="vi-add-object__info-icon" @click="showInfo">?</span>
                                <div class="vi-add-object__info">
                                    Имеется ли специальное парковочное место для людей с ограниченными возможностями? Выберите статус «Доступно», если на парковке есть специальное парковочное место; «Частично доступно» — если парковка есть, но нет специального парковочного места. Если парковка отсутствует, выберите статус «Не предусмотрено».
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Точка на карте</label>
                            </div>
                            <div class="col --long">
                                <div class="map">
                                    <img src="http://placehold.it/1200x500" style="width: 100%;height: auto;display: block;"/>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Категория</label>
                            </div>
                            <div class="col --long">
                                <div class="select col">
                                    <select class="--bcolor">
                                        <option disabled selected>Выберите категорию</option>
                                        <option>Категория1</option>
                                        <option>Категория2</option>
                                        <option>Категория3</option>
                                    </select>
                                </div>
                                <span class="vi-add-object__info-icon" @click="showInfo">?</span>
                                <div class="vi-add-object__info">
                                    Имеется ли специальное парковочное место для людей с ограниченными возможностями? Выберите статус «Доступно», если на парковке есть специальное парковочное место; «Частично доступно» — если парковка есть, но нет специального парковочного места. Если парковка отсутствует, выберите статус «Не предусмотрено».
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Подкатегория</label>
                            </div>
                            <div class="col --long">
                                <div class="select col">
                                    <select class="--bcolor">
                                        <option disabled selected>Выберите категорию</option>
                                        <option>Категория1</option>
                                        <option>Категория2</option>
                                        <option>Категория3</option>
                                    </select>
                                </div>
                                <span class="vi-add-object__info-icon" @click="showInfo">?</span>
                                <div class="vi-add-object__info">
                                    Имеется ли специальное парковочное место для людей с ограниченными возможностями? Выберите статус «Доступно», если на парковке есть специальное парковочное место; «Частично доступно» — если парковка есть, но нет специального парковочного места. Если парковка отсутствует, выберите статус «Не предусмотрено».
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Ссылка на видео <br>(не обязательно)</label>
                            </div>
                            <div class="col --long">
                                <div class="input col">
                                    <input type="text" placeholder="http://">
                                </div>
                                <span class="vi-add-object__info-icon" @click="showInfo">?</span>
                                <div class="vi-add-object__info">
                                    Имеется ли специальное парковочное место для людей с ограниченными возможностями? Выберите статус «Доступно», если на парковке есть специальное парковочное место; «Частично доступно» — если парковка есть, но нет специального парковочного места. Если парковка отсутствует, выберите статус «Не предусмотрено».
                                </div>
                                <div class="vi-add-object__width">
                                    <button type="button" class="vi__button">Добавить ещё видео</button>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Загрузить фото</label>
                            </div>
                            <div class="col --long">
                                <div class="photo-input__wrapper">
                                    <div class="photo-input">+</div>
                                    <div class="photo-input">+</div>
                                    <div class="photo-input">+</div>
                                    <div class="photo-input">+</div>
                                    <div class="photo-input">+</div>
                                </div>
                                <div class="vi-add-object__width">
                                    <button type="button" class="vi__button">Добавить ещё фото</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="vi-add-object__form-content" v-if="addObjectStep == 2">
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Количество парковочных мест (Не менее одного на 25 мест)</label>
                            </div>
                            <div class="col --answer">
                                <div class="select">
                                    <select class="--bcolor">
                                        <option>Да</option>
                                        <option>Нет</option>
                                        <option>Неизвестно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Размер парковки (Не менее 3,66 х 5,38 м)</label>
                            </div>
                            <div class="col --answer">
                                <div class="select">
                                    <select class="--bcolor">
                                        <option>Да</option>
                                        <option>Нет</option>
                                        <option>Неизвестно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line-title">
                            <h4>Обозначение автостоянки для инвалидов</h4>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label class="normal">Количество парковочных мест (Не менее одного на 25 мест)</label>
                            </div>
                            <div class="col --answer">
                                <div class="select">
                                    <select class="--bcolor">
                                        <option>Да</option>
                                        <option>Нет</option>
                                        <option>Неизвестно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label class="normal">Знак на плоскости стоянки</label>
                            </div>
                            <div class="col --answer">
                                <div class="select">
                                    <select class="--bcolor">
                                        <option>Да</option>
                                        <option>Нет</option>
                                        <option>Неизвестно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label class="normal">Знак 5.15 «Место стоянки»</label>
                            </div>
                            <div class="col --answer">
                                <div class="select">
                                    <select class="--bcolor">
                                        <option>Да</option>
                                        <option>Нет</option>
                                        <option>Неизвестно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label class="normal">Знак 7.15 «Инвалиды»</label>
                            </div>
                            <div class="col --answer">
                                <div class="select">
                                    <select class="--bcolor">
                                        <option>Да</option>
                                        <option>Нет</option>
                                        <option>Неизвестно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label class="normal">Стрелка и расстояние</label>
                            </div>
                            <div class="col --answer">
                                <div class="select">
                                    <select class="--bcolor">
                                        <option>Да</option>
                                        <option>Нет</option>
                                        <option>Неизвестно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label class="normal">Разметка на плоскости</label>
                            </div>
                            <div class="col --answer">
                                <div class="select">
                                    <select class="--bcolor">
                                        <option>Да</option>
                                        <option>Нет</option>
                                        <option>Неизвестно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Съезд с тротуара на парковку (рекомендуется ширина 1,5 м)</label>
                            </div>
                            <div class="col --answer">
                                <div class="select">
                                    <select class="--bcolor">
                                        <option>Да</option>
                                        <option>Нет</option>
                                        <option>Неизвестно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="vi-add-object__line">
                            <div class="col">
                                <label>Расстояние до входа в здание (для общественных зданий менее 50 м)</label>
                            </div>
                            <div class="col --answer">
                                <div class="select">
                                    <select class="--bcolor">
                                        <option>Да</option>
                                        <option>Нет</option>
                                        <option>Неизвестно</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <h4 class="vi-add-object__title">Оценка доступности зоны</h4>
                        <div class="vi-add-object__available-list">
                            <div class="vi-add-object__available vi-add-object__line">
                                <div class="col">
                                    Люди, передвигающиеся на кресло-коляске
                                </div>
                                <div class="col --answer">
                                    Частично доступно
                                </div>
                            </div>
                            <div class="vi-add-object__available vi-add-object__line">
                                <div class="col">
                                    Люди с нарушением опорно-двигательного аппарата
                                </div>
                                <div class="col --answer">
                                    Недоступно
                                </div>
                            </div>
                            <div class="vi-add-object__available vi-add-object__line">
                                <div class="col">
                                    Люди с нарушениями зрения
                                </div>
                                <div class="col --answer">
                                    Доступно
                                </div>
                            </div>
                            <div class="vi-add-object__available vi-add-object__line">
                                <div class="col">
                                    Люди с нарушениями слуха
                                </div>
                                <div class="col --answer">
                                    Доступно
                                </div>
                            </div>
                            <div class="vi-add-object__available vi-add-object__line">
                                <div class="col">
                                    Люди с интелектуальной инвалидностью
                                </div>
                                <div class="col --answer">
                                    Доступно
                                </div>
                            </div>
                        </div>
                        <h4 class="vi-add-object__title">Оценка доступности зоны</h4>
                        <p class="vi-add-object__text">Система оценила доступность парковки. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением</p>
                        <textarea class="vi-add-object__textarea" placeholder="Текст сообщения"></textarea>
                    </div>
                    <div class="vi-add-object__step-wrapper">
                        <button type="button" class="vi__button" v-if="addObjectStep == 1">Отмена</button>
                        <button type="button" class="vi__button" v-if="addObjectStep > 1" @click="stepPrev">Назад</button>
                        <button type="button" class="vi__button" @click="stepNext">Вперед</button>
                    </div>
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
                fontFamily: 'Lato',
                activeForm: 3,
                addObjectStep: 1,
                addObjectTitle: [
                    'Общая информация',
                    'Парковка',
                    'Входная группа',
                    'Пути движения по объекту',
                    'Зона оказания услуги',
                    'Туалет',
                    'Навигация',
                    'Доступность услуг'
                ]

            }
        },
        created() {
            this.$nuxt.$on('setFontSize', this.setFontSize);
            this.$nuxt.$on('setColorTheme', this.setColorTheme);
            this.$nuxt.$on('setFontFamily', this.setFontFamily);
        },
      beforeDestroy() {
        this.$nuxt.$off('setFontSize');
        this.$nuxt.$off('setColorTheme');
        this.$nuxt.$off('setFontFamily');
      },
      components: {
            ViHeader,
            ViFooter
        },
        methods: {
            showInfo: function(event){
              event.target.classList.toggle('--active');
            },
            stepNext(){
                if ( this.addObjectStep <= 7 ) {
                   this.addObjectStep++
                }
            },
            stepPrev(){
                this.addObjectStep--;
            },
            setActiveForm(n){
                this.activeForm = n;
            },
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
        &-add-object {
            padding: 34px 0;
            .vi-object__tab-link-b {
                margin: 34px 0;
            }
            .vi__button {
                min-width: 380px;
            }
            &__textarea {
                border: 1px solid;
                padding: 16px 30px 20px;
                min-height: 220px;
                width: 100%;
            }
            &__width {
                max-width: 100%;
                min-width: 100%;
                padding: 40px 0 0;
            }
            &__info {
                min-width: 100%;
                max-width: 100%;
                padding: 17px 0 20px;
                margin: 0 0 -40px;
                font-size: 20px !important;
                line-height: 30px;
                display: none;
                &-icon {
                    width: 60px;
                    height: 60px;
                    border: 1px solid;
                    line-height: 60px;
                    text-align: center;
                    margin: 0 0 0 40px;
                    font-size: 36px !important;
                    font-weight: 700;
                    cursor: pointer;
                    &:hover, &.--active {
                        background: #000000;
                        color: #FFFFFF;
                    }
                    &.--active + .vi-add-object__info {
                        display: block;
                    }
                }
            }
            &__line {
                display: flex;
                position: relative;
                margin: 40px 0 0;
                flex-wrap: wrap;
                &:first-child {
                    margin: 20px 0 0;
                }
                & + .vi-add-object__title {
                    margin: 48px 0 24px;
                }
                &-title {
                    margin: 50px 0 0;
                    h4 {
                        line-height: 40px;
                        font-weight: 700;
                    }
                    & + .vi-add-object__line {
                        margin: 20px 0 0;
                    }
                }
                .col {
                    position: relative;
                    flex-basis: 0;
                    -webkit-box-flex: 1;
                    flex-grow: 1;
                    max-width: 100%;
                    display: flex;
                    flex-wrap: wrap;
                    .map {
                        border: 1px solid;
                    }
                    label {
                        font-weight: 700;
                        line-height: 24px;
                        padding: 18px 0 0;
                        display: block;
                        &.normal {
                            font-weight: 400;
                        }
                    }
                    &.--long {
                        max-width: calc(100% - 300px);
                        min-width: calc(100% - 300px);
                    }
                    &.--info {
                        max-width: 100px;
                        min-width: 100px;
                    }
                    &.--answer {
                        max-width: 280px;
                        min-width: 280px;
                    }
                }
            }
            &__available {
                padding: 18px 0;
                border-bottom: 1px solid;
                display: flex;
                margin: 0;
                &-list {
                    & + .vi-add-object__title {
                        margin: 34px 0 16px;
                    }
                }
                .col {
                    padding: 0 20px 0 0;
                    &.--answer {
                        padding: 0 0 0 20px;
                    }
                }
            }
            &__text {
                line-height: 30px;
                margin: 16px 0 36px;
            }
            &__step {
                &-wrapper {
                    display: flex;
                    justify-content: space-between;
                    border-top: 2px solid;
                    padding: 60px 0 20px;
                }
            }
            &__title {
                font-size: 32px !important;
                line-height: 40px;
                margin: 0 0 34px;
            }
            &__form {
                padding: 30px 0 0;
                &-content {
                    padding: 1px 0 46px;
                }
            }
            &__link {
                line-height: 44px;
                height: 50px;
                padding: 0 20px;
                border: 3px solid transparent;
                display: inline-block;
                position: relative;
                cursor: pointer;
                &-list {
                    max-width: 450px;
                    min-width: 450px;
                    &:first-child {
                        margin: 0 0 0 -20px;
                    }
                }
                &-item {
                    height: 60px;
                    padding: 5px 0;
                }
                &.--active {
                    font-weight: 700;
                    border-color: #000;
                    &:before {
                        content: '';
                        position: absolute;
                        border-top: 6px solid #000000;
                        border-left: 7px solid transparent;
                        border-right: 7px solid transparent;
                        left: 50%;
                        margin: 0 0 0 -7px;
                        bottom: -9px;
                    }
                    &:after {
                        content: '';
                        position: absolute;
                        border-top: 5px solid #FFFFFF;
                        border-left: 6px solid transparent;
                        border-right: 6px solid transparent;
                        left: 50%;
                        margin: 0 0 0 -6px;
                        bottom: -5px;
                        z-index: 1;
                    }
                }
                &-wrapper {
                    display: flex;
                    align-items: flex-end;
                    border-bottom: 2px solid;
                    padding: 0 0 42px;
                }
            }
        }
    }
</style>