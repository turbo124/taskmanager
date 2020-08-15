import React, { Component } from 'react'
import { Alert, Button, Card, CardBody, FormGroup, Label, Row } from 'reactstrap'
import axios from 'axios'
import { translations } from '../common/_translations'
import Snackbar from '@material-ui/core/Snackbar'
import FormBuilder from './FormBuilder'

export default class DeviceSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            settings: {
                dark_theme: !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'dark-theme' : 'light-theme',
                number_of_rows: localStorage.getItem('number_of_rows') || 10
            },
            success: false,
            error: false
        }

        this.handleSettingsChange = this.handleSettingsChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.getAccount = this.getAccount.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleHeaderColor = this.handleHeaderColor.bind(this)
        this.handleFooterColor = this.handleFooterColor.bind(this)
        this.refresh = this.refresh.bind(this)
    }

    componentDidMount () {
        // this.getAccount()
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

    handleHeaderColor (event) {
        const value = event.target.dataset.name
        const text = event.target.dataset.text

        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                header_background_color: value,
                header_text_color: text
            }
        }), () => {
            this.setStorage()
        })
    }

    handleFooterColor (event) {
        const value = event.target.dataset.name
        const text = event.target.dataset.text

        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                footer_background_color: value,
                footer_text_color: text
            }
        }), () => {
            this.setStorage()
        })
    }

    setStorage () {
        const device_settings = {
            footer_background_color: this.state.settings.footer_background_color || 'bg-dark',
            footer_text_color: this.state.settings.footer_text_color || 'bg-light',
            header_background_color: this.state.settings.header_background_color || 'bg-dark',
            header_text_color: this.state.settings.header_text_color || 'bg-light'
        }

        localStorage.setItem('device_settings', JSON.stringify(device_settings))
    }

    refresh () {
        axios.get('/api/accounts/refresh')
            .then((response) => {
                if (response.data.success === true) {
                    const userData = {
                        name: response.data.data.name,
                        id: response.data.data.id,
                        email: response.data.data.email,
                        account_id: response.data.data.account_id,
                        auth_token: response.data.data.auth_token,
                        timestamp: new Date().toString()
                    }

                    const appState = {
                        isLoggedIn: true,
                        user: userData,
                        accounts: response.data.data.accounts
                    }

                    // save app state with user date in local storage
                    localStorage.appState = JSON.stringify(appState)
                    localStorage.setItem('account_id', response.data.data.account_id)
                    localStorage.setItem('currencies', JSON.stringify(response.data.data.currencies))
                    localStorage.setItem('languages', JSON.stringify(response.data.data.languages))
                    localStorage.setItem('countries', JSON.stringify(response.data.data.countries))
                }
            })
    }

    handleSettingsChange (event) {
        const name = event.target.name
        const value = event.target.type === 'checkbox' ? event.target.checked : event.target.value

        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                [name]: value
            }
        }), () => {
            switch (name) {
                case 'currency_format':
                    localStorage.setItem('currency_format', value)
                    break

                case 'dark_theme':
                    localStorage.setItem('dark_theme', value)
                    break

                case 'number_of_rows':
                    localStorage.setItem('number_of_rows', value)
                    break
            }
        })
    }

    getInventoryFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'dark_theme',
                    label: translations.dark_theme,
                    type: 'switch',
                    placeholder: translations.dark_theme,
                    value: settings.dark_theme
                },
                {
                    name: 'number_of_rows',
                    label: translations.number_of_rows,
                    type: 'select',
                    placeholder: translations.number_of_rows,
                    value: settings.number_of_rows,
                    options: [
                        {
                            value: 10,
                            text: 10
                        },
                        {
                            value: 25,
                            text: 25
                        },
                        {
                            value: 50,
                            text: 50
                        }
                    ]
                }
            ]
        ]

        return formFields
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
                this.setState({ success: true })
            })
            .catch((error) => {
                this.setState({ error: true })
            })
    }

    handleChange (event) {
        this.setState({ [event.target.name]: event.target.value })
    }

    handleClose () {
        this.setState({ success: false, error: false })
    }

    render () {
        const header_background_color = this.state.settings && this.state.settings.header_background_color ? this.state.settings.header_background_color : ''
        const colors = [
            { value: 'bg-success', label: 'Success', text_color: 'text-light' },
            { value: 'bg-secondary', label: 'Secondary', text_color: 'text-dark' },
            { value: 'bg-primary', label: 'Primary', text_color: 'text-light' },
            { value: 'bg-danger', label: 'Danger', text_color: 'text-light' },
            { value: 'bg-light', label: 'Light', text_color: 'text-dark' },
            { value: 'bg-info', label: 'Info', text_color: 'text-light' },
            { value: 'bg-dark', label: 'Dark', text_color: 'text-light' }
        ]

        return (
            <React.Fragment>
                <Snackbar open={this.state.success} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
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
                                <h4 className="pl-3 pt-2 pb-2">{translations.device_settings}</h4>
                                {/* <a className="pull-right pr-3" onClick={this.handleSubmit}>{translations.save}</a> */}
                            </div>
                        </CardBody>
                    </Card>
                </div>

                <Card className="fixed-margin-extra border-0">
                    <CardBody>
                        <Row>
                            <FormGroup className="col-4 d-flex justify-content-between align-items-center">
                                <Label>{translations.header_background_color}</Label>
                                <div className="col-4 d-flex justify-content-between align-items-center">
                                    {colors.map((color, idx) => {
                                        const selected = color.value === header_background_color ? 'border border-danger' : ''
                                        return <span style={{ borderWidth: '3px !important' }}
                                            data-text={color.text_color} data-name={color.value}
                                            onClick={this.handleHeaderColor}
                                            className={`${color.value} ${color.text_color} p-1 m-1 ${selected}`}>{color.label}</span>
                                    })}
                                </div>
                            </FormGroup>
                        </Row>

                        <Row>
                            <FormGroup className="mt-2 col-4 d-flex justify-content-between align-items-center">
                                <Label>{translations.footer_background_color}</Label>
                                <div className="col-4 d-flex justify-content-between align-items-center">
                                    {colors.map((color, idx) => {
                                        const selected = color.value === header_background_color ? 'border border-danger' : ''
                                        return <span style={{ borderWidth: '3px !important' }}
                                            data-text={color.text_color} data-name={color.value}
                                            onClick={this.handleFooterColor}
                                            className={`${color.value} ${color.text_color} p-1 m-1 ${selected}`}>{color.label}</span>
                                    })}
                                </div>
                            </FormGroup>
                        </Row>

                    </CardBody>
                </Card>

                <Card className="border-0">
                    <CardBody>
                        <FormBuilder
                            handleChange={this.handleSettingsChange}
                            formFieldsRows={this.getInventoryFields()}
                        />
                    </CardBody>
                </Card>

                <Card>
                    <CardBody>
                        <Button onClick={this.refresh} color="primary" block>{translations.refresh}</Button>
                    </CardBody>
                </Card>
            </React.Fragment>
        )
    }
}
