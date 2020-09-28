import React, { Component } from 'react'
import LineItem from './LineItem'
import { Button, FormGroup, Input, Label } from 'reactstrap'
import axios from 'axios'
import FormatMoney from './FormatMoney'
import CustomerModel from '../models/CustomerModel'
import { getExchangeRateWithMap } from '../utils/_money'
import CompanyModel from '../models/CompanyModel'
import { translations } from '../utils/_translations'
import ExpenseModel from '../models/ExpenseModel'
import { consts } from '../utils/_consts'
import TaskModel from '../models/TaskModel'
import { formatDate } from './FormatDate'
import ProductRepository from '../repositories/ProductRepository'
import TaxRateRepository from '../repositories/TaxRateRepository'
import ExpenseRepository from '../repositories/ExpenseRepository'
import TaskRepository from '../repositories/TaskRepository'

class LineItemEditor extends Component {
    constructor (props) {
        super(props)
        this.state = {
            rowData: [],
            products: [],
            taxRates: [],
            tasks: [],
            expenses: [],
            attributes: [],
            line_type: null,
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
    }

    componentDidMount () {
        // this.loadAttributes()
        this.loadTaxRates()
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

    handleLineTypeChange (e) {
        const line_type = parseInt(e.target.value)
        this.setState({ line_type: line_type }, () => {
            if (line_type === consts.line_item_expense && !this.state.expenses.length) {
                this.loadExpenses()
            }

            if (line_type === consts.line_item_task && !this.state.tasks.length) {
                this.loadTasks()
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
            const index = this.state.taxRates.findIndex(taxRate => taxRate.id === parseInt(e.target.value))
            const taxRate = this.state.taxRates[index]
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
            const index = this.state.expenses.findIndex(expense => expense.id === parseInt(e.target.value))
            const expense = this.state.expenses[index]

            const expenseModel = new ExpenseModel(expense, this.props.customers)

            rows[row].expense_id = parseInt(e.target.value)
            rows[row].unit_price = expenseModel.convertedAmount
            rows[row].quantity = this.settings.has_minimum_quantity === true ? 1 : null
            rows[row].type_id = consts.line_item_expense
            rows[row].notes = expense.public_notes
            rows[row].description = expense.category && Object.keys(expense.category).length ? expense.category.name : ''

            this.props.update(rows, row)

            return
        }

        if (e.target.name === 'task_id') {
            const index = this.state.tasks.findIndex(task => task.id === parseInt(e.target.value))
            const task = this.state.tasks[index]
            const taskModel = new TaskModel(task, this.props.customers)

            let notes = task.description + '\n'

            task.timers.filter(time => {
                return time.date.length && time.end_date.length
            }).map(time => {
                const start = formatDate(`${time.date} ${time.start_time}`, true)
                const end = formatDate(`${time.end_date} ${time.end_time}`, true)
                notes += `\n### ${start} - ${end}`
            })

            rows[row].task_id = parseInt(e.target.value)
            rows[row].unit_price = taskModel.calculateAmount(task.task_rate)
            rows[row].quantity = Math.round(task.duration, 3)
            rows[row].type_id = consts.line_item_task
            rows[row].notes = notes

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
            if (this.props.entity && this.props.entity === 'Company') {
                customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.invoice.company_id))
                customerModel = new CompanyModel(customer[0])
            } else {
                customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.invoice.customer_id))
                customerModel = this.props.model ? this.props.model : new CustomerModel(customer[0])
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

    handleRowAdd () {
        const variable = (this.state.line_type === consts.line_item_product) ? this.state.products : ((this.state.line_type === consts.line_item_task) ? (this.state.tasks) : (this.state.expenses))

        if (!variable || !variable.length) {
            return false
        }

        this.props.onAddFiled(parseInt(this.state.line_type))
    }

    render () {
        const variable = (this.state.line_type === consts.line_item_product) ? this.state.products : ((this.state.line_type === consts.line_item_task) ? (this.state.tasks) : (this.state.expenses))

        if (!variable) {
            console.log(`There are no ${this.state.line_type}`)
            return false
        }

        const lineItemRows = variable.length && this.state.taxRates.length
            ? <LineItem
                invoice={this.props.invoice}
                line_type={parseInt(this.state.line_type)}
                rows={this.props.invoice.line_items}
                tax_rates={this.state.taxRates}
                expenses={this.state.expenses}
                tasks={this.state.tasks}
                products={this.state.products}
                attributes={this.state.attributes}
                new={true}
                onChange={this.handleRowChange}
                handleTaskChange={this.updateTasks}
                onDelete={this.handleRowDelete}
            />
            : null

        let total = this.props.invoice.sub_total - this.props.invoice.discount_total

        total += this.props.invoice.tax_total

        let tax_total = this.props.invoice.tax_total

        if (this.props.invoice.total_custom_values && this.props.invoice.total_custom_values > 0) {
            total += this.props.invoice.total_custom_values
        }

        if (this.props.invoice.gateway_fee && this.props.invoice.gateway_fee > 0) {
            let gateway_amount = this.props.invoice.gateway_fee

            if (this.props.invoice.gateway_percentage === true) {
                gateway_amount = total * this.props.invoice.gateway_fee / 100
            }

            total += gateway_amount
        }

        if (this.props.invoice.total_custom_tax && this.props.invoice.total_custom_tax > 0) {
            total += this.props.invoice.total_custom_tax
            tax_total += this.props.invoice.total_custom_tax
        }

        return (
            <React.Fragment>

                <FormGroup>
                    <Label>{translations.line_type}</Label>
                    <Input name="line_type" type='select' value={this.state.line_type}
                        onChange={this.handleLineTypeChange} className='pa2 mr2 f6 form-control'>
                        <option value="">Select Line Type</option>
                        <option value={consts.line_item_product}>{translations.product}</option>

                        {!this.props.entity &&
                            <React.Fragment>
                                <option value={consts.line_item_task}>{translations.task}</option>
                                <option value={consts.line_item_expense}>{translations.expense}</option>
                            </React.Fragment>
                        }
                    </Input>

                </FormGroup>
                {!!this.state.line_type && lineItemRows}

                <table id='lines-table'>
                    <tfoot>
                        <tr>
                            <th/>
                            <th>{translations.tax}:</th>
                            <th>{<FormatMoney
                                amount={tax_total}/>}</th>
                            <th/>
                        </tr>

                        <tr>
                            <th/>
                            <th>{translations.discount}:</th>
                            <th>{<FormatMoney
                                amount={this.props.invoice.discount_total}/>}</th>
                            <th/>
                        </tr>

                        <tr>
                            <th/>
                            <th>{translations.subtotal}:</th>
                            <th>{<FormatMoney
                                amount={this.props.invoice.sub_total}/>}</th>
                            <th/>
                        </tr>

                        <tr>
                            <th/>
                            <th>{translations.total}:</th>
                            <th>{<FormatMoney
                                amount={total}/>}</th>
                            <th/>
                        </tr>
                    </tfoot>
                </table>

                <Button color="success" onClick={this.handleRowAdd}
                    className='f6 link dim ph3 pv1 mb2 dib white bg-dark-green bn'>Add
                </Button>
            </React.Fragment>
        )
    }
}

export default LineItemEditor
