import { make } from "vuex-pathify";
import objectZonesDetailed from "~/objectZonesDetailed";

export const state = () => ({
  item: {},
  id: null
});

export const mutations = make.mutations(state);

export const getters = {
  videos: ({ item }) =>
    item.videos.map(video => ({
      youtube: video.videoId,
      poster: video.thumbnail,
      type: "text/html",
      href: video.url
    })),
  attributes: ({ item: { attributes } }, getters, rootState) => {
    const attributesDescription =
      rootState.objectAdding.attributesList[attributes.form];
    return objectZonesDetailed
      .filter(zone => attributes.zones[zone.key])
      .map(zone => {
        return {
          group: zone.group,
          key: zone.key,
          groups: attributesDescription[zone.group].map(group => {
            return {
              title: group.title,
              subGroups: group.subGroups.map(subGroup => {
                return {
                  title: subGroup.title,
                  attributes: subGroup.attributes.map(attribute => {
                    return {
                      key: `attribute${attribute.key}`,
                      title: `${attribute.title || ""} ${attribute.subTitle ||
                        ""}`.trim(),
                      value:
                        attributes.zones[zone.key][`attribute${attribute.key}`]
                    };
                  })
                };
              })
            };
          })
        };
      });
  },
  exportLink: ({ id }) => `/api/objects/${id}/pdf`
};

export const actions = {
  async load({ commit, rootGetters, getters }, id) {
    const object = await this.$axios.$get(`/api/objects/${id}`, {
      params: {
        disabilitiesCategory:
          rootGetters["disabilitiesCategorySettings/currentCategoryValue"]
      }
    });
    commit("SET_ID", id);
    commit("SET_ITEM", object);
  },
  async reload({ dispatch, state }) {
    await dispatch("load", state.id);
  },
  async submitReview({ dispatch, state }, reviewText) {
    await this.$axios.post(`/api/objects/${state.id}/reviews`, {
      text: reviewText
    });
    await dispatch("reload");
  },
  async submitVerification({ dispatch, state: { id } }, status) {
    await this.$axios.post(`/api/objects/${id}/verification/${status}`);
    await dispatch("reload");
  }
};
