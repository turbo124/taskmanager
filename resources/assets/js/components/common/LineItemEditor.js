import React, { Component } from 'react'
import LineItem from './LineItem'
import { Button, FormGroup, Input, Label } from 'reactstrap'
import axios from 'axios'
import CustomerModel from '../models/CustomerModel'
import { getExchangeRateWithMap } from '../utils/_money'
import CompanyModel from '../models/CompanyModel'
import { translations } from '../utils/_translations'
import { consts } from '../utils/_consts'
import ProductRepository from '../repositories/ProductRepository'
import TaxRateRepository from '../repositories/TaxRateRepository'
import ExpenseRepository from '../repositories/ExpenseRepository'
import TaskRepository from '../repositories/TaskRepository'
import ProjectRepository from '../repositories/ProjectRepository'
import InvoiceReducer from '../invoice/InvoiceReducer'

class LineItemEditor extends Component {
    constructor (props) {
        super(props)
        this.state = {
            rowData: [],
            products: [],
            tax_rates: [],
            tasks: [],
            projects: [],
            expenses: [],
            attributes: [],
            line_type: this.props.line_type || null,
            total: this.props.invoice.total
        }

        const account_id = JSON.parse(localStorage.getItem('appState')).user.account_id
        const user_account = JSON.parse(localStorage.getItem('appState')).accounts.filter(account => account.account_id === parseInt(account_id))
        this.settings = user_account[0].account.settings

        this.handleRowChange = this.handleRowChange.bind(this)
        this.handleRowDelete = this.handleRowDelete.bind(this)
        this.handleRowAdd = this.handleRowAdd.bind(this)
        this.loadProducts = this.loadProducts.bind(this)
        this.loadTaxRates = this.loadTaxRates.bind(this)
        this.loadExpenses = this.loadExpenses.bind(this)
        this.handleLineTypeChange = this.handleLineTypeChange.bind(this)
        this.loadEntities = this.loadEntities.bind(this)
    }

    componentDidMount () {
        // this.loadAttributes()
        // this.loadTaxRates()

        if (this.props.line_type) {
            this.loadEntities(this.props.line_type)
        }
    }

