import React, { Component } from 'react'
import {
    Card,
    CardBody,
    NavLink,
    NavItem,
    Nav,
    TabPane,
    TabContent,
    Alert
} from 'reactstrap'
import axios from 'axios'
import CustomFieldSettingsForm from './CustomFieldSettingsForm'
import { translations } from '../common/_translations'
import Snackbar from '@material-ui/core/Snackbar'

class CustomFieldSettings extends Component {
    constructor (props) {
        super(props)

        this.modules = JSON.parse(localStorage.getItem('modules'))

        this.state = {
            success: false,
            error: false,
            activeTab: '1',
            quotes: [{ name: 'custom_value1', label: '', type: '' }, { name: 'custom_value2', label: '', type: '' }, {
                name: 'custom_value3',
                label: '',
                type: ''
            }, { name: 'custom_value4', label: '', type: '' }],
            companies: [{ name: 'custom_value1', label: '', type: '' }, { name: 'custom_value2', label: '', type: '' }, {
                name: 'custom_value3',
                label: '',
                type: ''
            }, { name: 'custom_value4', label: '', type: '' }],
            customers: [{ name: 'custom_value1', label: '', type: '' }, { name: 'custom_value2', label: '', type: '' }, {
                name: 'custom_value3',
                label: '',
                type: ''
            }, { name: 'custom_value4', label: '', type: '' }],
            product: [{ name: 'custom_value1', label: '', type: '' }, { name: 'custom_value2', label: '', type: '' }, {
                name: 'custom_value3',
                label: '',
                type: ''
            }, { name: 'custom_value4', label: '', type: '' }],
            invoices: [{ name: 'custom_value1', label: '', type: '' }, { name: 'custom_value2', label: '', type: '' }, {
                name: 'custom_value3',
                label: '',
                type: ''
            }, { name: 'custom_value4', label: '', type: '' }],
            payments: [{ name: 'custom_value1', label: '', type: '' }, { name: 'custom_value2', label: '', type: '' }, {
                name: 'custom_value3',
                label: '',
                type: ''
            }, { name: 'custom_value4', label: '', type: '' }],
            tasks: [{ name: 'custom_value1', label: '', type: '' }, { name: 'custom_value2', label: '', type: '' }, {
                name: 'custom_value3',
                label: '',
                type: ''
            }, { name: 'custom_value4', label: '', type: '' }],
            credits: [{ name: 'custom_value1', label: '', type: '' }, { name: 'custom_value2', label: '', type: '' }, {
                name: 'custom_value3',
                label: '',
                type: ''
            }, { name: 'custom_value4', label: '', type: '' }],
            expenses: [{ name: 'custom_value1', label: '', type: '' }, { name: 'custom_value2', label: '', type: '' }, {
                name: 'custom_value3',
                label: '',
                type: ''
            }, { name: 'custom_value4', label: '', type: '' }],
            orders: [{ name: 'custom_value1', label: '', type: '' }, { name: 'custom_value2', label: '', type: '' }, {
                name: 'custom_value3',
                label: '',
                type: ''
            }, { name: 'custom_value4', label: '', type: '' }]
        }

        this.handleChange = this.handleChange.bind(this)
        this.toggle = this.toggle.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.getSettings = this.getSettings.bind(this)
        this.handleOptionChange = this.handleOptionChange.bind(this)
    }

    componentDidMount () {
        this.getSettings()
    }

    getSettings () {
        axios.get('api/accounts/fields/getAllCustomFields')
            .then((r) => {
                if (r.data.Customer && Object.keys(r.data)) {
                    this.setState({
                        // orders: r.data.Order,
                        expenses: r.data.Expense,
                        product: r.data.Product,
                        customers: r.data.Customer,
                        payments: r.data.Payment,
                        invoices: r.data.Invoice,
                        companies: r.data.Company,
                        quotes: r.data.Quote,
                        credits: r.data.Credit,
                        tasks: r.data.Task
                    })
                    console.log('response', r.data.Product)
                }
            })
            .catch((e) => {
                this.setState({ error: true })
            })
    }

