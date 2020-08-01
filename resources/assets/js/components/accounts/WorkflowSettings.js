import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Alert, Card, CardBody, NavLink, Nav, NavItem, TabContent, TabPane } from 'reactstrap'
import axios from 'axios'
import { icons } from '../common/_icons'
import { translations } from '../common/_translations'
import { consts } from '../common/_consts'
import Snackbar from '@material-ui/core/Snackbar'

export default class WorkflowSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            id: localStorage.getItem('account_id'),
            settings: {},
            activeTab: '1',
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
                console.error(error)
                this.setState({ error: true })
            })
    }

    getInvoiceFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'should_lock_invoice',
                    label: translations.lock_invoice,
                    type: 'select',
                    value: settings.should_lock_invoice,
                    options: [
                        {
                            value: consts.lock_invoices_off,
                            text: translations.off
                        },
                        {
                            value: consts.lock_invoices_sent,
                            text: translations.when_sent
                        },
                        {
                            value: consts.lock_invoices_paid,
                            text: translations.when_paid
                        }
                    ]
                },
                {
                    name: 'should_email_invoice',
                    label: 'Auto Email',
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.should_email_invoice,
                    group: 1
                },
                {
                    name: 'should_archive_invoice',
                    label: 'Auto Archive',
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.should_archive_invoice,
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getOrderFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'should_email_order',
                    label: 'Auto Email',
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.should_email_order,
                    group: 1
                },
                {
                    name: 'should_archive_order',
                    label: 'Auto Archive',
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.should_archive_order,
                    group: 1
                },
                {
                    name: 'should_convert_order',
                    label: 'Auto Convert',
                    icon: `fa ${icons.book}`,
                    type: 'switch',
                    value: settings.should_convert_order,
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getLeadFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'should_email_lead',
                    label: 'Auto Email',
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.should_email_lead,
                    group: 1
                },
                {
                    name: 'should_archive_lead',
                    label: 'Auto Archive',
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.should_archive_lead,
                    group: 1
                },
                {
                    name: 'should_convert_lead',
                    label: 'Auto Convert',
                    icon: `fa ${icons.book}`,
                    type: 'switch',
                    value: settings.should_convert_lead,
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getQuoteFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'should_email_quote',
                    label: 'Auto Email',
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.should_email_quote,
                    group: 1
                },
                {
                    name: 'should_archive_quote',
                    label: 'Auto Archive',
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.should_archive_quote,
                    group: 1
                },
                {
                    name: 'should_convert_quote',
                    label: 'Auto Convert',
                    icon: `fa ${icons.book}`,
                    type: 'switch',
                    value: settings.should_convert_quote,
                    group: 1
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
                                <h4 className="pl-3 pt-2">{translations.workflow_settings}</h4>
                                <a className="pull-right pr-3" onClick={this.handleSubmit}>{translations.save}</a>
                            </div>
                            <Nav tabs className="nav-justified setting-tabs disable-scrollbars">
                                <NavItem>
                                    <NavLink
                                        className={this.state.activeTab === '1' ? 'active' : ''}
                                        onClick={() => {
                                            this.toggle('1')
                                        }}>
                                        {translations.invoices}
                                    </NavLink>
                                </NavItem>

                                <NavItem>
                                    <NavLink
                                        className={this.state.activeTab === '2' ? 'active' : ''}
                                        onClick={() => {
                                            this.toggle('2')
                                        }}>
                                        {translations.quotes}
                                    </NavLink>
                                </NavItem>

                                <NavItem>
                                    <NavLink
                                        className={this.state.activeTab === '3' ? 'active' : ''}
                                        onClick={() => {
                                            this.toggle('3')
                                        }}>
                                        {translations.leads}
                                    </NavLink>
                                </NavItem>

                                <NavItem>
                                    <NavLink
                                        className={this.state.activeTab === '4' ? 'active' : ''}
                                        onClick={() => {
                                            this.toggle('4')
                                        }}>
                                        {translations.orders}
                                    </NavLink>
                                </NavItem>
                            </Nav>
                        </CardBody>
                    </Card>
                </div>

                <TabContent className="fixed-margin-mobile bg-transparent" activeTab={this.state.activeTab}>
                    <TabPane className="px-0" tabId="1">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getInvoiceFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane className="px-0" tabId="2">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getQuoteFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane className="pr-0 pl-0" tabId="3">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getLeadFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane className="pr-0 pl-0" tabId="4">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getOrderFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                </TabContent>

            </React.Fragment>
        ) : null
    }
}
