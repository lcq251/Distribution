import {bootstrap} from '#/main/app/bootstrap'

// reducers
import {reducer} from '#/plugin/markdown/resources/markdown/reducer'
// Component
import {Resource} from '#/plugin/markdown/resources/markdown/components/resource'

// mount the react application
bootstrap(
  // app DOM container (also holds initial app data as data attributes)
  '.text-container',
  
  // app main component
  Resource,
  
  // app store configuration
  reducer
)
