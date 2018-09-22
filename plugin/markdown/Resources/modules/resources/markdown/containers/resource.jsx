import {withReducer} from '#/main/app/store/components/withReducer'

import {MarkdownResource as MarkdownResourceComponent} from '#/plugin/markdown/resources/markdown/components/resource'
import {reducer, selectors} from '#/plugin/markdown/resources/markdown/store'

const MarkdownResource = withReducer(selectors.STORE_NAME, reducer)(MarkdownResourceComponent)

export {
  MarkdownResource
}
