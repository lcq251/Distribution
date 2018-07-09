import {createSelector} from 'reselect'

const currentTabId = (state) => state.currentTabId
const editable = (state) => state.editable
const editing = (state) => state.editing
const context = (state) => state.context
const tabs = (state) => state.tabs

const currentTab = createSelector(
  [tabs, currentTabId],
  (tabs, currentTabId) => tabs.find(tab => currentTabId === tab.id)
)

const widgets = createSelector(
  [currentTab],
  (currentTab) => currentTab.widgets
)

export const select = {
  currentTab,
  currentTabId,
  editable,
  editing,
  context,
  tabs,
  widgets
}