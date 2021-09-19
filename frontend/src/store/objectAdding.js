import { make, Payload } from "vuex-pathify";
import flatMapDeep from "lodash/flatMapDeep";
import mapValues from "lodash/mapValues";
import set from "lodash/set";

export const state = () => ({
  isLoading: false,
  attributesList: {},
  categories: [],
  zonesTabs: [
    {
      key: "parking",
      title: "Парковка",
      group: "parking",
      commentLabel: "Комментарий к зоне парковки",
      commentPlaceholder:
        "Система оценила доступность парковки. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением",
    },
    {
      key: "entrance1",
      title: "Входная группа",
      group: "entrance",
      commentLabel: "Комментарий к зоне входной группы",
      commentPlaceholder:
        "Система оценила доступность входной группы. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением",
    },
    {
      key: "entrance2",
      title: "Входная группа",
      group: "entrance",
      commentLabel: "Комментарий к зоне входной группы",
      commentPlaceholder:
        "Система оценила доступность входной группы. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением",
    },
    {
      key: "entrance3",
      title: "Входная группа",
      group: "entrance",
      commentLabel: "Комментарий к зоне входной группы",
      commentPlaceholder:
        "Система оценила доступность входной группы. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением",
    },
    {
      key: "movement",
      title: "Пути движения по объекту",
      group: "movement",
      commentLabel: "Комментарий к зоне путей движения по объекту",
      commentPlaceholder:
        "Система оценила доступность путей движения по объекту. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением",
    },
    {
      key: "service",
      title: "Зона оказания услуги",
      group: "service",
      commentLabel: "Комментарий к зоне оказания услуги",
      commentPlaceholder:
        "Система оценила доступность зоны оказания услуги. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением",
    },
    {
      key: "toilet",
      title: "Туалет",
      group: "toilet",
      commentLabel: "Комментарий к зоне туалета",
      commentPlaceholder:
        "Система оценила доступность зоны туалета. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением",
    },
    {
      key: "navigation",
      title: "Навигация",
      group: "navigation",
      commentLabel: "Комментарий к навигации",
      commentPlaceholder:
        "Система оценила доступность навигации. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением",
    },
    {
      key: "serviceAccessibility",
      title: "Доступность услуги",
      group: "serviceAccessibility",
      commentLabel: "Комментарий к навигации",
      commentPlaceholder:
        "Система оценила доступность услуги. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением",
    },
    {
      key: "kidsAccessibility",
      title: "",
      group: "kidsAccessibility",
      commentLabel: "Комментарий к навигации",
      commentPlaceholder:
        "Система оценила доступность услуги. Если вы не согласны с оценкой, вы можете оставить комментарий с пояснением",
    },
  ],
  smallFormZonesTabs: [
    { key: "parking", title: "Территория объекта", group: "parking" },
    { key: "entrance1", title: "Входная группа", group: "entrance" },
    { key: "movement", title: "Пути движения по объекту", group: "movement" },
    { key: "service", title: "Зона оказания услуг", group: "service" },
    { key: "toilet", title: "Туалет", group: "toilet" },
    { key: "navigation", title: "Навигация", group: "navigation" },
    {
      key: "serviceAccessibility",
      title: "Доступность услуги",
      group: "serviceAccessibility",
    },
    { key: "kidsAccessibility", title: "", group: "kidsAccessibility" },
  ],
  forms: [
    { key: "small", title: "Базовая форма" },
    { key: "middle", title: "Форма для волонтеров" },
    { key: "full", title: "Форма для экспертов" },
  ],
  errors: [],
  data: {
    form: "small",
    first: {
      categoryId: null,
      videos: [""],
      photos: [],
      otherNames: "",
    },
    parking: {
      attributes: {},
      comment: null,
    },
    entrance1: {
      attributes: {},
      comment: null,
    },
    entrance2: null,
    entrance3: null,
    movement: {
      attributes: {},
      comment: null,
    },
    service: {
      attributes: {},
      comment: null,
    },
    toilet: {
      attributes: {},
      comment: null,
    },
    navigation: {
      attributes: {},
      comment: null,
    },
    serviceAccessibility: {
      attributes: {},
      comment: null,
    },
    kidsAccessibility: {
      attributes: {},
      comment: null,
    },
  },
});

