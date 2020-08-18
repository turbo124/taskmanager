import React, { Component } from 'react'
import {
    Alert,
    Card,
    CardBody,
    CardHeader,
    Col,
    ListGroup,
    ListGroupItem,
    ListGroupItemHeading,
    Nav,
    NavItem,
    NavLink,
    Row,
    TabContent,
    TabPane
} from 'reactstrap'
import ExpenseModel from '../models/ExpenseModel'
import ExpensePresenter from '../presenters/ExpensePresenter'
import FormatDate from '../common/FormatDate'
import { translations } from '../common/_translations'
import FileUploads from '../attachments/FileUploads'
import ViewEntityHeader from '../common/entityContainers/ViewEntityHeader'
import BottomNavigationButtons from '../common/BottomNavigationButtons'
import FieldGrid from '../common/entityContainers/FieldGrid'
import CompanyModel from '../models/CompanyModel'
import InvoiceModel from '../models/InvoiceModel'
import InfoMessage from '../common/entityContainers/InfoMessage'
import EntityListTile from '../common/entityContainers/EntityListTile'
import { icons } from '../common/_icons'

export default class Expense extends Component {
    constructor (props) {
        super(props)
        this.state = {
            categories: [],
            activeTab: '1',
            obj_url: null,
            show_success: false
        }

        this.expenseModel = new ExpenseModel(this.props.entity)
        this.companyModel = new CompanyModel()
        this.invoiceModel = new InvoiceModel()
        this.toggleTab = this.toggleTab.bind(this)
        this.triggerAction = this.triggerAction.bind(this)
    }

    componentDidMount () {
        this.getCategories()
    }

    getCategories () {
        this.expenseModel.getGateways().then(response => {
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
        this.invoiceModel.getInvoices().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ invoices: response }, () => {
                console.log('invoices', this.state.invoices)
            })
        })
    }

    triggerAction (action) {
        this.expenseModel.completeAction(this.props.entity, action).then(response => {
            this.setState({ show_success: true })

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
        const category = this.state.categories.length ? this.state.categories.filter(category => category.id === parseInt(this.props.entity.category_id)) : []
        const convertedAmount = this.expenseModel.convertedAmount
        const customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.entity.customer_id))
        const listClass = !Object.prototype.hasOwnProperty.call(localStorage, 'dark_theme') || (localStorage.getItem('dark_theme') && localStorage.getItem('dark_theme') === 'true') ? 'list-group-item-dark' : ''

        let user = null

        if (this.props.entity.assigned_to) {
            const assigned_user = JSON.parse(localStorage.getItem('users')).filter(user => user.id === parseInt(this.props.entity.assigned_to))
            user = <EntityListTile entity={translations.user} title={`${assigned_user[0].first_name} ${assigned_user[0].last_name}`} icon={icons.user} />
        }

        const fields = []

        if (this.props.entity.date.length) {
            fields.date = <FormatDate date={this.props.entity.date} />
        }

        if (this.props.entity.transaction_reference.length) {
            fields.transaction_reference = this.props.entity.transaction_reference
        }

        fields.currency =
            JSON.parse(localStorage.getItem('currencies')).filter(currency => currency.id === this.expenseModel.currencyId)[0].name

        if (this.props.entity.exchange_rate.length) {
            fields.exchange_rate = this.props.entity.exchange_rate
        }

        if (this.props.entity.payment_date.length) {
            fields.payment_date = <FormatDate date={this.props.entity.payment_date} />
        }

        if (category.length) {
            fields.category = category[0].name
        }

        if (this.props.entity.custom_value1.length) {
            const label1 = this.expenseModel.getCustomFieldLabel('Expense', 'custom_value1')
            fields[label1] = this.expenseModel.formatCustomValue(
                'Expense',
                'custom_value1',
                this.props.entity.custom_value1
            )
        }

        if (this.props.entity.custom_value2.length) {
            const label2 = this.expenseModel.getCustomFieldLabel('Expense', 'custom_value2')
            fields[label2] = this.expenseModel.formatCustomValue(
                'Expense',
                'custom_value2',
                this.props.entity.custom_value2
            )
        }

        if (this.props.entity.custom_value3.length) {
            const label3 = this.expenseModel.getCustomFieldLabel('Expense', 'custom_value3')
            fields[label3] = this.expenseModel.formatCustomValue(
                'Expense',
                'custom_value3',
                this.props.entity.custom_value3
            )
        }

        if (this.props.entity.custom_value4.length) {
            const label4 = this.expenseModel.getCustomFieldLabel('Expense', 'custom_value4')
            fields[label4] = this.expenseModel.formatCustomValue(
                'Expense',
                'custom_value4',
                this.props.entity.custom_value4
            )
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
                        <ViewEntityHeader heading_1={translations.amount} value_1={this.props.entity.amount}
                            heading_2={translations.converted} value_2={convertedAmount}/>

                        <ExpensePresenter entity={this.props.entity} field="status_field"/>

                        {!!this.props.entity.private_notes.length &&
                        <Row>
                            <InfoMessage message={this.props.entity.private_notes} />
                        </Row>
                        }

                        <Row>
                            <EntityListTile entity={translations.customer} title={customer[0].name} icon={icons.customer} />
                        </Row>

                        {!!user &&
                        <Row>
                            {user}
                        </Row>
                        }

                        <FieldGrid fields={fields} />
                    </TabPane>

                    <TabPane tabId="2">
                        <Row>
                            <Col>
                                <Card>
                                    <CardHeader>{translations.documents}</CardHeader>
                                    <CardBody>
                                        <FileUploads entity_type="Expense" entity={this.props.entity}
                                            user_id={this.props.entity.user_id}/>
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

                <BottomNavigationButtons button1_click={(e) => this.toggleTab('3')} button1={{ label: translations.view_pdf }}
                    button2_click={(e) => this.triggerAction('clone_to_invoice')} button2={{ label: translations.clone_to_invoice }}/>

            </React.Fragment>

        )
    }
}