    handleChange (e) {
        const entity = e.target.dataset.entity
        const id = e.target.dataset.id
        const className = e.target.dataset.field
        const value = e.target.value

        if (['type', 'label'].includes(className)) {
            const products = [...this.state[entity]]
            products[id][className] = value
            this.setState({ [entity]: products }, () => console.log(this.state))
        } else {
            // this.setState({ [e.target.name]: e.target.value })
        }

        if (className === 'type' && value === 'select' && !this.state[entity].options) {
            const products = [...this.state[entity]]
            products[id].options = [{ value: '', text: '' }]
            this.setState({ [entity]: products }, () => console.log(this.state))
        }
    }

    handleOptionChange (e) {
        console.log('entity', e)
        const entity = e.data_entity
        const id = e.data_id

        const products = [...this.state[entity]]
        products[id].options = e.options
        this.setState({ [entity]: products }, () => console.log(this.state))
        console.log('element', e)
    }

    handleSubmit (e) {
        const fields = {}
        fields.Order = this.state.orders
        fields.Product = this.state.product
        fields.Customer = this.state.customers
        fields.Company = this.state.companies
        fields.Payment = this.state.payments
        fields.Invoice = this.state.invoices
        fields.Quote = this.state.quotes
        fields.Task = this.state.tasks
        fields.Credit = this.state.credits
        fields.Expense = this.state.expenses

        axios.post('/api/accounts/fields', {
            fields: JSON.stringify(fields)
        }).then((response) => {
            this.setState({ success: true })
        })
            .catch((error) => {
                if (error.response.data.errors) {
                    this.setState({
                        errors: error.response.data.errors
                    })
                } else {
                    this.setState({ message: error.response.data })
                }
            })
    }

    toggle (e) {
        const tab = String(e.target.dataset.id)
        if (this.state.activeTab !== tab) {
            this.setState({ activeTab: tab })
        }
    }

    handleClose () {
        this.setState({ success: false, error: false })
    }

