import {selectors as formSelectors} from '#/main/app/content/form/store/selectors'

import {selectors as markdownSelectors} from '#/plugin/markdown/resources/markdown/store/selectors'

const FORM_NAME = markdownSelectors.STORE_NAME+'.markdownForm'

const markdown = (state) => formSelectors.data(formSelectors.form(state, FORM_NAME))

export const selectors = {
  FORM_NAME,
  markdown
}
