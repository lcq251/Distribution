import {makeReducer} from '#/main/core/scaffolding/reducer'
import {makeResourceReducer} from '#/main/core/resource/reducer'
import {FORM_SUBMIT_SUCCESS} from '#/main/core/data/form/actions'
import {reducer as editorReducer} from '#/plugin/markdown/resources/markdown/editor/reducer'

const reducer = makeResourceReducer({}, {
   markdownForm: editorReducer.markdownForm,
  markdown: makeReducer({}, {
    // replaces path data after success updates
    [FORM_SUBMIT_SUCCESS+'/markdownForm']: (state, action) => action.updatedData
  })
})

export {
  reducer
}