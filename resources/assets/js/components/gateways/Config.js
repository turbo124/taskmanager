import React, { Component } from 'react'
import FormBuilder from '../accounts/FormBuilder'

export default class Config extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            settings: {}
        }
    }

    getAuthorizeConfig () {
        const settings = this.props.gateway.config

        const formFields = [
            [
                {
                    name: 'apiLoginId',
                    label: 'Api Login ID',
                    type: 'text',
                    placeholder: 'Api Login ID',
                    value: settings.apiLoginId
                },
                {
                    name: 'transactionKey',
                    label: 'Transaction Key',
                    type: 'password',
                    placeholder: 'Transaction Key',
                    value: settings.transactionKey
                }
            ]
        ]

        return formFields
    }

    getPaypalConfig () {
        const settings = this.props.gateway.config

        const formFields = [
            [
                {
                    name: 'password',
                    label: 'Password',
                    type: 'password',
                    placeholder: 'Password',
                    value: settings.password
                },
                {
                    name: 'signature',
                    label: 'Signature',
                    type: 'text',
                    placeholder: 'Signature',
                    value: settings.signature
                },
                {
                    name: 'username',
                    label: 'Username',
                    type: 'text',
                    placeholder: 'Username',
                    value: settings.username
                }
            ]
        ]

        return formFields
    }

    getStripeConfig () {
        const settings = this.props.gateway.config

        const formFields = [
            [
                {
                    name: 'apiKey',
                    label: 'Secret Key',
                    type: 'password',
                    placeholder: 'Secret Key',
                    value: settings.apiKey
                },
                {
                    name: 'publishable_key',
                    label: 'Publishable Key',
                    type: 'password',
                    placeholder: 'Publishable Key',
                    value: settings.publishable_key
                }
            ]
        ]

        return formFields
    }

    getFormFields (key) {
        switch (key) {
            case '8ab2dce2':
                return this.getAuthorizeConfig()
            case '64bcbdce':
                return this.getPaypalConfig()
            case '13bb8d58':
                return this.getStripeConfig()
        }
    }

    render () {
        const formFields = this.props.gateway.gateway_key && this.props.gateway.gateway_key.length ? this.getFormFields(this.props.gateway.gateway_key) : null

        return formFields !== null ? <FormBuilder
            handleChange={this.props.handleConfig}
            formFieldsRows={formFields}
        /> : null
    }
}
