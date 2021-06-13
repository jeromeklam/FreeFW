import { Filter, FILTER_MODE_AND, FILTER_OPER_GREATER_OR_EQUAL_OR_NULL, FILTER_OPER_EQUAL } from 'react-bootstrap-front';
import { getNewNormalizedObject } from 'jsonapi-front';

let initialFilters = new Filter();
initialFilters.setMode(FILTER_MODE_AND);

const initialState = {
  items: getNewNormalizedObject('[[:FEATURE_MODEL:]]'),
  selected: [],
  page_number: 1,
  page_size: process.env.REACT_APP_PAGE_SIZE,
  tab: "1",
  filters: initialFilters,
  sort: [{col:"id",way:"up"}],
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
};

export default initialState;
