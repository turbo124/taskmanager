import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Button, Card, CardBody, CardHeader } from 'reactstrap'
import axios from 'axios'
import { toast, ToastContainer } from 'react-toastify'
import SignatureCanvas from 'react-signature-canvas'
import styles from './style.module.css'
import { translations } from '../common/_translations'
import { consts } from "../common/_consts";

class EmailSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            sigPad: {},
            settings: {}
        }

        this.handleSettingsChange = this.handleSettingsChange.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.getAccount = this.getAccount.bind(this)
        this.trim = this.trim.bind(this)
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
        const value = event.target.type === 'checkbox' ? event.target.checked : event.target.value

        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                [name]: value
            }
        }))
    }

    trim () {
        const value = this.state.sigPad.getTrimmedCanvas()
            .toDataURL('image/png')

        return new Promise((resolve, reject) => {
            this.setState(prevState => ({
                settings: {
                    ...prevState.settings,
                    email_signature: value
                }
            }), () => resolve(true))
        })
    }

    handleSubmit (e) {
        this.trim().then(result => {
            axios.put(`/api/accounts/${this.state.id}`, { settings: JSON.stringify(this.state.settings) }, {
            }).then((response) => {
                toast.success('Settings updated successfully')
            }).catch((error) => {
                toast.error(`There was an issue updating the settings ${error}`)
            })
        })
    }

    getFormFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'email_style',
                    label: translations.email_style,
                    type: 'select',
                    placeholder: translations.email_style,
                    value: settings.email_style,
                    options: [
                        {
                            value: consts.email_design_plain,
                            text: translations.plain
                        },
                        {
                            value: consts.email_design_light,
                            text: translations.light
                        },
                        {
                            value: consts.email_design_dark,
                            text: translations.dark
                        },
                        {
                            value: consts.email_design_custom,
                            text: translations.custom
                        }
                    ]
                },
                {
                    name: 'reply_to_email',
                    label: translations.reply_to_email,
                    type: 'text',
                    placeholder: translations.reply_to_email,
                    value: settings.reply_to_email
                },
                {
                    name: 'bcc_email',
                    label: translations.bcc_email,
                    type: 'text',
                    placeholder: translations.bcc_email,
                    value: settings.bcc_email
                },
                /* {
                    name: 'enable_email_markup',
                    label: translations.enable_email_markup,
                    type: 'switch',
                    placeholder: translations.enable_email_markup,
                    value: settings.enable_email_markup
                }, */
                {
                    name: 'pdf_email_attachment',
                    label: translations.pdf_email_attachment,
                    icon: 'fa fa-file-pdf-o',
                    type: 'switch',
                    placeholder: translations.pdf_email_attachment,
                    value: settings.pdf_email_attachment
                },
                {
                    name: 'document_email_attachment',
                    label: translations.document_email_attachment,
                    icon: 'fa fa-file-image-o',
                    type: 'switch',
                    placeholder: translations.document_email_attachment,
                    value: settings.document_email_attachment
                },
                {
                    name: 'ubl_email_attachment',
                    label: translations.ubl_email_attachment,
                    icon: 'fa fa-file-archive-o',
                    type: 'switch',
                    placeholder: translations.ubl_email_attachment,
                    value: settings.ubl_email_attachment
                }
            ]
        ]

        return formFields
    }

    render () {
        return this.state.loaded === true ? (
            <React.Fragment>
                <ToastContainer/>
                <Card>
                    <CardHeader>Settings</CardHeader>
                    <CardBody>
                        <FormBuilder
                            handleChange={this.handleSettingsChange}
                            formFieldsRows={this.getFormFields()}
                        />

                        <SignatureCanvas canvasProps={{ className: styles.sigPad }}
                            ref={(ref) => { this.state.sigPad = ref }} />

                        <Button color="primary" onClick={this.handleSubmit}>Save</Button>
                    </CardBody>
                </Card>
            </React.Fragment>
        ) : null
    }
}

export default EmailSettings
