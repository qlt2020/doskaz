<template>
    <div class="add-object__content">
        <field :error="validationErrors.name">
            <div class="col">
                <label class="add-object__label" for="input_name">{{ $t('objectAdding.objectName') }}</label>
            </div>
            <div class="col --long">
                <div class="input">
                    <input id="input_name" v-model.trim="name" type="text" placeholder="Юбилейный">
                </div>
            </div>
        </field>
        <field :error="validationErrors.otherNames">
            <div class="col">
                <label class="add-object__label">{{ $t('objectAdding.objectOtherNames') }}</label>
            </div>
            <div class="col --long">
                <div class="input">
                    <input type="text" placeholder="KAZKOM, КАЗКОМ" v-model.trim="otherNames">
                </div>
            </div>
        </field>
        <field :error="validationErrors.point">
            <div class="col"><label class="add-object__label --title">{{ $t('objectAdding.mapPoint') }}</label></div>
            <div class="col --long">
                <client-only>
                    <yandex-map
                            @map-was-initialized="mapInitialized"
                            @click="click"
                            :style="{width: '100%', height: '300px'}"
                            :zoom="4"
                            :coords.sync="mapCoords"
                            :controls="['zoomControl']"
                            :settings="{
                    apiKey: 'c1050142-1c08-440e-b357-f2743155c1ec',
                    lang: 'ru_RU',
                    coordorder: 'latlong',
                    version: '2.1'
                }">
                        <ymap-marker :coords="point" v-if="point" marker-id="point"></ymap-marker>
                    </yandex-map>
                </client-only>
            </div>
        </field>
        <field :error="validationErrors.address">
            <div class="col">
                <label class="add-object__label">{{ $t('objectAdding.address') }}</label>
            </div>
            <div class="col --long">
                <div class="input">
                    <input type="text" :placeholder="$t('objectAdding.addressPlaceholder')" v-model.trim="address"/>
                </div>
            </div>
        </field>
        <div class="add-object__line --lrg">
            <div class="col">
                <label class="add-object__label">{{ $t('objectAdding.category') }}</label>
            </div>
            <div class="col --long --info">
                <div class="select">
                    <select v-model="selectedCategory">
                        <option disabled :value="null">{{ $t('objectAdding.emptyCategoryPlaceholder') }}</option>
                        <option :value="category" v-for="category in categories" :key="category.id">{{ category.title
                            }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="add-object__info" :class="{ '--selected': selectedInfo === 'infoCategory'}">
                <span class="add-object__info-icon" @click="toggleSelectInfo('infoCategory')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <path d="M20 40C31.0457 40 40 31.0457 40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40Z" fill="#F1F8FC"/>
                        <path d="M19.3871 15.4286C18.2074 15.4286 17.6175 15.7972 16.9908 16.6452L15 14.3594C15.8111 13.0323 17.47 12 19.6083 12C22.7419 12 24.5852 13.8433 24.5852 16.0922C24.5852 20.5161 20.3825 20.6636 20.6774 22.9493H17.5806C17.0645 19.1521 20.9355 19.4101 20.9355 16.8664C20.8986 16.0922 20.235 15.4286 19.3871 15.4286ZM19.2396 24.2396C20.3088 24.2396 21.0461 25.1613 21.0461 26.0461C21.0461 27.0415 20.3088 28 19.2396 28C18.023 28 17.2857 27.0783 17.2857 26.0461C17.3226 25.1982 18.023 24.2396 19.2396 24.2396Z" fill="#7B95A7"/>
                    </svg>
                </span>
                <div class="add-object__info-text">
                    {{ $t('objectAdding.categoryHelp1') }}<br>{{ $t('objectAdding.categoryHelp2') }}
                </div>
            </div>
        </div>
        <field :error="validationErrors.categoryId">
            <div class="col">
                <label class="add-object__label">{{ $t('objectAdding.subCategory') }}</label>
            </div>
            <div class="col --long --info">
                <div class="select">
                    <select :disabled="!selectedCategory" v-model="categoryId">
                        <option disabled :value="null">{{ $t('objectAdding.emptySubCategoryPlaceholder') }}</option>
                        <template v-if="selectedCategory">
                            <option :value="category.id" v-for="category in selectedCategory.subCategories"
                                    :key="category.id">{{ category.title }}
                            </option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="add-object__info" :class="{ '--selected': selectedInfo === 'infoSubCategory'}">
                <span class="add-object__info-icon" @click="toggleSelectInfo('infoSubCategory')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <path d="M20 40C31.0457 40 40 31.0457 40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40Z" fill="#F1F8FC"/>
                        <path d="M19.3871 15.4286C18.2074 15.4286 17.6175 15.7972 16.9908 16.6452L15 14.3594C15.8111 13.0323 17.47 12 19.6083 12C22.7419 12 24.5852 13.8433 24.5852 16.0922C24.5852 20.5161 20.3825 20.6636 20.6774 22.9493H17.5806C17.0645 19.1521 20.9355 19.4101 20.9355 16.8664C20.8986 16.0922 20.235 15.4286 19.3871 15.4286ZM19.2396 24.2396C20.3088 24.2396 21.0461 25.1613 21.0461 26.0461C21.0461 27.0415 20.3088 28 19.2396 28C18.023 28 17.2857 27.0783 17.2857 26.0461C17.3226 25.1982 18.023 24.2396 19.2396 24.2396Z" fill="#7B95A7"/>
                    </svg>
                </span>
                <div class="add-object__info-text">
                    {{ $t('objectAdding.subCategoryHelp1') }}<br>{{ $t('objectAdding.subCategoryHelp2') }}
                </div>
            </div>
        </field>
        <field :error="validationErrors.description">
            <div class="col">
                <label class="add-object__label">{{ $t('objectAdding.description') }}</label>
            </div>
            <div class="col --long">
                <textarea class="add-object__textarea" :value="description" @input="updateData({path: 'first.description', value: $event.target.value})"></textarea>
            </div>
        </field>
        <field>
            <div class="col">
                <label class="add-object__label">{{ $t('objectAdding.videoLink') }}</label>
            </div>
            <div class="col --long">
                <div class="input" v-for="(photo, index) in videos" :key="index">
                    <input type="text" placeholder="http://" :value="videos[index]" @input="updateData({path: `first.videos.${index}`, value: $event.target.value})"/>
                </div>
                <button type="button" class="add-link" @click="videos = [...videos, '']">{{ $t('objectAdding.addMoreVideos') }}</button>
            </div>
        </field>

        <field :error="validationErrors.photos">
            <div class="col">
                <label class="add-object__label">{{ $t('objectAdding.uploadPhotos') }}</label>
            </div>
            <div class="col --long --info">
                <photo-uploader v-model="photos" @is-uploading="$emit('is-photos-uploading', $event)"/>
            </div>
            <div class="add-object__info" :class="{ '--selected': selectedInfo == 'infoPhoto'}">
                <span class="add-object__info-icon" @click="toggleSelectInfo('infoPhoto')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <path d="M20 40C31.0457 40 40 31.0457 40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40Z" fill="#F1F8FC"/>
                        <path d="M19.3871 15.4286C18.2074 15.4286 17.6175 15.7972 16.9908 16.6452L15 14.3594C15.8111 13.0323 17.47 12 19.6083 12C22.7419 12 24.5852 13.8433 24.5852 16.0922C24.5852 20.5161 20.3825 20.6636 20.6774 22.9493H17.5806C17.0645 19.1521 20.9355 19.4101 20.9355 16.8664C20.8986 16.0922 20.235 15.4286 19.3871 15.4286ZM19.2396 24.2396C20.3088 24.2396 21.0461 25.1613 21.0461 26.0461C21.0461 27.0415 20.3088 28 19.2396 28C18.023 28 17.2857 27.0783 17.2857 26.0461C17.3226 25.1982 18.023 24.2396 19.2396 24.2396Z" fill="#7B95A7"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none" class="black">
                        <path d="M20 40C31.0457 40 40 31.0457 40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40Z" fill="#000000"/>
                        <path d="M19.3871 15.4286C18.2074 15.4286 17.6175 15.7972 16.9908 16.6452L15 14.3594C15.8111 13.0323 17.47 12 19.6083 12C22.7419 12 24.5852 13.8433 24.5852 16.0922C24.5852 20.5161 20.3825 20.6636 20.6774 22.9493H17.5806C17.0645 19.1521 20.9355 19.4101 20.9355 16.8664C20.8986 16.0922 20.235 15.4286 19.3871 15.4286ZM19.2396 24.2396C20.3088 24.2396 21.0461 25.1613 21.0461 26.0461C21.0461 27.0415 20.3088 28 19.2396 28C18.023 28 17.2857 27.0783 17.2857 26.0461C17.3226 25.1982 18.023 24.2396 19.2396 24.2396Z" fill="#FFFFFF"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none" class="white">
                        <path d="M20 40C31.0457 40 40 31.0457 40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40Z" fill="#FFFFFF"/>
                        <path d="M19.3871 15.4286C18.2074 15.4286 17.6175 15.7972 16.9908 16.6452L15 14.3594C15.8111 13.0323 17.47 12 19.6083 12C22.7419 12 24.5852 13.8433 24.5852 16.0922C24.5852 20.5161 20.3825 20.6636 20.6774 22.9493H17.5806C17.0645 19.1521 20.9355 19.4101 20.9355 16.8664C20.8986 16.0922 20.235 15.4286 19.3871 15.4286ZM19.2396 24.2396C20.3088 24.2396 21.0461 25.1613 21.0461 26.0461C21.0461 27.0415 20.3088 28 19.2396 28C18.023 28 17.2857 27.0783 17.2857 26.0461C17.3226 25.1982 18.023 24.2396 19.2396 24.2396Z" fill="#000000"/>
                    </svg>
                </span>
                <div class="add-object__info-text">
                    {{ $t('objectAdding.photosHelp1') }}<br>{{ $t('objectAdding.photosHelp2') }}
                </div>
            </div>
        </field>
    </div>
</template>

<script>
    import Field from "./Field";
    import PhotoUploader from "./PhotoUploader";
    import {sync, get, call} from 'vuex-pathify'

    export default {
        name: "FirstStep",
        components: {PhotoUploader, Field},
        props: [
            'value'
        ],
        data() {
            return {
                coords: null,
                selectedCategory: null,
                mapCoords: [47.74887674893552, 67.04712168264118],
                zoom: null,
                selectedInfo: false
            }
        },
        methods: {
            mapInitialized(map) {
                this.map = map;
                this.map.setBounds(this.selectedCity.bounds)
            },
            click(e) {
                this.point = e.get('coords');
                this.mapCoords = this.point;
                if(this.map && !this.zoom) {
                    this.zoom = 12
                    this.map.setCenter(this.point, 12)
                }
                ymaps.geocode(this.point).then(res => {
                    const result = res.geoObjects.get(0);
                    if(result.getThoroughfare()) {
                        this.address = [result.getThoroughfare(), result.getPremiseNumber()].filter(item => !!item).join(', ')
                    } else {
                        this.address = ''
                    }
                });
            },
            toggleSelectInfo(infoCat) {
                this.selectedInfo = (this.selectedInfo ===  infoCat) ? false : infoCat;
            },
            ...call('objectAdding', [
                'updateData'
            ])
        },
        watch: {
            selectedCategory() {
                this.categoryId = null
            }
        },
        computed: {
            ...sync('objectAdding/data@first', [
                'name',
                'address',
                'point',
                'categoryId',
                'videos',
                'photos',
                'otherNames',
                'description'
            ]),
            ...get('objectAdding', [
                'categories'
            ]),
            selectedCity: get('cities/selectedCity'),
            errors: get('objectAdding/validationErrors'),
            validationErrors() {
                return this.errors.first || {}
            }
        }
    }
</script>

<style>
    .input:not(:first-of-type) {
        margin-top: 20px;
    }
</style>