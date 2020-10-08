import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Card, CardBody, Nav, NavItem, NavLink, TabContent, TabPane } from 'reactstrap'
import axios from 'axios'
import { credit_pdf_fields } from '../models/CreditModel'
import { quote_pdf_fields } from '../models/QuoteModel'
import { invoice_pdf_fields } from '../models/InvoiceModel'
import PdfFields from './PdfFields'
import { translations } from '../utils/_translations'
import { order_pdf_fields } from '../models/OrderModel'
import { purchase_order_pdf_fields } from '../models/PurchaseOrderModel'
import { customer_pdf_fields } from '../models/CustomerModel'
import { account_pdf_fields } from '../models/AccountModel'
import SnackbarMessage from '../common/SnackbarMessage'
import Header from './Header'
import AccountRepository from "../repositories/AccountRepository";
import { icons } from "../utils/_icons";
import BlockButton from "../common/BlockButton";

class InvoiceSettings extends Component {
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
        this.handleColumnChange = this.handleColumnChange.bind(this)
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

    handleColumnChange (values) {
        this.setState(prevState => ({
            settings: {
                ...prevState.settings,
                pdf_variables: values
            }
        }), () => this.handleSubmit())
    }

    handleSubmit () {
        const { settings } = this.state
        const formData = new FormData()
        formData.append('settings', JSON.stringify(settings))
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

        const design_options = [
            {
                value: '1',
                text: translations.basic
            },
            {
                value: '2',
                text: translations.danger
            },
            {
                value: '3',
                text: translations.dark
            },
            {
                value: '4',
                text: translations.happy
            },
            {
                value: '5',
                text: translations.info
            },
            {
                value: '6',
                text: translations.jazzy
            },
            {
                value: '7',
                text: translations.picture
            },
            {
                value: '8',
                text: translations.secondary
            },
            {
                value: '9',
                text: translations.simple
            },
            {
                value: '11',
                text: translations.warning
            }
        ]

        return [
            [
                {
                    name: 'invoice_design_id',
                    label: 'Invoice Design',
                    type: 'select',
                    value: settings.invoice_design_id,
                    options: design_options,
                    group: 1
                },
                {
                    name: 'case_design_id',
                    label: 'Case Design',
                    type: 'select',
                    value: settings.case_design_id,
                    options: design_options,
                    group: 1
                },
                {
                    name: 'task_design_id',
                    label: 'Task Design',
                    type: 'select',
                    value: settings.task_design_id,
                    options: design_options,
                    group: 1
                },
                {
                    name: 'purchase_order_design_id',
                    label: 'Purchase Order Design',
                    type: 'select',
                    value: settings.purchase_order_design_id,
                    options: design_options,
                    group: 1
                },
                {
                    name: 'lead_design_id',
                    label: 'Lead Design',
                    type: 'select',
                    value: settings.lead_design_id,
                    options: design_options,
                    group: 1
                },
                {
                    name: 'deal_design_id',
                    label: 'Deal Design',
                    type: 'select',
                    value: settings.deal_design_id,
                    options: design_options,
                    group: 1
                },
                {
                    name: 'credit_design_id',
                    label: 'Credit Design',
                    type: 'select',
                    value: settings.credit_design_id,
                    options: design_options,
                    group: 1
                },
                {
                    name: 'order_design_id',
                    label: 'Order Design',
                    type: 'select',
                    value: settings.order_design_id,
                    options: design_options,
                    group: 1
                },
                {
                    name: 'quote_design_id',
                    label: 'Quote Design',
                    type: 'select',
                    value: settings.quote_design_id,
                    options: design_options,
                    group: 1
                },
                {
                    name: 'page_size',
                    label: 'Page Size',
                    type: 'select',
                    value: settings.page_size,
                    options: [
                        {
                            value: 'A1',
                            text: 'A1'
                        },
                        {
                            value: 'A2',
                            text: 'A2'
                        },
                        {
                            value: 'A3',
                            text: 'A3'
                        },
                        {
                            value: 'A4',
                            text: 'A4'
                        },
                        {
                            value: 'A5',
                            text: 'A5'
                        },
                        {
                            value: 'A6',
                            text: 'A6'
                        }
                    ],
                    group: 1
                }
            ]
        ]
    }

    getInvoiceSettingFields () {
        const settings = this.state.settings

        return [
            [
                {
                    name: 'all_pages_header',
                    label: 'Show Header On',
                    type: 'select',
                    value: settings.all_pages_header,
                    options: [
                        {
                            value: 'true',
                            text: 'All Pages'
                        },
                        {
                            value: 'false',
                            text: 'First Page'
                        }
                    ],
                    group: 1
                },
                {
                    name: 'all_pages_footer',
                    label: 'Show Footer On',
                    type: 'select',
                    value: settings.all_pages_footer,
                    options: [
                        {
                            value: 'true',
                            text: 'All Pages'
                        },
                        {
                            value: 'false',
                            text: 'First Page'
                        }
                    ],
                    group: 1
                }
            ]
        ]
    }

