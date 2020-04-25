import React, { Component } from 'react'
import DataTable from '../common/DataTable'
import axios from 'axios'
import AddExpense from './AddExpense'
import {
    Card, CardBody
} from 'reactstrap'
import ExpenseFilters from './ExpenseFilters'
import ExpenseItem from './ExpenseItem'

export default class Expenses extends Component {
    constructor (props) {
        super(props)
        this.state = {
            per_page: 5,
            view: {
                ignore: ['user_id', 'assigned_user_id', 'company_id', 'customer_id', 'invoice_id', 'bank_id', 'deleted_at', 'customer_id', 'invoice_currency_id', 'payment_type_id', 'expense_currency_id', 'recurring_expense_id', 'updated_at', 'invoice_category_id'],
                viewMode: false,
                viewedId: null,
                title: null
            },
            expenses: [],
            companies: [],
            cachedData: [],
            bulk: [],
            dropdownButtonActions: ['download'],
            filters: {
                status_id: 'active',
                searchText: '',
                customer_id: '',
                company_id: '',
                start_date: '',
                end_date: ''
            },
            ignoredColumns:
                [
                    'user_id',
                    'company_id',
                    'invoice_currency_id',
                    'foreign_amount',
                    'exchange_rate',
                    'deleted_at',
                    'recurring_expense_id',
                    'expense_currency_id',
                    'type_id',
                    'invoice_id',
                    'assigned_user_id',
                    'bank_id',
                    'invoice_category_id',
                    'should_be_invoiced',
                    'invoice_documents',
                    'public_notes',
                    'private_notes',
                    'archived_at',
                    'created_at',
                    'updated_at',
                    'is_deleted',
                    'payment_type_id',
                    'custom_value1',
                    'custom_value2',
                    'custom_value3',
                    'custom_value4',
                    'tax_name1',
                    'tax_rate1',
                    'tax_name2',
                    'tax_rate2',
                    'tax_name3',
                    'tax_rate3'
                ],
            custom_fields: [],
            customers: [],
            showRestoreButton: false
        }

        this.updateExpenses = this.updateExpenses.bind(this)
        this.expenseList = this.expenseList.bind(this)
        this.filterExpenses = this.filterExpenses.bind(this)
        this.getCompanies = this.getCompanies.bind(this)
    }

    componentDidMount () {
        this.getCustomers()
        this.getCustomFields()
        this.getCompanies()
    }

    getCompanies () {
        axios.get('/api/companies')
            .then((r) => {
                this.setState({
                    companies: r.data
                })
            })
            .catch((e) => {
                console.error(e)
            })
    }

    filterExpenses (filters) {
        this.setState({ filters: filters })
    }

    getCustomers () {
        axios.get('/api/customers')
            .then((r) => {
                this.setState({
                    customers: r.data
                })
            })
            .catch((e) => {
                console.error(e)
            })
    }

    updateExpenses (expenses) {
        const cachedData = !this.state.cachedData.length ? expenses : this.state.cachedData
        this.setState({
            expenses: expenses,
            cachedData: cachedData
        })
    }

    expenseList (props) {
        const { expenses, customers, custom_fields, companies } = this.state
        return <ExpenseItem showCheckboxes={props.showCheckboxes} expenses={expenses} customers={customers}
            companies={companies}
            custom_fields={custom_fields}
            ignoredColumns={props.ignoredColumns} updateExpenses={this.updateExpenses}
            toggleViewedEntity={props.toggleViewedEntity}
            onChangeBulk={props.onChangeBulk}/>
    }

    getCustomFields () {
        axios.get('api/accounts/fields/Expense')
            .then((r) => {
                this.setState({
                    custom_fields: r.data.fields && Object.keys(r.data.fields).length ? r.data.fields : []
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    err: e
                })
            })
    }

    render () {
        const { expenses, customers, custom_fields, view, companies } = this.state
        const { searchText, status_id, customer_id, company_id, start_date, end_date } = this.state.filters
        const fetchUrl = `/api/expenses?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&company_id=${company_id}&start_date=${start_date}&end_date=${end_date}`
        const addButton = customers.length ? <AddExpense
            custom_fields={custom_fields}
            customers={customers}
            companies={companies}
            action={this.updateExpenses}
            expenses={expenses}
        /> : null

        return this.state.customers.length ? (
            <div className="data-table">

                <Card>
                    <CardBody>
                        <ExpenseFilters expenses={expenses} companies={companies}
                            updateIgnoredColumns={this.updateIgnoredColumns}
                            filters={this.state.filters} filter={this.filterExpenses}
                            saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>
                        {addButton}

                        <DataTable
                            dropdownButtonActions={this.state.dropdownButtonActions}
                            entity_type="Expense"
                            bulk_save_url="/api/expense/bulk"
                            view={view}
                            columnMapping={{ customer_id: 'CUSTOMER' }}
                            disableSorting={['id']}
                            defaultColumn='amount'
                            userList={this.expenseList}
                            ignore={this.state.ignoredColumns}
                            fetchUrl={fetchUrl}
                            updateState={this.updateExpenses}
                        />
                    </CardBody>
                </Card>
            </div>
        ) : null
    }
}
