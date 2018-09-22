import React, { Component } from 'react' 
import ReactDOM from 'react-dom' 
import { connect } from "react-redux" 


class Iframe extends Component {
    
  render() { 
        const defaultViewUrl = "/packages/markdown-plus/dist/view.html"
        const noteViewUrl = "/packages/markdown-plus/dist/view.html"
        console.log(this.props.mode);
        
      return ( 
         <iframe 
         className='editor-iframe'   
         ref="iframe" 
         style="height:700px"
         src={ defaultViewUrl }
         /> 
         ) 
     } 
 } 
 
 export {
     Iframe
 }
 
 
  
    