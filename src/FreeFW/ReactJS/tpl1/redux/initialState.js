import { getNewNormalizedObject } from 'jsonapi-front';
import { getInitFilters } from './initFilters';

/**
 * Initialisation du store pour l'objet 
 */
const initialState = {
  objectName: '[[:FEATURE_MODEL:]]',
  items: getNewNormalizedObject('[[:FEATURE_MODEL:]]'),
  currentId: 0,
  currentMode: 'none',
  currentIsFirst: true,
  currentIsLast: true,
  selected: [],
  page_number: 1,
  page_size: process.env.REACT_APP_PAGE_SIZE,
  tab: 'ident',
  filters: getInitFilters(),
  sort: [[:FEATURE_SORT:]],
  include: '[[:FEATURE_INCLUDE:]]',
  includeMore: '[[:FEATURE_INCLUDE:]]',
  loadMorePending: false,
  loadMoreFinish: false,
  loadMoreError: null,
  loadOnePending: false,
  loadOneItem: null,
  loadOneError: null,
  createOnePending: false,
  createOneError: null,
  updateOnePending: false,
  updateOneError: null,
  delOnePending: false,
  delOneError: null,
  printOnePending: false,
  printOneError: null,
  exportPending: false,
  exportError: null,
  setNextPending: false,
  setNextError: null,
};

export default initialState;
