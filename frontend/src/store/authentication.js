import { commit, make } from "vuex-pathify";

export const state = () => ({
  authenticated: false,
  user: null,
  created: false,
  modal: false,
});

export const mutations = make.mutations(state);

export const actions = {
  async loadUser({ commit, rootState }) {
    return new Promise(async (resolve) => {
      const { data: user } = await this.$axios.get("/api/profile", {
        validateStatus(status) {
          return status < 500;
        },
        params: {
          // cityId: rootState.settings.cityId,
        },
      });
      commit("SET_USER", user);
      resolve(user);
    });
  },
  async deAuthenticate({ commit }) {
    commit("SET_USER", null);
  },
  async oauthAuthenticate({ dispatch }, { code, provider }) {
    const { status } = await this.$axios.post("/api/accessToken/oauth", {
      provider,
      code,
    });
    await dispatch("loadUser");
    if (status === 201) {
      commit("SET_CREATED", true);
      await this.$router.push(this.app.localePath('/login?popup=true'));
    } else {
      const redirect =
        this.app.$cookies.get("redirect") || this.app.localePath("/");
      this.app.$cookies.remove("redirect");
      window.location.href = redirect;
    }
  },
  async phoneAuthenticate({ dispatch, commit }, idToken) {
    const { status } = await this.$axios.post("/api/accessToken/phone", {
      idToken,
    });
    await dispatch("loadUser");

    if (status === 201) {
        commit('SET_CREATED', true)
        await this.$router.push(this.app.localePath('/login?popup=true'))
    } else {
        const redirect = this.app.$cookies.get('redirect') || this.app.localePath('/');
        this.app.$cookies.remove('redirect');
        window.location.href = redirect
    }
  },
  async setModalForFirstTime({commit}) {
    commit("SET_MODAL", true);
    await this.$router.push(this.app.localePath('/'))
  },
  async updateProfile({ commit }, user) {
    var result = await Promise.all([this.$axios.put("/api/profile", user)]);
    commit("SET_USER", user);
  },
  async logout({ dispatch, commit }) {
    await this.$axios.delete("/api/token");
    dispatch("deAuthenticate");
    commit("SET_MODAL", false);
    await this.$router.push(this.app.localePath("/"));
  },
};

export const getters = {
  name: (state) =>
    [state.user.lastName, state.user.firstName]
      .filter((item) => !!item)
      .join(" "),
  modal: (state) => state.modal,
};
