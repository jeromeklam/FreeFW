import { useCallback } from 'react';
import { useDispatch } from 'react-redux';
import { normalizedObjectModeler } from 'jsonapi-front';
import { [[:FEATURE_UPPER:]]_SELECT_ALL } from './constants';


/**
 * Selection de tous les modèles de la liste
 */
export function selectAll() {
  return {
    type: [[:FEATURE_UPPER:]]_SELECT_ALL,
  };
}

export function useSelectAll() {
  const dispatch = useDispatch();
  const boundAction = useCallback((...params) => dispatch(selectAll(...params)), [dispatch]);
  return { selectAll: boundAction };
}

/**
 * Reducer
 * 
 * @param {Object} state  Etat courant de la mémoire (store)
 * @param {Object} action Action à réaliser sur cet état avec options
 */
export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_SELECT_ALL:
      let selected = [];
      if (state.items.[[:FEATURE_MODEL:]]) {
        const items = normalizedObjectModeler(state.items, '[[:FEATURE_MODEL:]]');
        items.forEach(elem => selected.push(elem.id));
      }
      return {
        ...state,
        selected: selected,
      };

    default:
      return state;
  }
}
