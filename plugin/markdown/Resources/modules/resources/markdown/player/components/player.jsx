
import React, {Component} from 'react'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'

import {selectors} from '#/plugin/markdown/resources/markdown/store'
import {Markdown as MarkdownTypes} from '#/plugin/markdown/resources/markdown/prop-types'

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
    


PlayerComponent.propTypes = {
  markdown: T.shape(MarkdownTypes.propTypes).isRequired
}

const Player = connect(
   state => ({
   markdown: selectors.markdown(state)
  })
)(PlayerComponent)

export {
  Player
}


