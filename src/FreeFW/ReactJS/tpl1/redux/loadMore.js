import { freeAssoApi } from '../../../common';
import { jsonApiNormalizer, objectToQueryString, getNewNormalizedObject } from 'jsonapi-front';
import {
  [[:FEATURE_UPPER:]]_LOAD_MORE_INIT,
  [[:FEATURE_UPPER:]]_LOAD_MORE_BEGIN,
  [[:FEATURE_UPPER:]]_LOAD_MORE_SUCCESS,
  [[:FEATURE_UPPER:]]_LOAD_MORE_FAILURE,
  [[:FEATURE_UPPER:]]_LOAD_MORE_DISMISS_ERROR,
} from './constants';

export function loadMore(args = false, reload = false) {
  return (dispatch, getState) => {
    const loaded =  getState().[[:FEATURE_LOWER:]].loadMoreFinish;
    if (!loaded || reload) {
      if (reload) {
        dispatch({
          type: [[:FEATURE_UPPER:]]_LOAD_MORE_INIT,
        });
      } else {
        dispatch({
          type: [[:FEATURE_UPPER:]]_LOAD_MORE_BEGIN,
        });
      }
      const promise = new Promise((resolve, reject) => {
        let filters = getState().[[:FEATURE_LOWER:]].filters.asJsonApiObject()
        let params = {
          page: { number: getState().[[:FEATURE_LOWER:]].page_number, size: getState().[[:FEATURE_LOWER:]].page_size },
          ...filters
        };
        let sort = '';
        getState().[[:FEATURE_LOWER:]].sort.forEach(elt => {
          let add = elt.col;
          if (elt.way === 'down') {
            add = '-' + add;
          }
          if (sort === '') {
            sort = add;
          } else {
            sort = sort + ',' + add;
          }
        });
        if (sort !== '') {
          params.sort = sort;
        }
        const addUrl = objectToQueryString(params);
        const doRequest = freeAssoApi.get('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SERVICE:]]' + addUrl, {});
        doRequest.then(
          (res) => {
            dispatch({
              type: [[:FEATURE_UPPER:]]_LOAD_MORE_SUCCESS,
              data: res,
            });
            resolve(res);
          },
          // Use rejectHandler as the second argument so that render errors won't be caught.
          (err) => {
            dispatch({
              type: [[:FEATURE_UPPER:]]_LOAD_MORE_FAILURE,
              data: { error: err },
            });
            reject(err);
          },
        );
      });
      return promise;
    }
  };
}

export function dismissLoadMoreError() {
  return {
    type: [[:FEATURE_UPPER:]]_LOAD_MORE_DISMISS_ERROR,
  };
}

export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_LOAD_MORE_INIT:
      // Just after a request is sent
      return {
        ...state,
        loadMorePending: true,
        loadMoreError: null,
        loadMoreFinish: false,
        selected: [],
        items: getNewNormalizedObject('[[:FEATURE_MODEL:]]'),
        page_number: 1,
        page_size: process.env.REACT_APP_PAGE_SIZE,
      };

    case [[:FEATURE_UPPER:]]_LOAD_MORE_BEGIN:
      // Just after a request is sent
      return {
        ...state,
        loadMorePending: true,
        loadMoreError: null,
      };

    case [[:FEATURE_UPPER:]]_LOAD_MORE_SUCCESS:
      // The request is success
      let list = {};
      let nbre = 0;
      let result = false;
      if (action.data && action.data.data) {
        result = action.data.data;
      }
      if (result.data) {
        nbre = result.data.length;
      }
      if (nbre > 0) {
        if (state.items) {
          list = jsonApiNormalizer(result, state.items);
        } else {
          list = jsonApiNormalizer(result);
        }
      } else {
        list = state.items;
      }
      return {
        ...state,
        loadMorePending: false,
        loadMoreError: null,
        loadMoreFinish: (nbre < state.page_size),
        items: list,
        page_number: state.page_number+1
      };

    case [[:FEATURE_UPPER:]]_LOAD_MORE_FAILURE:
      // The request is failed
      return {
        ...state,
        loadMorePending: false,
        loadMoreError: action.data.error,
      };

    case [[:FEATURE_UPPER:]]_LOAD_MORE_DISMISS_ERROR:
      // Dismiss the request failure error
      return {
        ...state,
        loadMoreError: null,
      };

    default:
      return state;
  }
}
