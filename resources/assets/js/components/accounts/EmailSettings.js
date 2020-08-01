import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Card, CardBody, FormGroup, Label, Alert } from 'reactstrap'
import axios from 'axios'
import SignatureCanvas from 'react-signature-canvas'
import { translations } from '../common/_translations'
import { consts } from '../common/_consts'
import { icons } from '../common/_icons'
import Snackbar from '@material-ui/core/Snackbar'

class EmailSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            sigPad: {},
            settings: {},
            success: false,
            error: false
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
                this.setState({ error: true })
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
                this.setState({ success: true })
            }).catch((error) => {
                this.setState({ error: true })
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
                }
                /* {
                    name: 'enable_email_markup',
                    label: translations.enable_email_markup,
                    type: 'switch',
                    placeholder: translations.enable_email_markup,
                    value: settings.enable_email_markup
                }, */
            ]
        ]

        return formFields
    }

    getAttachmentFormFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'pdf_email_attachment',
                    label: translations.pdf_email_attachment,
                    icon: `fa ${icons.pdf}`,
                    type: 'switch',
                    placeholder: translations.pdf_email_attachment,
                    value: settings.pdf_email_attachment
                },
                {
                    name: 'document_email_attachment',
                    label: translations.document_email_attachment,
                    icon: `fa ${icons.image_file}`,
                    type: 'switch',
                    placeholder: translations.document_email_attachment,
                    value: settings.document_email_attachment
                },
                {
                    name: 'ubl_email_attachment',
                    label: translations.ubl_email_attachment,
                    icon: `fa ${icons.archive_file}`,
                    type: 'switch',
                    placeholder: translations.ubl_email_attachment,
                    value: settings.ubl_email_attachment
                }
            ]
        ]

        return formFields
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
                                <h4 className="pl-3 pt-2 pb-2">{translations.email_settings}</h4>
                                <a className="pull-right pr-3" onClick={this.handleSubmit}>{translations.save}</a>
                            </div>
                        </CardBody>
                    </Card>
                </div>

                <Card className="fixed-margin-extra border-0">
                    <CardBody>
                        <FormBuilder
                            handleChange={this.handleSettingsChange}
                            formFieldsRows={this.getFormFields()}
                        />
                    </CardBody>
                </Card>

                <Card className="border-0">
                    <CardBody>
                        <FormBuilder
                            handleChange={this.handleSettingsChange}
                            formFieldsRows={this.getAttachmentFormFields()}
                        />
                    </CardBody>
                </Card>

                <Card>
                    <CardBody>
                        <FormGroup>
                            <Label>Email Signature</Label>
                            <SignatureCanvas canvasProps={{ width: 1050, height: 200, className: 'sigCanvas border border-light' }}
                                ref={(ref) => { this.state.sigPad = ref }} />
                        </FormGroup>
                    </CardBody>
                </Card>
            </React.Fragment>
        ) : null
    }
}

export default EmailSettings
