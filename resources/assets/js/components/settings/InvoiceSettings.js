import React, { Component } from 'react'
import FormBuilder from './FormBuilder'
import { Alert, Card, CardBody, Nav, NavItem, NavLink, TabContent, TabPane } from 'reactstrap'
import axios from 'axios'
import { credit_pdf_fields } from '../models/CreditModel'
import { quote_pdf_fields } from '../models/QuoteModel'
import { invoice_pdf_fields } from '../models/InvoiceModel'
import PdfFields from './PdfFields'
import { translations } from '../utils/_translations'
import Snackbar from '@material-ui/core/Snackbar'
import { order_pdf_fields } from '../models/OrderModel'
import { purchase_order_pdf_fields } from '../models/PurchaseOrderModel'
import { customer_pdf_fields } from '../models/CustomerModel'
import { account_pdf_fields } from "../models/AccountModel";

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

        const formFields = [
            [
                {
                    name: 'invoice_design_id',
                    label: 'Invoice Design',
                    type: 'select',
                    value: settings.invoice_design_id,
                    options: [
                        {
                            value: '1',
                            text: translations.clean
                        },
                        {
                            value: '2',
                            text: translations.bold
                        },
                        {
                            value: '3',
                            text: translations.modern
                        },
                        {
                            value: '4',
                            text: translations.plain
                        }
                    ],
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

        return formFields
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
                                <h4 className="pl-3 pt-2">{translations.invoice_settings}</h4>
                                <a className="pull-right pr-3" onClick={this.handleSubmit}>{translations.save}</a>
                            </div>
                            <Nav className="nav-justified setting-tabs disable-scrollbars" tabs>
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
                                        {translations.purchase_order}
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
