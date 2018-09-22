import {combineReducers, makeReducer} from '#/main/app/store/reducer'

import {FORM_SUBMIT_SUCCESS} from '#/main/app/content/form/store/actions'
import {RESOURCE_LOAD} from '#/main/core/resource/store/actions'

import {selectors as editorSelectors} from '#/plugin/markdown/resources/markdown/editor/store/selectors'
import {reducer as editorReducer} from '#/plugin/markdown/resources/markdown/editor/store/reducer'

const reducer = combineReducers({
  markdownForm: editorReducer.markdownForm,
  markdown: makeReducer({}, {
    [RESOURCE_LOAD]: (state, action) => action.resourceData.markdown,
    // replaces path data after success updates
    [FORM_SUBMIT_SUCCESS+'/'+editorSelectors.FORM_NAME]: (state, action) => action.updatedData
  })
})

export {
  reducer
}