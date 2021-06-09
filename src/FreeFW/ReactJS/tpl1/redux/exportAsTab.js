import { useEffect, useCallback } from 'react';
import { useDispatch, useSelector, shallowEqual } from 'react-redux';
import { getNewJsonApi, objectToQueryString } from 'jsonapi-front';
import mime from 'mime-types';
import {
  [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_BEGIN,
  [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_SUCCESS,
  [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_FAILURE,
  [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_DISMISS_ERROR,
} from './constants';
import { downloadBlob } from '../../ui';
import { freeAssoApi } from '../../../common';

export function exportAsTab(mode = 'all') {
  return (dispatch, getState) => {
    dispatch({
      type: [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_BEGIN,
    });
    const promise = new Promise((resolve, reject) => {
      let filters = getState().[[:FEATURE_LOWER:]].filters.asJsonApiObject();
      if (mode === 'selection') {
        if (!filters['filter']['and']) {
          filters['filter']['and'] = {id: ''};
        }
        filters['filter']['and']['id'] = {in: getState().[[:FEATURE_LOWER:]].selected.join(',')};
      }
      let params = {
        page: { number: 1, size: 99999999 },
        ...filters,
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
      const options = getNewJsonApi('FreeFW_PrintOptions', null, {
        prt_name: '[[:FEATURE_LOWER:]]',
        prt_type: 'XLSX',
      });
      const doRequest = freeAssoApi.post('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SERVICE:]]/export' + addUrl, options, {
        responseType: 'arraybuffer',
      });
      doRequest.then(
        res => {
          let resRes = true;
          if (res.headers && res.headers['content-type'].indexOf('json') > 0) {
            resRes = false;
          } else {
            try {
              const type = res.headers['content-type'] || 'application/octet-stream';
              const extension = mime.extension(type);
              downloadBlob(res.data, type, '[[:FEATURE_LOWER:]].' + extension);
            } catch (ex) {

            }
          }
          dispatch({
            type: [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_SUCCESS,
            data: res,
          });
          resolve(resRes);
        },
        err => {
          dispatch({
            type: [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_FAILURE,
            data: { error: err },
          });
          reject(err);
        },
      );
    });
    return promise;
  };
}

export function dismissExportAsTabError() {
  return {
    type: [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_DISMISS_ERROR,
  };
}

export function useExportAsTab() {
  const dispatch = useDispatch();

  const { exportAllPending, exportAllError } = useSelector(
    state => ({
      exportAllPending: state.[[:FEATURE_LOWER:]].exportAllPending,
      exportAllError: state.[[:FEATURE_LOWER:]].exportAllError,
    }),
    shallowEqual,
  );

  const boundAction = useCallback(
    (...args) => {
      return dispatch(exportAsTab(...args));
    },
    [dispatch],
  );

  const boundDismissError = useCallback(() => {
    return dispatch(dismissExportAsTabError());
  }, [dispatch]);

  return {
    export: boundAction,
    exportAllPending,
    exportAllError,
    dismissExportError: boundDismissError,
  };
}

export function reducer(state, action) {
  switch (action.type) {
    case [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_BEGIN:
      // Just after a request is sent
      return {
        ...state,
        exportPending: true,
        exportError: null,
      };

    case [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_SUCCESS:
      // The request is success
      return {
        ...state,
        exportPending: false,
        exportError: null,
      };

    case [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_FAILURE:
      // The request is failed
      return {
        ...state,
        exportPending: false,
        exportError: action.data.error,
      };

    case [[:FEATURE_UPPER:]]_EXPORT_AS_TAB_DISMISS_ERROR:
      // Dismiss the request failure error
      return {
        ...state,
        exportError: null,
      };

    default:
      return state;
  }
}
