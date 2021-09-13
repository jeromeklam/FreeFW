import { useCallback } from 'react';
import { useDispatch } from 'react-redux';
import { jsonApiNormalizer, normalizedObjectUpdate, normalizedObjectRemove } from 'jsonapi-front';
import { [[:FEATURE_UPPER:]]_PROPAGATE } from './constants';

/**
 * Propage la modification d'un modèle
 * 
 * @param {Object}  data       Les données
 * @param {Boolean} ignoreAdd  Ne pas ajouter si non trouvé
 * @param {Boolean} remove     Suppression
 */
export function propagate(data, ignoreAdd = false, remove = false) {
  return {
    type: [[:FEATURE_UPPER:]]_PROPAGATE,
    data: data,
    ignoreAdd: ignoreAdd,
    remove: remove,
  };
}

/**
 * Hook associé
 */
export function usePropagate() {
  const dispatch = useDispatch();
  const boundAction = useCallback((...params) => dispatch(propagate(...params)), [dispatch]);
  return { propagate: boundAction };
}

/**
 * Reducer
 * 
 * @param {Object} state  Etat courant de la mémoire (store)
 * @param {Object} action Action à réaliser sur cet état avec options
 */
export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_PROPAGATE:
      let object = jsonApiNormalizer(action.data.data);
      let myItems = state.items;
      let news = myItems;
      if (action.remove && action.remove === true) {
        news = normalizedObjectRemove(myItems, state.objectName, object);
      } else {
        news = normalizedObjectUpdate(myItems, state.objectName, object, action.ignoreAdd || false);
      }
      return {
        ...state,
        updateOneError: null,
        items: news,
      };

    default:
      return state;
  }
}
