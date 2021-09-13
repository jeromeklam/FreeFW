import { useCallback } from 'react';
import { useDispatch } from 'react-redux';
import { [[:FEATURE_UPPER:]]_SET_CURRENT } from './constants';

/**
 * Met à jour du modèle courant qu'on traite
 * 0 si on est en création
 * l'id concerné si on est en modif
 */
export function setCurrent(id = -1, mode = 'none') {
  return {
    type: [[:FEATURE_UPPER:]]_SET_CURRENT,
    id: id,
    mode: mode,
  };
}

export function useSetCurrent() {
  const dispatch = useDispatch();
  const boundAction = useCallback((...params) => dispatch(setCurrent(...params)), [dispatch]);
  return { setCurrent: boundAction };
}

/**
 * Reducer
 * 
 * @param {Object} state  Etat courant de la mémoire (store)
 * @param {Object} action Action à réaliser sur cet état avec options
 */
export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_SET_CURRENT:
      if (action.mode === 'off') {
        return {
          ...state,
          currentMode: 'none',
        };
      } else {
        if (action.id > 0) {
          const idx = state.items.SORTEDELEMS.findIndex(elem => elem === action.id);
          return {
            ...state,
            currentId: action.id,
            currentMode: action.mode,
            currentIsFirst: idx === 0,
            currentIsLast: idx === state.items.SORTEDELEMS.length,
          };
        } else {
          return {
            ...state,
            currentMode: action.mode,
          };
        }
      }

    default:
      return state;
  }
}