    loadProducts () {
        const productRepository = new ProductRepository()
        productRepository.get().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ products: response }, () => {
                console.log('products', this.state.products)
            })
        })
    }

    loadAttributes () {
        axios.get('/api/attributeValues').then(data => {
            this.setState({ attributes: data.data })
        })
    }

    loadTaxRates () {
        const taxRateRepository = new TaxRateRepository()
        taxRateRepository.get().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ taxRates: response }, () => {
                console.log('taxRates', this.state.taxRates)
            })
        })
    }

    loadExpenses () {
        const expenseRepository = new ExpenseRepository()
        expenseRepository.get(consts.expense_status_pending, this.props.invoice.customer_id ? this.props.invoice.customer_id : null).then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ expenses: response }, () => {
                console.log('expenses', this.state.expenses)
            })
        })
    }

    loadTasks () {
        const taskRepository = new TaskRepository()
        taskRepository.get(null, this.props.invoice.customer_id ? this.props.invoice.customer_id : null).then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ tasks: response }, () => {
                console.log('tasks', this.state.tasks)
            })
        })
    }

    loadProjects () {
        const projectRepository = new ProjectRepository()
        projectRepository.get(this.props.invoice.customer_id ? this.props.invoice.customer_id : null).then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ projects: response }, () => {
                console.log('projects', this.state.projects)
            })
        })
    }

    handleLineTypeChange (e) {
        const line_type = parseInt(e.target.value)
        this.loadEntities(line_type)
    }

    loadEntities (line_type) {
        const tax_rates = JSON.parse(localStorage.getItem('tax_rates'))

        this.setState({ tax_rates: tax_rates, line_type: line_type }, () => {
            if (line_type === consts.line_item_expense && !this.state.expenses.length) {
                this.loadExpenses()
            }

            if (line_type === consts.line_item_task && !this.state.tasks.length) {
                this.loadTasks()
            }

            if (line_type === consts.line_item_project && !this.state.projects.length) {
                this.loadProjects()
            }

            if (line_type === consts.line_item_product) {
                if (!this.state.products.length) {
                    this.loadProducts()
                }

                if (!this.state.attributes.length) {
                    this.loadAttributes()
                }
            }
        })
    }

    handleRowChange (e) {
        const rows = [...this.props.invoice.line_items]

        const row = e.target.dataset.line

        if (e.target.name === 'unit_tax') {
            const index = this.state.tax_rates.findIndex(taxRate => taxRate.id === parseInt(e.target.value))
            const taxRate = this.state.tax_rates[index]
            rows[row].tax_rate_id = taxRate.id
            rows[row].tax_rate_name = taxRate.name
            rows[row].unit_tax = taxRate.rate
            this.props.update(rows, row)

            return
        }

        if (e.target.name === 'product_id') {
            const product = this.convertProductToInvoiceItem(e.target.value, rows[row])
            rows[row].unit_price = product.cost
            rows[row].description = product.description
            rows[row].product_id = e.target.value
            rows[row].type_id = consts.line_item_product
            rows[row].quantity = product.quantity
            rows[row].notes = product.notes
            this.props.update(rows, row)
            return
        }

        if (e.target.name === 'attribute_id') {
            rows[row].unit_price = e.target.options[e.target.selectedIndex].dataset.price
            rows[row].attribute_id = e.target.value
            rows[row].type_id = 1
            this.props.update(rows, row)

            return
        }

        if (e.target.name === 'expense_id') {
            const invoiceReducer = new InvoiceReducer(parseInt(e.target.value), 'expense')

            const index = this.state.expenses.findIndex(expense => expense.id === parseInt(e.target.value))
            const expense = this.state.expenses[index]

            rows[row] = invoiceReducer.buildExpense(expense, true)
            this.props.update(rows, row)

            return
        }

        if (e.target.name === 'task_id') {
            const invoiceReducer = new InvoiceReducer(parseInt(e.target.value), 'task')
            const index = this.state.tasks.findIndex(task => task.id === parseInt(e.target.value))
            const task = this.state.tasks[index]
            rows[row] = invoiceReducer.buildTask(task, true)

            this.props.update(rows, row)

            return
        }

        if (e.target.name === 'project_id') {
            const invoiceReducer = new InvoiceReducer(parseInt(e.target.value), 'project')
            const index = this.state.projects.findIndex(project => project.id === parseInt(e.target.value))
            const project = this.state.projects[index]
            rows[row] = invoiceReducer.buildProject(project, true)

            this.props.update(rows, row)

            return
        }

        rows[row][e.target.name] = e.target.value
        this.props.update(rows, row)
    }

    convertProductToInvoiceItem (product_id, row) {
        const index = this.state.products.findIndex(product => product.id === parseInt(product_id))
        const product = this.state.products[index]

        let cost = product.price
        let customer = []
        let customerModel = null

        if (this.settings.fill_products) {
            if (this.props.model.entity === 'PurchaseOrder') {
                customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.invoice.company_id))
                customerModel = new CompanyModel(customer[0])
            } else {
                customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.invoice.customer_id))
                customerModel = new CustomerModel(customer[0])
            }

            if (customer.length && customerModel) {
                const client_currency = customerModel.currencyId

                if (this.settings.convert_product_currency &&
                    client_currency !== parseInt(this.settings.currency_id)) {
                    const currencies = JSON.parse(localStorage.getItem('currencies'))
                    const currency = currencies.filter(currency => currency.id === client_currency)

                    cost = cost *
                        getExchangeRateWithMap(currencies, this.settings.currency_id, client_currency)
                    cost = Math.round(cost, currency[0].precision)
                }
            }

            return {
                notes: product.notes,
                cost: cost,
                quantity: (this.settings.quantity_can_be_changed === true && row.quantity) ? row.quantity : (this.settings.has_minimum_quantity === true) ? 1 : null,
                description: product.description
            }
        }

        return {
            notes: product.notes,
            cost: product.price,
            quantity: (this.settings.quantity_can_be_changed === true && row.quantity) ? row.quantity : (this.settings.has_minimum_quantity === true) ? 1 : null,
            description: this.settings.fill_products ? product.description : ''
        }
    }

    handleRowDelete (index) {
        this.props.delete(index)
    }

    _getEntity () {
        let variable = ''

        switch (parseInt(this.state.line_type)) {
            case consts.line_item_product:
                variable = this.state.products
                break
            case consts.line_item_task:
                variable = this.state.tasks
                break
            case consts.line_item_expense:
                variable = this.state.expenses
                break
            case consts.line_item_project:
                variable = this.state.projects
                break
            default:
                variable = this.state.products
        }

        return variable
    }

    handleRowAdd () {
        const variable = this._getEntity()

        if (!variable || !variable.length) {
            return false
        }

        this.props.onAddFiled(parseInt(this.state.line_type))
    }

    render () {
        const variable = this._getEntity()

        if (!variable) {
            console.log(`There are no ${this.state.line_type}`)
            return false
        }

        const lineItemRows = variable.length && this.state.tax_rates.length
            ? <LineItem
                invoice={this.props.invoice}
                line_type={parseInt(this.state.line_type)}
                rows={this.props.invoice.line_items}
                tax_rates={this.state.tax_rates}
                expenses={this.state.expenses}
                projects={this.state.projects}
                tasks={this.state.tasks}
                products={this.state.products}
                attributes={this.state.attributes}
                new={true}
                onChange={this.handleRowChange}
                handleTaskChange={this.updateTasks}
                onDelete={this.handleRowDelete}
            />
            : null

        return (
            <React.Fragment>

                <FormGroup>
                    <Label>{translations.line_type}</Label>
                    <Input name="line_type" type='select' value={this.state.line_type}
                        onChange={this.handleLineTypeChange} className='pa2 mr2 f6 form-control'>
                        <option value="">Select Line Type</option>
                        <option value={consts.line_item_product}>{translations.product}</option>
                        <option value={consts.line_item_project}>{translations.project}</option>

                        {this.props.model.entity === 'Invoice' &&
                        <React.Fragment>
                            <option value={consts.line_item_task}>{translations.task}</option>
                            <option value={consts.line_item_expense}>{translations.expense}</option>
                        </React.Fragment>
                        }
                    </Input>

                </FormGroup>
                {!!this.state.line_type && lineItemRows}

                <Button color="success" onClick={this.handleRowAdd}
                    className='f6 link dim ph3 pv1 mb2 dib white bg-dark-green bn'>Add
                </Button>
            </React.Fragment>
        )
    }
}

export default LineItemEditor
