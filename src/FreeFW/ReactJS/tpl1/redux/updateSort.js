import { [[:FEATURE_UPPER:]]_UPDATE_SORT } from './constants';

/**
 * Mise à jour du tri de la liste
 */
export function updateSort(col, way, pos = 99) {
  return {
    type: [[:FEATURE_UPPER:]]_UPDATE_SORT,
    col: col,
    way: way,
    pos: pos,
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
    case [[:FEATURE_UPPER:]]_UPDATE_SORT:
      let sort = state.sort;
      let nSort = [];
      sort.forEach(elt => {
        if (elt.col !== action.col) {
          nSort.push(elt);
        }
      });
      if (action.way === 'up' || action.way === 'down') {
        const elt = {
          col: action.col,
          way: action.way,
        };
        if (action.pos >= nSort.length) {
          nSort.push(elt);
        } else {
          nSort = nSort.splice(action.pos - 1, 0, elt);
        }
      }
      return {
        ...state,
        sort: nSort,
      };

    default:
      return state;
  }
}
