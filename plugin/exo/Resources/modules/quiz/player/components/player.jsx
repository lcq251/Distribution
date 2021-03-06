import React, {Component} from 'react'
import {PropTypes as T} from 'prop-types'
import {connect} from 'react-redux'
import Panel from 'react-bootstrap/lib/Panel'

import {trans} from '#/main/core/translation'
import {withRouter} from '#/main/core/router'

import {actions as modalActions} from '#/main/core/layout/modal/actions'
import {MODAL_CONFIRM} from '#/main/core/layout/modal'
import {HtmlText} from '#/main/core/layout/components/html-text'

import {getDefinition, isQuestionType} from './../../../items/item-types'
import {getContentDefinition} from './../../../contents/content-types'
import selectQuiz from './../../selectors'
import {select} from './../selectors'
import {actions} from './../actions'
import {ItemPlayer} from '#/plugin/exo/items/components/item-player'
import {ItemFeedback} from '#/plugin/exo/items/components/item-feedback'
import {ContentItemPlayer} from '#/plugin/exo/contents/components/content-item-player'
import {PlayerNav} from './nav-bar.jsx'
import {getNumbering} from './../../../utils/numbering'
import {NUMBERING_NONE} from './../../../quiz/enums'

// TODO : rethink the loading paper process (it's a little hacky to make it quickly compatible with Router)

const CurrentStep = props =>
  <section className="current-step">
    <h2 className="h4 h-first">
      {props.step.title ? props.step.title : trans('step', {}, 'quiz') + ' ' + props.number}
    </h2>

    {props.step.description &&
      <HtmlText className="step-description">{props.step.description}</HtmlText>
    }

    {props.items.map((item, index) => (
      <Panel key={item.id}>
        {!isQuestionType(item.type) ?
          <ContentItemPlayer
            item={item}
          >
            {React.createElement(getContentDefinition(item.type).player, {item: item})}
          </ContentItemPlayer>
          : (!props.feedbackEnabled ?
            <ItemPlayer
              item={item}
              showHint={props.showHint}
              usedHints={props.answers[item.id] ? props.answers[item.id].usedHints : []}
              numbering={props.numbering !== NUMBERING_NONE ? props.number + '.' + getNumbering(props.numbering, index): null}
            >
              {React.createElement(getDefinition(item.type).player, {
                item: item,
                answer: props.answers[item.id] && props.answers[item.id].data ? props.answers[item.id].data : undefined,
                onChange: (answerData) => props.updateAnswer(item.id, answerData)
              })}
            </ItemPlayer>
            :
            <ItemFeedback
              item={item}
              usedHints={props.answers[item.id] ? props.answers[item.id].usedHints : []}
              numbering={props.numbering !== NUMBERING_NONE ? props.number + '.' + getNumbering(props.numbering, index): null}
            >
              {React.createElement(getDefinition(item.type).feedback, {
                item: item,
                answer: props.answers[item.id] && props.answers[item.id].data ? props.answers[item.id].data : undefined
              })}
            </ItemFeedback>
          )}
      </Panel>
    ))}
  </section>

CurrentStep.propTypes = {
  numbering: T.string.isRequired,
  number: T.number.isRequired,
  step: T.shape({
    id: T.string.isRequired,
    title: T.string,
    description: T.string
  }).isRequired,
  items: T.array.isRequired,
  answers: T.object.isRequired,
  feedbackEnabled: T.bool.isRequired,

  updateAnswer: T.func.isRequired,
  showHint: T.func.isRequired
}

class PlayerComponent extends Component {
  constructor(props) {
    super(props)

    this.state = {
      fetching: true,
      error: false
    }

    // TODO : display why the user cannot play quiz
    this.props
      .start()
      .then(
        () => this.setState({fetching: false}),
        () => this.setState({fetching: false, error: true})
      )
  }

