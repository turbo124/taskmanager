import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Card, CardBody, Nav, NavItem, NavLink, TabContent, TabPane } from 'reactstrap'
import axios from 'axios'
import { icons } from '../utils/_icons'
import { translations } from '../utils/_translations'
import { consts } from '../utils/_consts'
import SnackbarMessage from '../common/SnackbarMessage'
import Header from './Header'
import AccountRepository from '../repositories/AccountRepository'

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

    toggle (tab, e) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }

        const parent = e.currentTarget.parentNode
        const rect = parent.getBoundingClientRect()
        const rect2 = parent.nextSibling.getBoundingClientRect()
        const rect3 = parent.previousSibling.getBoundingClientRect()
        const winWidth = window.innerWidth || document.documentElement.clientWidth
        const widthScroll = winWidth * 33 / 100

        if (rect.left <= 10 || rect3.left <= 10) {
            const container = document.getElementsByClassName('setting-tabs')[0]
            container.scrollLeft -= widthScroll
        }

        if (rect.right >= winWidth - 10 || rect2.right >= winWidth - 10) {
            const container = document.getElementsByClassName('setting-tabs')[0]
            container.scrollLeft += widthScroll
        }
    }

    getAccount () {
        const accountRepository = new AccountRepository()
        accountRepository.getById(this.state.id).then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({
                loaded: true,
                settings: response.settings
            }, () => {
                console.log(response)
            })
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

    getPurchaseOrderFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'should_email_purchase_order',
                    label: 'Auto Email',
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.should_email_purchase_order,
                    group: 1
                },
                {
                    name: 'should_archive_purchase_order',
                    label: 'Auto Archive',
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.should_archive_purchase_order,
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getExpenseFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'create_expense_invoice',
                    label: translations.create_expense_invoice,
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.create_expense_invoice,
                    help_text: translations.create_expense_invoice_help,
                    group: 1
                },
                {
                    name: 'include_expense_documents',
                    label: translations.include_expense_documents,
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.include_expense_documents,
                    help_text: translations.include_expense_documents_help,
                    group: 1
                },
                {
                    name: 'create_expense_payment',
                    label: translations.create_expense_payment,
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.create_expense_payment,
                    help_text: translations.create_expense_payment_help,
                    group: 1
                },
                {
                    name: 'convert_expense_currency',
                    label: translations.convert_expense_currency,
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.convert_expense_currency,
                    help_text: translations.convert_expense_currency_help,
                    group: 1
                }
            ]
        ]

        return formFields
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

    getDealFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'should_email_deal',
                    label: 'Auto Email',
                    icon: `fa ${icons.envelope}`,
                    type: 'switch',
                    value: settings.should_email_deal,
                    group: 1
                },
                {
                    name: 'should_archive_deal',
                    label: 'Auto Archive',
                    icon: `fa ${icons.archive}`,
                    type: 'switch',
                    value: settings.should_archive_deal,
                    group: 1
                },
                {
                    name: 'should_convert_deal',
                    label: 'Auto Convert',
                    icon: `fa ${icons.book}`,
                    type: 'switch',
                    value: settings.should_convert_deal,
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getCaseFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'default_case_priority',
                    label: translations.default_case_priority,
                    icon: `fa ${icons.envelope}`,
                    type: 'select',
                    options: [
                        {
                            value: consts.low_priority,
                            text: translations.low
                        },
                        {
                            value: consts.medium_priority,
                            text: translations.medium
                        },
                        {
                            value: consts.high_priority,
                            text: translations.high
                        }
                    ],
                    value: settings.default_case_priority,
                    group: 1
                }
            ]
        ]

        return formFields
    }

    getPaymentFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'invoice_payment_deleted_status',
                    label: translations.invoice_payment_deleted_status,
                    icon: `fa ${icons.envelope}`,
                    type: 'select',
                    options: [
                        {
                            value: consts.invoice_status_draft,
                            text: translations.draft
                        },
                        {
                            value: consts.invoice_status_sent,
                            text: translations.sent
                        },
                        {
                            value: 100,
                            text: translations.deleted
                        }
                    ],
                    value: settings.invoice_payment_deleted_status,
                    group: 1
                },
                {
                    name: 'credit_payment_deleted_status',
                    label: translations.credit_payment_deleted_status,
                    icon: `fa ${icons.envelope}`,
                    type: 'select',
                    options: [
                        {
                            value: consts.credit_status_draft,
                            text: translations.draft
                        },
                        {
                            value: consts.credit_status_sent,
                            text: translations.sent
                        },
                        {
                            value: 100,
                            text: translations.deleted
                        }
                    ],
                    value: settings.credit_payment_deleted_status,
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
        const modules = JSON.parse(localStorage.getItem('modules'))
        const tabs = <Nav tabs className="nav-justified setting-tabs disable-scrollbars">
            {modules && modules.invoices &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '1' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('1', e)
                    }}>
                    {translations.invoices}
                </NavLink>
            </NavItem>
            }

            {modules && modules.quotes &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '2' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('2', e)
                    }}>
                    {translations.quotes}
                </NavLink>
            </NavItem>
            }

            {modules && modules.leads &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '3' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('3', e)
                    }}>
                    {translations.leads}
                </NavLink>
            </NavItem>
            }

            {modules && modules.orders &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '4' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('4', e)
                    }}>
                    {translations.orders}
                </NavLink>
            </NavItem>
            }

            {modules && modules.deals &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '5' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('5', e)
                    }}>
                    {translations.deals}
                </NavLink>
            </NavItem>
            }

            {modules && modules.purchase_orders &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '6' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('6', e)
                    }}>
                    {translations.POS}
                </NavLink>
            </NavItem>
            }

            {modules && modules.cases &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '7' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('7', e)
                    }}>
                    {translations.cases}
                </NavLink>
            </NavItem>
            }

            {modules && modules.payments &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '8' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('8', e)
                    }}>
                    {translations.payments}
                </NavLink>
            </NavItem>
            }

            {modules && modules.expenses &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '9' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('9', e)
                    }}>
                    {translations.expenses}
                </NavLink>
            </NavItem>
            }
        </Nav>

        return this.state.loaded === true ? (
            <React.Fragment>
                <SnackbarMessage open={this.state.success} onClose={this.handleClose.bind(this)} severity="success"
                    message={translations.settings_saved}/>

                <SnackbarMessage open={this.state.error} onClose={this.handleClose.bind(this)} severity="danger"
                    message={translations.settings_not_saved}/>

                <Header title={translations.workflow_settings} handleSubmit={this.handleSubmit}
                    tabs={tabs}/>

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

                    <TabPane className="pr-0 pl-0" tabId="5">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getOrderFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane className="pr-0 pl-0" tabId="6">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getPurchaseOrderFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane className="pr-0 pl-0" tabId="7">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getCaseFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane className="pr-0 pl-0" tabId="8">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getPaymentFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane className="pr-0 pl-0" tabId="9">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getExpenseFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                </TabContent>

            </React.Fragment>
        ) : null
    }
}
