import React from 'react'
import {connect} from 'react-redux'
import {PropTypes as T} from 'prop-types'

import {trans} from '#/main/core/translation'
import {select as formSelect} from '#/main/core/data/form/selectors'
import {Markdown as MarkdownTypes} from '#/plugin/markdown/resources/markdown/prop-types'
import {FormContainer} from '#/main/core/data/form/containers/form.jsx'

const EditorComponent = () =>
  <FormContainer
    name="markdownForm"
    sections={[
      {
        title: 'test',
        primary: true,
        fields: [
          {
            name: 'content',
            type: 'html',
            label: 'markdown111',
            hideLabel: true,
            required: true,
            options: {
              minRows: 3
            }
          }
        ]
      }
    ]}
  />

EditorComponent.propTypes = {
  markdown: T.shape(MarkdownTypes.propTypes).isRequired
}

const Editor = connect(
  state => ({
    markdown: formSelect.data(formSelect.form(state, 'markdownForm'))
  })
)(EditorComponent)

export {
  Editor
}