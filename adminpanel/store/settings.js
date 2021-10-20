import { commit, make } from "vuex-pathify";
import defaults from "lodash/defaults";

export const state = () => ({
  cityId: null,
  userCategory: null,
  citySelected: false,
  mobileMenu: false,
});

export const mutations = make.mutations(state);

export const actions = {
  menuOpen({ commit, state }) {
    if (!state.mobileMenu) {
      commit("SET_MOBILE_MENU", true);
    } else {
      commit("SET_MOBILE_MENU", false);
    }
  },
  async init({ commit, dispatch }) {
    const settings = defaults(this.app.$cookies.get("settings") || {}, {});
    if (!settings.cityId) {
      const { id } = await this.$axios.$get("/api/cities/detect");
      settings.cityId = id;
    }

    commit("SET_CITY_ID", settings.cityId);
    commit("SET_USER_CATEGORY", settings.userCategory);
    dispatch("saveSettings");
  },
  select({ commit, dispatch }, cityId) {
    commit("SET_CITY_ID", Number(cityId));
    dispatch("saveSettings");
  },
  selectUserCategory({ commit, dispatch }, userCategory) {
    commit("SET_USER_CATEGORY", userCategory);
    dispatch("saveSettings");
  },
  saveSettings({ state }) {
    this.app.$cookies.set("settings", state, {
      maxAge: 3600 * 24 * 365,
      path: "/",
    });
  },
  setCitySeleted({ commit, dispatch }) {
    commit("SET_CITY_SELECTED", true);
    dispatch("saveSettings");
  },
};
