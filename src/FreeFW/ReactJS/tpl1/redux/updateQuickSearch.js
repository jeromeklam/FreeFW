import {
  [[:FEATURE_UPPER:]]_UPDATE_QUICK_SEARCH,
} from './constants';
import {
  Filter,
  FILTER_MODE_AND,
  FILTER_OPER_LIKE,
  FILTER_OPER_EQUAL,
  FILTER_SEARCH_QUICK
} from 'react-bootstrap-front';

export function updateQuickSearch(value) {
  return {
    type: [[:FEATURE_UPPER:]]_UPDATE_QUICK_SEARCH,
    value: value,
  };
}

export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_UPDATE_QUICK_SEARCH:
      let filters = state.filters;
      filters.init(FILTER_MODE_AND, FILTER_OPER_LIKE);
      if (action.value !== '') {
        filters.setSearch(FILTER_SEARCH_QUICK)
        filters.addFilter('id', action.value);
      }
      return {
        ...state,
        filters: filters
      };

    default:
      return state;
  }
}
