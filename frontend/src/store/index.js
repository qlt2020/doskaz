import pathify from "vuex-pathify";

export const plugins = [pathify.plugin];

export const actions = {
  async nuxtServerInit({ dispatch }) {
    await dispatch("settings/init");
    await dispatch("visualImpairedModeSettings/init");
    await Promise.all([
      await dispatch("authentication/loadUser"),
      await dispatch("cities/load")
    ]);
    dispatch("disabilitiesCategorySettings/init");
  }
};
