import React from 'react'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'

import {ResourcePageContainer} from '#/main/core/resource/containers/page.jsx'
import {getPlainText} from '#/main/core/data/types/html/utils'
import {HtmlText} from '#/main/core/layout/components/html-text'
import {IframeModal} from '#/main/core/layout/modal/components/iframe'

import {currentUser} from '#/main/core/user/current'

const authenticatedUser = currentUser()

const PlayerComponent = props =>{
   let markdownViewUrl = "/packages/markdown-plus/dist/view.html";
   
 return (
   <div className={classes('markdown_content')}>
 	<iframe ref="markdownView"
               onLoad={this.onload}
               src= { markdownViewUrl } 
                height="700px" 
                width="100%" 
                frameborder="no" 
                border="0" 
                marginwidth="0" 
                marginheight="0" 
                scrolling="no" 
                allowtransparency="yes"></iframe>
  </div>)
}
  
 function  onLoad = () => {    
       console.log('testdddddddddddddddd');
   }
 
PlayerComponent.propTypes = {
  content: T.string.isRequired
}

const Player = connect(
   state => ({
    content: state.markdown.content,
    markdown: state.markdown
  })
)(PlayerComponent)

export {
  Player
}


