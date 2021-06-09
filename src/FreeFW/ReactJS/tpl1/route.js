import { List, Input } from './';

export default {
  path: '',
  name: '',
  childRoutes: [
    { path: 'cause/:cautId', name: 'List', component: List, auth: 'PRIVATE' },
    { path: 'cause/create', name: 'Create', component: Input, auth: 'PRIVATE' },
    { path: 'cause/modify/:causeId', name: 'Modify', component: Input, auth: 'PRIVATE' },
  ],
};
