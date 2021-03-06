import {TextResource} from '#/main/core/resources/text/components/resource'
import {reducer} from '#/main/core/resources/text/reducer'

/**
 * Text resource application.
 *
 * @constructor
 */
export const App = () => ({
  component: TextResource,
  store: reducer
})
