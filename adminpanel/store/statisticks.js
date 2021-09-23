import {make} from "vuex-pathify";

export const state = () => ({
  complaintsStat: [],
  complaintsCount: null,
  complaintsFilter: {
    params: {
      year_id: 2021
    }
  },
  complaintsFilteredStat: [],
  objectsStat: [],
  objectsStatPrimary: [],
  objectsStatTable: [],
  objectsCount: {},
  objectsStatFilter: {
    params: {}
  },
  feedbackStat: [],
  feedbackCount: null,
  feedbackFilter: {
    params: {
      year_id: 2020
    }
  },
  feedbackFilteredStat: [],
  usersStat: [],
  usersAge: [],
  usersAgeFilter: null,
  usersList: [],
  usersFilter: {
    params: {
    }
  },
  complaintsListFilter: {
    params: {
        city_id: '',
        dateFrom: '',
        dateTo: ''
    }
  },
  complaintsList: [],
  group: {
    options:[
      {value: 'kidsTotal', title: 'Семьи с детьми до семи лет', usersValue: 'withChild'},
      {value: 'movementTotal', title: 'Люди передвигающиеся на кресло коляске', usersValue: 'movement'},
      {value: 'babyCarriageTotal', title: 'Люди с детскими колясками', usersValue: 'babyCarriage'},
      {value: 'visionTotal', title: 'Люди с инвалидностью по зрению', usersValue: 'vision'},
      {value: 'limbTotal', title: 'Люди с нарушениями опорно-двигательного аппарата', usersValue: 'limb'},
      {value: 'temporalTotal', title: 'Временно травмированные люди', usersValue: 'temporal'},
      {value: 'missingLimbsTotal', title: 'Люди с отсутствующими конечностями', usersValue: 'missingLimbs'},
      {value: 'pregnantTotal', title: 'Беременные женщины', usersValue: 'pregnant'},
      {value: 'agedPeopleTotal', title: 'Пожилые люди', usersValue: 'agedPeople'},
      {value: 'hearingTotal', title: 'Люди с инвалидностью по слуху', usersValue: 'hearing'},
      {value: 'intellectualTotal', title: 'Люди с интеллектуальной инвалидностью', usersValue: 'intellectual'},
    ],
  },
  propertyList: {
    hearingTotal: 0,
    hearing_full_accessible: 0,
    hearing_not_accessible: 0,
    hearing_partial_accessible: 0,

    intellectualTotal: 0,
    intellectual_full_accessible: 0,
    intellectual_not_accessible: 0,
    intellectual_partial_accessible: 0,

    kidsTotal: 0,
    kids_full_accessible: 0,
    kids_not_accessible: 0,
    kids_partial_accessible: 0,

    limbTotal: 0,
    limb_full_accessible: 0,
    limb_not_accessible: 0,
    limb_partial_accessible: 0,

    movementTotal: 0,
    movement_full_accessible: 0,
    movement_not_accessible: 0,
    movement_partial_accessible: 0,

    visionTotal: 0,
    vision_full_accessible: 0,
    vision_not_accessible: 0,
    vision_partial_accessible: 0,

    agedPeopleTotal: 0,
    agedPeople_full_accessible: 0,
    agedPeople_not_accessible: 0,
    agedPeople_partial_accessible: 0,

    babyCarriageTotal: 0,
    babyCarriage_full_accessible: 0,
    babyCarriage_not_accessible: 0,
    babyCarriage_partial_accessible: 0,

    temporalTotal: 0,
    temporal_full_accessible: 0,
    temporal_not_accessible: 0,
    temporal_partial_accessible: 0,

    pregnantTotal: 0,
    pregnant_full_accessible: 0,
    pregnant_not_accessible: 0,
    pregnant_partial_accessible: 0,

    missingLimbsTotal: 0,
    missingLimbs_full_accessible: 0,
    missingLimbs_not_accessible: 0,
    missingLimbs_partial_accessible: 0
  },
})

export const mutations = {
  ...make.mutations(state),
  filterComplaints: (state, { field, value }) => {
    state.complaintsFilter.params[field] = value
  },
  feedbackFilter: (state, { field, value }) => {
    state.feedbackFilter.params[field] = value
  },
  filterUsersList: (state, { field, value }) => {
    state.usersFilter.params[field] = value
  },
  filterUsersListReset: state => {
    state.usersFilter.params = {}
  },
  filterComplaintsListReset: state => {
    state.complaintsListFilter.params.city_id = ''
    state.complaintsListFilter.params.dateFrom = ''
    state.complaintsListFilter.params.dateTo = ''
  },
  filterComplaintsList: (state, { field, value }) => {
      state.complaintsListFilter.params[field] = value
  },
  filterObjectsStat: (state, { field, value }) => {
    state.objectsStatFilter.params[field] = value
  },
  filterObjectsReset: state => {
    state.objectsStatFilter.params = {}
  },
}

