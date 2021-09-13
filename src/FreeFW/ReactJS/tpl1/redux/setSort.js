import { [[:FEATURE_UPPER:]]_SET_SORT } from './constants';

/**
 * Met un tri sur la liste des modèles
 */
export function setSort(sort) {
  return {
    type: [[:FEATURE_UPPER:]]_SET_SORT,
    sort: sort,
  };
}

export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_SET_SORT:
      return {
        ...state,
        sort: action.sort,
      };

    default:
      return state;
  }
}