    render () {
        const { customers, product, invoices, payments, companies, quotes, credits, tasks, expenses, orders } = this.state
        let tabCounter = 1
        const tabContent = []
        const tabItems = []

        if (customers && this.modules.customers === true) {
            tabContent.push(<TabPane tabId={String(tabCounter)}className="px-0">
                <Card className="border-0">
                    <CardBody>
                        {
                            customers.map((val, idx) => {
                                const catId = `custom_value${idx}`
                                const ageId = `age-${idx}`
                                return <CustomFieldSettingsForm idx={idx} age={ageId} obj={customers[idx]}
                                    handleOptionChange={this.handleOptionChange}
                                    entity="customers" type={customers[idx].type}
                                    handleChange={this.handleChange} catId={catId}
                                    label={customers[idx].label}/>
                            })
                        }
                    </CardBody>
                </Card>
            </TabPane>)

            tabItems.push(<NavItem>
                <NavLink
                    data-id={tabCounter}
                    className={this.state.activeTab === String(tabCounter) ? 'active' : ''}
                    onClick={this.toggle}>
                    {translations.customers}
                </NavLink>
            </NavItem>)
            tabCounter++
        }

        if (product && this.modules.products === true) {
            tabContent.push(<TabPane tabId={String(tabCounter)} className="px-0">
                <Card className="border-0">
                    <CardBody>
                        {
                            product.map((val, idx) => {
                                const catId = `custom_value${idx}`
                                const ageId = `age-${idx}`
                                return <CustomFieldSettingsForm idx={idx} age={ageId} obj={product[idx]}
                                    handleOptionChange={this.handleOptionChange}
                                    entity="product" type={product[idx].type}
                                    handleChange={this.handleChange} catId={catId}
                                    label={product[idx].label}/>
                            })
                        }
                    </CardBody>
                </Card>
            </TabPane>)

            tabItems.push(<NavItem>
                <NavLink
                    data-id={tabCounter}
                    className={this.state.activeTab === String(tabCounter) ? 'active' : ''}
                    onClick={this.toggle}>
                    {translations.products}
                </NavLink>
            </NavItem>)

            tabCounter++
        }

        if (invoices && this.modules.invoices === true) {
            tabContent.push(<TabPane tabId={String(tabCounter)} className="px-0">
                <Card className="border-0">
                    <CardBody>
                        {
                            invoices.map((val, idx) => {
                                const catId = `custom_name${idx}`
                                const ageId = `age-${idx}`
                                return <CustomFieldSettingsForm idx={idx} age={ageId} obj={invoices[idx]}
                                    handleOptionChange={this.handleOptionChange}
                                    entity="invoices" type={invoices[idx].type}
                                    handleChange={this.handleChange} catId={catId}
                                    label={invoices[idx].label}/>
                            })
                        }
                    </CardBody>
                </Card>
            </TabPane>)

            tabItems.push(<NavItem>
                <NavLink
                    data-id={tabCounter}
                    className={this.state.activeTab === String(tabCounter) ? 'active' : ''}
                    onClick={this.toggle}>
                    Invoices
                </NavLink>
            </NavItem>)

            tabCounter++
        }

        if (payments && this.modules.payments === true) {
            tabContent.push(<TabPane tabId={String(tabCounter)} className="px-0">
                <Card className="border-0">
                    <CardBody>
                        {
                            payments.map((val, idx) => {
                                const catId = `custom_value${idx}`
                                const ageId = `age-${idx}`
                                return <CustomFieldSettingsForm idx={idx} age={ageId} obj={payments[idx]}
                                    handleOptionChange={this.handleOptionChange}
                                    entity="payments" type={payments[idx].type}
                                    handleChange={this.handleChange} catId={catId}
                                    label={payments[idx].label}/>
                            })
                        }
                    </CardBody>
                </Card>
            </TabPane>)

            tabItems.push(<NavItem>
                <NavLink
                    data-id={tabCounter}
                    className={this.state.activeTab === String(tabCounter) ? 'active' : ''}
                    onClick={this.toggle}>
                    {translations.payments}
                </NavLink>
            </NavItem>)

            tabCounter++
        }

        if (companies && this.modules.companies === true) {
            tabContent.push(<TabPane tabId={String(tabCounter)} className="px-0">
                <Card className="border-0">
                    <CardBody>
                        {
                            companies.map((val, idx) => {
                                const catId = `custom_value${idx}`
                                const ageId = `age-${idx}`
                                return <CustomFieldSettingsForm idx={idx} age={ageId} obj={companies[idx]}
                                    handleOptionChange={this.handleOptionChange}
                                    entity="companies" type={companies[idx].type}
                                    handleChange={this.handleChange} catId={catId}
                                    label={companies[idx].label}/>
                            })
                        }
                    </CardBody>
                </Card>
            </TabPane>)

            tabItems.push(<NavItem>
                <NavLink
                    data-id={tabCounter}
                    className={this.state.activeTab === String(tabCounter) ? 'active' : ''}
                    onClick={this.toggle}>
                    {translations.companies}
                </NavLink>
            </NavItem>)

            tabCounter++
        }

        if (quotes && this.modules.quotes === true) {
            tabContent.push(<TabPane tabId={String(tabCounter)} className="px-0">
                <Card className="border-0">
                    <CardBody>
                        {
                            quotes.map((val, idx) => {
                                const catId = `custom_value${idx}`
                                const ageId = `age-${idx}`
                                return <CustomFieldSettingsForm idx={idx} age={ageId} obj={quotes[idx]}
                                    handleOptionChange={this.handleOptionChange}
                                    entity="quotes" type={quotes[idx].type}
                                    handleChange={this.handleChange} catId={catId}
                                    label={quotes[idx].label}/>
                            })
                        }
                    </CardBody>
                </Card>
            </TabPane>)

            tabItems.push(<NavItem>
                <NavLink
                    data-id={tabCounter}
                    className={this.state.activeTab === String(tabCounter) ? 'active' : ''}
                    onClick={this.toggle}>
                    {translations.quotes}
                </NavLink>
            </NavItem>)

            tabCounter++
        }

        if (credits && this.modules.credits === true) {
            tabContent.push(<TabPane tabId={String(tabCounter)} className="px-0">
                <Card className="border-0">
                    <CardBody>
                        {
                            credits.map((val, idx) => {
                                const catId = `custom_value${idx}`
                                const ageId = `age-${idx}`
                                return <CustomFieldSettingsForm idx={idx} age={ageId} obj={credits[idx]}
                                    handleOptionChange={this.handleOptionChange}
                                    entity="credits" type={credits[idx].type}
                                    handleChange={this.handleChange} catId={catId}
                                    label={credits[idx].label}/>
                            })
                        }
                    </CardBody>
                </Card>
            </TabPane>)

            tabItems.push(<NavItem>
                <NavLink
                    data-id={tabCounter}
                    className={this.state.activeTab === String(tabCounter) ? 'active' : ''}
                    onClick={this.toggle}>
                    {translations.credits}
                </NavLink>
            </NavItem>)

            tabCounter++
        }

        if (tasks && this.modules.tasks === true) {
            tabContent.push(<TabPane tabId={String(tabCounter)} className="px-0">
                <Card>
                    <CardBody>
                        {
                            tasks.map((val, idx) => {
                                const catId = `custom_value${idx}`
                                const ageId = `age-${idx}`
                                return <CustomFieldSettingsForm idx={idx} age={ageId} obj={tasks[idx]}
                                    handleOptionChange={this.handleOptionChange}
                                    entity="tasks" type={tasks[idx].type}
                                    handleChange={this.handleChange} catId={catId}
                                    label={tasks[idx].label}/>
                            })
                        }
                    </CardBody>
                </Card>
            </TabPane>)

            tabItems.push(<NavItem>
                <NavLink
                    className={this.state.activeTab === String(tabCounter) ? 'active' : ''}
                    data-id={tabCounter}
                    onClick={this.toggle}>
                    {translations.tasks}
                </NavLink>
            </NavItem>)

            tabCounter++
        }

        if (expenses && this.modules.expenses === true) {
            tabContent.push(<TabPane tabId={String(tabCounter)} className="px-0">
                <Card className="border-0">
                    <CardBody>
                        {
                            expenses.map((val, idx) => {
                                const catId = `custom_value${idx}`
                                const ageId = `age-${idx}`
                                return <CustomFieldSettingsForm idx={idx} age={ageId} obj={expenses[idx]}
                                    handleOptionChange={this.handleOptionChange}
                                    entity="expenses" type={expenses[idx].type}
                                    handleChange={this.handleChange} catId={catId}
                                    label={expenses[idx].label}/>
                            })
                        }
                    </CardBody>
                </Card>
            </TabPane>)

            tabItems.push(<NavItem>
                <NavLink
                    className={this.state.activeTab === String(tabCounter) ? 'active' : ''}
                    data-id={tabCounter}
                    onClick={this.toggle}>
                    {translations.expenses}
                </NavLink>
            </NavItem>)

            tabCounter++
        }

        if (orders && this.modules.orders === true) {
            tabContent.push(<TabPane tabId={String(tabCounter)} className="px-0">
                <Card className="border-0">
                    <CardBody>
                        {
                            orders.map((val, idx) => {
                                const catId = `custom_value${idx}`
                                const ageId = `age-${idx}`
                                return <CustomFieldSettingsForm idx={idx} age={ageId} obj={orders[idx]}
                                    handleOptionChange={this.handleOptionChange}
                                    entity="orders" type={orders[idx].type}
                                    handleChange={this.handleChange} catId={catId}
                                    label={orders[idx].label}/>
                            })
                        }
                    </CardBody>
                </Card>
            </TabPane>)

            tabItems.push(<NavItem>
                <NavLink
                    className={this.state.activeTab === String(tabCounter) ? 'active' : ''}
                    data-id={tabCounter}
                    onClick={this.toggle}>
                    Orders
                </NavLink>
            </NavItem>)
        }

        return (
            <React.Fragment>
                <Snackbar open={this.state.success} autoHideDuration={3000}  onClose={this.handleClose.bind(this)}>
                    <Alert severity="success">
                        {translations.settings_saved}
                    </Alert>
                </Snackbar>

                <div className="topbar">
                    <Card className="m-0">
                        <CardBody className="p-0">
                            <div className="d-flex justify-content-between align-items-center">
                                <h4 className="pl-3 pt-2">{translations.custom_fields}</h4>
                                <a className="pull-right pr-3" onClick={this.handleSubmit}>{translations.save}</a>
                            </div>
                            <Nav tabs className="setting-tabs disable-scrollbars">
                                {tabItems}
                            </Nav>
                        </CardBody>
                    </Card>
                </div>

                <TabContent className="fixed-margin-mobile bg-transparent" activeTab={this.state.activeTab}>
                    {tabContent}
                </TabContent>
            </React.Fragment>
        )
    }
}

export default CustomFieldSettings
