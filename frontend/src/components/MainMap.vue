<template>
  <div class="map_wrapper">
    <yandex-map
      class="ymap"
      :settings="settings"
      :coords="cityBounds"
      :zoom.sync="zoom"
      :controls="controls"
      @map-was-initialized="mapWasInitialized"
    />
  </div>
</template>

<script>
import { sync, get, call } from "vuex-pathify";
import queryString from "query-string";
import debounce from "lodash/debounce";
import now from "lodash/now";

export default {
  props: {
    location: {
      type: Array,
    },
  },
  data() {
    return {
      loaded: false,
      autoDetectModal: false,
      detectedLocation: [],
      city: "",
      mapInstance: Object,
      settings: {
        apiKey: "c1050142-1c08-440e-b357-f2743155c1ec",
        lang: "ru_RU",
        coordorder: "latlong",
        version: "2.1",
      },
      controls: [],
      loader: this.$loading.show(),
    };
  },

  methods: {
    setClickedObject(id) {
      this.$store.dispatch("map/clickedObject", id);
    },
    setLocation(loc) {
      if (loc) {
        this.location = loc;
        this.map.setCenter(loc);
      } else {
        this.location = this.cityBounds[0];
        this.map.setBounds(this.cityBounds);
      }
    },

    mapWasInitialized(map) {
      ymaps.geolocation.get().then((result) => {
        let detectedLocation = result.geoObjects.position;
        let city = result.geoObjects
          .get(0)
          .properties.get("name")
          .replace(/ *\([^)]*\) */g, "");
        this.$store.set("detectLocation/cityName", city);
        this.$store.set("detectLocation/position", detectedLocation);
        this.$store.set("detectLocation/loaded", true);
      });

      this.map = map;
      /* this.map.setBounds(this.cityBounds); */
      if (this.coordinatesAndZoom) {
        this.map.setCenter(
          this.coordinatesAndZoom.coordinates,
          this.coordinatesAndZoom.zoom
        );
      } else {
        this.map.setBounds(this.cityBounds);
      }

      const CustomObjectIconLayout = ymaps.templateLayoutFactory.createClass(
        `<div style="border: none; font-size: 20px; display: flex; width: 50px; height: 60px; padding-bottom: 11px; justify-content: center; align-items: center; top: -60px; left: -25px; position:absolute;">
            <svg width="50" height="50" viewBox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg" style="position: absolute; top: 0; left: 0; z-index: 0;">
                <rect opacity="0.3" x="0.734863" y="0.211182" width="54" height="54" rx="13" fill="$[properties.color]"/>
                <rect opacity="0.6" x="4.73486" y="4.21118" width="46" height="46" rx="10" fill="$[properties.color]"/>
                <rect x="7.73486" y="7.21118" width="40" height="40" rx="10" fill="$[properties.fill]"/>
            </svg>
            <div style="background-color: {{properties.background}}; mask: url('/{{properties.icon}}') no-repeat center; -webkit-mask: url('/{{properties.icon}}') no-repeat center; z-index: 1; width: 18px; height: 18px; "></div>
        </div>`
      );
      ymaps.layout.storage.add(
        "custom#objectIconLayout",
        CustomObjectIconLayout
      );

      let MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
        `<div style="position: relative">
          {{number}}
           <svg style="position: absolute; right: -10px; margin-top: auto" width="13" height="100%" viewBox="0 0 13 39" fill="none" xmlns="http://www.w3.org/2000/svg">
              {% if levels.full > 0 && levels.partial === 0 && levels.not_accessible === 0  %}
              <rect y="13" width="13" height="33%" rx="6.5" fill="white"/>
              <circle cx="6.5" cy="19.5" r="3.5" fill="#27AE60"/>
              {% endif %}
              {% if levels.full > 0 && levels.partial > 0 && levels.not_accessible === 0  %}
              <rect y="8" width="13" height="66%" rx="6.5" fill="white"/>
              <circle cx="6.5" cy="15.5" r="3.5" fill="#27AE60"/>
              <circle cx="6.5" cy="25.5" r="3.5" fill="#F2994A"/>
              {% endif %}
              {% if levels.full > 0 && levels.partial > 0 && levels.not_accessible > 0  %}
              <rect width="13" height="99%" rx="6.5" fill="white"/>
              <circle cx="6.5" cy="9.5" r="3.5" fill="#27AE60"/>
              <circle cx="6.5" cy="19.5" r="3.5" fill="#F2994A"/>
              <circle cx="6.5" cy="29.5" r="3.5" fill="#EB5757"/>
              {% endif %}
              {% if levels.full === 0 && levels.partial > 0 && levels.not_accessible > 0  %}
              <rect y="8" width="13" height="66%" rx="6.5" fill="white"/>
              <circle cx="6.5" cy="15.5" r="3.5" fill="#F2994A"/>
              <circle cx="6.5" cy="25.5" r="3.5" fill="#EB5757"/>
              {% endif %}
              {% if levels.full === 0 && levels.partial > 0 && levels.not_accessible === 0  %}
              <rect y="13" width="13" height="33%" rx="6.5" fill="white"/>
              <circle cx="6.5" cy="19.5" r="3.5" fill="#F2994A"/>
              {% endif %}
              {% if levels.full === 0 && levels.partial === 0 && levels.not_accessible > 0  %}
              <rect y="13" width="13" height="33%" rx="6.5" fill="white"/>
              <circle cx="6.5" cy="19.5" r="3.5" fill="#EB5757"/>
              {% endif %}
              {% if levels.full > 0 && levels.partial === 0 && levels.not_accessible > 0  %}
              <rect y="8" width="13" height="66%" rx="6.5" fill="white"/>
              <circle cx="6.5" cy="15.5" r="3.5" fill="#27AE60"/>
              <circle cx="6.5" cy="25.5" r="3.5" fill="#EB5757"/>
              {% endif %}
           </svg>
         </div>`
      );
      let yamap = new ymaps.RemoteObjectManager(this.url, {
        splitRequests: false,
        clusterIcons: [
          {
            href: require("~/assets/icons/clusters/map-cluster.svg"),
            size: [40, 40],
            offset: [-20, -20],
          },
          {
            href: require("~/assets/icons/clusters/map-cluster.svg"),
            size: [60, 60],
            offset: [-30, -30],
          },
        ],
        clusterNumbers: [30],
        clusterIconContentLayout: MyIconContentLayout,
      });
      this.objectManager = yamap;
      map.geoObjects.add(yamap);
      this.loader.hide();
      yamap.objects.events.add(["click"], (e) => {
        this.clickOnObject(e);
      });
    },
    clickOnObject(e) {
      var id = e.get("objectId");
      var allObjects = e.originalEvent.currentTarget._objectsById;
      var allObjectsId = Object.keys(allObjects);
      var currentObject = allObjects[id];
      currentObject.properties.background = "white";
      currentObject.properties.fill = currentObject.properties.color;
      if (allObjectsId.find((el) => el == this.clickedObjectId)) {
        allObjects[this.clickedObjectId].properties.background =
          allObjects[this.clickedObjectId].properties.color;
        allObjects[this.clickedObjectId].properties.fill = "white";
      }
      this.setClickedObject(id);
      const isSame =
        this.$route.name === "objects-id" &&
        this.$route.params.id === e.get("objectId");
      this.$router.push(
        this.localePath({
          name: "objects-id",
          params: { id: e.get("objectId") },
          query: isSame
            ? {
                t: now(),
              }
            : undefined,
        })
      );
    },
    applyFilter: debounce(
      function(val) {
        if (!this.objectManager) {
          return;
        }
        this.objectManager.setUrlTemplate(val);
        this.objectManager.reloadData();
      },
      800,
      { leading: true }
    ),
  },
  watch: {
    url(val) {
      this.applyFilter(val);
    },
    cityBounds(val) {
      if (!this.map) {
        return;
      }
      this.map.setBounds(val);
    },
    coordinates(val) {
      if (!this.map) {
        return;
      }
      if (val) {
        this.map.panTo(val);
      }
    },
    coordinatesAndZoom(val, prev) {
      if (!this.map) {
        return;
      }
      if (!val) {
        return;
      }
      if (val.zoom && val.zoom !== (prev || {}).zoom) {
        this.map.setCenter(val.coordinates, val.zoom);
      } else {
        this.map.panTo(val.coordinates);
      }
    },
    location(val) {
      this.setLocation(val);
    },
  },
  computed: {
    ...sync("map", [
      "coordinates",
      "zoom",
      "coordinatesAndZoom",
      "clickedObjectId",
    ]),
    ...get("map", ["selectedCategories", "accessibilityLevels", "search"]),
    cities: get("cities/list"),
    cityId: get("settings/cityId"),
    userCategory: get("disabilitiesCategorySettings/currentCategory"),
    url() {
      const serializedParams = queryString.stringify(
        {
          categories: this.selectedCategories,
          accessibilityLevels: this.accessibilityLevels,
          disabilitiesCategory: this.userCategory
            ? this.userCategory.category
            : undefined,
        },
        { arrayFormat: "index" }
      );

      return "/api/objects/ymaps?bbox=%b&zoom=%z".concat(
        serializedParams ? `&${serializedParams}` : ""
      );
    },
    cityBounds() {
      const city = this.cities.find((city) => city.id === this.cityId);
      if (city) {
        return city.bounds;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.ymap {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}
</style>
