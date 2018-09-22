
import React, {Component} from 'react'
import ReactDOM from 'react-dom';

import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'

import {ResourcePageContainer} from '#/main/core/resource/containers/page.jsx'
import {MkView} from '#/plugin/markdown/resources/markdown/player/components/mkview'

import {currentUser} from '#/main/core/user/current'

const authenticatedUser = currentUser()

const PlayerComponent = props =>
          <div className={classes('markdown_content')}>
             <MkView/>
         </div>
     
PlayerComponent.propTypes = {
  content: T.string.isRequired
}

const Player = connect(
   state => ({
    defaultmode: state.markdown.defaultmode,
    content: state.markdown.content,
    markdown: state.markdown
  })
)(PlayerComponent)

export {
  Player
}


