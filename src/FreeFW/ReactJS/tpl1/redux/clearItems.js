import { [[:FEATURE_UPPER:]]_CLEAR_ITEMS } from './constants';

/**
 * Non utilisé pour le moment
 * Initialise tous les élements de la gestion en cours
 */
export function clearItems() {
  return {
    type: [[:FEATURE_UPPER:]]_CLEAR_ITEMS,
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
    case [[:FEATURE_UPPER:]]_CLEAR_ITEMS:
      return {
        ...state,
        loadMorePending: false,
        loadMoreError: null,
        loadMoreFinish: false,
        items: [],
        currentId: 0,
        currentMode: 'none',
        currentIsFirst: true,
        currentIsLast: true,
        selected: [],
        page_number: 1,
        page_size: process.env.REACT_APP_PAGE_SIZE,
      };

    default:
      return state;
  }
}
