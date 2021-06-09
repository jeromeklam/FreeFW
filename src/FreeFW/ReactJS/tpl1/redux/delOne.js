import { jsonApiNormalizer } from 'jsonapi-front';
import { freeAssoApi } from '../../../common';
import {
  [[:FEATURE_UPPER:]]_DEL_ONE_BEGIN,
  [[:FEATURE_UPPER:]]_DEL_ONE_SUCCESS,
  [[:FEATURE_UPPER:]]_DEL_ONE_FAILURE,
  [[:FEATURE_UPPER:]]_DEL_ONE_DISMISS_ERROR,
} from './constants';

export function delOne(id) {
  return (dispatch) => { 
    dispatch({
      type: [[:FEATURE_UPPER:]]_DEL_ONE_BEGIN,
    });

    const promise = new Promise((resolve, reject) => {
      const doRequest = freeAssoApi.delete('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SERVICE:]]/' + id);
      doRequest.then(
        (res) => {
          dispatch({
            type: [[:FEATURE_UPPER:]]_DEL_ONE_SUCCESS,
            data: res,
          });
          resolve(res);
        },
        (err) => {
          dispatch({
            type: [[:FEATURE_UPPER:]]_DEL_ONE_FAILURE,
            data: { error: err },
          });
          reject(err);
        },
      );
    });

    return promise;
  };
}

export function dismissDelOneError() {
  return {
    type: [[:FEATURE_UPPER:]]_DEL_ONE_DISMISS_ERROR,
  };
}

export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_DEL_ONE_BEGIN:
      // Just after a request is sent
      return {
        ...state,
        delOnePending: true,
        delOneError: null,
      };

    case [[:FEATURE_UPPER:]]_DEL_ONE_SUCCESS:
      // The request is success
      return {
        ...state,
        delOnePending: false,
        delOneError: null,
      };

    case [[:FEATURE_UPPER:]]_DEL_ONE_FAILURE:
      // The request is failed
      let error = null;
      if (action.data.error && action.data.error.response) {
        error = jsonApiNormalizer(action.data.error.response);
      }
      return {
        ...state,
        delOnePending: false,
        delOneError: error,
      };

    case [[:FEATURE_UPPER:]]_DEL_ONE_DISMISS_ERROR:
      // Dismiss the request failure error
      return {
        ...state,
        delOneError: null,
      };

    default:
      return state;
  }
}
