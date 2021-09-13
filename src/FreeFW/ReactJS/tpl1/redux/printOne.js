import { useCallback } from 'react';
import { useDispatch, useSelector, shallowEqual } from 'react-redux';
import { getNewJsonApi } from 'jsonapi-front';
import mime from 'mime-types';
import {
  [[:FEATURE_UPPER:]]_PRINT_ONE_BEGIN,
  [[:FEATURE_UPPER:]]_PRINT_ONE_SUCCESS,
  [[:FEATURE_UPPER:]]_PRINT_ONE_FAILURE,
  [[:FEATURE_UPPER:]]_PRINT_ONE_DISMISS_ERROR,
} from './constants';
import { downloadBlob } from '../../ui';
import { default as freeAssoApi } from '../../../common/api';

/**
 * Impression d'un modèle
 */
export function printOne(id, ediId) {
  return (dispatch) => {
    dispatch({
      type: [[:FEATURE_UPPER:]]_PRINT_ONE_BEGIN,
    });
    const promise = new Promise((resolve, reject) => {
      const options = getNewJsonApi('FreeFW_PrintOptions', id, {prt_name : '[[:FEATURE_CAMEL:]]', prt_type : 'EDITION', edi_id: ediId});
      const doRequest = freeAssoApi.post('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SNAKE:]]/print/' + id, options, {responseType: 'arraybuffer'});
      doRequest.then(
        (res) => {
          const type = res.headers['content-type'] || 'application/octet-stream';
          const extension = mime.extension(type);
          downloadBlob(res.data, type, '[[:FEATURE_CAMEL:]]_' + id + '.' + extension);
          dispatch({
            type: [[:FEATURE_UPPER:]]_PRINT_ONE_SUCCESS,
            data: res,
          });
          resolve(res);
        },
        (err) => {
          dispatch({
            type: [[:FEATURE_UPPER:]]_PRINT_ONE_FAILURE,
            data: { error: err },
          });
          reject(err);
        },
      );
    });
    return promise;
  };
}

export function dismissPrintOneError() {
  return {
    type: [[:FEATURE_UPPER:]]_PRINT_ONE_DISMISS_ERROR,
  };
}

export function usePrintOne() {
  const dispatch = useDispatch();

  const { printOnePending, printOneError } = useSelector(
    state => ({
      printOnePending: state.[[:FEATURE_CAMEL:]].printOnePending,
      printOneError: state.[[:FEATURE_CAMEL:]].printOneError,
    }),
    shallowEqual,
  );

  const boundAction = useCallback((...args) => {
    return dispatch(printOne(...args));
  }, [dispatch]);

  const boundDismissError = useCallback(() => {
    return dispatch(dismissPrintOneError());
  }, [dispatch]);

  return {
    printOne: boundAction,
    printOnePending,
    printOneError,
    dismissPrintOneError: boundDismissError,
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
    case [[:FEATURE_UPPER:]]_PRINT_ONE_BEGIN:
      // Just after a request is sent
      return {
        ...state,
        printOnePending: true,
        printOneError: null,
      };

    case [[:FEATURE_UPPER:]]_PRINT_ONE_SUCCESS:
      // The request is success
      return {
        ...state,
        printOnePending: false,
        printOneError: null,
      };

    case [[:FEATURE_UPPER:]]_PRINT_ONE_FAILURE:
      // The request is failed
      return {
        ...state,
        printOnePending: false,
        printOneError: action.data.error,
      };

    case [[:FEATURE_UPPER:]]_PRINT_ONE_DISMISS_ERROR:
      // Dismiss the request failure error
      return {
        ...state,
        printOneError: null,
      };

    default:
      return state;
  }
}
