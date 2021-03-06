import React from 'react'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'

import {trans} from '#/main/core/translation'
import {select as resourceSelect} from '#/main/core/resource/selectors'
import {select as formSelect} from '#/main/core/data/form/selectors'
import {actions as formActions} from '#/main/core/data/form/actions'
import {RoutedPageContent} from '#/main/core/layout/router'
import {ResourcePageContainer} from '#/main/core/resource/containers/page.jsx'

import {select} from '#/plugin/path/resources/path/selectors'
import {Overview} from '#/plugin/path/resources/path/overview/components/overview.jsx'
import {Editor} from '#/plugin/path/resources/path/editor/components/editor.jsx'
import {Player} from '#/plugin/path/resources/path/player/components/player.jsx'

const Resource = props => {
  const redirect = []
  const routes = [
    {
      path: '/edit',
      component: Editor,
      disabled: !props.editable
    }, {
      path: '/play',
      component: Player
    }, {
      path: '/',
      exact: true,
      component: Overview,
      disabled: !props.path.display.showOverview
    }
  ]

  if (!props.path.display.showOverview) {
    // redirect to player
    redirect.push({
      from: '/',
      to: '/play',
      exact: true
    })
  }

  return (
    <ResourcePageContainer
      editor={{
        path: '/edit',
        save: {
          disabled: !props.saveEnabled,
          action: () => props.saveForm(props.path.id)
        }
      }}
      customActions={[
        {
          type: 'link',
          icon: 'fa fa-fw fa-home',
          label: trans('show_overview'),
          displayed: props.path.display.showOverview,
          target: '/',
          exact: true
        }, {
          type: 'link',
          icon: 'fa fa-fw fa-play',
          label: trans('start', {}, 'actions'),
          target: '/play'
        }
      ]}
    >
      <RoutedPageContent
        headerSpacer={false}
        redirect={redirect}
        routes={routes}
      />
    </ResourcePageContainer>
  )
}

Resource.propTypes = {
  path: T.object.isRequired,
  editable: T.bool.isRequired,
  saveEnabled: T.bool.isRequired,

  saveForm: T.func.isRequired
}

const PathResource = connect(
  (state) => ({
    path: select.path(state),
    editable: resourceSelect.editable(state),
    saveEnabled: formSelect.saveEnabled(formSelect.form(state, 'pathForm'))
  }),
  (dispatch) => ({
    saveForm: (pathId) => dispatch(formActions.saveForm('pathForm', ['apiv2_path_update', {id: pathId}]))
  })
)(Resource)

export {
  PathResource
}
