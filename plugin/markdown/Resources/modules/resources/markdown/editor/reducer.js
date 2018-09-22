import {makeFormReducer} from '#/main/core/data/form/reducer'

const reducer = {
  markdownForm: makeFormReducer('markdownForm', {}, {})
}

export {
  reducer
}