    getInvoiceFields () {
        return invoice_pdf_fields
    }

    getOrderFields () {
        return order_pdf_fields
    }

    getPurchaseOrderFields () {
        return purchase_order_pdf_fields
    }

    getQuoteFields () {
        return quote_pdf_fields
    }

    getCreditFields () {
        return credit_pdf_fields
    }

    getProductFields () {
        return []
    }

    getTaskFields () {
        return []
    }

    handleClose () {
        this.setState({ success: false, error: false })
    }

    render () {
        const tabs = <Nav className="nav-justified setting-tabs disable-scrollbars" tabs>
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
                    {translations.invoice}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '3' ? 'active' : ''}
                    onClick={() => {
                        this.toggle('3')
                    }}>
                    {translations.customer}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '4' ? 'active' : ''}
                    onClick={() => {
                        this.toggle('4')
                    }}>
                    {translations.account}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '5' ? 'active' : ''}
                    onClick={() => {
                        this.toggle('5')
                    }}>
                    {translations.invoice}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '6' ? 'active' : ''}
                    onClick={() => {
                        this.toggle('6')
                    }}>
                    {translations.quote}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '7' ? 'active' : ''}
                    onClick={() => {
                        this.toggle('7')
                    }}>
                    {translations.order}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '8' ? 'active' : ''}
                    onClick={() => {
                        this.toggle('8')
                    }}>
                    PO's
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '9' ? 'active' : ''}
                    onClick={() => {
                        this.toggle('9')
                    }}>
                    {translations.credit}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '10' ? 'active' : ''}
                    onClick={() => {
                        this.toggle('10')
                    }}>
                    {translations.product}
                </NavLink>
            </NavItem>

            <NavItem>
                <NavLink
                    className={this.state.activeTab === '11' ? 'active' : ''}
                    onClick={() => {
                        this.toggle('11')
                    }}>
                    {translations.task}
                </NavLink>
            </NavItem>
        </Nav>

        return this.state.loaded === true ? (
            <React.Fragment>
                <SnackbarMessage open={this.state.success} onClose={this.handleClose.bind(this)} severity="success"
                    message={translations.settings_saved}/>

                <SnackbarMessage open={this.state.error} onClose={this.handleClose.bind(this)} severity="danger"
                    message={translations.settings_not_saved}/>

                <Header title={translations.invoice_settings} handleSubmit={this.handleSubmit}
                    tabs={tabs}/>

                <TabContent className="fixed-margin-mobile bg-transparent" activeTab={this.state.activeTab}>
                    <TabPane tabId="1" className="px-0">
                        <BlockButton icon={icons.link} button_text={translations.customize_and_preview}
                                     button_link="/#/designs"/>

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
                            <CardBody/>
                        </Card>
                    </TabPane>

                    <TabPane tabId="3" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <PdfFields onChange2={this.handleColumnChange} settings={this.state.settings}
                                    section="client_details" columns={customer_pdf_fields}
                                    ignored_columns={this.state.settings.pdf_variables}/>
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="4" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <PdfFields onChange2={this.handleColumnChange} settings={this.state.settings}
                                    section="company_details" columns={account_pdf_fields}
                                    ignored_columns={this.state.settings.pdf_variables}/>
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="5" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <PdfFields onChange2={this.handleColumnChange} settings={this.state.settings}
                                    section="invoice" columns={this.getInvoiceFields()}
                                    ignored_columns={this.state.settings.pdf_variables}/>
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="6" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <PdfFields onChange2={this.handleColumnChange} settings={this.state.settings}
                                    section="quote" columns={this.getQuoteFields()}
                                    ignored_columns={this.state.settings.pdf_variables}/>
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="7" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <PdfFields onChange2={this.handleColumnChange} settings={this.state.settings}
                                    section="order" columns={this.getOrderFields()}
                                    ignored_columns={this.state.settings.pdf_variables}/>
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="8" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <PdfFields onChange2={this.handleColumnChange} settings={this.state.settings}
                                    section="purchase_order" columns={this.getPurchaseOrderFields()}
                                    ignored_columns={this.state.settings.pdf_variables}/>
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="9" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <PdfFields onChange2={this.handleColumnChange} settings={this.state.settings}
                                    section="credit" columns={this.getCreditFields()}
                                    ignored_columns={this.state.settings.pdf_variables}/>
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="10" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <PdfFields onChange2={this.handleColumnChange} settings={this.state.settings}
                                    section="product_columns" columns={this.getProductFields()}
                                    ignored_columns={this.state.settings.pdf_variables}/>
                            </CardBody>
                        </Card>
                    </TabPane>

                    <TabPane tabId="11" className="px-0">
                        <Card className="border-0">
                            <CardBody>
                                <PdfFields onChange2={this.handleColumnChange} settings={this.state.settings}
                                    section="task_columns" columns={this.getTaskFields()}
                                    ignored_columns={this.state.settings.pdf_variables}/>
                            </CardBody>
                        </Card>
                    </TabPane>
                </TabContent>

            </React.Fragment>
        ) : null
    }
}

export default InvoiceSettings
