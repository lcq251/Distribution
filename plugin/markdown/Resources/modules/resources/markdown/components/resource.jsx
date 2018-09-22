import React from 'react'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'

import {ResourcePageContainer} from '#/main/core/resource/containers/page.jsx'
import {RoutedPageContent} from '#/main/core/layout/router'

import {trans} from '#/main/core/translation'
import {select as resourceSelect} from '#/main/core/resource/selectors'
import {actions as formActions} from '#/main/core/data/form/actions'
import {select as formSelect} from '#/main/core/data/form/selectors'
import {Markdown as MarkdownTypes} from '#/plugin/markdown/resources/markdown/prop-types'

import {Player} from '#/plugin/markdown/resources/markdown/player/components/player.jsx'
import {Editor} from '#/plugin/markdown/resources/markdown/editor/components/editor.jsx'

const Resource = props =>
  <ResourcePageContainer
    editor={{
      path: '/edit',
      save: {
        disabled: !props.saveEnabled,
        action: () => props.saveForm(props.markdown.id)
      }
    }}
    test={{
      path: '/edit',
      save: {
        disabled: !props.saveEnabled,
        action: () => props.saveForm(props.markdown.id)
      }
    }} 
    customActions={[
      {
        type: 'link',
        icon: 'fa fa-fw fa-home',
        label: trans('show_overview'),
        displayed: props.canEdit,
        target: '/'
      }
    ]}
  >
    <RoutedPageContent
      headerSpacer={true}
      redirect={[
        {from: '/', exact: true, to: '/play'}
      ]}
      routes={[
        {
          path: '/play',
          component: Player
        }, {
          path: '/edit',
          component: Editor,
          canEnter: () => props.canEdit,
          onEnter: () => props.resetForm(props.markdown)
        }
      ]}
    />
  </ResourcePageContainer>

Resource.propTypes = {
  markdown: T.shape(MarkdownTypes.propTypes).isRequired,
  resource: T.shape({
    id: T.string.isRequired,
    autoId: T.number.isRequired
  }).isRequired,
  canEdit: T.bool.isRequired,
  saveEnabled: T.bool.isRequired,
  resetForm: T.func.isRequired,
  saveForm: T.func.isRequired
}

const ResourceContainer = connect(
   state => ({
    markdown: state.markdown,
    resourceNode: state.resourceNode,
    canEdit: resourceSelect.editable(state),
    saveEnabled: formSelect.saveEnabled(formSelect.form(state, 'markdownForm'))
  }),
  (dispatch) => ({
    resetForm: (formData) => dispatch(formActions.resetForm('markdownForm', formData)),
    saveForm: (id) => dispatch(formActions.saveForm('markdownForm', ['apiv2_resource_markdown_update', {id: id}]))
  })
)(Resource)

export {
  ResourceContainer as Resource
}
