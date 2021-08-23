<template>
    <div class="user-profile">
        <div class="user-profile__content" v-bind:class="{ '--verified': isVerified }">

            <div class="user-profile__favorites" v-if="currentAward">
                <img :src="require(`~/assets/img/user/award-${currentAward.type}.svg`)" :alt="currentAward.title"/>
            </div>

            <div class="user-profile__icon"
                 v-bind:style="{'background-image': 'url(' + avatar + ')'}">
                <div class="user-profile__icon-edit">
                    <span class="user-profile__icon-link" v-on:click="popupAvatarDefault">{{ $t('profile.updateAvatar') }}</span>
                    <span class="user-profile__icon-link" v-on:click="avatarDelete">{{ $t('profile.deleteAvatar') }}</span>
                </div>
            </div>

            <!--  <div class="user-profile__icon" v-if="avatarLoadedCheck == 1"
                   v-bind:style="{'background-image': 'url(' + require('./../../assets/img/user/av' + defaultAvatarType + '.svg') + ')'}">
                  <div class="user-profile__icon-edit">
                      <span class="user-profile__icon-link" v-on:click="popupAvatarDefault">Обновить фотографию</span>
                      <span class="user-profile__icon-link" v-on:click="avatarDelete">Удалить</span>
                  </div>
              </div>
              <div class="user-profile__icon" v-if="avatarLoadedCheck == 2"
                   v-bind:style="{'background-image': 'url(' + require('./../../assets/img/user/default.svg') + ')'}">
                  <div class="user-profile__icon-edit">
                      <span class="user-profile__icon-link" v-on:click="popupAvatarDefault">Обновить фотографию</span>
                  </div>
              </div>
              <div class="user-profile__icon &#45;&#45;hl" v-if="avatarLoadedCheck == 3"
                   v-bind:style="{'background-image': 'url(' + require('./../../assets/user.png') + ')'}">
                  &lt;!&ndash; загруженная аватарка &ndash;&gt;
                  <div class="user-profile__icon-edit">
                      <span class="user-profile__icon-link">Обновить фотографию</span>
                      <span class="user-profile__icon-link" v-on:click="avatarDelete">Удалить</span>
                  </div>
              </div>
              <div class="user-profile__icon" v-if="avatarLoadedCheck == 4"
                   v-bind:style="{'background-image': 'url(' + require('./../../assets/img/user/default.svg') + ')'}">
                  <div class="user-profile__icon-edit">
                      <span class="user-profile__icon-link">Обновить фотографию</span>
                  </div>
              </div>-->
            <div class="user-profile__mob-info">
                <div class="user-profile__name">
                    <username :value="name" :placeholder="$t('profile.namePlaceholder')"/>
                </div>
                <div class="user-profile__title">
                    <span>{{ profile.status || $t('profile.statusPlaceholder') }}</span>
                </div>
                <div class="user-profile__email">
                    <span>{{ profile.email || $t('profile.emailPlaceholder') }}</span>
                </div>
                <div class="user-profile__phone">
                    <span>{{ profile.phone || $t('profile.phonePlaceholder') }}</span>
                </div>
                <div class="user-profile__edit" v-if="$route.name !== 'profile-edit'">
                    <nuxt-link :to="localePath({name: 'profile-edit'})">
                        <span>{{ $t('profile.editLink') }}</span>
                    </nuxt-link>
                </div>
            </div>
            <div class="popup__wrapper" v-show="popupAvatar && !profile.abilities.includes('avatar_upload')">
                <div class="popup__scroll">
                    <div class="popup__in">
                        <span class="popup__close" v-on:click="popupAvatar = false"></span>
                        <p class="popup__title">{{ $t('profile.avatarChangeHint', {level: 7}) }}</p>
                        <div class="user-profile__avatar-list">
                         <span v-for="(preset, index) in avatarPresets"
                               v-bind:style="{'background-image': 'url(' + preset + ')'}"
                               v-on:click="chooseAvatarPreset(index+1)" class="user-profile__avatar"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="popup__wrapper" v-show="popupAvatar && profile.abilities.includes('avatar_upload')">
                <div class="popup__scroll">
                    <div class="popup__in --lrg">
                        <span class="popup__close" v-on:click="popupAvatar = false"></span>
                        <h3 class="popup__title">{{ $t('profile.avatarChangePopupTitle') }}</h3>
                        <div class="user-profile__avatar-list">
                        <span class="user-profile__avatar">
                            <span class="user-profile__avatar-file">
                                <input type="file" @change="uploadAvatar" accept="image/jpeg, image/png">
                                <img :src="require('~/assets/icons/avatar-img.svg')"/>
                            </span>
                        </span>
                            <span v-for="(preset, index) in avatarPresets"
                                  v-bind:style="{'background-image': 'url(' + preset + ')'}"
                                  v-on:click="chooseAvatarPreset(index+1)" class="user-profile__avatar"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {sync, get, call} from 'vuex-pathify'
    import Username from "../Username";
    import range from 'lodash/range'

    export default {
        components: {Username},
        data() {
            return {
                isAchievement: true,
                isVerified: true,
                userLevel: 7,
                isAvatarLoaded: false,
                defaultAvatarType: 0,
                popupAvatar: false,
                popupAvatarLevel: false
            }
        },
        computed: {
            avatar() {
                return this.profile.avatar || require('~/assets/img/user/default.svg')
            },
            currentPage() {
                return this.$route.path;
            },
            avatarLoadedCheck() {
                var avatarType = 0;

                if (this.userLevel < 7) {
                    this.isAvatarLoaded ? (avatarType = 1) : (avatarType = 2);
                } else {
                    this.isAvatarLoaded ? (avatarType = 3) : (avatarType = 4);
                }

                return avatarType;
            },
            name: get('authentication/name'),
            profile: sync('authentication/user'),
            avatarPresets() {
                return range(1, 7).map(presetNumber => require(`~/assets/img/user/av${presetNumber}.svg`))
            },
            currentAward: get('awards/last'),
            currentAwardIcon() {

            }
        },
        methods: {
            popupAvatarDefault: function () {
                this.popupAvatar = true;
            },
            async avatarDelete() {
                await this.$axios.delete('/api/profile/avatar')
                await this.loadUser();
            },
            async chooseAvatarPreset(presetNumber) {
                await this.$axios.post(`/api/profile/chooseAvatarPreset/${presetNumber}`)
                await this.loadUser();
                this.popupAvatar = false;
            },
            ...call('authentication', ['loadUser']),
            async uploadAvatar(e) {
                await this.$axios.post('/api/profile/avatar', e.target.files[0])
                await this.loadUser();
                this.popupAvatar = false
            }
        }
    };
