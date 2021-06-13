import {
  [[:FEATURE_UPPER:]]_SET_FILTERS,
} from './constants';

export function setFilters(filters) {
  return {
    type: [[:FEATURE_UPPER:]]_SET_FILTERS,
    filters: filters,
  };
}

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
