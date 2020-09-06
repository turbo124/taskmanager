import React, { Component } from 'react'
import LineItem from './LineItem'
import { Button, FormGroup, Input, Label } from 'reactstrap'
import axios from 'axios'
import FormatMoney from './FormatMoney'
import CustomerModel from '../models/CustomerModel'
import { getExchangeRateWithMap } from './_money'

class LineItemEditor extends Component {
    constructor (props) {
        super(props)
        this.state = {
            rowData: [],
            products: [],
            taxRates: [],
            expenses: [],
            attributes: [],
            line_type: 1,
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
        this.loadProducts()
        // this.loadAttributes()
        this.loadTaxRates()
        this.loadExpenses()
    }

    loadProducts () {
        axios.get('/api/products').then(data => {
            this.setState({ products: data.data })
        })
    }

    loadAttributes () {
        axios.get('/api/attributeValues').then(data => {
            this.setState({ attributes: data.data })
        })
    }

    loadTaxRates () {
        axios.get('/api/taxRates').then(data => {
            this.setState({ taxRates: data.data })
        })
    }

    loadExpenses () {
        axios.get('/api/expenses').then(data => {
            this.setState({ expenses: data.data })
        })
    }

    handleLineTypeChange (e) {
        this.setState({ line_type: e.target.value })
    }

    handleRowChange (e) {
        const rows = [...this.props.invoice.line_items]

        if (e.target.name.includes('task_id')) {
            const test = e.target.name.split('|')
            const row = test[0]

            rows[row].task_id = e.target.value
            rows[row].quantity = 1
            rows[row].type_id = 3
            this.props.update(rows, row)

            return
        }

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
            rows[row].type_id = 1
            rows[row].quantity = product.quantity
            this.props.update(rows, row)
            return
        }

        if (e.target.name === 'attribute_id') {
            const price = e.target.options[e.target.selectedIndex].dataset.price
            rows[row].unit_price = price
            rows[row].attribute_id = e.target.value
            rows[row].type_id = 1
            this.props.update(rows, row)

            return
        }

        if (e.target.name === 'expense_id') {
            const index = this.state.expenses.findIndex(expense => expense.id === parseInt(e.target.value))
            const expense = this.state.expenses[index]

            rows[row].expense_id = e.target.value
            rows[row].unit_price = expense.amount
            rows[row].quantity = 1
            rows[row].type_id = 6
            this.props.update(rows, row)

            return
        }

        rows[row][e.target.name] = e.target.value
        this.props.update(rows, row)
    }

    convertProductToInvoiceItem (product_id, row) {
        const index = this.state.products.findIndex(product => product.id === parseInt(product_id))
        const product = this.state.products[index]
    
        if (customer.length && this.settings.fill_products) {
            if(this.props.entity && this.props.entity === 'Company') {
                const customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.invoice.company_id))
                const customerModel = new CompanyModel(customer[0])
                let cost = product.price
                const client_currency = customerModel.currencyId
            } else {
                const customer = this.props.customers.filter(customer => customer.id === parseInt(this.props.invoice.customer_id))
                const customerModel = this.props.model ? this.props.model : new CustomerModel(customer[0])
                let cost = product.price
                const client_currency = customerModel.currencyId
            }

            if (this.settings.convert_product_currency &&
                client_currency !== parseInt(this.settings.currency_id)) {
                const currencies = JSON.parse(localStorage.getItem('currencies'))
                const currency = currencies.filter(currency => currency.id === client_currency)

                cost = cost *
                    getExchangeRateWithMap(currencies, this.settings.currency_id, client_currency)
                cost = Math.round(cost, currency[0].precision)
            }

            return {
                cost: cost,
                quantity: (this.settings.quantity_can_be_changed === true && row.quantity) ? row.quantity : (this.settings.has_minimum_quantity === true) ? 1 : null,
                description: product.description
            }
        }

        return {
            cost: product.price,
            quantity: (this.settings.quantity_can_be_changed === true && row.quantity) ? row.quantity : (this.settings.has_minimum_quantity === true) ? 1 : null,
            description: this.settings.fill_products ? product.description : ''
        }
    }

    handleRowDelete (index) {
        this.props.delete(index)
    }

    handleRowAdd () {
        this.props.onAddFiled()
    }

    render () {
        const lineItemRows = this.state.products.length && this.state.taxRates.length
            ? <LineItem
                invoice={this.props.invoice}
                line_type={parseInt(this.state.line_type)}
                rows={this.props.invoice.line_items}
                tax_rates={this.state.taxRates}
                expenses={this.state.expenses}
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
                    <Label>Tax</Label>
                    <Input name="line_type" type='select' value={this.state.line_type}
                        onChange={this.handleLineTypeChange} className='pa2 mr2 f6 form-control'>
                        <option value="1">Product</option>
                        <option value="2">Task</option>
                        <option value="3">Expense</option>
                    </Input>
                </FormGroup>
                {lineItemRows}

                <table id='lines-table'>
                    <tfoot>
                        <tr>
                            <th/>
                            <th>Tax total:</th>
                            <th>{<FormatMoney
                                amount={tax_total}/>}</th>
                            <th/>
                        </tr>

                        <tr>
                            <th/>
                            <th>Discount total:</th>
                            <th>{<FormatMoney
                                amount={this.props.invoice.discount_total}/>}</th>
                            <th/>
                        </tr>

                        <tr>
                            <th/>
                            <th>Sub total:</th>
                            <th>{<FormatMoney
                                amount={this.props.invoice.sub_total}/>}</th>
                            <th/>
                        </tr>

                        <tr>
                            <th/>
                            <th>Grand total:</th>
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
