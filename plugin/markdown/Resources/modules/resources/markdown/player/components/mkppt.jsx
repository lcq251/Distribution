
import React, {Component} from 'react'
import ReactDOM from 'react-dom';

import $ from 'jquery'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'


class MkPPTComponent extends React.Component {
    constructor(props) {
        super(props);
       this.state = {
        iFrameHeight: '100px'
    }
    }
   // componentDidMount(){  
  //    var varSource = $("#iframe-markdown-ppt").contents().find("#source");
  //    varSource.innerHTML = this.props.content;
 //     }
 shouldComponentUpdate() {
        var varSource = $("#iframe-markdown-ppt").contents().find("#source");
        varSource.innerHTML = this.props.content;
  }

    render() {
       let remarkSrc = "/mdqh/markdown/boilerplate-single.html";
       
        return (
                    <iframe src= {remarkSrc} 
                      id ="iframe-markdown-ppt" 
                      name ="iframe-markdown-ppt" 
                      style={{width:'100%', height:this.state.iFrameHeight, overflow:'visible'}}
                     onLoad={() => { 
                         const obj = ReactDOM.findDOMNode(this.refs.iframe);
                         var varSource = $("#iframe-markdown-ppt").contents().find("#source");
                         varSource.text(this.props.content);
                         console.log(varSource.text());                        
                         this.setState({ "iFrameHeight": '600px' });
                    }}
                     marginwidth="0"
                     marginheight="0"
                      ref="iframe" 
                      frameborder="no" 
                      border="0"
                      scrolling="no" 
                      allowtransparency="yes"/>  
        )
    }
}

MkPPTComponent.propTypes = {
    content: T.string.isRequired
}

const MkPPT = connect(
        state => ({
                defaultmode: state.markdown.defaultmode,
                content: state.markdown.content,
                markdown: state.markdown
            })
)(MkPPTComponent)

export {
MkPPT
}


