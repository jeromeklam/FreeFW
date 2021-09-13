import { [[:FEATURE_UPPER:]]_SET_FILTERS } from './constants';

/**
 * Met un filtre sur liste des modèles
 */
export function setFilters(filters) {
  return {
    type: [[:FEATURE_UPPER:]]_SET_FILTERS,
    filters: filters,
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
    case [[:FEATURE_UPPER:]]_SET_FILTERS:
      return {
        ...state,
        filters: action.filters,
      };

    default:
      return state;
  }
}
