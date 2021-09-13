import { [[:FEATURE_UPPER:]]_UPDATE_QUICK_SEARCH } from './constants';
import { FILTER_MODE_AND, FILTER_OPER_LIKE, FILTER_SEARCH_QUICK } from 'react-bootstrap-front';

/**
 * Modification de la recherche rapide en haut de la liste
 */
export function updateQuickSearch(value) {
  return {
    type: [[:FEATURE_UPPER:]]_UPDATE_QUICK_SEARCH,
    value: value,
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
    case [[:FEATURE_UPPER:]]_UPDATE_QUICK_SEARCH:
      let filters = state.filters;
      filters.init(FILTER_MODE_AND, FILTER_OPER_LIKE);
      if (action.value !== '') {
        filters.setSearch(FILTER_SEARCH_QUICK);
[[:FEATURE_SEARCH:]]
      }
      return {
        ...state,
        filters: filters,
      };

    default:
      return state;
  }
}
