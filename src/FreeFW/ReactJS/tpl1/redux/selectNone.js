import { useCallback } from 'react';
import { useDispatch } from 'react-redux';
import {
  [[:FEATURE_UPPER:]]_SELECT_NONE,
} from './constants';

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