export const getters = {
  complaints: state => state.complaintsStat,
  objectsStat: state => state.objectsStat,
  feedbackStat: state => state.feedbackStat,
  complaintsCount: state => state.complaintsCount,
  feedbackCount: state => state.feedbackCount,
  getObjectsStat: state => state.objectsStat,
  getObjectsStatTable: state => state.objectsStatTable
}

function getCountToCategory(data, id, value, getCount) {
  return Object.values(
    data.reduce((a, c) => {
      (
        a[c[id]] ||
        (a[c[id]] = {
          id: c[id],
          name: c[value],
          count: 0
        })
      ).count += c[getCount]
      return a
    }, {})
  )
}

function totalCount(data, count) {
  return data.reduce(
      (prev, curr) => {
        prev[count] += curr[count]
        return prev
      },
      {
        count: 0,
      }
    )
}

export const actions = {
  async loadComplaints({commit}) {
    const {data} = await this.$axios.get('/api/complaints/statistic', {})

    const count = totalCount(data.result, 'count')
    commit('SET_COMPLAINTS_COUNT', count)

    commit('SET_COMPLAINTS_STAT', data)
  },

  async getComplaintsFilter({commit, state}) {
    const {data} = await this.$axios.get('/api/complaints/statistic', state.complaintsFilter)
    const complaintsCount = getCountToCategory(data.result, 'month', 'year', 'count')

    commit('SET_COMPLAINTS_FILTERED_STAT', complaintsCount)
  },

  async loadFeedback({commit}) {
      const {data} = await this.$axios.get('/api/admin/feedback/statistic', {})
      const count = totalCount(data.result, 'count')
      commit('SET_FEEDBACK_COUNT', count)
      commit('SET_FEEDBACK_STAT', data)
  },

  async getFeedbackFilter ({commit, state}) {
    const {data} = await this.$axios.get('/api/admin/feedback/statistic', state.feedbackFilter)
    const feedbackStat = getCountToCategory(data.result, 'month', 'year', 'count')
    commit('SET_FEEDBACK_FILTERED_STAT', feedbackStat)
  },

  async getObjectsStat({commit, state}) {
    const listProperty = state.propertyList

    const {data} = await this.$axios.get('/api/objects/statistic', state.objectsStatFilter);
    commit('SET_OBJECTS_STAT_PRIMARY', data)
    const objectsByCategory = data.reduce((a, c) => {
      const newObject = (
            a[c['main_category_id']] ||
            (a[c['main_category_id']] = {
                category_id: c['main_category_id'],
                category_title: c['main_category_title'],
                ...listProperty
            }
          )
        )
      Object.keys(listProperty).forEach(property => {
        const replacement = property.substring(property.indexOf('_') +1);
        if (property === `agedPeople_${replacement}` ||
            property === `pregnant_${replacement}` ||
            property === `missingLimbs_${replacement}` ||
            property === `temporal_${replacement}`) {
          newObject[property] +=c[`limb_${replacement}`]
        } else if (property === `babyCarriage_${replacement}`) {
          newObject[property] +=c[`movement_${replacement}`]
        } 
        else if (property === 'hearingTotal') {
          newObject[property] +=
          +(c['hearing_full_accessible'] + c['hearing_not_accessible'] + c['hearing_partial_accessible']);
        } else if (property === 'intellectualTotal') {
          newObject[property] +=
          +(c['intellectual_full_accessible'] + c['intellectual_not_accessible'] + c['intellectual_partial_accessible']);
        } else if (property === 'kidsTotal') {
          newObject[property] +=
          +(c['kids_full_accessible'] + c['kids_not_accessible'] + c['kids_partial_accessible']);
        } else if (property === 'visionTotal') {
          newObject[property] +=
          +(c['vision_full_accessible'] + c['vision_not_accessible'] + c['vision_partial_accessible']);
        } 
        else if
                (property === 'agedPeopleTotal' ||
                property === 'pregnantTotal' ||
                property === 'missingLimbsTotal' ||
                property === 'temporalTotal' ||
                property === 'limbTotal') {
          newObject[property] +=
          +(c['limb_full_accessible'] + c['limb_not_accessible'] + c['limb_partial_accessible']);
        } else if 
                (property === 'babyCarriageTotal' || property === 'movementTotal') {
          newObject[property] += +(c['movement_full_accessible'] + c['movement_not_accessible'] + c['movement_partial_accessible']);
        }
        else {
          newObject[property] +=c[property];
        }
      })
      return a
      },[])
      .filter(objects => objects != null || objects!='empty' || objects!='undefined')

    function sumAccesibles(typeAccesibles, item) {
      let result = item[`hearing${typeAccesibles}`]+
                    item[`intellectual${typeAccesibles}`]+
                    item[`kids${typeAccesibles}`]+
                    item[`limb${typeAccesibles}`]+
                    item[`movement${typeAccesibles}`]+
                    item[`vision${typeAccesibles}`]
      return result
    }
    let fullAccessible = 0;
    let partialAccessible = 0;
    let notAccessible = 0;

    objectsByCategory.forEach(item => {
      fullAccessible += sumAccesibles('_full_accessible', item)
      partialAccessible += sumAccesibles('_partial_accessible', item)
      notAccessible += sumAccesibles('_not_accessible', item)
    })

    commit('SET_OBJECTS_COUNT', {fullAccessible, partialAccessible, notAccessible})
    commit('SET_OBJECTS_STAT', objectsByCategory)


  },

  async getObjectsStatTable({commit, state, dispatch}) {
    await dispatch('getObjectsStat')

    const listProperty = state.propertyList
    const data = state.objectsStatPrimary
    const objectsByCategory = [...state.objectsStat]
    const totalObjects = JSON.parse(JSON.stringify(state.objectsStat))

    const objectsBySubategory = data.reduce((a, c) => {
      const newObject = (
            a[c['category_id']] ||
            (a[c['category_id']] = {
                sub_category_id: c['category_id'],
                sub_category_title: c['category_title'],
                main_category_id: c['main_category_id'],
                main_category_title: c['main_category_title'],
                ...listProperty
            }
          )
        )

        Object.keys(listProperty).forEach(property => {
          const replacement = property.substr(property.indexOf('_') +1);
          if (property === `agedPeople_${replacement}` ||
              property === `pregnant_${replacement}` ||
              property === `missingLimbs_${replacement}` ||
              property === `temporal_${replacement}`) {
            newObject[property] +=c[`limb_${replacement}`]
          } else if (property === `babyCarriage_${replacement}`) {
            newObject[property] +=c[`movement_${replacement}`]
          } 
          else if (property === 'hearingTotal') {
            newObject[property] +=
            +(c['hearing_full_accessible'] + c['hearing_not_accessible'] + c['hearing_partial_accessible']);
          } else if (property === 'intellectualTotal') {
            newObject[property] +=
            +(c['intellectual_full_accessible'] + c['intellectual_not_accessible'] + c['intellectual_partial_accessible']);
          } else if (property === 'kidsTotal') {
            newObject[property] +=
            +(c['kids_full_accessible'] + c['kids_not_accessible'] + c['kids_partial_accessible']);
          } else if (property === 'visionTotal') {
            newObject[property] +=
            +(c['vision_full_accessible'] + c['vision_not_accessible'] + c['vision_partial_accessible']);
          } 
          else if
                  (property === 'agedPeopleTotal' ||
                  property === 'pregnantTotal' ||
                  property === 'missingLimbsTotal' ||
                  property === 'temporalTotal' ||
                  property === 'limbTotal') {
            newObject[property] +=
            +(c['limb_full_accessible'] + c['limb_not_accessible'] + c['limb_partial_accessible']);
          } else if 
                  (property === 'babyCarriageTotal' || property === 'movementTotal') {
            newObject[property] += +(c['movement_full_accessible'] + c['movement_not_accessible'] + c['movement_partial_accessible']);
          }
          else {
            newObject[property] +=c[property];
          }
        })
        
        return a
    },[]).filter(objects => objects != null || objects!='empty' || objects!='undefined')

    objectsByCategory.map(category => {
      return category.subcategory = [...objectsBySubategory.filter(subcategory => {
        return category.category_id === subcategory.main_category_id
      })]
    })

    const total = totalObjects.reduce((prev, curr)=> {
      Object.keys(listProperty).forEach(property => {
        prev[property] +=curr[property]
      })
      return prev
    }, {...listProperty, category_id: 0, category_title: 'Общее'})
    objectsByCategory.unshift(total)

    commit('SET_OBJECTS_STAT_TABLE', objectsByCategory)
  },

  async getUsersStat({commit, state}) {
    if (state.usersStat.length) {
      return;
    }

    return this.$axios.get('/api/dashboard/users/statistics').then(res => {
      commit('SET_USERS_STAT', res.data)
    })
  },

  async getUsersAge({commit, state}) {
    return this.$axios.get('/api/dashboard/users/age-statistics', {
      params: {
        category: state.usersAgeFilter
      }}).then(res => {
          commit('SET_USERS_AGE', res.data[0])
        })
      },

  async usersList({commit, state}) {
    const {data} = await this.$axios.get('/api/admin/users/statistics', state.usersFilter)

    data.forEach(function(v){ delete v.categories.justView});
    commit('SET_USERS_LIST', data)
  },

  async complaintsList({commit, state}) {
      const {data} = await this.$axios.get('/api/complaints/allstatistic', state.complaintsListFilter)

      commit('SET_COMPLAINTS_LIST', data)
  }
}
