import React, { Component } from 'react'
import moment from 'moment'
import axios from 'axios'
import { Button, Card, CardBody, CardHeader, FormGroup, Input, Label } from 'reactstrap'
import SuccessMessage from '../../common/SucessMessage'
import ErrorMessage from '../../common/ErrorMessage'
import ElapsedTime from './ElapsedTime'
import { translations } from '../../utils/_translations'
import TimePickerInput from '../../common/TimePickerInput'
import Duration from '../../common/Duration'
import { KeyboardDatePicker, MuiPickersUtilsProvider } from '@material-ui/pickers'
import MomentUtils from '@date-io/moment'
import TimerModel from '../../models/TimerModel'

class EditTaskTimes extends Component {
    constructor (props, context) {
        super(props, context)
        this.state = {
            currentIndex: null,
            showSuccess: false,
            showError: false,
            times: this.props.timers,
            visible: 'collapse',
            dropdownOpen: false,
            dropdown2Open: true
        }

        this.timerModel = new TimerModel()
        this.timerModel.time_log = this.state.times
        this.handleSlideClick = this.handleSlideClick.bind(this)
        this.closeForm = this.closeForm.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleDateChange = this.handleDateChange.bind(this)
        this.handleDelete = this.handleDelete.bind(this)
        this.handleSave = this.handleSave.bind(this)
        this.handleTimeChange = this.handleTimeChange.bind(this)
        this.addTaskTime = this.addTaskTime.bind(this)
    }

    handleSlideClick (index) {
        this.setState({
            currentIndex: index,
            dropdownOpen: !this.state.dropdownOpen,
            dropdown2Open: !this.state.dropdown2Open
        })
    }

    closeForm () {
        this.setState({
            currentIndex: null,
            dropdownOpen: !this.state.dropdownOpen,
            dropdown2Open: !this.state.dropdown2Open
        })
    }

    handleTimeChange (e) {
        const times = this.timerModel.updateTaskTime(e.index, e.name, e.value)
        this.setState({ times: times })
        console.log('times', times)
        console.log('time', e.value)
    }

    handleDateChange (date, index) {
        const times = this.timerModel.updateTaskTime(index, 'date', moment(date).format('YYYY-MM-DD'))
        this.setState({ times: times })
        console.log('times', times)
    }

    handleChange (e) {
        const value = e.target.value

        if (!value || !value.length) {
            return true
        }

        const times = this.timerModel.addDuration(this.state.currentIndex, value)
        this.setState({ times: times })
        console.log('times', times)
    }

    handleDelete (idx) {
        const times = this.timerModel.deleteTaskTime(idx)

        this.setState({
            times: times
        }, () => {
            this.closeForm()
            this.handleSave(true)
        })
    }

    addTaskTime () {
        const times = this.timerModel.addTaskTime()
        this.setState({ times: times })
    }

    handleSave (isDelete = false) {
        axios.post('/api/timer', {
            time_log: this.state.times,
            task_id: this.props.task_id
        })
            .then((response) => {
                this.setState({ showSuccess: true, showError: false })
                this.closeForm()
            })
            .catch((error) => {
                this.setState({
                    showSuccess: false,
                    showError: true,
                    err: error.response.data.errors
                })
            })
    }

    render () {
        const { model } = this.props
        const { currentIndex, times, showSuccess, showError } = this.state
        const timeList = times.length ? times.map((time, index) => {
            return <div key={index}
                className="list-group-item list-group-item-action flex-column align-items-start">
                <div className="d-flex w-100 justify-content-between">
                    <h5 className="mb-1">{moment(time.date).format('DD-MM-YYYY')}</h5>

                    {time.end_time && <small>{model.calculateDuration(time.start_time, time.end_time)}</small>}

                    {!time.end_time.length && <ElapsedTime date={time.date} currentStartTime={time.start_time}/>}

                    <i onClick={() => this.handleSlideClick(index)} className="fa fa-arrow-right"/>
                </div>
                <p className="mb-1">{`${time.start_time} - ${time.end_time}`}</p>
            </div>
        }) : <h2>No times added</h2>

        const showSuccessMessage = showSuccess === true ? <SuccessMessage message="Times updated successfully"/> : null
        const showErrorMessage = showError === true
            ? <ErrorMessage message="Times could not be updated successfully"/> : null

        const currentData = currentIndex !== null ? times[currentIndex] : null

        const form = currentData && Object.keys(currentData).length ? <React.Fragment>
            <FormGroup>
                <Label>{translations.date}</Label>

                <MuiPickersUtilsProvider libInstance={moment} utils={MomentUtils}>
                    <KeyboardDatePicker
                        margin="normal"
                        id="date-picker-dialog"
                        format="MMMM DD, YYYY"
                        value={moment(currentData.date).format('YYYY-MM-DD')}
                        onChange={(e) => { this.handleDateChange(e, currentIndex) }}
                        KeyboardButtonProps={{
                            'aria-label': 'change date'
                        }}
                    />
                </MuiPickersUtilsProvider>
            </FormGroup>

            <FormGroup>
                <Label>{translations.start_time}</Label>
                <TimePickerInput name="start_time" index={currentIndex} value={currentData.start_time}
                    setValue={this.handleTimeChange}/>
            </FormGroup>

            <FormGroup>
                <Label>{translations.end_time}</Label>
                <TimePickerInput name="end_time" index={currentIndex} value={currentData.end_time}
                    setValue={this.handleTimeChange}/>
            </FormGroup>

            <FormGroup>
                <Label>{translations.duration} {model.calculateDuration(currentData.start_time, currentData.end_time)}</Label>
                <Duration onChange={this.handleChange} />
            </FormGroup>

            <Button className="mr-2" color="primary" onClick={this.handleSave}>{translations.done}</Button>
            <Button color="danger" onClick={() => this.handleDelete(currentIndex)}>{translations.remove}</Button>
        </React.Fragment> : null

        return (
            <React.Fragment>
                {showSuccessMessage}
                {showErrorMessage}
                <div className={`list-group ${this.state.dropdown2Open ? 'collapse show' : 'collapse'}`}>
                    {timeList}
                    <Button className="mt-2 mb-2" color="primary" onClick={this.addTaskTime}>{translations.add}</Button>
                </div>

                <div className={this.state.dropdownOpen ? 'collapse show' : 'collapse'}>
                    <Card>
                        <CardHeader>Update</CardHeader>
                        <CardBody>
                            {form}
                        </CardBody>
                    </Card>
                </div>

                <Button color="primary" onClick={this.handleSave}>Done</Button>
            </React.Fragment>
        )
    }
}

export default EditTaskTimes
