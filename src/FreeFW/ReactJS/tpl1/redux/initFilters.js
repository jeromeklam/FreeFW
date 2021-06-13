import { FILTER_MODE_AND, FILTER_OPER_CONTAINS, FILTER_OPER_EQUAL } from 'react-bootstrap-front';
import { [[:FEATURE_UPPER:]]_INIT_FILTERS } from './constants';

export function initFilters(def = false) {
  return {
    type: [[:FEATURE_UPPER:]]_INIT_FILTERS,
    def: def,
  };
}

export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_INIT_FILTERS:
      let filters = state.filters;
      if (action.def) { 
        filters.disableDefaults();
      } else {
        filters.enableDefaults();
      }
      filters.init(FILTER_MODE_AND, FILTER_OPER_CONTAINS);
      return {
        ...state,
        filters: filters,
      };

    default:
      return state;
  }
}
