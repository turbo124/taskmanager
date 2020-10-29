import React, { Component } from 'react'
import axios from 'axios'
import AddRecurringQuote from './edit/AddRecurringQuote'
import { Alert, Card, CardBody, Row } from 'reactstrap'
import DataTable from '../common/DataTable'
import RecurringQuoteItem from './RecurringQuoteItem'
import RecurringQuoteFilters from './RecurringQuoteFilters'
import Snackbar from '@material-ui/core/Snackbar'
import { translations } from '../utils/_translations'
import CustomerRepository from '../repositories/CustomerRepository'
import QuoteRepository from '../repositories/QuoteRepository'
import queryString from 'query-string'

export default class RecurringQuotes extends Component {
    constructor (props) {
        super(props)
        this.state = {
            isOpen: window.innerWidth > 670,
            error: '',
            show_success: false,
            error_message: translations.unexpected_error,
            success_message: translations.success_message,
            per_page: 5,
            view: {
                ignore: [],
                viewMode: false,
                viewedId: null,
                title: null
            },
            invoices: [],
            cachedData: [],
            custom_fields: [],
            customers: [],
            allQuotes: [],
            bulk: [],
            dropdownButtonActions: ['download', 'start_recurring', 'stop_recurring'],
            filters: {
                user_id: queryString.parse(this.props.location.search).user_id || '',
                status_id: 'Draft',
                customer_id: queryString.parse(this.props.location.search).customer_id || '',
                project_id: queryString.parse(this.props.location.search).project_id || '',
                searchText: '',
                start_date: '',
                end_date: ''
            },
            showRestoreButton: false,
            entity_id: queryString.parse(this.props.location.search).entity_id || false,
            entity_type: queryString.parse(this.props.location.search).entity_type || false,
            ignoredColumns: ['auto_billing_enabled', 'project_id', 'tax_rate', 'tax_rate_name', 'tax_2', 'tax_3', 'tax_rate_name_2', 'tax_rate_name_3', 'schedule', 'grace_period', 'last_sent_date', 'quotes', 'currency_id', 'exchange_rate', 'gateway_fee', 'transaction_fee', 'shipping_cost', 'gateway_percentage', 'transaction_fee_tax', 'shipping_cost_tax', 'audits', 'invitations', 'files', 'id', 'custom_value1', 'invoice_id', 'custom_value2', 'custom_value3', 'custom_value4', 'updated_at', 'deleted_at', 'created_at', 'public_notes', 'private_notes', 'use_inclusive_taxes', 'terms', 'footer', 'last_send_date', 'line_items', 'date_to_send', 'first_name', 'last_name', 'tax_total', 'discount_total', 'sub_total']

        }

        this.ignore = []

        this.updateInvoice = this.updateInvoice.bind(this)
        this.userList = this.userList.bind(this)
        this.filterInvoices = this.filterInvoices.bind(this)
        this.getQuotes = this.getQuotes.bind(this)
    }

    componentDidMount () {
        this.getCustomers()
        this.getQuotes()
    }

    updateInvoice (invoices) {
        const cachedData = !this.state.cachedData.length ? invoices : this.state.cachedData
        this.setState({
            invoices: invoices,
            cachedData: cachedData
        })
    }

