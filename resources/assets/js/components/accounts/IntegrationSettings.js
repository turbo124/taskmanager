import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Card, CardBody, Alert } from 'reactstrap'
import axios from 'axios'
import { translations } from '../common/_translations'
import Snackbar from '@material-ui/core/Snackbar'

class IntegrationSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            settings: {},
            success: false,
            error: false
        }

        this.handleSettingsChange = this.handleSettingsChange.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.getAccount = this.getAccount.bind(this)
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
                this.setState({ error: true })
            })
    }

    handleChange (event) {
        this.setState({ [event.target.name]: event.target.value })
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
        formData.append('_method', 'PUT')

        axios.post(`/api/accounts/${this.state.id}`, formData, {
            headers: {
                'content-type': 'multipart/form-data'
            }
        })
            .then((response) => {
                this.setState({ success: true })
            })
            .catch((error) => {
                this.setState({ error: true })
            })
    }

    getFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'slack_webhook_url',
                    label: translations.slack_url,
                    type: 'text',
                    placeholder: translations.slack_url,
                    value: settings.slack_webhook_url
                },
                {
                    name: 'google_analytics_url',
                    label: translations.google_analytics_url,
                    type: 'text',
                    placeholder: translations.google_analytics_url,
                    value: settings.google_analytics_url
                }
            ]
        ]
    }

    handleClose () {
        this.setState({ success: false, error: false })
    }

    render () {
        return this.state.loaded === true ? (
            <React.Fragment>
                <Snackbar open={this.state.success} autoHideDuration={3000}  onClose={this.handleClose.bind(this)}>
                    <Alert severity="success">
                        {translations.settings_saved}
                    </Alert>
                </Snackbar>

                <Snackbar open={this.state.error} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                    <Alert severity="danger">
                        {translations.settings_not_saved}
                    </Alert>
                </Snackbar>

                <div className="topbar">
                    <Card className="m-0">
                        <CardBody className="p-0">
                            <div className="d-flex justify-content-between align-items-center">
                                <h4 className="pl-3 pt-2 pb-2">{translations.integration_settings}</h4>
                                <a className="pull-right pr-3" onClick={this.handleSubmit}>{translations.save}</a>
                            </div>
                        </CardBody>
                    </Card>
                </div>

                <Card className="fixed-margin-extra border-0">
                    <CardBody>
                        <FormBuilder
                            handleChange={this.handleSettingsChange}
                            formFieldsRows={this.getFields()}
                        />
                    </CardBody>
                </Card>
            </React.Fragment>
        ) : null
    }
}

export default IntegrationSettings
