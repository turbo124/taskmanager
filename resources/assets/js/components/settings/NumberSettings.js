import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Card, CardBody, Nav, NavItem, NavLink, TabContent, TabPane } from 'reactstrap'
import axios from 'axios'
import { translations } from '../utils/_translations'
import SnackbarMessage from '../common/SnackbarMessage'
import Header from './Header'
import AccountRepository from '../repositories/AccountRepository'

class NumberSettings extends Component {
    constructor (props) {
        super(props)

        this.state = {
            activeTab: '1',
            id: localStorage.getItem('account_id'),
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

    getSettingFields () {
        const settings = this.state.settings

        console.log('settings', settings)

        return [
            [
                {
                    name: 'recurring_number_prefix',
                    label: 'Recurring Prefix',
                    type: 'text',
                    placeholder: 'Recurring Prefix',
                    value: settings.recurring_number_prefix,
                    group: 1
                },
                {
                    name: 'counter_padding',
                    label: 'Counter Padding',
                    type: 'text',
                    placeholder: 'Counter Padding',
                    value: settings.counter_padding
                }
            ]
        ]
    }

    getInvoiceFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'invoice_number_pattern',
                    label: translations.number_pattern,
                    type: 'text',
                    placeholder: translations.number_pattern,
                    value: settings.invoice_number_pattern,
                    group: 1
                },
                {
                    name: 'invoice_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.invoice_number_counter
                }
            ]
        ]
    }

    getPurchaseOrderFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'purchaseorder_number_pattern',
                    label: translations.number_pattern,
                    type: 'text',
                    placeholder: translations.number_pattern,
                    value: settings.purchaseorder_number_pattern,
                    group: 1
                },
                {
                    name: 'purchaseorder_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.purchaseorder_number_counter
                }
            ]
        ]
    }

    getDealFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'deal_number_pattern',
                    label: translations.number_pattern,
                    type: 'text',
                    placeholder: translations.number_pattern,
                    value: settings.deal_number_pattern,
                    group: 1
                },
                {
                    name: 'deal_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.deal_number_counter
                }
            ]
        ]
    }

    getCaseFields () {
        const settings = this.state.settings

        const formFields = [
            [
                {
                    name: 'case_number_pattern',
                    label: translations.number_pattern,
                    type: 'text',
                    placeholder: translations.number_pattern,
                    value: settings.case_number_pattern,
                    group: 1
                },
                {
                    name: 'case_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.case_number_counter
                }
            ]
        ]

        return formFields
    }

    getTaskFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'task_number_pattern',
                    label: translations.number_pattern,
                    type: 'text',
                    placeholder: translations.number_pattern,
                    value: settings.task_number_pattern,
                    group: 1
                },
                {
                    name: 'task_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.task_number_counter
                }
            ]
        ]
    }

    getRecurringInvoiceFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'recurringinvoice_number_pattern',
                    label: translations.number_pattern,
                    type: 'text',
                    placeholder: translations.number_pattern,
                    value: settings.recurringinvoice_number_pattern,
                    group: 1
                },
                {
                    name: 'recurringinvoice_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.recurringinvoice_number_counter
                }
            ]
        ]
    }

    getRecurringQuoteFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'recurringquote_number_pattern',
                    label: translations.number_pattern,
                    type: 'text',
                    placeholder: translations.number_pattern,
                    value: settings.recurringquote_number_pattern,
                    group: 1
                },
                {
                    name: 'recurringquote_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.recurringquote_number_counter
                }
            ]
        ]
    }

    getOrderFields () {
        const settings = this.state.settings

        console.log('settings', settings)

        const formFields = [
            [
                {
                    name: 'order_number_pattern',
                    label: translations.number_pattern,
                    type: 'text',
                    placeholder: translations.number_pattern,
                    value: settings.order_number_pattern,
                    group: 1
                },
                {
                    name: 'order_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.order_number_counter
                }
            ]
        ]

        return formFields
    }

    getQuoteFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'quote_number_pattern',
                    label: translations.number_pattern,
                    type: 'text',
                    placeholder: translations.number_pattern,
                    value: settings.quote_number_pattern,
                    group: 1
                },
                {
                    name: 'quote_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.quote_number_counter
                },
                {
                    name: 'quote_design_id',
                    label: 'Quote Design',
                    type: 'select',
                    value: settings.quote_design_id,
                    options: [
                        {
                            value: '1',
                            text: 'Clean'
                        },
                        {
                            value: '2',
                            text: 'Bold'
                        },
                        {
                            value: '3',
                            text: 'Modern'
                        },
                        {
                            value: '4',
                            text: 'Plain'
                        }
                    ],
                    group: 1
                }
            ]
        ]
    }

    getCreditFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'credit_number_pattern',
                    label: translations.number_pattern,
                    type: 'text',
                    placeholder: translations.number_pattern,
                    value: settings.credit_number_pattern,
                    group: 1
                },
                {
                    name: 'credit_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.credit_number_counter
                }
                // {
                //     name: 'credit_design_id',
                //     label: 'Credit Design',
                //     type: 'select',
                //     value: settings.credit_design_id,
                //     options: [
                //         {
                //             value: '1',
                //             text: 'Clean'
                //         },
                //         {
                //             value: '2',
                //             text: 'Bold'
                //         },
                //         {
                //             value: '3',
                //             text: 'Modern'
                //         },
                //         {
                //             value: '4',
                //             text: 'Plain'
                //         }
                //     ],
                //     group: 1
                // }
            ]
        ]
    }

    getPaymentFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'payment_number_counter',
                    label: translations.number_counter,
                    type: 'text',
                    placeholder: translations.number_counter,
                    value: settings.payment_number_counter
                },
                {
                    name: 'payment_terms',
                    label: 'Payment Terms',
                    type: 'select',
                    placeholder: 'Payment Terms',
                    value: settings.payment_terms,
                    options: [
                        {
                            value: '1',
                            text: 'Yes'
                        },
                        {
                            value: '0',
                            text: 'No'
                        }
                    ]
                }
            ]
        ]
    }

    handleClose () {
        this.setState({ success: false, error: false })
    }

    render () {
        const modules = JSON.parse(localStorage.getItem('modules'))

        const tabs = <Nav tabs className="nav-justified setting-tabs disable-scrollbars">
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '1' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('1', e)
                    }}>
                    {translations.settings}
                </NavLink>
            </NavItem>

            {modules && modules.invoices &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '2' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('2', e)
                    }}>
                    {translations.invoices}
                </NavLink>
            </NavItem>
            }

            {modules && modules.quotes &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '3' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('3', e)
                    }}>
                    {translations.quotes}
                </NavLink>
            </NavItem>
            }

            {modules && modules.payments &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '4' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('4', e)
                    }}>
                    {translations.payments}
                </NavLink>
            </NavItem>
            }

            {modules && modules.credits &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '5' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('5', e)
                    }}>
                    {translations.credits}
                </NavLink>
            </NavItem>
            }

            {modules && modules.orders &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '6' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('6', e)
                    }}>
                    {translations.orders}
                </NavLink>
            </NavItem>
            }

            {modules && modules.purchase_orders &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '7' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('7', e)
                    }}>
                    {translations.POS}
                </NavLink>
            </NavItem>
            }

            {modules && modules.deals &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '8' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('8', e)
                    }}>
                    {translations.deals}
                </NavLink>
            </NavItem>
            }

            {modules && modules.cases &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '9' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('9', e)
                    }}>
                    {translations.cases}
                </NavLink>
            </NavItem>
            }

            {modules && modules.tasks &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '10' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('10', e)
                    }}>
                    {translations.tasks}
                </NavLink>
            </NavItem>
            }
            {modules && modules.recurringInvoices &&
            <NavItem>
                <NavLink
                    className={`${this.state.activeTab === '11' ? 'active' : ''} extra-tab-space`}
                    onClick={(e) => {
                        this.toggle('11', e)
                    }}>
                    {translations.recurring_invoices_abbr}
                </NavLink>
            </NavItem>
            }
            {modules && modules.recurringQuotes &&
            <NavItem>
                <NavLink
                    className={this.state.activeTab === '12' ? 'active' : ''}
                    onClick={(e) => {
                        this.toggle('12', e)
                    }}>
                    {translations.recurring_quotes_abbr}
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

                <Header title={translations.number_settings} handleSubmit={this.handleSubmit}
                    tabs={tabs}/>

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

                    {modules && modules.invoices &&
                    <TabPane tabId="2" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getInvoiceFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }

                    {modules && modules.quotes &&
                    <TabPane tabId="3" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getQuoteFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }

                    {modules && modules.payments &&
                    <TabPane tabId="4" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getPaymentFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }

                    {modules && modules.credits &&
                    <TabPane tabId="5" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getCreditFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }

                    {modules && modules.orders &&
                    <TabPane tabId="6" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getOrderFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }

                    {modules && modules.purchase_orders &&
                    <TabPane tabId="7" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getPurchaseOrderFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }

                    {modules && modules.deals &&
                    <TabPane tabId="8" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getDealFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }

                    {modules && modules.cases &&
                    <TabPane tabId="9" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getCaseFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }

                    {modules && modules.tasks &&
                    <TabPane tabId="10" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getTaskFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }

                    {modules && modules.recurringInvoices &&
                    <TabPane tabId="11" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getRecurringInvoiceFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }

                    {modules && modules.recurringQuotes &&
                    <TabPane tabId="12" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <FormBuilder
                                    handleChange={this.handleSettingsChange}
                                    formFieldsRows={this.getRecurringQuoteFields()}
                                />
                            </CardBody>
                        </Card>
                    </TabPane>
                    }
                </TabContent>
            </React.Fragment>
        ) : null
    }
}

export default NumberSettings
