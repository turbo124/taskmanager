import React, { Component } from 'react'
import { Alert, Card, CardBody, CardHeader, Col, Nav, NavItem, NavLink, Row, TabContent, TabPane } from 'reactstrap'
import ExpenseModel from '../../models/ExpenseModel'
import FormatDate from '../../common/FormatDate'
import { translations } from '../../utils/_translations'
import FileUploads from '../../documents/FileUploads'
import BottomNavigationButtons from '../../common/BottomNavigationButtons'
import CompanyModel from '../../models/CompanyModel'
import InvoiceModel from '../../models/InvoiceModel'
import EntityListTile from '../../common/entityContainers/EntityListTile'
import { icons } from '../../utils/_icons'
import Overview from './Overview'
import InvoiceRepository from '../../repositories/InvoiceRepository'
import ExpenseRepository from '../../repositories/ExpenseRepository'
import FormatMoney from '../../common/FormatMoney'

export default class Expense extends Component {
    constructor (props) {
        super(props)
        this.state = {
            entity: this.props.entity,
            categories: [],
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.expenseModel = new ExpenseModel(this.state.entity)
        this.companyModel = new CompanyModel()
        this.invoiceModel = new InvoiceModel()
        this.toggleTab = this.toggleTab.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
        this.refresh = this.refresh.bind(this)
    }

    componentDidMount () {
        this.getCategories()
    }

    refresh (entity) {
        this.expenseModel = new ExpenseModel(entity)
        this.setState({ entity: entity })
    }

    getCategories () {
        const expenseRepo = new ExpenseRepository()
        expenseRepo.getCategories().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ categories: response }, () => {
                console.log('categories', this.state.categories)
            })
        })
    }

    getCompanies () {
        this.companyModel.getCompanies().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ companies: response }, () => {
                console.log('companies', this.state.companies)
            })
        })
    }

    getInvoices () {
        const invoiceRepository = new InvoiceRepository()
        invoiceRepository.get().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ invoices: response }, () => {
                console.log('invoices', this.state.invoices)
            })
        })
    }

    triggerAction (action) {
        this.expenseModel.completeAction(this.state.entity, action).then(response => {
            this.setState({ show_success: true }, () => {
                this.props.updateState(response, this.refresh)
            })

            setTimeout(
                function () {
                    this.setState({ show_success: false })
                }
                    .bind(this),
                2000
            )
        })
    }

    toggleTab (tab) {
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab }, () => {
                if (this.state.activeTab === '3') {
                    this.loadPdf()
                }
            })
        }
    }

    render () {
        const category = this.state.categories.length ? this.state.categories.filter(category => category.id === parseInt(this.state.entity.category_id)) : []
        const convertedAmount = this.expenseModel.convertedAmount
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.state.entity.customer_id))

        let user = null

        if (this.state.entity.assigned_to) {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(this.state.entity.assigned_to))
            user = <EntityListTile entity={translations.user}
                title={`${assigned_user[0].first_name} ${assigned_user[0].last_name}`}
                icon={icons.user}/>
        }

        const fields = []

        if (this.state.entity.date.length) {
            fields.date = <FormatDate date={this.state.entity.date}/>
        }

        if (this.state.entity.transaction_reference.length) {
            fields.transaction_reference = this.state.entity.transaction_reference
        }

        if (this.expenseModel.isConverted) {
            fields.currency =
                JSON.parse(localStorage.getItem('currencies')).filter(currency => currency.id === this.expenseModel.invoice_currency_id)[0].name
        }

        if (this.state.entity.exchange_rate.length && this.expenseModel.isConverted) {
            fields.exchange_rate = this.state.entity.exchange_rate
        }

        if (this.state.entity.payment_date.length) {
            fields.payment_date = <FormatDate date={this.state.entity.payment_date}/>
        }

        if (category.length) {
            fields.category = category[0].name
        }

        const tax_total = this.expenseModel.calculateTaxes(false)

        if (tax_total > 0) {
            fields.tax = <FormatMoney amount={tax_total} customers={this.props.customers}/>
        }

        if (this.state.entity.custom_value1.length) {
            const label1 = this.expenseModel.getCustomFieldLabel('Expense', 'custom_value1')
            fields[label1] = this.expenseModel.formatCustomValue(
                'Expense',
                'custom_value1',
                this.state.entity.custom_value1
            )
        }

        if (this.state.entity.custom_value2.length) {
            const label2 = this.expenseModel.getCustomFieldLabel('Expense', 'custom_value2')
            fields[label2] = this.expenseModel.formatCustomValue(
                'Expense',
                'custom_value2',
                this.state.entity.custom_value2
            )
        }

        if (this.state.entity.custom_value3.length) {
            const label3 = this.expenseModel.getCustomFieldLabel('Expense', 'custom_value3')
            fields[label3] = this.expenseModel.formatCustomValue(
                'Expense',
                'custom_value3',
                this.state.entity.custom_value3
            )
        }

        if (this.state.entity.custom_value4.length) {
            const label4 = this.expenseModel.getCustomFieldLabel('Expense', 'custom_value4')
            fields[label4] = this.expenseModel.formatCustomValue(
                'Expense',
                'custom_value4',
                this.state.entity.custom_value4
            )
        }

        const recurring = []

        if (this.state.entity.is_recurring === true) {
            if (this.state.entity.recurring_start_date.length) {
                recurring.start_date = <FormatDate date={this.state.entity.recurring_start_date}/>
            }

            if (this.state.entity.recurring_end_date.length) {
                recurring.end_date = <FormatDate date={this.state.entity.recurring_end_date}/>
            }

            if (this.state.entity.recurring_due_date.length) {
                recurring.due_date = <FormatDate date={this.state.entity.recurring_due_date}/>
            }

            if (this.state.entity.recurring_frequency.toString().length) {
                recurring.frequency = this.state.entity.recurring_frequency.toString()
            }
        }

        return (
            <React.Fragment>
                <Nav tabs className="nav-justified disable-scrollbars">
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '1' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('1')
                            }}
                        >
                            {translations.details}
                        </NavLink>
                    </NavItem>
                    <NavItem>
                        <NavLink
                            className={this.state.activeTab === '2' ? 'active' : ''}
                            onClick={() => {
                                this.toggleTab('2')
                            }}
                        >
                            {translations.documents} ({this.expenseModel.fileCount})
                        </NavLink>
                    </NavItem>
                </Nav>
                <TabContent activeTab={this.state.activeTab}>
                    <TabPane tabId="1">
                        <Overview model={this.expenseModel} recurring={recurring} customer={customer} fields={fields}
                            user={user}
                            entity={this.state.entity}
                            customers={this.props.customers} convertedAmount={convertedAmount}/>
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader>{translations.documents}</CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Expense" entity={this.state.entity}
                                            user_id={this.state.entity.user_id}/>
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </TabPane>
                </TabContent>

                {this.state.show_success &&
                <Alert color="primary">
                    {translations.action_completed}
                </Alert>
                }

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('3')}
                    button1={{ label: translations.view_pdf }}
                    button2_click={(e) => this.triggerAction('clone_to_invoice')}
                    button2={{ label: translations.clone_to_invoice }}/>

            </React.Fragment>

        )
    }
}
