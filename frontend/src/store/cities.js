import { make } from "vuex-pathify";

export const state = () => ({
  country: {
    id: 1,
    name: "Весь Казахстан",
    bounds: [
      [33.29544794707255, 35.41992187499996],
      [59.3785168273564, 92.94433593749997],
    ],
  },
  list: [],
  opened: true,
  openedList: false,
});

export const mutations = make.mutations(state);

export const getters = {
  selectedCity: ({ list: cities }, getters, { settings: { cityId } }) =>
    cities.find((city) => city.id === cityId),
  country: (state) => {
    return state.country;
  },
  opened: (state) => {
    return state.opened;
  },
};

export const actions = {
  async load({ commit, getters }) {
    const cities = await this.$axios.$get("/api/cities");
    cities.unshift(getters.country);
    console.log(cities);
    commit("SET_LIST", cities);
  },
  ...make.mutations(state),
};
