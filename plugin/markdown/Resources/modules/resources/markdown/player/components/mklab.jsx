
import React, {Component} from 'react'
import ReactDOM from 'react-dom';
import ReactTable from "react-table"
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';


import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import classes from 'classnames'

import {MkCust} from '#/plugin/markdown/resources/markdown/player/components/mkcust'


class MkLabComponent extends React.Component {
    constructor(props) {
        super(props);
    }
    render() {
        return  (
                <div class="text-content">
                    <Tabs>
                        <TabList>
                            <Tab>编辑实验</Tab>
                            <Tab>实验学习</Tab>
                            <Tab>模仿学习</Tab>
                            <Tab>自定义实验</Tab>
                            <Tab>实验流程定制</Tab>
                            <Tab>实验报告</Tab>
                        </TabList>    
                        <TabPanel>
                            <MkCust/>
                        </TabPanel>
                        <TabPanel>
                            <h2>Any content 3</h2>
                        </TabPanel>
                        <TabPanel>
                            <h2>Any content 3</h2>
                        </TabPanel>
                        <TabPanel>
                            <h2>Any content 3</h2>
                        </TabPanel>
                        <TabPanel>
                            <h2>Any content 3</h2>
                        </TabPanel>
                        <TabPanel>
                            <h2>Any content 3</h2>
                        </TabPanel>
                    </Tabs>
                </div>
                )
    }
}

MkLabComponent.propTypes = {
    content: T.string.isRequired
}

const MkLab = connect(
        state => ({
                content: state.markdown.content,
            })
)(MkLabComponent)

export {
MkLab
        }


