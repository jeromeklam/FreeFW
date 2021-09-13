import { jsonApiNormalizer, getJsonApi, normalizedObjectModeler, objectToQueryString } from 'jsonapi-front';
import {
  [[:FEATURE_UPPER:]]_UPDATE_ONE_BEGIN,
  [[:FEATURE_UPPER:]]_UPDATE_ONE_SUCCESS,
  [[:FEATURE_UPPER:]]_UPDATE_ONE_FAILURE,
  [[:FEATURE_UPPER:]]_UPDATE_ONE_DISMISS_ERROR,
} from './constants';
import { freeAssoApi, propagateModel } from '../../../common';

/**
 * Enregistrement d'un modèle
 */
export function updateOne(id, obj = {}, propagate = true) {
  return (dispatch, getState) => {
    dispatch({
      type: [[:FEATURE_UPPER:]]_UPDATE_ONE_BEGIN,
    });
    const promise = new Promise((resolve, reject) => {
      const params = {
        include: getState().[[:FEATURE_CAMEL:]].include
      }
      const addUrl = objectToQueryString(params);
      const japiObj = getJsonApi(obj, '[[:FEATURE_MODEL:]]');
      const doRequest = freeAssoApi.put('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SNAKE:]]/' + id + addUrl, japiObj);
      doRequest.then(
        result => {
          const object = jsonApiNormalizer(result.data);
          const item   = normalizedObjectModeler(object, '[[:FEATURE_MODEL:]]', id, { eager: true } );
          if (propagate) {
            dispatch(propagateModel('[[:FEATURE_MODEL:]]', result));
          }
          dispatch({
            type: [[:FEATURE_UPPER:]]_UPDATE_ONE_SUCCESS,
            data: result,
            item: item,
          });
          resolve(item);
        },
        err => {
          dispatch({
            type: [[:FEATURE_UPPER:]]_UPDATE_ONE_FAILURE,
            data: { error: err },
          });
          reject(err);
        },
      );
    });
    return promise;
  };
}

export function dismissUpdateOneError() {
  return {
    type: [[:FEATURE_UPPER:]]_UPDATE_ONE_DISMISS_ERROR,
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
    case [[:FEATURE_UPPER:]]_UPDATE_ONE_BEGIN:
      // Just after a request is sent
      return {
        ...state,
        updateOnePending: true,
        updateOneError: null,
      };

    case [[:FEATURE_UPPER:]]_UPDATE_ONE_SUCCESS:
      // The request is success
      return {
        ...state,
        updateOnePending: false,
        updateOneError: null,
      };

    case [[:FEATURE_UPPER:]]_UPDATE_ONE_FAILURE:
      // The request is failed
      let error = null;
      if (action.data.error && action.data.error.response) {
        error = jsonApiNormalizer(action.data.error.response);
      }
      return {
        ...state,
        updateOnePending: false,
        updateOneError: error,
      };

    case [[:FEATURE_UPPER:]]_UPDATE_ONE_DISMISS_ERROR:
      // Dismiss the request failure error
      return {
        ...state,
        updateOneError: null,
      };

    default:
      return state;
  }
}