export const mutations = make.mutations(state);

export const getters = {
  formZones: (state) => state.attributesList[state.data.form],
  formAttributesByZone: (state, { formZones }) => {
    return mapValues(formZones, (zone) =>
      flatMapDeep(zone, (group) => {
        return group.subGroups.map((subGroup) => {
          return subGroup.attributes.map((attribute) => ({
            ...attribute,
            key: `attribute${attribute.key}`,
          }));
        });
      })
    );
  },
  zonesTabsAvailable: ({ data, zonesTabs, smallFormZonesTabs }) =>
    (data.form === "small" ? smallFormZonesTabs : zonesTabs).filter(
      (z) => !!data[z.key]
    ),
  validationErrors: ({ errors }) => {
    const violations = {};
    errors.forEach((e) => set(violations, e.property, e.message));
    return violations;
  },
};

export const actions = {
  async init({ commit, dispatch }) {
    const [{ data: attributes }, { data: categories }] = await Promise.all([
      this.$axios.get("/api/objects/attributes"),
      this.$axios.get("/api/objectCategories"),
    ]);
    commit("SET_ERRORS", []);
    commit("SET_IS_LOADING", false);
    commit("SET_ATTRIBUTES_LIST", attributes);
    commit("SET_CATEGORIES", categories);
    commit("SET_DATA", state().data);
    dispatch("initializeForm");
  },
  initializeForm({
    state: { data },
    getters: { formAttributesByZone, zonesTabsAvailable },
    commit,
  }) {
    zonesTabsAvailable.forEach(({ key, group }) => {
      const defaults = {};

      formAttributesByZone[group].forEach((attribute) => {
        if (!data[key].attributes[attribute.key]) {
          defaults[attribute.key] = "unknown";

          commit(
            "SET_DATA",
            new Payload("", `${key}.attributes`, {
              ...defaults,
              ...data[key].attributes,
            })
          );
        }
      });
    });
  },
  changeForm({ commit, dispatch }, form) {
    commit("SET_DATA", new Payload("", "form", form));
    dispatch("initializeForm");
  },
  duplicateEntranceStep({ commit, state: { data } }) {
    const { entrance1, entrance2, entrance3 } = data;
    if (!entrance2) {
      return commit(
        "SET_DATA",
        new Payload("", "entrance2", {
          comment: entrance1.comment,
          attributes: {
            ...entrance1.attributes,
          },
        })
      );
    }
    if (!entrance3) {
      return commit(
        "SET_DATA",
        new Payload("", "entrance3", {
          comment: entrance2.comment,
          attributes: {
            ...entrance2.attributes,
          },
        })
      );
    }
  },
  updateData({ commit }, { path, value }) {
    commit("SET_DATA", new Payload("", path, value));
  },
  async validate({ commit, state: { data } }) {
    commit("SET_IS_LOADING", true);
    const resp = await this.$axios.post(
      "/api/objects/requests/validate",
      data,
      {
        validateStatus: (status) => status <= 400,
      }
    );
    if (resp.status === 400) {
      commit("SET_ERRORS", resp.data.errors);
    } else {
      commit("SET_ERRORS", []);
    }
    commit("SET_IS_LOADING", false);
  },
  async submit({ state: { data }, commit }) {
    commit("SET_IS_LOADING", true);
    commit("SET_ERRORS", []);
    const resp = await this.$axios.post("/api/objects/requests", data, {
      validateStatus: (status) => status <= 400,
    });
    if (resp.status === 400) {
      commit("SET_ERRORS", resp.data.errors);
      commit("SET_IS_LOADING", false);
      window.scrollTo({ top: 0 });
    } else {
      this.app.$cookies.set("objectAdditionSubmitted", true, {
        maxAge: 60,
        path: "/",
      });
      return this.$router.push(this.app.localePath("/"));
    }
  },
};
