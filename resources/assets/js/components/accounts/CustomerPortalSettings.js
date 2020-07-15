import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Button, Card, CardBody, CardHeader } from 'reactstrap'
import axios from 'axios'
import { toast, ToastContainer } from 'react-toastify'
import { translations } from '../common/_translations'

export default class CustomerPortalSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            settings: {}
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
                toast.error('There was an issue updating the settings')
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
                toast.success('Settings updated successfully')
            })
            .catch((error) => {
                toast.error('There was an issue updating the settings ' + error)
            })
    }

    getFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'portal_terms',
                    label: translations.customer_signup_terms,
                    type: 'textarea',
                    placeholder: translations.customer_signup_terms,
                    value: settings.portal_terms
                },
                {
                    name: 'portal_privacy_policy',
                    label: translations.portal_privacy_policy,
                    type: 'textarea',
                    placeholder: translations.portal_privacy_policy,
                    value: settings.portal_privacy_policy
                },
                {
                    name: 'portal_dashboard_message',
                    label: translations.dashboard_message,
                    type: 'textarea',
                    placeholder: translations.dashboard_message,
                    value: settings.portal_dashboard_message
                }
            ]
        ]
    }

    render () {
        return this.state.loaded === true ? (
            <React.Fragment>
                <ToastContainer/>
                <Card>
                    <CardHeader>{translations.customer_portal}</CardHeader>
                    <CardBody>
                        <FormBuilder
                            handleChange={this.handleSettingsChange}
                            formFieldsRows={this.getFields()}
                        />

                        <Button color="primary" onClick={this.handleSubmit}>{translations.save}</Button>
                    </CardBody>
                </Card>
            </React.Fragment>
        ) : null
    }
}
