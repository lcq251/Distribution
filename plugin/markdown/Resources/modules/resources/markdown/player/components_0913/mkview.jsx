
import React, {Component} from 'react'
import ReactDOM from 'react-dom';

import $ from 'jquery'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'

class MkViewComponent extends React.Component{
   constructor(props){
    super(props);
  }
      
     componentDidMount(){
         $('#editSection').tuiEditor({
        initialEditType: 'markdown',
        initialValue: this.props.content,
        previewStyle: 'vertical',
        height: '700px',
        exts: ['scrollSync', 'colorSyntax', 'uml', 'chart', 'mark', 'table']
      });
     }   
     
    render(){         
      return ( 
          <div id="editSection"></div>
         ) 
     } 
 }
   
MkViewComponent.propTypes = {
  content: T.string.isRequired
}

const MkView = connect(
   state => ({
    defaultmode: state.markdown.defaultmode,
    content: state.markdown.content,
    markdown: state.markdown
  })
)(MkViewComponent)

export {
  MkView
}


