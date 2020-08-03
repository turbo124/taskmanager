import React, { Component } from 'react'
import DataTable from '../common/DataTable'
import axios from 'axios'
import AddExpense from './AddExpense'
import {
    Alert,
    Card, CardBody
} from 'reactstrap'
import ExpenseFilters from './ExpenseFilters'
import ExpenseItem from './ExpenseItem'
import queryString from 'query-string'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../common/_translations'

export default class Expenses extends Component {
    constructor (props) {
        super(props)
        this.state = {
            error: '',
            per_page: 5,
            view: {
                ignore: ['user_id', 'assigned_to', 'company_id', 'customer_id', 'invoice_id', 'bank_id', 'deleted_at', 'customer_id', 'invoice_currency_id', 'payment_type_id', 'currency_id', 'recurring_expense_id', 'updated_at', 'category_id'],
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
                customer_id: queryString.parse(this.props.location.search).customer_id || '',
                category_id: queryString.parse(this.props.location.search).category_id || '',
                searchText: queryString.parse(this.props.location.search).number || '',
                company_id: queryString.parse(this.props.location.search).company_id || '',
                start_date: '',
                end_date: ''
            },
            ignoredColumns:
                [
                    'files',
                    'customer_name',
                    'user_id',
                    'company_id',
                    'invoice_currency_id',
                    'converted_amount',
                    'exchange_rate',
                    'deleted_at',
                    'recurring_expense_id',
                    'currency_id',
                    'type_id',
                    'invoice_id',
                    'assigned_to',
                    'bank_id',
                    'category_id',
                    'create_invoice',
                    'include_documents',
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
                    'tax_rate_name',
                    'tax_rate'
                ],
            custom_fields: [],
            customers: [],
            showRestoreButton: false
        }

        this.updateExpenses = this.updateExpenses.bind(this)
        this.expenseList = this.expenseList.bind(this)
        this.filterExpenses = this.filterExpenses.bind(this)
        this.getCompanies = this.getCompanies.bind(this)
        this.getCustomers = this.getCustomers.bind(this)
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
                this.setState({
                    loading: false,
                    error: e
                })
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
                this.setState({
                    loading: false,
                    error: e
                })
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
            viewId={props.viewId}
            companies={companies}
            custom_fields={custom_fields}
            ignoredColumns={props.ignoredColumns} updateExpenses={this.updateExpenses}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
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
                    error: e
                })
            })
    }

    render () {
        const { expenses, customers, custom_fields, view, companies, error } = this.state
        const { searchText, status_id, customer_id, company_id, start_date, end_date, category_id } = this.state.filters
        const fetchUrl = `/api/expenses?search_term=${searchText}&status=${status_id}&customer_id=${customer_id}&company_id=${company_id}&start_date=${start_date}&end_date=${end_date}&category_id=${category_id}`
        const addButton = customers.length ? <AddExpense
            custom_fields={custom_fields}
            customers={customers}
            companies={companies}
            action={this.updateExpenses}
            expenses={expenses}
        /> : null

        return customers.length ? (
            <React.Fragment>
                <div className="topbar">
                    <Card>
                        <CardBody>
                            <ExpenseFilters customers={customers} expenses={expenses} companies={companies}
                                updateIgnoredColumns={this.updateIgnoredColumns}
                                filters={this.state.filters} filter={this.filterExpenses}
                                saveBulk={this.saveBulk} ignoredColumns={this.state.ignoredColumns}/>
                            {addButton}
                        </CardBody>
                    </Card>
                </div>

                {error &&
                <Snackbar open={this.state.error} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                    <Alert severity="danger">
                        {translations.unexpected_error}
                    </Alert>
                </Snackbar>
                }

                <div className="fixed-margin-datatable-large fixed-margin-datatable-large-mobile">
                    <Card>
                        <CardBody>
                            <DataTable
                                customers={customers}
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
            </React.Fragment>
        ) : null
    }
}
