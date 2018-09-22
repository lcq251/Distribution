
import React, {Component} from 'react'
import ReactDOM from 'react-dom';

import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'

import {ResourcePageContainer} from '#/main/core/resource/containers/page.jsx'
import {MkView} from '#/plugin/markdown/resources/markdown/player/components/mkview'
import {MkPPT} from '#/plugin/markdown/resources/markdown/player/components/mkppt'
import {MkLab} from '#/plugin/markdown/resources/markdown/player/components/mklab'
import {MkNote} from '#/plugin/markdown/resources/markdown/player/components/mknote'
import {currentUser} from '#/main/core/user/current'

const authenticatedUser = currentUser()

const PlayerComponent = props =>
    <div className={classes('markdown_content')}>
      {(() => {
                    switch (props.defaultmode) {

                        case 0:
                                return  <MkView/>;
                                break;
                        case 1:
                                return <MkLab/>;
                                break;
                        case 2:
                                return <MkNote/>;
                                break;
                        case 3:
                                return <MkPPT/>;
                                break;
                        default:
                                return null;
              }
              }
              )()}
       </div>
    

const mapStateToProps = (state) => {
  return {
    defaultmode: state.markdown.defaultmode
  }
}
PlayerComponent.propTypes = {
  content: T.string.isRequired
}

const Player = connect(
   state => ({
    defaultmode: state.markdown.defaultMode,
    content: state.markdown.content,
    markdown: state.markdown
  })
)(PlayerComponent)

export {
  Player
}