    getQuotes () {
        const quoteRepository = new QuoteRepository()
        quoteRepository.get().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ allQuotes: response }, () => {
                console.log('allQuotes', this.state.allQuotes)
            })
        })
    }

    filterInvoices (filters) {
        this.setState({ filters: filters })
    }

    handleClose () {
        this.setState({ error: '', show_success: false })
    }

    userList (props) {
        const { invoices, custom_fields, customers, allQuotes } = this.state
        return <RecurringQuoteItem showCheckboxes={props.showCheckboxes} allQuotes={allQuotes} invoices={invoices}
            viewId={props.viewId}
            customers={customers} custom_fields={custom_fields}
            ignoredColumns={props.ignoredColumns} updateInvoice={this.updateInvoice}
            toggleViewedEntity={props.toggleViewedEntity}
            bulk={props.bulk}
            onChangeBulk={props.onChangeBulk}/>
    }

    renderErrorFor () {

    }

    getCustomFields () {
        axios.get('api/accounts/fields/RecurringQuote')
            .then((r) => {
                this.setState({
                    custom_fields: r.data.fields
                })
            })
            .catch((e) => {
                this.setState({
                    loading: false,
                    error: e
                })
            })
    }

    getCustomers () {
        const customerRepository = new CustomerRepository()
        customerRepository.get().then(response => {
            if (!response) {
                alert('error')
            }

            this.setState({ customers: response }, () => {
                console.log('customers', this.state.customers)
            })
        })
    }

    setFilterOpen (isOpen) {
        this.setState({ isOpen: isOpen })
    }

    setError (message = null) {
        this.setState({ error: true, error_message: message === null ? translations.unexpected_error : message })
    }

    setSuccess (message = null) {
        this.setState({
            show_success: true,
            success_message: message === null ? translations.success_message : message
        })
    }

    render () {
        const { invoices, custom_fields, customers, allQuotes, view, filters, error, isOpen, error_message, success_message, show_success } = this.state
        const { status_id, customer_id, searchText, start_date, end_date, project_id, user_id } = this.state.filters
        const fetchUrl = `/api/recurring-quote?search_term=${searchText}&user_id=${user_id}&status=${status_id}&customer_id=${customer_id}&project_id=${project_id}&start_date=${start_date}&end_date=${end_date}`
        const addButton = customers.length ? <AddRecurringQuote
            allQuotes={allQuotes}
            entity_id={this.state.entity_id}
            entity_type={this.state.entity_type}
            custom_fields={custom_fields}
            customers={customers}
            invoice={{}}
            add={false}
            action={this.updateInvoice}
            invoices={invoices}
            modal={true}
        /> : null
        const margin_class = isOpen === false || (Object.prototype.hasOwnProperty.call(localStorage, 'datatable_collapsed') && localStorage.getItem('datatable_collapsed') === true)
            ? 'fixed-margin-datatable-collapsed'
            : 'fixed-margin-datatable fixed-margin-datatable-mobile'

        return (
            <Row>
                <div className="col-12">
                    <div className="topbar">
                        <Card>
                            <CardBody>
                                <RecurringQuoteFilters setFilterOpen={this.setFilterOpen.bind(this)}
                                    invoices={invoices}
                                    updateIgnoredColumns={this.updateIgnoredColumns}
                                    filters={filters} filter={this.filterInvoices}
                                    saveBulk={this.saveBulk}
                                    ignoredColumns={this.state.ignoredColumns}/>
                                {addButton}
                            </CardBody>
                        </Card>
                    </div>

                    {error &&
                    <Snackbar open={error} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                        <Alert severity="danger">
                            {error_message}
                        </Alert>
                    </Snackbar>
                    }

                    {show_success &&
                    <Snackbar open={show_success} autoHideDuration={3000} onClose={this.handleClose.bind(this)}>
                        <Alert severity="success">
                            {success_message}
                        </Alert>
                    </Snackbar>
                    }

                    <div className={margin_class}>
                        <Card>
                            <CardBody>
                                <DataTable
                                    setSuccess={this.setSuccess.bind(this)}
                                    setError={this.setError.bind(this)}
                                    customers={this.state.customers}
                                    dropdownButtonActions={this.state.dropdownButtonActions}
                                    entity_type="RecurringQuote"
                                    bulk_save_url="/api/recurring-quote/bulk"
                                    view={view}
                                    columnMapping={{ customer_id: 'CUSTOMER' }}
                                    ignore={this.state.ignoredColumns}
                                    disableSorting={['id']}
                                    defaultColumn='number'
                                    userList={this.userList}
                                    fetchUrl={fetchUrl}
                                    updateState={this.updateInvoice}
                                />
                            </CardBody>
                        </Card>
                    </div>
                </div>
            </Row>
        )
    }
}
