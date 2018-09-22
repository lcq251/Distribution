
import React, {Component} from 'react'
import ReactDOM from 'react-dom';

import $ from 'jquery'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'

import {select as formSelect} from '#/main/core/data/form/selectors'
import {Markdown as MarkdownTypes} from '#/plugin/markdown/resources/markdown/prop-types'
import {FormContainer} from '#/main/core/data/form/containers/form.jsx'


class MkEditComponent extends React.Component{
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
     <FormContainer
    name="markdownForm"
    sections={[
      {
        title: 'markdown',
        primary: true,
        fields: [
          {
            name: 'editSection',
            id: 'editSection',
            type: 'textarea',
            label: 'editSection',
            hideLabel: true,
            required: true,
            options: {
              minRows: 3
            }
          }
        ]
      }
    ]}
  />
        ) 
     } 
 }

MkEditComponent.propTypes = {
    markdown: T.shape(MarkdownTypes.propTypes).isRequired
}

const MkEdit = connect(
  state => ({
    markdown: formSelect.data(formSelect.form(state, 'markdownForm'))
  })
)(MkEditViewComponent)

export {
 MkEdit
}


