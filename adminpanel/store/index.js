import pathify from 'vuex-pathify'

export const plugins = [pathify.plugin];

export const actions = {
  async nuxtServerInit({ dispatch }) {
    await dispatch("settings/init");
  }

}