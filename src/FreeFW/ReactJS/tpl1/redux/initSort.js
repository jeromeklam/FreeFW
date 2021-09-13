import { [[:FEATURE_UPPER:]]_INIT_SORT } from './constants';

/**
 * Initialisation du tri de la liste
 */
export function initSort() {
  return {
    type: [[:FEATURE_UPPER:]]_INIT_SORT,
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
    case [[:FEATURE_UPPER:]]_INIT_SORT:
      return {
        ...state,
        sort: [[:FEATURE_SORT:]],
      };

    default:
      return state;
  }
}
