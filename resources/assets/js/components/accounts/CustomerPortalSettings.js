import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Alert, Card, CardBody, NavLink, Nav, NavItem, TabContent, TabPane, ListGroup } from 'reactstrap'
import axios from 'axios'
import { translations } from '../common/_translations'
import { icons } from '../common/_icons'
import Snackbar from '@material-ui/core/Snackbar'

export default class CustomerPortalSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            activeTab: '1',
            settings: {},
            success: false,
            error: false
        }

        this.handleSettingsChange = this.handleSettingsChange.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.getAccount = this.getAccount.bind(this)
        this.toggle = this.toggle.bind(this)
    }

    componentDidMount () {
        this.getAccount()
    }

    toggle (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
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

    getSettingFields () {
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

    getSecurityFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'display_invoice_terms',
                    label: translations.display_invoice_terms,
                    icon: `fa ${icons.checkbox_o}`,
                    type: 'switch',
                    placeholder: translations.display_invoice_terms,
                    value: settings.display_invoice_terms
                },
                {
                    name: 'display_quote_terms',
                    label: translations.display_quote_terms,
                    icon: `fa ${icons.checkbox_o}`,
                    type: 'switch',
                    placeholder: translations.display_quote_terms,
                    value: settings.display_quote_terms
                },
                {
                    name: 'display_invoice_signature',
                    label: translations.display_invoice_signature,
                    icon: `fa ${icons.pencil}`,
                    type: 'switch',
                    placeholder: translations.display_invoice_signature,
                    value: settings.display_invoice_signature
                },
                {
                    name: 'display_quote_signature',
                    label: translations.display_quote_signature,
                    icon: `fa ${icons.pencil}`,
                    type: 'switch',
                    placeholder: translations.display_quote_signature,
                    value: settings.display_quote_signature
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
                                <h4 className="pl-3 pt-2">{translations.customer_portal}</h4>
                                <a className="pull-right pr-3" onClick={this.handleSubmit}>{translations.save}</a>
                            </div>

                            <Nav tabs className="nav-justified setting-tabs disable-scrollbars">
                                <NavItem>
                                    <NavLink
                                        className={this.state.activeTab === '1' ? 'active' : ''}
                                        onClick={() => {
                                            this.toggle('1')
                                        }}>
                                        {translations.settings}
                                    </NavLink>
                                </NavItem>

                                <NavItem>
                                    <NavLink
                                        className={this.state.activeTab === '2' ? 'active' : ''}
                                        onClick={() => {
                                            this.toggle('2')
                                        }}>
                                        {translations.security}
                                    </NavLink>
                                </NavItem>
                            </Nav>
                        </CardBody>
                    </Card>
                </div>

                <TabContent className="fixed-margin-mobile bg-transparent" activeTab={this.state.activeTab}>
                    <TabPane tabId="1" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getSettingFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="2" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getSecurityFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                </TabContent>
            </React.Fragment>
        ) : null
    }
}