  // TODO : better error display
  render() {
    return (
      <div className="quiz-player">
        {this.state.fetching &&
          <span>{trans('attempt_loading', {}, 'quiz')}</span>
        }

        {(!this.state.fetching && this.state.error) &&
          <span>{trans('attempt_not_available', {}, 'quiz')}</span>
        }

        {(!this.state.fetching && !this.state.error) &&
          <CurrentStep
            numbering={this.props.numbering}
            number={this.props.number}
            step={this.props.step}
            items={this.props.items}
            answers={this.props.answers}
            feedbackEnabled={this.props.feedbackEnabled}
            updateAnswer={this.props.updateAnswer}
            showHint={(questionId, hint) => this.props.showHint(this.props.quizId, this.props.paper.id, questionId, hint)}
          />
        }

        {(!this.state.fetching && !this.state.error) &&
          <PlayerNav
            previous={this.props.previous}
            mandatoryQuestions={this.props.mandatoryQuestions}
            next={this.props.next}
            step={this.props.step}
            answers={this.props.answers}
            showFeedback={this.props.showFeedback}
            feedbackEnabled={this.props.feedbackEnabled}
            navigateTo={(step) => this.props.navigateTo(this.props.quizId, this.props.paper.id, step, this.props.answers, false, false)}
            navigateToAndValidate={(step) => this.props.navigateTo(this.props.quizId, this.props.paper.id, step, this.props.answers, this.props.currentStepSend, false)}
            openFeedbackAndValidate={(step) => this.props.navigateTo(this.props.quizId, this.props.paper.id, step, this.props.answers, this.props.currentStepSend, true)}
            submit={() => this.props.submit(this.props.quizId, this.props.paper.id, this.props.answers)}
            finish={() => this.props.finish(this.props.quizId, this.props.paper, this.props.answers, this.props.showFeedback, this.props.showEndConfirm, this.props.history.push)}
            currentStepSend={this.props.currentStepSend}
          />
        }
      </div>
    )
  }
}

PlayerComponent.propTypes = {
  history: T.object.isRequired,
  quizId: T.string.isRequired,
  numbering: T.string.isRequired,
  number: T.number.isRequired,
  step: T.object,
  items: T.array.isRequired,
  mandatoryQuestions: T.bool.isRequired,
  answers: T.object.isRequired,
  paper: T.shape({
    id: T.string.isRequired,
    number: T.number.isRequired
  }).isRequired,
  next: T.object,
  previous: T.object,
  showFeedback: T.bool.isRequired,
  showEndConfirm: T.bool.isRequired,
  feedbackEnabled: T.bool.isRequired,
  currentStepSend: T.bool.isRequired,

  start: T.func.isRequired,
  updateAnswer: T.func.isRequired,
  navigateTo: T.func.isRequired,
  submit: T.func.isRequired,
  finish: T.func.isRequired,
  showHint: T.func.isRequired
}

PlayerComponent.defaultProps = {
  next: null,
  previous: null,
  answers: {}
}

const Player = withRouter(connect(
  state => ({
    mandatoryQuestions: selectQuiz.parameters(state).mandatoryQuestions,
    quizId: selectQuiz.id(state),
    number: select.currentStepNumber(state),
    step: select.currentStep(state),
    items: select.currentStepItems(state),
    paper: select.paper(state),
    answers: select.currentStepAnswers(state),
    next: select.next(state),
    previous: select.previous(state),
    showFeedback: select.showFeedback(state),
    showEndConfirm: select.showEndConfirm(state),
    feedbackEnabled: select.feedbackEnabled(state),
    currentStepSend: select.currentStepSend(state),
    numbering: selectQuiz.quizNumbering(state)
  }),
  dispatch => ({
    start() {
      // The return is to be able to link on the Promise (this is not really clean)
      return dispatch(actions.play())
    },
    updateAnswer(questionId, answerData) {
      dispatch(actions.updateAnswer(questionId, answerData))
    },
    navigateTo(quizId, paperId, nextStep, pendingAnswers, currentStepSend, openFeedback) {
      dispatch(actions.navigateTo(quizId, paperId, nextStep, pendingAnswers, currentStepSend, openFeedback))
    },
    submit(quizId, paperId, answers) {
      dispatch(actions.submit(quizId, paperId, answers))
    },
    finish(quizId, paper, pendingAnswers, showFeedback, showConfirm, navigate) {
      if (showConfirm) {
        dispatch(modalActions.showModal(MODAL_CONFIRM, {
          title: trans('finish_confirm_title', {}, 'quiz'),
          question: trans('finish_confirm_question', {}, 'quiz'),
          handleConfirm: () => dispatch(actions.finish(quizId, paper, pendingAnswers, showFeedback, navigate))
        }))
      } else {
        dispatch(actions.finish(quizId, paper, pendingAnswers, showFeedback, navigate))
      }
    },
    showHint(quizId, paperId, questionId, hint) {
      dispatch(modalActions.showModal(MODAL_CONFIRM, {
        title: trans('hint_confirm_title', {}, 'quiz'),
        question: trans('hint_confirm_question', {}, 'quiz'),
        handleConfirm: () => dispatch(actions.showHint(quizId, paperId, questionId, hint))
      }))
    }
  })
)(PlayerComponent))

export {
  Player
}
