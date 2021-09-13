import { useCallback } from 'react';
import { useDispatch } from 'react-redux';
import { [[:FEATURE_UPPER:]]_SELECT_NONE } from './constants';

/**
 * Désélection de tous les modèles sélectionnés dans la liste
 */
export function selectNone() {
  return {
    type: [[:FEATURE_UPPER:]]_SELECT_NONE,
  };
}

export function useSelectNone() {
  const dispatch = useDispatch();
  const boundAction = useCallback((...params) => dispatch(selectNone(...params)), [dispatch]);
  return { selectNone: boundAction };
}

/**
 * Reducer
 * 
 * @param {Object} state  Etat courant de la mémoire (store)
 * @param {Object} action Action à réaliser sur cet état avec options
 */
export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_SELECT_NONE:
      return {
        ...state,
        selected: [],
      };

    default:
      return state;
  }
}
