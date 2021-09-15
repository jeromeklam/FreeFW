import { jsonApiNormalizer, normalizedObjectModeler, objectToQueryString } from 'jsonapi-front';
import { axios } from 'axios';
import { freeAssoApi } from '../../common';

let [[:FEATURE_CAMEL:]]CancelToken = null;

export const search[[:FEATURE_CAMEL_FULL:]] = search => {
  const promise = new Promise((resolve, reject) => {
    if ([[:FEATURE_CAMEL:]]CancelToken) {
      [[:FEATURE_CAMEL:]]CancelToken.cancel();
    }
    [[:FEATURE_CAMEL:]]CancelToken = axios.CancelToken.source();
    const headers = {
      'Content-Type': 'application/json',
      Accept: 'application/json',
    };
    const doRequest = freeAssoApi.get(
      process.env.REACT_APP_BO_URL + '/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SNAKE:]]/autocomplete/' + search,
      {
        headers: headers,
        cancelToken: [[:FEATURE_CAMEL:]]CancelToken.token,
      },
    );
    doRequest.then(
      res => {
        let list = [];
        if (res.data && res.data.length > 0) {
          res.data.map(item =>
            list.push({ item: item }),
          );
        }
        resolve(list);
      },
      err => {
        reject(err);
      },
    );
  });
  return promise;
}

/**
 * Appel du webservice de la récupération d'un modèle en fonction de son identifiant
 */
export const getOneModel = (id, params = {}) => {
  const addUrl = objectToQueryString(params);
  return freeAssoApi.get('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SNAKE:]]/' + id + addUrl);
};

/**
 * Récupération d'un modèle en fonction de son identifiant en mode asynchorne
 * Renvoie un résultat qu'au retour de la promesse
 */
export const getOneModelAsModel = (id, params = {}) => {
  return new Promise((resolve, reject) => {
    getOneModel(id, params).then(
      res => {
        const object = jsonApiNormalizer(res.data);
        const item = normalizedObjectModeler(object, '[[:FEATURE_MODEL:]]', id, { eager: true });
        resolve(item);
      },
      err => {
        reject(err);
      },
    );
  });
};

/**
 * Récupération de modèle(s) en fonction de critères
 * Renvoie un résultat qu'au retour de la promesse
 */
export const getAll[[:FEATURE_CAMEL_FULL:]]AsModel = (filters = {}, sort= [], page = 1, size = 99999) => {
  return new Promise((resolve, reject) => {
    let filters = filters.asJsonApiObject()
    let params = {
      page: page,
      size: size,
      ...filters
    };
    let sort = '';
    sort.forEach(elt => {
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
    const doRequest = freeAssoApi.get('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SNAKE:]]' + addUrl, {});
    doRequest.then(
      (res) => {
        let result = false;
        if (res && res.data && res.data.data) {
          result = res.data.data;
        }
        if (result) {
          resolve(jsonApiNormalizer(result));
        } else {
          resolve(null);
        }
      },
      // Use rejectHandler as the second argument so that render errors won't be caught.
      (err) => {
        reject(err);
      },
    );
  });
};