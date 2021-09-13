import { useCallback } from 'react';
import { useDispatch, useSelector, shallowEqual } from 'react-redux';
import {
  [[:FEATURE_UPPER:]]_SET_NEXT_BEGIN,
  [[:FEATURE_UPPER:]]_SET_NEXT_SUCCESS,
  [[:FEATURE_UPPER:]]_SET_NEXT_FAILURE,
  [[:FEATURE_UPPER:]]_SET_NEXT_DISMISS_ERROR,
} from './constants';
import { loadMore } from './loadMore';

/**
 * On va au suivant.
 * Si on a atteint la fin on tente de charger les suivants
 */
export function setNext() {
  return (dispatch, getState) => {
    dispatch({
      type: [[:FEATURE_UPPER:]]_SET_NEXT_BEGIN,
    });
    let elemId = getState().[[:FEATURE_CAMEL:]].currentId;
    let idx = getState().[[:FEATURE_CAMEL:]].items.SORTEDELEMS.findIndex(elem => elem === elemId);
    if ((idx+1) < getState().[[:FEATURE_CAMEL:]].items.SORTEDELEMS.length) {
      dispatch({
        type: [[:FEATURE_UPPER:]]_SET_NEXT_SUCCESS,
      });
    } else {
      const promise = Promise.all([dispatch(loadMore())]);
      return promise.then(
        res => {
          dispatch({
            type: [[:FEATURE_UPPER:]]_SET_NEXT_SUCCESS,
            data: res,
          });
        },
        err => {
          dispatch({
            type: [[:FEATURE_UPPER:]]_SET_NEXT_FAILURE,
            data: { error: err },
          });
        },
      );
    }
  };
}

/**
 * Annulation
 */
export function dismissSetNextError() {
  return {
    type: [[:FEATURE_UPPER:]]_SET_NEXT_DISMISS_ERROR,
  };
}

/**
 * Hook associé
 */
export function useSetNext() {
  const dispatch = useDispatch();

  const { setNextPending, setNextError } = useSelector(
    state => ({
      setNextPending: state.[[:FEATURE_CAMEL:]].setNextPending,
      setNextError: state.[[:FEATURE_CAMEL:]].setNextError,
    }),
    shallowEqual,
  );

  const boundAction = useCallback(
    (...args) => {
      return dispatch(setNext(...args));
    },
    [dispatch],
  );

  const boundDismissError = useCallback(() => {
    return dispatch(dismissSetNextError());
  }, [dispatch]);

  return {
    setNext: boundAction,
    setNextPending,
    setNextError,
    dismissSetNextError: boundDismissError,
  };
}

/**
 * Reducer
 *
 * On positionne le suivant si on l'objet courant n'est pas le dernier
 *
 * @param {Object} state  Etat courant de la mémoire (store)
 * @param {Object} action Action à réaliser sur cet état avec options
 */
export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_SET_NEXT_BEGIN:
      // Just after a request is sent
      return {
        ...state,
        setNextPending: true,
        setNextError: null,
      };

    case [[:FEATURE_UPPER:]]_SET_NEXT_SUCCESS:
      // The request is success
      if (state.currentId !== 0) {
        let elemId = state.currentId;
        let idx = state.items.SORTEDELEMS.findIndex(elem => elem === elemId);
        if ((idx+1) < state.items.SORTEDELEMS.length) {
          idx++;
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

    case [[:FEATURE_UPPER:]]_SET_NEXT_FAILURE:
      // The request is failed
      return {
        ...state,
        setNextPending: false,
        setNextError: action.data.error,
      };

    case [[:FEATURE_UPPER:]]_SET_NEXT_DISMISS_ERROR:
      // Dismiss the request failure error
      return {
        ...state,
        setNextError: null,
      };

    default:
      return state;
  }
}
