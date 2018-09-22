
import React, {Component} from 'react'
import ReactDOM from 'react-dom';

import $ from 'jquery'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'
import ReactMarkdown from 'react-markdown'


const input = '# This is a header\n\n```js \n\nvar React = require(\'react\');\n\nvar Markdown = require(\'react-markdown\');\n\n```\n\n[百度](http://baidu.com)\n\n![](https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=1387101787,2058857891&fm=23&gp=0.jpg)'

class MkNoteComponent extends React.Component{
 constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="ag-theme-fresh">
           <ReactMarkdown source={input} />,
        </div>
            );
    }
}

  
MkNoteComponent.propTypes = {
   content: T.string.isRequired
} 

const MkNote = connect(
   state => ({
    content: state.markdown.content,
  })
)(MkNoteComponent)

export {
  MkNote
}


