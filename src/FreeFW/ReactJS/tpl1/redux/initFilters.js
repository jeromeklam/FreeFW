import { Filter, FILTER_MODE_AND, FILTER_OPER_EQUAL } from 'react-bootstrap-front';
import { [[:FEATURE_UPPER:]]_INIT_FILTERS } from './constants';

/**
 * Filtres par défaut
 *
 * @param {Bool} enable
 */
export const getInitFilters = (enable = true) => {
  let initFilters = new Filter();
  //initFilters.addFilter('<field>', '<value>', FILTER_OPER_EQUAL, false, true, enable);
  initFilters.setMode(FILTER_MODE_AND); 
  return initFilters;
}

/**
 * Initialisation des filtres de la liste
 */
export function initFilters(def = false) {
  return {
    type: [[:FEATURE_UPPER:]]_INIT_FILTERS,
    def: def,
  };
}

/**
 * Reducer
 * 
 * @param {Object} state  Etat courant de la mémoire (store)
 * @param {Object} action Action à réaliser sur cet état avec options
 */
export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_INIT_FILTERS:
      let initialFilters = getInitFilters();
      initialFilters.setMode(FILTER_MODE_AND);
      if (action.def) { 
        initialFilters.disableDefaults();
      } else {
        initialFilters.enableDefaults();
      }
      return {
        ...state,
        filters: initialFilters,
      };

    default:
      return state;
  }
}
