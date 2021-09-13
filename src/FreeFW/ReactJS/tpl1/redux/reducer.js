import initialState from './initialState';
import { reducer as loadMoreReducer } from './loadMore';
import { reducer as loadOneReducer } from './loadOne';
import { reducer as createOneReducer } from './createOne';
import { reducer as updateOneReducer } from './updateOne';
import { reducer as delOneReducer } from './delOne';
import { reducer as clearItemsReducer } from './clearItems';
import { reducer as setSortReducer } from './setSort';
import { reducer as setFiltersReducer } from './setFilters';
import { reducer as updateQuickSearchReducer } from './updateQuickSearch';
import { reducer as updateSortReducer } from './updateSort';
import { reducer as initFiltersReducer } from './initFilters';
import { reducer as initSortReducer } from './initSort';
import { reducer as printOneReducer } from './printOne';
import { reducer as propagateReducer } from './propagate';
import { reducer as onSelectReducer } from './onSelect';
import { reducer as selectNoneReducer } from './selectNone';
import { reducer as selectAllReducer } from './selectAll';
import { reducer as exportAsTabReducer } from './exportAsTab';
import { reducer as setCurrentReducer } from './setCurrent';
import { reducer as setPreviousReducer } from './setPrevious';
import { reducer as setNextReducer } from './setNext';

const reducers = [
  loadMoreReducer,
  loadOneReducer,
  createOneReducer,
  updateOneReducer,
  delOneReducer,
  clearItemsReducer,
  setSortReducer,
  setFiltersReducer,
  updateQuickSearchReducer,
  updateSortReducer,
  initFiltersReducer,
  initSortReducer,
  printOneReducer,
  propagateReducer,
  onSelectReducer,
  selectNoneReducer,
  selectAllReducer,
  exportAsTabReducer,
  setCurrentReducer,
  setPreviousReducer,
  setNextReducer,
];

export default function reducer(state = initialState, action) {
  let newState;
  switch (action.type) {
    // Handle cross-topic actions here
    default:
      newState = state;
      break;
  }
  return reducers.reduce((s, r) => r(s, action), newState);
}
