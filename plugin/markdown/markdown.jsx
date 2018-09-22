import React from 'react'
import {PropTypes as T} from 'prop-types'
import classes from 'classnames'

/**
 * Interprets and displays HTML content.
 *
 * @param props
 * @constructor
 */
const MarkdownComponent = props =>
  <div
    className={classes('text-html-content', props.className)}
    dangerouslySetInnerHTML={{ __html: props.children }}
  />

MarkdownComponent.propTypes = {
}

const MarkdownComponent = connect(
  state => ({
    markdown: state.markdown,
    resourceNode: state.resourceNode
  })
)(Markdown)

export {
  Markdown
}
