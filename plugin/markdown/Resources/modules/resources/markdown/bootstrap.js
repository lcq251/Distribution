import {bootstrap} from '#/main/app/bootstrap'

import {App} from '#/plugin/markdown/resources/markdown'

// generate application
const MarkdownApp = new App()

// mount the react application
bootstrap('.text-container', MarkdownApp.component, MarkdownApp.store)
