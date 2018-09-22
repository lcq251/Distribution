import {PropTypes as T} from 'prop-types'

const Markdown = {
  propTypes: {
    id: T.number,
    content: T.string,
    meta: T.shape({
      version: T.number
    })
  }
}

export {
  Markdown
}