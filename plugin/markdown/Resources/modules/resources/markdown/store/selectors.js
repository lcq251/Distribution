import {createSelector} from 'reselect'

const STORE_NAME = 'resource'

const resource = (state) => state[STORE_NAME]

const markdown = createSelector(
  [resource],
  (resource) => resource.markdown
)

export const selectors = {
  STORE_NAME,
  resource,
  markdown
}
