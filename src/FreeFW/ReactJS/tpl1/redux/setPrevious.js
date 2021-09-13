import { useCallback } from 'react';
import { useDispatch } from 'react-redux';
import { [[:FEATURE_UPPER:]]_SET_PREVIOUS } from './constants';

/**
 * On ne fait qu'appeler le reducer pour aller au précédent
 */
export function setPrevious() {
  return {
    type: [[:FEATURE_UPPER:]]_SET_PREVIOUS,
  };
}

/**
 * Hook associé
 */
export function useSetPrevious() {
  const dispatch = useDispatch();
  const boundAction = useCallback((...params) => dispatch(setPrevious(...params)), [dispatch]);
  return { setPrevious: boundAction };
}

/**
 * Reducer
 * 
 * Si l'objet courant n'est pas le premier on va à l'objet précédent
 * 
 * @param {Object} state  Etat courant de la mémoire (store)
 * @param {Object} action Action à réaliser sur cet état avec options
 */
export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_SET_PREVIOUS:
      if (state.currentId !== 0) {
        let elemId = state.currentId;
        let idx = state.items.SORTEDELEMS.findIndex(elem => elem === elemId);
        if (idx > 0) {
          idx--;
        }
        elemId = state.items.SORTEDELEMS[idx];
        return {
          ...state,
          currentId: elemId,
          currentIsFirst: idx === 0,
          currentIsLast: idx === state.items.SORTEDELEMS.length,
        };
      } else {
        return {
          ...state,
          currentId: 0,
          currentMode: 'none',
          currentIsFirst: true,
          currentIsLast: true,
        };
      }

    default:
      return state;
  }
}
