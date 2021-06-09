import React from 'react';
import { displayMonetary } from 'react-bootstrap-front';
import { jsonApiNormalizer, normalizedObjectModeler } from 'jsonapi-front';
import { freeAssoApi } from '../../common';

/**
 *
 */
export const getOne[[:FEATURE_CAMEL:]] = id => {
  return freeAssoApi.get('/v1/[[:FEATURE_COLLECTION:]]/[[:FEATURE_SERVICE:]]/' + id);
};

/**
 * 
 */
export const getOne[[:FEATURE_CAMEL:]]AsModel = id => {
  return new Promise((resolve, reject) => {
    getOneCause(id).then(
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