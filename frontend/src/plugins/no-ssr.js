import Vue from 'vue'
import VuejsDatePicker from 'vuejs-datepicker'
import maskedInput from 'vue-masked-input'
import YmapPlugin, { loadYmap } from 'vue-yandex-maps'
import VueGallery from 'vue-gallery'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import {IMaskDirective} from 'vue-imask';
import VueHotkey from 'v-hotkey'

const plugin = {
    install(Vue) {
        Vue.component('VuejsDatePicker', VuejsDatePicker);
        Vue.component('MaskedInput', maskedInput);
        Vue.component('gallery', VueGallery)
        Vue.directive('imask', IMaskDirective)
    }
};

Vue.use(plugin);
Vue.use(Loading);
Vue.use(YmapPlugin)
Vue.use(VueHotkey);