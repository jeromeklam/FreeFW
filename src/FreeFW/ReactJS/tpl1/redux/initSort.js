import {
  [[:FEATURE_UPPER:]]_INIT_SORT,
} from './constants';

export function initSort() {
  return {
    type: [[:FEATURE_UPPER:]]_INIT_SORT,
  };
}

export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_INIT_SORT:
      return {
        ...state,
        sort: [{col:"id",way:"up"}],
      };

    default:
      return state;
  }
}
