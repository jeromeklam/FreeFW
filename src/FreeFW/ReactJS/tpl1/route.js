import { List, Input } from './';

export default {
  path: '',
  name: '',
  childRoutes: [
    { path: '/[[:FEATURE_LOWER:]]', name: 'List', component: List, auth: 'PRIVATE' },
    { path: '/[[:FEATURE_LOWER:]]/create', name: 'Create', component: Input, auth: 'PRIVATE' },
    { path: '/[[:FEATURE_LOWER:]]/modify/:id', name: 'Modify', component: Input, auth: 'PRIVATE' },
  ],
};
