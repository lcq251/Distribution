
import React, {Component} from 'react'
import ReactDOM from 'react-dom';
import $ from 'jquery'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'
//import Bootstrap from './bootstrap.css'
//const boot = require(bootstrap.css)


class MkCustComponent extends React.Component{
   constructor(props){
    super(props);
  }

    render(){  
        const classNames = require("classnames");
        let remarkSrc = "/mdqh/gateone/html/gateone.html";
        return ( 
		<div classnames='panel panel-default'>
			<div className="panel-heading"></div>
			<div className="mkcust-main panel-body clearfix">
                            <div id="gateone_container" style="position: relative; width: 60em; height: 30em;">
                                <div id="gateone"></div>
                             </div>
                      		<div className="mkcust-panel-right pull-left">
					<div className="panel panel-success margin-bottom-0">
						<div className="panel-heading mkcust-center padding-0">
							<ul className="list-group-mkcust panel-heading clearfix">
								<li className="list-group-item">状态1</li>
								<li className="list-group-item">状态2</li>
								<li className="list-group-item">状态3</li>
								<li className="list-group-item">状态4</li>
							</ul>
						</div>
					</div>
					<div className="panel panel-info margin-bottom-0">
						<div className="panel-heading mkcust-center">说明栏</div>
					</div>
					<div className="panel panel-warning margin-bottom-0">
						<div className="panel-heading mkcust-center">编辑区</div>
					</div>
					<div className="panel-body-cust">
						<div className="panel-body-wrap mkcust-center">test1111</div>
					</div>
				</div>
			</div>
                        </div>
         ); 
     } 
 }
   
MkCustComponent.propTypes = {
  content: T.string.isRequired
};

const MkCust = connect(
   state => ({
    defaultmode: state.markdown.defaultmode,
    content: state.markdown.content,
    markdown: state.markdown
  })
)(MkCustComponent)

export {
  MkCust
}


