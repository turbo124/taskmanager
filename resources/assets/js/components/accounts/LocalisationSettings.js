import React, { Component } from 'react'
import { Button, Card, CardBody, CardHeader, FormGroup, Input, Label } from 'reactstrap'
import axios from 'axios'
import { toast, ToastContainer } from 'react-toastify'
import moment from 'moment'
import { translations } from '../common/_translations'

export default class LocalisationSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            settings: {},
            first_month_of_year: null,
            first_day_of_week: null,
            date_formats: ['DD/MMM/YYYY']
        }

        this.handleSettingsChange = this.handleSettingsChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.getAccount = this.getAccount.bind(this)
        this.handleChange = this.handleChange.bind(this)
    }

    componentDidMount () {
        this.getAccount()
    }

    getAccount () {
        axios.get(`api/accounts/${this.state.id}`)
            .then((r) => {
                this.setState({
                    loaded: true,
                    settings: r.data.settings
                })
            })
            .catch((e) => {
                toast.error('There was an issue updating the settings')
            })
    }

    handleSettingsChange (event) {
        const name = event.target.name
        const value = event.target.value

        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                [name]: value
            }
        }))
    }

    handleSubmit (e) {
        const formData = new FormData()
        formData.append('settings', JSON.stringify(this.state.settings))
        formData.append('first_month_of_year', this.state.first_month_of_year)
        formData.append('first_day_of_week', this.state.first_day_of_week)
        formData.append('_method', 'PUT')

        axios.post(`/api/accounts/${this.state.id}`, formData, {
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then((response) => {
                toast.success('Settings updated successfully')
            })
            .catch((error) => {
                toast.error('There was an issue updating the settings ' + error)
            })
    }

    handleChange (event) {
        this.setState({ [event.target.name]: event.target.value })
    }

    render () {
        const { date_formats } = this.state
        const days = moment.weekdays()
        const months = moment.months()

        const month_list = months.map(function (item, i) {
            console.log('test')
            return <option key={i} value={item}>{item}</option>
        })

        const day_list = days.map(function (item, i) {
            console.log('test')
            return <option key={i} value={item}>{item}</option>
        })

        const date_format_list = date_formats && date_formats.length ? date_formats.map(date_format => {
            return <option key={date_format.id} value={date_format.id}>{moment().format(date_format.format_moment)}</option>
        }) : null

        return date_formats && date_formats.length ? (
            <React.Fragment>
                <ToastContainer/>
                <Card className="mt-3">
                    <CardBody className="d-flex justify-content-between align-items-center">
                        <h6>{translations.localisation_settings}</h6>
                        <a className="pull-right" onClick={this.handleSubmit}>{translations.save}</a>
                    </CardBody>
                </Card>
                <Card>
                    <CardHeader>{translations.settings}</CardHeader>
                    <CardBody>
                        <FormGroup>
                            <Label>Date Format</Label>
                            <Input type="select" name="date_format" onChange={this.handleSettingsChange} >
                                {date_format_list}
                            </Input>
                        </FormGroup>

                        <FormGroup>
                            <Label>First Day of the Week</Label>
                            <Input type="select" name="first_day_of_week" onChange={this.handleSettingsChange} >
                                <option value=""/>
                                {day_list}
                            </Input>
                        </FormGroup>

                        <FormGroup>
                            <Label>First Month of the Year</Label>
                            <Input type="select" name="first_month_of_year" onChange={this.handleSettingsChange} >
                                <option value=""/>
                                {month_list}
                            </Input>
                        </FormGroup>
                    </CardBody>
                </Card>
            </React.Fragment>
        ) : null
    }
}
