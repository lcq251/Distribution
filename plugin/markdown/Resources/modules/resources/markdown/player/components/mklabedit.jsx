
import React, {Component} from 'react'
import ReactDOM from 'react-dom';


import $ from 'jquery'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'
import { AgGridReact } from 'ag-grid-react';


const initialState = {
    files: [
        {id: 1, file: 'notes.txt', folder: 'txt'},
        {id: 2, file: 'book.pdf', folder: 'pdf'},
        {id: 3, file: 'cv.pdf', folder: 'pdf'},
        // more files ...
    ]
};

class MkLabEditComponent extends React.Component{
 constructor(props) {
        super(props);

        this.state = {
            columnDefs: [
                {headerName: "Make", field: "make"},
                {headerName: "Model", field: "model"},
                {headerName: "Price", field: "price"}

            ],
            rowData: [
                {make: "Toyota", model: "Celica", price: 35000},
                {make: "Ford", model: "Mondeo", price: 32000},
                {make: "Porsche", model: "Boxter", price: 72000}
            ]
        }
    }

    render() {
        return (
            <div className="ag-theme-fresh">
            <AgGridReact
                // provide column definitions
                columnDefs={this.colDefs}
                // specify auto group column definition
                autoGroupColumnDef={this.autoGroupColumnDef}
                // row data provided via props from the file store
                rowData={this.props.files}
                // expand groups by default
                groupDefaultExpanded={-1}
                // provide context menu callback
                getContextMenuItems={this.getContextMenuItems}
                // enable delta updates
                deltaRowDataMode={true}
                // return id required for delta updates
                getRowNodeId={data => data.id}>
            </AgGridReact>
        </div>
            );
    }
}

  
MkLabComponent.propTypes = {
   content: T.string.isRequired
} 

const MkLabEdit = connect(
   state => ({
    content: state.markdown.content,
  })
)(MkLabComponent)

export {
  MkLabEdit
}


