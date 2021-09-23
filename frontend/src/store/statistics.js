import {make} from "vuex-pathify";

export const state = () => ({
  objectsStatTable: [],
  objectsStatFilter: {
    params: {}
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
  filterObjectsStat: (state, { field, value }) => {
    state.objectsStatFilter.params[field] = value
  },
  filterObjectsReset: state => {
    state.objectsStatFilter.params = {}
  }
}

export const getters = {
  getObjectsStatTable: state => state.objectsStatTable
}

export const actions = {

  async getObjectsStatTable({commit, state, dispatch}) {
    const listProperty = state.propertyList

    const {data} = await this.$axios.get('/api/objects/statistic', state.objectsStatFilter);
    // commit('SET_OBJECTS_STAT_PRIMARY', data)

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
      },[]).filter(objects => objects != null || objects!='empty' || objects!='undefined')

    const totalObjects = objectsByCategory

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
}