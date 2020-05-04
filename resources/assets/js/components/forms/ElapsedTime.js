/* eslint-disable no-unused-vars */
import React from 'react'
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap'
import ViewTask from './ViewTask'
import Avatar from '../common/Avatar'
import axios from 'axios'
import moment from 'moment'

class ElapsedTime extends React.Component {
    constructor (props) {
        super(props)
        this.state = {
            modal: false,
            secondsElapsed: '',
            currentStartTime: this.props.currentStartTime,
            date: this.props.date,
            errors: [],
            subtasks: [],
            visible: 'collapse'
        }

        this.tick = this.tick.bind(this)
    }

    componentDidMount() {
        this.tick()
        this.interval = setInterval(this.tick, 1000);
    }

    tick() {
        const formattedDate = moment(this.state.date + ' ' + this.state.currentStartTime)
        const elapsedDuration = moment.duration(moment().diff(moment(formattedDate).format('YYYY-MM-DD hh:mm:ss')));                
        this.setState({secondsElapsed: elapsedDuration});
    }

    render() {
        return Object.keys(this.state.secondsElapsed).length ? <small>{moment.utc(this.state.secondsElapsed.as('milliseconds')).format('HH:mm:ss')}</small> : null
  }
}

export default ElapsedTime