</script>

<style lang="scss">
    @import "./../../styles/mixins.scss";

    .user-profile {
        background: #f1f8fc;
        padding: 40px 40px 35px;
        width: 100%;
        position: relative;

        &__mob-title {
            font-size: 18px;
            line-height: 20px;
            font-weight: 700;
            display: none;
            margin: 0 0 12px;
            @media all and (max-width: 768px) {
                display: block;
            }
        }

        @media all and (max-width: 1280px) {
            padding: 30px;
        }
        @media all and (max-width: 1024px) {
            padding: 20px;
        }

        &__mob-info {
            @media all and (max-width: 768px) {
                width: calc(100% - 100px);
            }
        }

        &__avatar {
            width: 120px;
            height: 120px;
            background-position: center;
            background-size: 100%;
            margin: 30px 15px 0;
            cursor: pointer;

            &-file {
                width: 100%;
                height: 100%;
                position: relative;
                display: block;
                border-radius: 50%;
                background-color: #F1F9FD;
                overflow: hidden;

                input[type=file] {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    display: block;
                    cursor: pointer;
                    z-index: 2;
                    opacity: 0;
                }

                img {
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    margin: -21px 0 0 -18px;
                    z-index: 1;
                }
            }

            &-list {
                display: flex;
                justify-content: flex-start;
                flex-wrap: wrap;
                margin: -14px -15px 0;
            }
        }

        &__content {
            @media all and (max-width: 768px) {
                display: flex;
                justify-content: baseline;
            }

            &.--verified:before {
                content: "";
                background-image: url("data:image/svg+xml,%3Csvg width='50' height='70' viewBox='0 0 50 70' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0H50V70L25 60L0 70V0Z' fill='%237AB73F'/%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M13 30.6733L25.5712 43L38 30.638L34.5812 27L25.5712 35.936L16.49 27.0353L13 30.6733Z' fill='white'/%3E%3C/svg%3E%0A");
                position: absolute;
                top: 0;
                left: 40px;
                width: 50px;
                height: 70px;
                z-index: 1;
                @media all and (max-width: 1023px) {
                    width: 36px;
                    height: 50px;
                    background-size: 36px;
                    left: 20px;
                }
                @media all and (max-width: 768px) {
                    width: 22px;
                    height: 30px;
                    background-size: 22px;
                }
            }
        }

        &__favorites {
            position: absolute;
            right: 70px;
            top: 40px;
            cursor: pointer;
            font-size: 0;
            z-index: 1;
            width: 60px;
            height: 60px;

            img {
                width: 100%;
                height: auto;
                display: block;
            }

            @media all and (max-width: 1023px) {
                right: 20px;
                top: 20px;
                width: 50px;
                height: 50px;
            }
            @media all and (max-width: 768px) {
                right: auto;
                left: 78px;
                width: 20px;
                height: 20px;
            }
        }

        &__icon {
            position: relative;
            display: block;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            margin: 0 auto;
            background-color: #ffffff;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            overflow: hidden;
            text-align: center;
            font-size: 0;
            @media all and (max-width: 1280px) {
                width: 200px;
                height: 200px;
            }
            @media all and (max-width: 1024px) {
                width: 160px;
                height: 160px;
            }
            @media all and (max-width: 768px) {
                width: 80px;
                height: 80px;
            }

            &-link {
                display: block;
                font-size: 14px;
                line-height: 20px;
                color: #ffffff;
                cursor: pointer;
                transition: opacity 0.3s;

                &:hover {
                    opacity: 0.7;
                }

                & + .user-profile__icon-link {
                    margin: 10px 0 0;
                }
            }

            &-edit {
                padding: 15px 0 0;
                position: absolute;
                width: 250px;
                height: 90px;
                background: rgba(0, 0, 0, 0.8);
                bottom: 0;
                left: 0;
                display: none;
            }

            &.--hl {
                border: 4px solid #9348f2;
            }

            &:hover {
                .user-profile__icon-edit {
                    display: block;
                }
            }
        }

        &__name {
            font-weight: bold;
            font-size: 32px;
            line-height: 40px;
            color: #333333;
            margin-top: 28px;
            @media all and (max-width: 1280px) {
                font-size: 28px;
                line-height: 36px;
            }
            @media all and (max-width: 1024px) {
                font-size: 16px;
                line-height: 20px;
                margin-top: 14px;
            }
        }

        &__title {
            margin-top: 26px;
            font-weight: bold;
            font-size: 16px;
            line-height: 20px;
            color: #333333;
            @media all and (max-width: 1024px) {
                font-size: 12px;
                line-height: 20px;
                font-weight: 400;
                margin-top: 12px;
            }
        }

        &__email,
        &__phone {
            margin-top: 20px;
            font-size: 16px;
            line-height: 20px;
            color: #333333;
            @media all and (max-width: 1024px) {
                font-size: 12px;
                line-height: 20px;
                margin-top: 10px;
            }
        }

        &__edit {
            margin-top: 30px;
            font-size: 14px;
            line-height: 20px;
            color: #5b6067;
            @media all and (max-width: 1024px) {
                font-size: 12px;
                line-height: 20px;
                margin-top: 12px;
            }

            a {
                color: #5b6067;
                transition: opacity 0.3s;

                &:hover {
                    opacity: 0.7;
                }
            }
        }
    }
</style>